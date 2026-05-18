<?php


namespace App\Repository;


use App\Models\Grade;
use App\Models\promotion;
use App\Models\Student;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class StudentPromotionRepository implements StudentPromotionRepositoryInterface
{

    public function index()
    {
        $Grades = Grade::all();
        $academicYears = \App\Models\AcademicYear::all();
        return view('pages.Students.promotion.index',compact('Grades', 'academicYears'));
    }

    public function create()
    {
        $promotions = promotion::all();
        return view('pages.Students.promotion.management',compact('promotions'));
    }

    public function store($request)
    {
        DB::beginTransaction();

        try {

            $old_section = \App\Models\Section::findOrFail($request->section_id);
            $students = $old_section->students;

            if($students->count() < 1){
                return redirect()->back()->with('error_promotions', __('لاتوجد بيانات في جدول الطلاب'));
            }

            // update in table student
            foreach ($students as $student){

                // attach the new section with student
                $student->sections()->syncWithoutDetaching([$request->section_id_new]);
                
                // update student academic year
                $student->update([
                    'academic_year_id' => $request->academic_year_new,
                ]);

                // insert in to promotions
                Promotion::updateOrCreate([
                    'student_id'=>$student->id,
                    'from_grade'=>$request->Grade_id,
                    'from_Classroom'=>$request->Classroom_id,
                    'from_section'=>$request->section_id,
                    'to_grade'=>$request->Grade_id_new,
                    'to_Classroom'=>$request->Classroom_id_new,
                    'to_section'=>$request->section_id_new,
                    'academic_year'=>$request->academic_year,
                    'academic_year_new'=>$request->academic_year_new,
                ]);

            }
            DB::commit();
            toastr()->success(trans('messages.success'));
            return redirect()->back();

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }

    }

    public function destroy($request)
    {
        DB::beginTransaction();

        try {

            // التراجع عن الكل
            if($request->page_id ==1){

             $Promotions = Promotion::all();
             foreach ($Promotions as $Promotion){

                 //التحديث في جدول الطلاب
                 $ids = explode(',',$Promotion->student_id);
                 $students = \App\Models\Student::whereIn('id', $ids)->get();
                 foreach ($students as $student) {
                     $student->sections()->detach($Promotion->to_section);
                     $student->update([
                         'academic_year_id' => $Promotion->academic_year,
                     ]);
                 }

                 //حذف جدول الترقيات
                 Promotion::truncate();

             }
                DB::commit();
                toastr()->error(trans('messages.Delete'));
                return redirect()->back();

            }

            else{

                $Promotion = Promotion::findorfail($request->id);
                $student = \App\Models\Student::find($Promotion->student_id);
                if ($student) {
                    $student->sections()->detach($Promotion->to_section);
                    $student->update([
                        'academic_year_id' => $Promotion->academic_year,
                    ]);
                }


                Promotion::destroy($request->id);
                DB::commit();
                toastr()->error(trans('messages.Delete'));
                return redirect()->back();

            }

        }

        catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }


}
