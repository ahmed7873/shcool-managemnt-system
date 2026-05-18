<?php

namespace App\Http\Controllers\Students;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStudentsRequest;
use App\Models\AcademicYear;
use App\Models\Attendance;
use App\Models\Classroom;
use App\Models\Degree;
use App\Models\Grade;
use App\Models\Quizze;
use App\Models\Section;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\teacher_subject;
use App\Models\Term;
use App\Repository\StudentRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{

    protected $Student;

    public function __construct(StudentRepositoryInterface $Student)
    {
        $this->Student = $Student;
    }


    public function index()
    {
        return $this->Student->Get_Student();
    }


    public function create()
    {
        return $this->Student->Create_Student();
    }

    public function store(StoreStudentsRequest $request)
    {
        return $this->Student->Store_Student($request);
    }

    public function show($id)
    {

        return $this->Student->Show_Student($id);
    }


    public function edit($id)
    {
        return $this->Student->Edit_Student($id);
    }


    public function update(StoreStudentsRequest $request)
    {
        return $this->Student->Update_Student($request);
    }


    public function destroy(Request $request)
    {
        return $this->Student->Delete_Student($request);
    }

    public function Get_classrooms($id)
    {
        return $this->Student->Get_classrooms($id);
    }

    public function Get_Sections($id)
    {
        return $this->Student->Get_Sections($id);
    }

    public function Upload_attachment(Request $request)
    {
        return $this->Student->Upload_attachment($request);
    }

    public function Download_attachment($studentsname, $filename)
    {
        return $this->Student->Download_attachment($studentsname, $filename);
    }

    public function Delete_attachment(Request $request)
    {
        return $this->Student->Delete_attachment($request);
    }
    function student_report($id)
    {
        $student = Student::with([
            'gender',
            'Nationality',
            'academicYear',
            'myparent',
            'sections.term.academicyear',
            'sections.term.classroom.Grades',
            'sections.term.subjects',
        ])->findOrFail($id);

        // Teacher-subject assignments per section
        $sectionTeachers = [];
        foreach ($student->sections as $section) {
            $records = teacher_subject::where('section_id', $section->id)->get();
            $teacherIds = $records->pluck('teacher_id')->unique();
            $teachers = Teacher::whereIn('id', $teacherIds)->get();
            $subjects = Subject::whereIn('id', $records->pluck('subject_id')->unique())->get();
            $data = [];
            foreach ($teachers as $teacher) {
                $sIds = $records->where('teacher_id', $teacher->id)->pluck('subject_id');
                $data[] = [
                    'teacher' => $teacher,
                    'subjects' => $subjects->whereIn('id', $sIds),
                ];
            }
            $sectionTeachers[$section->id] = $data;
        }

        // Marks/degrees grouped by section
        $degrees = Degree::where('student_id', $student->id)
            ->with(['quizze.subject', 'quizze.section.term.academicyear', 'quizze.section.term.classroom'])
            ->get();
        $degreesBySection = $degrees->groupBy(fn($d) => $d->quizze->section_id ?? 0);

        // Attendance per section
        $attendanceBySection = [];
        foreach ($student->sections as $section) {
            $att = Attendance::where('student_id', $student->id)
                ->where('section_id', $section->id)->get();
            $subjectStats = [];
            foreach ($att->pluck('subject_id')->unique() as $subId) {
                $sub = Subject::find($subId);
                if ($sub) {
                    $subjectStats[] = [
                        'name' => $sub->name,
                        'present' => $att->where('subject_id', $subId)->where('state', 1)->count(),
                        'absent' => $att->where('subject_id', $subId)->where('state', 0)->count(),
                        'late' => $att->where('subject_id', $subId)->where('state', 2)->count(),
                    ];
                }
            }
            $attendanceBySection[$section->id] = [
                'present' => $att->where('state', 1)->count(),
                'absent' => $att->where('state', 0)->count(),
                'late' => $att->where('state', 2)->count(),
                'subjects' => $subjectStats,
            ];
        }

        // Overall totals
        $totalPresent = $student->attendance()->where('state', 1)->count();
        $totalAbsent = $student->attendance()->where('state', 0)->count();
        $totalLate = $student->attendance()->where('state', 2)->count();

        return view('pages.Students.report.show', compact(
            'student', 'sectionTeachers', 'degreesBySection',
            'attendanceBySection', 'totalPresent', 'totalAbsent', 'totalLate'
        ));
    }

    // ============ Section Marks Report ============

    public function section_marks_report_select()
    {
        $academicYears = AcademicYear::all();
        $grades = Grade::all();
        return view('pages.Students.report.section_marks_select', compact('academicYears', 'grades'));
    }

    public function section_marks_report_show(Request $request)
    {
        $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'grade_id' => 'required|exists:grades,id',
            'classroom_id' => 'required|exists:classrooms,id',
            'term_id' => 'required|exists:terms,id',
            'section_id' => 'required|exists:sections,id',
        ], [
            'academic_year_id.required' => 'يرجى اختيار السنة الدراسية',
            'grade_id.required' => 'يرجى اختيار المرحلة',
            'classroom_id.required' => 'يرجى اختيار الصف',
            'term_id.required' => 'يرجى اختيار الفصل',
            'section_id.required' => 'يرجى اختيار الشعبة',
        ]);

        $section = Section::with(['term.academicyear', 'term.classroom.Grades', 'students', 'teachers'])->findOrFail($request->section_id);
        $exams = Quizze::where('section_id', $section->id)->with('subject')->get();
        $students = $section->students;

        // Group exams by subject
        $examsBySubject = $exams->groupBy(function ($exam) {
            return $exam->subject_id;
        });

        // Get degrees for all students in this section
        $studentDegrees = [];
        foreach ($students as $student) {
            $degrees = Degree::where('student_id', $student->id)
                ->whereIn('quizze_id', $exams->pluck('id'))
                ->get()
                ->keyBy('quizze_id');
            $studentDegrees[$student->id] = $degrees;
        }

        return view('pages.Students.report.section_marks_show', compact(
            'section', 'exams', 'examsBySubject', 'students', 'studentDegrees'
        ));
    }

    public function section_marks_custom_report_select()
    {
        $academicYears = AcademicYear::all();
        $grades = Grade::all();
        return view('pages.Students.report.section_marks_custom_select', compact('academicYears', 'grades'));
    }

    public function section_marks_custom_report_show(Request $request)
    {
        $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'grade_id' => 'required|exists:grades,id',
            'classroom_id' => 'required|exists:classrooms,id',
            'term_id' => 'required|exists:terms,id',
            'section_id' => 'required|exists:sections,id',
        ], [
            'academic_year_id.required' => 'يرجى اختيار السنة الدراسية',
            'grade_id.required' => 'يرجى اختيار المرحلة',
            'classroom_id.required' => 'يرجى اختيار الصف',
            'term_id.required' => 'يرجى اختيار الفصل',
            'section_id.required' => 'يرجى اختيار الشعبة',
        ]);

        $section = Section::with(['term.academicyear', 'term.classroom.Grades', 'students', 'teachers'])->findOrFail($request->section_id);
        $exams = Quizze::where('section_id', $section->id)->with('subject')->get();
        $students = $section->students;

        // Group exams by subject
        $examsBySubject = $exams->groupBy(function ($exam) {
            return $exam->subject_id;
        });

        // Get degrees for all students in this section
        $studentDegrees = [];
        foreach ($students as $student) {
            $degrees = Degree::where('student_id', $student->id)
                ->whereIn('quizze_id', $exams->pluck('id'))
                ->get()
                ->keyBy('quizze_id');
            $studentDegrees[$student->id] = $degrees;
        }

        return view('pages.Students.report.section_marks_custom_show', compact(
            'section', 'exams', 'examsBySubject', 'students', 'studentDegrees'
        ));
    }

    public function section_marks_appreciation_report_select()
    {
        $academicYears = AcademicYear::all();
        $grades = Grade::all();
        return view('pages.Students.report.section_marks_appreciation_select', compact('academicYears', 'grades'));
    }

    public function section_marks_appreciation_report_show(Request $request)
    {
        $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'grade_id' => 'required|exists:grades,id',
            'classroom_id' => 'required|exists:classrooms,id',
            'term_id' => 'required|exists:terms,id',
            'section_id' => 'required|exists:sections,id',
        ], [
            'academic_year_id.required' => 'يرجى اختيار السنة الدراسية',
            'grade_id.required' => 'يرجى اختيار المرحلة',
            'classroom_id.required' => 'يرجى اختيار الصف',
            'term_id.required' => 'يرجى اختيار الفصل',
            'section_id.required' => 'يرجى اختيار الشعبة',
        ]);

        $section = Section::with(['term.academicyear', 'term.classroom.Grades', 'students', 'teachers'])->findOrFail($request->section_id);
        $exams = Quizze::where('section_id', $section->id)->with('subject')->get();
        $students = $section->students;

        // Group exams by subject
        $examsBySubject = $exams->groupBy(function ($exam) {
            return $exam->subject_id;
        });

        // Get degrees for all students in this section
        $studentDegrees = [];
        foreach ($students as $student) {
            $degrees = Degree::where('student_id', $student->id)
                ->whereIn('quizze_id', $exams->pluck('id'))
                ->get()
                ->keyBy('quizze_id');
            $studentDegrees[$student->id] = $degrees;
        }

        return view('pages.Students.report.section_marks_appreciation_show', compact(
            'section', 'exams', 'examsBySubject', 'students', 'studentDegrees'
        ));
    }

    public function section_marks_appreciation2_report_select()
    {
        $academicYears = AcademicYear::all();
        $grades = Grade::all();
        return view('pages.Students.report.section_marks_appreciation2_select', compact('academicYears', 'grades'));
    }

    public function section_marks_appreciation2_report_show(Request $request)
    {
        $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'grade_id' => 'required|exists:grades,id',
            'classroom_id' => 'required|exists:classrooms,id',
            'term_id' => 'required|exists:terms,id',
            'section_id' => 'required|exists:sections,id',
        ], [
            'academic_year_id.required' => 'يرجى اختيار السنة الدراسية',
            'grade_id.required' => 'يرجى اختيار المرحلة',
            'classroom_id.required' => 'يرجى اختيار الصف',
            'term_id.required' => 'يرجى اختيار الفصل',
            'section_id.required' => 'يرجى اختيار الشعبة',
        ]);

        $section = Section::with(['term.academicyear', 'term.classroom.Grades', 'students', 'teachers'])->findOrFail($request->section_id);
        $exams = Quizze::where('section_id', $section->id)->with('subject')->get();
        $students = $section->students;

        // Group exams by subject
        $examsBySubject = $exams->groupBy(function ($exam) {
            return $exam->subject_id;
        });

        // Get degrees for all students in this section
        $studentDegrees = [];
        foreach ($students as $student) {
            $degrees = Degree::where('student_id', $student->id)
                ->whereIn('quizze_id', $exams->pluck('id'))
                ->get()
                ->keyBy('quizze_id');
            $studentDegrees[$student->id] = $degrees;
        }

        return view('pages.Students.report.section_marks_appreciation2_show', compact(
            'section', 'exams', 'examsBySubject', 'students', 'studentDegrees'
        ));
    }

    public function section_marks_appreciation3_report_select()
    {
        $academicYears = AcademicYear::all();
        $grades = Grade::all();
        return view('pages.Students.report.section_marks_appreciation3_select', compact('academicYears', 'grades'));
    }

    public function section_marks_appreciation3_report_show(Request $request)
    {
        $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'grade_id' => 'required|exists:grades,id',
            'classroom_id' => 'required|exists:classrooms,id',
            'term_id' => 'required|exists:terms,id',
            'section_id' => 'required|exists:sections,id',
        ], [
            'academic_year_id.required' => 'يرجى اختيار السنة الدراسية',
            'grade_id.required' => 'يرجى اختيار المرحلة',
            'classroom_id.required' => 'يرجى اختيار الصف',
            'term_id.required' => 'يرجى اختيار الفصل',
            'section_id.required' => 'يرجى اختيار الشعبة',
        ]);

        $section = Section::with(['term.academicyear', 'term.classroom.Grades', 'students', 'teachers'])->findOrFail($request->section_id);
        $exams = Quizze::where('section_id', $section->id)->with('subject')->get();
        $students = $section->students;

        // Group exams by subject
        $examsBySubject = $exams->groupBy(function ($exam) {
            return $exam->subject_id;
        });

        // Get degrees for all students in this section
        $studentDegrees = [];
        foreach ($students as $student) {
            $degrees = Degree::where('student_id', $student->id)
                ->whereIn('quizze_id', $exams->pluck('id'))
                ->get()
                ->keyBy('quizze_id');
            $studentDegrees[$student->id] = $degrees;
        }

        return view('pages.Students.report.section_marks_appreciation3_show', compact(
            'section', 'exams', 'examsBySubject', 'students', 'studentDegrees'
        ));
    }

    public function get_terms($academicyear_id, $classroom_id)
    {
        $terms = Term::where('academicyear_id', $academicyear_id)
            ->where('classrooms_id', $classroom_id)
            ->pluck('name', 'id');
        return response()->json($terms);
    }

    public function get_sections_by_term($term_id)
    {
        $sections = Section::where('term_id', $term_id)->pluck('Name_Section', 'id');
        return response()->json($sections);
    }
}
