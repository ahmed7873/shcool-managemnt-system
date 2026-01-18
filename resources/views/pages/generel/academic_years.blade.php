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
    تصفح
@stop
@endsection
@section('page-header')
<!-- breadcrumb -->
@section('PageTitle')
    تصفح
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
                    @foreach ($academicYears as $academicYear)
                        <a href="{{ route('show_grades', $academicYear) }}"
                            class="col-3 card m-1 p-2 text-center">{{ $academicYear->academicyear }}</a>
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
