<?php

namespace App\Http\Controllers;

use App\Models\ClassTable;
use App\Models\Term;
use Illuminate\Http\Request;

class ClassTableController extends Controller
{
    function show_class_tabel()
    {
        $subjects = Term::findOrFail(session()->get('term'))->subjects;
        $classTableSat = ClassTable::where('section_id', session()->get('section'))->where('lucture_day', 1)->get();
        $classTableSun = ClassTable::where('section_id', session()->get('section'))->where('lucture_day', 2)->get();
        $classTableMon = ClassTable::where('section_id', session()->get('section'))->where('lucture_day', 3)->get();
        $classTableTue = ClassTable::where('section_id', session()->get('section'))->where('lucture_day', 4)->get();
        $classTableThu = ClassTable::where('section_id', session()->get('section'))->where('lucture_day', 5)->get();
        $classTableWend = ClassTable::where('section_id', session()->get('section'))->where('lucture_day', 6)->get();
        $classTableFri = ClassTable::where('section_id', session()->get('section'))->where('lucture_day', 7)->get();
        return view('pages.generel.section_setting.class_table.show_class_tabel', compact('subjects', 'classTableSat', 'classTableSun', 'classTableMon', 'classTableTue', 'classTableThu', 'classTableWend', 'classTableFri'));
    }
    function save_class_tabel(Request $request)
    {
        $sectionId = session()->get('section');
        ClassTable::where('section_id', $sectionId)->delete();
        foreach ($request->satSubjects as $luctureNumber => $subject) {
            if ($subject != "ـــــــــــــــــــــ") {
                $classTableRow = new ClassTable();
                $classTableRow->section_id = $sectionId;
                $classTableRow->subject_id = $subject;
                $classTableRow->lucture_number = $luctureNumber;
                $classTableRow->lucture_day = 1;
                $classTableRow->start = $request->startSat[$luctureNumber];
                $classTableRow->end = $request->endSat[$luctureNumber];
                $classTableRow->save();
            }
        }
        foreach ($request->sanSubjects as $luctureNumber => $subject) {
            if ($subject != "ـــــــــــــــــــــ") {
                $classTableRow = new ClassTable();
                $classTableRow->section_id = $sectionId;
                $classTableRow->subject_id = $subject;
                $classTableRow->lucture_number = $luctureNumber;
                $classTableRow->lucture_day = 2;
                $classTableRow->start = $request->startSan[$luctureNumber];
                $classTableRow->end = $request->endSan[$luctureNumber];
                $classTableRow->save();
            }
        }
        foreach ($request->monSubjects as $luctureNumber => $subject) {
            if ($subject != "ـــــــــــــــــــــ") {
                $classTableRow = new ClassTable();
                $classTableRow->section_id = $sectionId;
                $classTableRow->subject_id = $subject;
                $classTableRow->lucture_number = $luctureNumber;
                $classTableRow->lucture_day = 3;
                $classTableRow->start = $request->startMon[$luctureNumber];
                $classTableRow->end = $request->endMon[$luctureNumber];
                $classTableRow->save();
            }
        }
        foreach ($request->tueSubjects as $luctureNumber => $subject) {
            if ($subject != "ـــــــــــــــــــــ") {
                $classTableRow = new ClassTable();
                $classTableRow->section_id = $sectionId;
                $classTableRow->subject_id = $subject;
                $classTableRow->lucture_number = $luctureNumber;
                $classTableRow->lucture_day = 4;
                $classTableRow->start = $request->startTue[$luctureNumber];
                $classTableRow->end = $request->endTue[$luctureNumber];
                $classTableRow->save();
            }
        }
        foreach ($request->thuSubjects as $luctureNumber => $subject) {
            if ($subject != "ـــــــــــــــــــــ") {
                $classTableRow = new ClassTable();
                $classTableRow->section_id = $sectionId;
                $classTableRow->subject_id = $subject;
                $classTableRow->lucture_number = $luctureNumber;
                $classTableRow->lucture_day = 5;
                $classTableRow->start = $request->startThu[$luctureNumber];
                $classTableRow->end = $request->endThu[$luctureNumber];
                $classTableRow->save();
            }
        }
        foreach ($request->wendSubjects as $luctureNumber => $subject) {
            if ($subject != "ـــــــــــــــــــــ") {
                $classTableRow = new ClassTable();
                $classTableRow->section_id = $sectionId;
                $classTableRow->subject_id = $subject;
                $classTableRow->lucture_number = $luctureNumber;
                $classTableRow->lucture_day = 6;
                $classTableRow->start = $request->startWend[$luctureNumber];
                $classTableRow->end = $request->endWend[$luctureNumber];
                $classTableRow->save();
            }
        }
        foreach ($request->friSubjects as $luctureNumber => $subject) {
            if ($subject != "ـــــــــــــــــــــ") {
                $classTableRow = new ClassTable();
                $classTableRow->section_id = $sectionId;
                $classTableRow->subject_id = $subject;
                $classTableRow->lucture_number = $luctureNumber;
                $classTableRow->lucture_day = 7;
                $classTableRow->start = $request->startFri[$luctureNumber];
                $classTableRow->end = $request->endFri[$luctureNumber];
                $classTableRow->save();
            }
        }
        return redirect()->route('show_class_tabel');;
    }
}
