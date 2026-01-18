@extends('layouts.master')
@section('css')
    <style>
        a:hover {
            color: white !important;
            background-color: green;
            transition: all 0.5s;
        }
    </style>
    @toastr_css
@section('title')
    مدرسي القسم
@stop
@endsection
@section('page-header')
<div class="title mb-3">
    <div style="display: flex; justify-content: space-between;">
        <nav class="navbar navbar-light bg-light p-2" style="justify-content: start;">
            <a class="mx-2" style="font-size: 14px; text-decoration: underline" href="{{ route('generel') }}">السنوات
                الدراسية</a>->
            <a class="mx-2" style="font-size: 14px; text-decoration: underline"
                href="{{ route('show_grades', session()->get('academicYear')) }}">{{ App\Models\AcademicYear::findOrFail(session()->get('academicYear'))->academicyear }}</a>->
            <a class="mx-2" style="font-size: 14px; text-decoration: underline"
                href="{{ route('show_classrooms', session()->get('grade')) }}">{{ App\Models\Grade::findOrFail(session()->get('grade'))->Name }}</a>->
            <a class="mx-2" style="font-size: 14px; text-decoration: underline"
                href="{{ route('show_terms', session()->get('classroom')) }}">{{ App\Models\Classroom::findOrFail(session()->get('classroom'))->Name_Class }}</a>->
            <a class="mx-2" style="font-size: 14px; text-decoration: underline"
                href="{{ route('show_sections', session()->get('term')) }}">{{ App\Models\Term::findOrFail(session()->get('term'))->name }}</a>->
            <a class="mx-2" style="font-size: 14px; text-decoration: underline"
                href="{{ route('show_sections_settings', session()->get('section')) }}">{{ App\Models\Section::findOrFail(session()->get('section'))->Name_Section }}</a>
        </nav>
        <a class="mx-2" style="font-size: 14px; text-decoration: underline"
            href="{{ route('show_sections_teachers') }}">الرجوع للمدرسين</a>
    </div>
</div>
<!-- breadcrumb -->
@section('PageTitle')
    مدرسي القسم
@stop
<!-- breadcrumb -->
@endsection
@section('content')
<!-- row -->
<div class="row">


    {{-- @if ($errors->any())
        <div class="error">{{ $errors->first('Name') }}</div>
    @endif --}}



    <div class="col-xl-12 mb-30">
        <div class="card card-statistics h-100">
            <div class="card-body">
                <form class="p-3" action="{{ route('save_related_with_subjects') }}" method="POST">
                    @csrf
                    <h4> المدرس {{ $teacher->Name }}</h4>
                    <div class="form-group mb-3">
                        <p for="exampleInputEmail1">المواد</p>
                        @foreach ($subjects as $subject)
                            <input type="checkbox" name="subject_id[]" value="{{ $subject->id }}" id=""
                                @if ($teacherSubjectInThisProng->count() > 0) @foreach ($teacherSubjectInThisProng as $subject2)
                                    {{ $subject2->subject_id == $subject->id ? 'checked' : '' }}
                                @endforeach @endif
                                @if ($otherTeacherSubjectInThisProng->count() > 0) @foreach ($otherTeacherSubjectInThisProng as $otherSubject2)
                                    {{ $otherSubject2->subject_id == $subject->id ? 'disabled' : '' }}
                                @endforeach @endif>
                            <span class="mx-2">{{ $subject->name }}</span><br>
                        @endforeach
                        <input type="hidden" name="teacher_id" value="{{ $teacher->id }}">
                    </div>

                    <button style="width: 100%" type="submit" class="btn btn-success">حفظ</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- row closed -->
@endsection
@section('js')
@toastr_js
@toastr_render
@endsection
