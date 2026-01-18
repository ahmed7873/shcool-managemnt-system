@extends('layouts.master')
@section('css')
    <style>
        a:hover {
            color: white !important;
            background-color: green;
            transition: all 0.5s;
        }
    </style>
@section('title')
    المراحل
@stop
@endsection
@section('page-header')
<div class="title mb-3">
    <nav class="navbar navbar-light bg-light p-2" style="justify-content: start;">
        <a class="mx-2" style="font-size: 14px; text-decoration: underline" href="{{ route('generel') }}">السنوات
            الدراسية</a>->
        <a class="mx-2" style="font-size: 14px; text-decoration: underline"
            href="{{ route('show_grades', session()->get('academicYear')) }}">{{ App\Models\AcademicYear::findOrFail(session()->get('academicYear'))->academicyear }}</a>->
    </nav>
</div>
<!-- breadcrumb -->
@section('PageTitle')
    المراحل
@stop
<!-- breadcrumb -->
@endsection
@section('content')
<!-- row -->
<div class="row">
    <div class="col-md-12 mb-30">
        <div class="card card-statistics h-100">
            <div class="card-body">
                <div class="row">
                    @foreach ($grades as $grade)
                        <a href="{{ route('show_classrooms', $grade) }}"
                            class="col-3 card m-1 p-2 text-center">{{ $grade->Name }}</a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
<!-- row closed -->
@endsection
@section('js')

@endsection
