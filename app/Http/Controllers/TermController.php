<?php

namespace App\Http\Controllers;

use App\Http\Requests\TermsRequest;
use App\Models\Term;
use Illuminate\Http\Request;

class TermController extends Controller
{
    public function store(TermsRequest $request)
    {
        try {
            $validated = $request->validated();
            $term = new Term();
            $term->name = ['en' => $request->Name_en, 'ar' => $request->Name];
            $term->academicyear_id = $request->academicyear_id;
            $term->max_lectures_per_day = $request->max_lectures_per_day;
            $term->classrooms_id = $request->classrooms_id;
            $term->save();
            toastr()->success(trans('messages.success'));
            return redirect()->route('show_terms', session()->get('classroom'));
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }
    public function update(TermsRequest $request)
    {
        try {
            $validated = $request->validated();
            $term = Term::findOrFail($request->id);
            $term->update([
                $term->name = ['ar' => $request->Name, 'en' => $request->Name_en],
                $term->max_lectures_per_day = $request->max_lectures_per_day,
            ]);
            toastr()->success(trans('messages.Update'));
            return redirect()->route('show_terms', session()->get('classroom'));
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }
    public function destroy(Request $request)
    {
        Term::findOrFail($request->id)->delete();
        toastr()->error(trans('messages.Delete'));
        return redirect()->route('show_terms', session()->get('classroom'));
    }
}
