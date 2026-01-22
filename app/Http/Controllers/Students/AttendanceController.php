<?php

namespace App\Http\Controllers\Students;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\ClassTable;
use App\Models\Section;
use App\Models\Term;
use App\Repository\AttendanceRepositoryInterface;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{

    protected $Attendance;

    public function __construct(AttendanceRepositoryInterface $Attendance)
    {
        $this->Attendance = $Attendance;
    }


    public function index()
    {
        return $this->Attendance->index();
    }



    public function store(Request $request)
    {
        return $this->Attendance->store($request);
    }


    public function show($id)
    {
        return $this->Attendance->show($id);
    }

    public function edit($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {
        //
    }

    public function show_section_subjects_day()
    {
        $sectionSubjects = [];
        if (date('D') == 'Sun') {
            $sectionSubjects = ClassTable::where('section_id', session()->get('section'))->where('lucture_day', 2)->get();
        } else if (date('D') == 'Mon') {
            $sectionSubjects = ClassTable::where('section_id', session()->get('section'))->where('lucture_day', 3)->get();
        } else if (date('D') == 'Tue') {
            $sectionSubjects = ClassTable::where('section_id', session()->get('section'))->where('lucture_day', 4)->get();
        } else if (date('D') == 'Wed') {
            $sectionSubjects = ClassTable::where('section_id', session()->get('section'))->where('lucture_day', 6)->get();
        } else if (date('D') == 'Thu') {
            $sectionSubjects = ClassTable::where('section_id', session()->get('section'))->where('lucture_day', 5)->get();
        } else if (date('D') == 'Sat') {
            $sectionSubjects = ClassTable::where('section_id', session()->get('section'))->where('lucture_day', 1)->get();
        } else if (date('D') == 'Fri') {
            $sectionSubjects = ClassTable::where('section_id', session()->get('section'))->where('lucture_day', 7)->get();
        }
        return view('pages.generel.section_setting.section_attendences.show_sections_subjects_day', compact('sectionSubjects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create_attendance($subject)
    {
        $sectionStudents = Section::findOrFail(session()->get('section'))->students;
        $classTable = ClassTable::findOrFail($subject);
        return view('pages.generel.section_setting.section_attendences.get_section_attendace', compact('sectionStudents', 'classTable'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store_attendance(Request $request)
    {
        foreach ($request->attendences as $student_id => $state) {
            $attendance = Attendance::where('student_id', $student_id)
                ->where('section_id', session()->get('section'))
                ->where('subject_id', $request->subject)
                ->where('lucture_number', $request->lucture_number)
                ->where('attendance_date', date("Y-m-d"))->get();

            if ($attendance->count() > 0) {
                $attendance[0]->student_id = $student_id;
                $attendance[0]->section_id = session()->get('section');
                $attendance[0]->subject_id = $request->subject;
                $attendance[0]->lucture_number = $request->lucture_number;
                $attendance[0]->attendance_date = date("Y-m-d");
                $attendance[0]->state = $state;
                $attendance[0]->notes = $request->notes[$student_id];
                $attendance[0]->save();
            } else {
                $newAttendance = new Attendance();
                $newAttendance->student_id = $student_id;
                $newAttendance->section_id = session()->get('section');
                $newAttendance->subject_id = $request->subject;
                $newAttendance->lucture_number = $request->lucture_number;
                $newAttendance->attendance_date = date("Y-m-d");
                $newAttendance->state = $state;
                $newAttendance->notes = $request->notes[$student_id];
                $newAttendance->save();
            }
        }
        toastr()->success('تم الحفظ بنجاح');
        return redirect()->route('create_attendance', $request->class_table_id);
    }

    function createAttendenceExpect($date = null, $subjectId = null, $luctureNumber = null)
    {
        $subjects = Term::findOrFail(session()->get('term'))->subjects;
        $students = Section::findOrFail(session()->get('section'))->students;
        return view('pages.generel.section_setting.section_attendences.get_section_expect_attendace', compact('subjects', 'students', 'date', 'subjectId', 'luctureNumber'));
    }

    function storeAttendenceExpect(Request $request)
    {
        $request->validate(
            [
                'subject' => ['required'],
                'lucture_number' => ['required'],
            ],
            [
                'subject.required' => 'اختر المادة',
                'lucture_number.required' => 'اكتب رقم المحاضرة',
            ]
        );
        foreach ($request->attendences as $student_id => $state) {
            $attendance = Attendance::where('student_id', $student_id)
                ->where('section_id', session()->get('section'))
                ->where('subject_id', $request->subject)
                ->where('lucture_number', $request->lucture_number)
                ->where('attendance_date', $request->date)->get();

            if ($attendance->count() > 0) {
                $attendance[0]->student_id = $student_id;
                $attendance[0]->section_id = session()->get('section');
                $attendance[0]->subject_id = $request->subject;
                $attendance[0]->lucture_number = $request->lucture_number;
                $attendance[0]->attendance_date = $request->date;
                $attendance[0]->state = $state;
                $attendance[0]->notes = $request->notes[$student_id];
                $attendance[0]->save();
            } else {
                $newAttendance = new Attendance();
                $newAttendance->student_id = $student_id;
                $newAttendance->section_id = session()->get('section');
                $newAttendance->subject_id = $request->subject;
                $newAttendance->lucture_number = $request->lucture_number;
                $newAttendance->attendance_date = $request->date;
                $newAttendance->state = $state;
                $newAttendance->notes = $request->notes[$student_id];
                $newAttendance->save();
            }
        }
        toastr()->success('تم الحفظ بنجاح');
        return redirect()->route('createAttendenceExpect', [
            'date'    => $request->date,
            'subjectId' => $request->subject,
            'luctureNumber' => $request->lucture_number,
        ]);
    }

    function attendenceDelete(Request $request)
    {
        $request->validate(
            [
                'subject' => ['required'],
                'lucture_number' => ['required'],
            ],
            [
                'subject.required' => 'اختر المادة',
                'lucture_number.required' => 'اكتب رقم المحاضرة',
            ]
        );
        Attendance::where('section_id', session()->get('section'))
            ->where('subject_id', $request->subject)
            ->where('lucture_number', $request->lucture_number)
            ->where('attendance_date', $request->date)->delete();
        return redirect()->route('createAttendenceExpect');
    }
}
