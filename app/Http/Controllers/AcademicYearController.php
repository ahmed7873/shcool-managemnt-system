<?php

namespace App\Http\Controllers;

use App\Http\Requests\AcademicYearRequest;
use App\Models\AcademicYear;
use Illuminate\Http\Request;

class AcademicYearController extends Controller
{
    public function index()
    {
        $academicYears = AcademicYear::all();
        return view('pages.academicYears.academic_years', compact('academicYears'));
    }
    public function store(AcademicYearRequest $request)
    {
        try {
            $validated = $request->validated();
            $academicYear = new AcademicYear();
            $academicYear->academicyear = ['en' => $request->Name_en, 'ar' => $request->Name];
            $academicYear->save();
            toastr()->success(trans('messages.success'));
            return redirect()->route('academic_years');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }
    public function update(AcademicYearRequest $request)
    {
        try {
            $validated = $request->validated();
            $academicYear = AcademicYear::findOrFail($request->id);
            $academicYear->update([
                $academicYear->academicyear = ['ar' => $request->Name, 'en' => $request->Name_en],
            ]);
            toastr()->success(trans('messages.Update'));
            return redirect()->route('academic_years');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }
    public function destroy(Request $request)
    {
        AcademicYear::findOrFail($request->id)->delete();
        toastr()->error(trans('messages.Delete'));
        return redirect()->route('academic_years');
    }
}
