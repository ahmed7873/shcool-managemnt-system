<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\TeacherAttendence;
use Illuminate\Http\Request;

class TeacherAttendenceController extends Controller
{
    public function createAttendenceTeachers($date = null)
    {
        $taechers = Teacher::all();
        return view('pages.teacher_attendance.index', compact('taechers', 'date'));
    }

    public function saveAttendenceTeachers(Request $request)
    {
        foreach ($request->attendences as $teacher => $value) {
            $attendance = TeacherAttendence::where('attendence_date', $request->date)->where('teacher_id', $teacher)->get();

            if ($attendance->count() > 0) {
                $attendance[0]->teacher_id = $teacher;
                $attendance[0]->attendence_date = $request->date;
                $attendance[0]->enter = $request->enter[$teacher];
                $attendance[0]->out = $request->out[$teacher];
                $attendance[0]->state = $value;
                $attendance[0]->notes = $request->notes[$teacher];
                $attendance[0]->save();
            } else {
                $newAttendance = new TeacherAttendence();
                $newAttendance->teacher_id = $teacher;
                $newAttendance->attendence_date = $request->date;
                $newAttendance->enter = $request->enter[$teacher];
                $newAttendance->out = $request->out[$teacher];
                $newAttendance->state = $value;
                $newAttendance->notes = $request->notes[$teacher];
                $newAttendance->save();
            }
        }
        return redirect()->route('createAttendenceTeachers');
    }

    function teacherAttendenceDelete(Request $request)
    {
        $request->validate(
            [
                'date' => ['required'],
            ],
            [
                'date.required' => 'اختر التاريخ',
            ]
        );
        TeacherAttendence::where('attendence_date', $request->date)->delete();
        return redirect()->route('createAttendenceTeachers');
    }
}
