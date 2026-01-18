@extends('layouts.master')
@section('css')
    <style>
        a:hover {
            color: white !important;
            background-color: green;
            transition: all 0.5s;
        }

        .nice-select.fancyselect {
            width: 100%
        }
    </style>
    @toastr_css
@section('title')
    مواد الفصل
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
            href="{{ route('show_section_subjects') }}">الرجوع للمواد</a>
    </div>
</div>
<!-- breadcrumb -->
@section('PageTitle')
    مواد الفصل
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

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @isset($exams[0]->id)
                    <h1 class="text-center">درجات المادة {{ $exams[0]->subject->name }}</h1>
                @endisset
                <br><br>

                <form action="{{ route('store_sections_marks') }}" method="post">
                    @csrf
                    @isset($exams[0]->id)
                        <input type="hidden" name="subject" value="{{ $exams[0]->subject->id }}">
                    @endisset
                    <div class="table-responsive">
                        <table id="datatable" class="table  table-hover table-sm table-bordered p-0"
                            data-page-length="50" style="text-align: center">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>الطالب</th>
                                    @foreach ($exams as $exam)
                                        <th>{{ $exam->name }} من {{ $exam->full_mark }}</th>
                                    @endforeach
                                    <th>المجموع</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0; ?>
                                @foreach ($sectionStudents as $sectionStudent)
                                    <tr>
                                        <?php $i++;
                                        $total = 0; ?>
                                        <td>{{ $i }}</td>
                                        <td>{{ $sectionStudent->name }}</td>
                                        @foreach ($exams as $exam)
                                            <td>
                                                <input class="form-control text-center mark" type="number"
                                                    name="mark[{{ $sectionStudent->id }}][{{ $exam->id }}]"
                                                    id="" max="{{ $exam->full_mark }}" min="0" step="any"
                                                    @if (isset(App\Models\Degree::where('quizze_id', $exam->id)->where('student_id', $sectionStudent->id)->first()->score)) value="{{ App\Models\Degree::where('quizze_id', $exam->id)->where('student_id', $sectionStudent->id)->first()->score }}" 
                                                    {{ $total += App\Models\Degree::where('quizze_id', $exam->id)->where('student_id', $sectionStudent->id)->first()->score }}
                                                    @else
                                                value="0.0" @endif>
                                            </td>
                                        @endforeach
                                        <td>{{ $total }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <button type="submit" class="button my-3" style="width: 100%;">
                        حفظ
                    </button>
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
