<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExammRequest;
use App\Models\AcademicYear;
use App\Models\Attendance;
use App\Models\Classroom;
use App\Models\ClassTable;
use App\Models\Degree;
use App\Models\Grade;
use App\Models\Quizze;
use App\Models\Section;
use App\Models\Setting;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\teacher_subject;
use App\Models\Term;
use Illuminate\Http\Request;

class GeneralController extends Controller
{
    function academicYears()
    {
        $academicYears = AcademicYear::all();
        return view('pages.generel.academic_years', compact('academicYears'));
    }
    function show_grades($academicYear)
    {
        session()->put('academicYear', $academicYear);
        $grades = Grade::all();
        return view('pages.generel.show_grades', compact('grades'));
    }
    function show_classrooms($grade)
    {
        session()->put('grade', $grade);
        $classrooms = Classroom::where('Grade_id', $grade)->get();
        return view('pages.generel.show_classrooms', compact('classrooms'));
    }
    function show_terms($classroom)
    {
        session()->put('classroom', $classroom);
        $terms = Term::where('academicyear_id', session()->get('academicYear'))
            ->where('classrooms_id', $classroom)
            ->get();
        return view('pages.generel.show_terms', compact('terms'));
    }
    function show_sections($term)
    {
        session()->put('term', $term);
        $sections = Section::where('term_id', session()->get('term'))
            ->get();
        $teachers = Teacher::all();
        return view('pages.generel.show_sections', compact('sections', 'teachers'));
    }
    function show_sections_settings($section)
    {
        session()->put('section', $section);
        return view('pages.generel.show_sections_settings');
    }
    function show_sections_students()
    {
        $sectionStudents = Section::findOrFail(session()->get('section'))->students;
        return view('pages.generel.section_setting.section_students.show_section_students', compact('sectionStudents'));
    }
    function get_sections_students()
    {
        $sectionStudents = Section::findOrFail(session()->get('section'))->students;
        $ids = [];
        $index = 0;
        $students = Student::all();
        for ($i = 0; $i < $sectionStudents->count(); $i++) {
            for ($j = 0; $j < $students->count(); $j++) {
                if ($sectionStudents[$i]->id == $students[$j]->id) {
                    $ids[$index] = $sectionStudents[$i]->id;
                    $index++;
                }
            }
        }
        $students = Student::whereNotIn('id', $ids)->get();
        return view('pages.generel.section_setting.section_students.get_section_students', compact('students'));
    }
    function releatoin_section_students(Request $request)
    {
        $request->validate([
            'students' => ['required']
        ]);
        Section::findOrFail(session()->get('section'))->students()->attach($request->students);
        return redirect()->route('show_sections_students');
    }
    function unreleatoin_section_students(Request $request)
    {
        $request->validate([
            'students' => ['required']
        ]);
        Section::findOrFail(session()->get('section'))->students()->detach($request->students);
        return redirect()->route('show_sections_students');
    }
    function show_sections_teachers()
    {
        $sectionTeachers = Section::findOrFail(session()->get('section'))->teachers;
        return view('pages.generel.section_setting.section_teachers.show_section_teachers', compact('sectionTeachers'));
    }
    function related_with_subjects($teacher)
    {
        $teacherSubjectInThisProng = teacher_subject::where('teacher_id', $teacher)->where('section_id', session()->get('section'))->get();
        $otherTeacherSubjectInThisProng = teacher_subject::where('teacher_id', '<>', $teacher)->where('section_id', session()->get('section'))->get();
        $subjects = Term::findOrFail(session()->get('term'))->subjects;
        $teacher = Teacher::findOrFail($teacher);
        return view('pages.generel.section_setting.section_teachers.releated_with_subjects', compact('teacher', 'subjects', 'teacherSubjectInThisProng', 'otherTeacherSubjectInThisProng'));
    }
    function save_related_with_subjects(Request $request)
    {
        $teacherSubjectInThisProng = Teacher_subject::where('teacher_id', $request->teacher_id)->where('section_id', session()->get('section'))->get();
        foreach ($teacherSubjectInThisProng as $teacherSubjectInThisProng2) {
            $teacherSubjectInThisProng2->delete();
        }
        if (isset($request->subject_id)) {
            for ($i = 0; $i < count($request->subject_id); $i++) {
                $newTeacherSubject = new Teacher_subject();
                $newTeacherSubject->teacher_id = $request->teacher_id;
                $newTeacherSubject->subject_id = $request->subject_id[$i];
                $newTeacherSubject->section_id = session()->get('section');
                $newTeacherSubject->save();
            }
        }
        return redirect()->route('related_with_subjects', $request->teacher_id);
    }
    function get_sections_exams()
    {
        $exams = Quizze::where('section_id', session()->get('section'))->get();
        $subjects = Term::findOrFail(session()->get('term'))->subjects;
        return view('pages.generel.section_setting.section_exams.get_section_exams', compact('exams', 'subjects'));
    }
    function store_sections_exams(ExammRequest $request)
    {
        try {
            $exam = new Quizze();
            $exam->name = ['en' => $request->Name_Exam_Ar, 'ar' => $request->Name_Exam_En];
            $exam->full_mark = $request->full_mark;
            $exam->subject_id = $request->subject_id;
            $exam->section_id = $request->section_id;
            $exam->save();
            toastr()->success(trans('messages.success'));
            return redirect()->route('get_sections_exams');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }
    function update_sections_exams(ExammRequest $request)
    {
        try {
            $exam = Quizze::findOrFail($request->id);
            $exam->update([
                $exam->name = ['ar' => $request->Name_Exam_Ar, 'en' => $request->Name_Exam_En],
                $exam->full_mark = $request->full_mark,
                $exam->subject_id = $request->subject_id,
            ]);
            toastr()->success(trans('messages.Update'));
            return redirect()->route('get_sections_exams');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }
    public function delete_sections_exams(Request $request)
    {
        Quizze::findOrFail($request->id)->delete();
        toastr()->error(trans('messages.Delete'));
        return redirect()->route('get_sections_exams');
    }
    function show_section_subjects()
    {
        $subjects = Term::findOrFail(session()->get('term'))->subjects;
        return view('pages.generel.section_setting.section_marks.show_sections_subjects', compact('subjects'));
    }
    function get_sections_marks($subjectId)
    {
        $exams = Quizze::where('subject_id', $subjectId)->get();
        $sectionStudents = Section::findOrFail(session()->get('section'))->students;
        return view('pages.generel.section_setting.section_marks.get_section_marks', compact('exams', 'sectionStudents'));
    }
    public function store_sections_marks(Request $request)
    {
        foreach ($request->mark as $studentId => $exams) {
            foreach ($exams as $exam => $mark) {
                $oldMak = Degree::where('quizze_id', $exam)->where('student_id', $studentId)->get();
                if ($oldMak->count() > 0) {
                    $oldMak[0]->score = $mark;
                    $oldMak[0]->save();
                } else {
                    $myMark = new Degree();
                    $myMark->quizze_id = $exam;
                    $myMark->student_id = $studentId;
                    $myMark->score = $mark;
                    $myMark->save();
                }
            }
        }
        return redirect()->route('get_sections_marks', $request->subject);
    }
    // baracode store
    function baracoe()
    {
        $collection = Setting::all();
        $setting['setting'] = $collection->flatMap(function ($collection) {
            return [$collection->key => $collection->value];
        });
        return view('pages.baracode.baracode', compact('setting'));
    }
    function baracoe_store(Request $request)
    {
        $request->validate([
            'student_id' => ['required', 'exists:students,id']
        ]);
        $student = Student::findOrFail($request->student_id);
        $collection = Setting::all();
        $setting['setting'] = $collection->flatMap(function ($collection) {
            return [$collection->key => $collection->value];
        });
        foreach ($student->sections as $section) {
            if ($section->term->academicyear_id == $setting['setting']['current_session']) {
                $sectionSubjects = [];
                if (date('D') == 'Sun') {
                    $sectionSubjects = ClassTable::where('section_id', $section->id)->where('lucture_day', 2)->get();
                } else if (date('D') == 'Mon') {
                    $sectionSubjects = ClassTable::where('section_id', $section->id)->where('lucture_day', 3)->get();
                } else if (date('D') == 'Tue') {
                    $sectionSubjects = ClassTable::where('section_id', $section->id)->where('lucture_day', 4)->get();
                } else if (date('D') == 'Wed') {
                    $sectionSubjects = ClassTable::where('section_id', $section->id)->where('lucture_day', 6)->get();
                } else if (date('D') == 'Thu') {
                    $sectionSubjects = ClassTable::where('section_id', $section->id)->where('lucture_day', 5)->get();
                } else if (date('D') == 'Sat') {
                    $sectionSubjects = ClassTable::where('section_id', $section->id)->where('lucture_day', 1)->get();
                } else if (date('D') == 'Fri') {
                    $sectionSubjects = ClassTable::where('section_id', $section->id)->where('lucture_day', 7)->get();
                }
                foreach ($sectionSubjects as $subject) {
                    $attendance = Attendance::where('student_id', $request->student_id)
                        ->where('section_id', $section->id)
                        ->where('subject_id', $subject->subject_id)
                        ->where('lucture_number', $subject->lucture_number)
                        ->where('attendance_date', date("Y-m-d"))->get();

                    if ($attendance->count() > 0) {
                    } else {
                        $newAttendance = new Attendance();
                        $newAttendance->student_id = $request->student_id;
                        $newAttendance->section_id = $section->id;
                        $newAttendance->subject_id = $subject->subject_id;
                        $newAttendance->lucture_number = $subject->lucture_number;
                        $newAttendance->attendance_date = date("Y-m-d");
                        $newAttendance->state = 1;
                        $newAttendance->save();
                    }
                }
            }
        }
        toastr()->success("تم التحضير " . $student->name);
        return redirect()->route('baracoe');
    }
}
