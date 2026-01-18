<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\Term;
use Illuminate\Http\Request;

class TermSubjectController extends Controller
{
    function show_term_subjects()
    {
        $sectionSubjects = Term::findOrFail(session()->get('term'))->subjects;
        return view('pages.generel.section_setting.section_subjects.show_section_subjects', compact('sectionSubjects'));
    }
    function get_term_subjects()
    {
        $sectionSubjects = Term::findOrFail(session()->get('term'))->subjects;
        $ids = [];
        $index = 0;
        $subjects = Subject::all();
        for ($i = 0; $i < $sectionSubjects->count(); $i++) {
            for ($j = 0; $j < $subjects->count(); $j++) {
                if ($sectionSubjects[$i]->id == $subjects[$j]->id) {
                    $ids[$index] = $sectionSubjects[$i]->id;
                    $index++;
                }
            }
        }
        $subjects = Subject::whereNotIn('id', $ids)->get();
        return view('pages.generel.section_setting.section_subjects.get_section_subjects', compact('subjects'));
    }
    function releatoin_term_subjects(Request $request)
    {
        $request->validate([
            'subjects' => ['required']
        ]);
        Term::findOrFail(session()->get('term'))->subjects()->attach($request->subjects);
        return redirect()->route('show_term_subjects');
    }
    function unreleatoin_term_subjects(Request $request)
    {
        $request->validate([
            'subjects' => ['required']
        ]);
        Term::findOrFail(session()->get('term'))->subjects()->detach($request->subjects);
        return redirect()->route('show_term_subjects');
    }
}
