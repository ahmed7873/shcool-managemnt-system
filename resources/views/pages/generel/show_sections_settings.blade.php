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
    الاقسام
@stop
@endsection
@section('page-header')
<div class="title mb-3">
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
</div>
<!-- breadcrumb -->
@section('PageTitle')
    الاقسام
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
                <div class="row" style="justify-content: center">
                    <a href="{{ route('show_sections_students') }}" class="btn btn-primary col-3 m-1 p-2 text-center">الطلاب</a>
                    <a href="{{ route('show_sections_teachers') }}" class="btn btn-info col-3 m-1 p-2 text-center">المدرسين</a>
                    <a href="{{ route('show_class_tabel') }}" class="btn btn-success col-3 m-1 p-2 text-center">جدول الحصص</a>
                    <a href="{{ route('get_sections_exams') }}" class="btn btn-danger col-3 m-1 p-2 text-center">الاختبارات</a>
                    <a href="{{ route('show_section_subjects') }}" class="btn btn-warning col-3 m-1 p-2 text-center">الدرجات</a>
                    <a href="{{ route('show_section_subjects_day') }}" class="btn btn-dark col-3 m-1 p-2 text-center">التحضير</a>
                </div>
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
