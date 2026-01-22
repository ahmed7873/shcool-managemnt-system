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
            href="{{ route('show_section_subjects_day') }}">الرجوع للمواد</a>
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

                <h1 class="text-center">تحضير المادة {{ App\Models\Subject::findOrFail($classTable->subject_id)->name }} المحاضرة رقم {{ $classTable->lucture_number }}</h1>
                <br><br>

                <form action="{{ route('store_attendance') }}" method="post">
                    @csrf
                    <input type="hidden" name="subject" value="{{ $classTable->subject_id }}">
                    <input type="hidden" name="lucture_number" value="{{ $classTable->lucture_number }}">
                    <input type="hidden" name="class_table_id" value="{{ $classTable->id }}">
                    <div class="table-responsive">
                        <table id="datatable" class="table  table-hover table-sm table-bordered p-0"
                            data-page-length="50" style="text-align: center">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th scope="col">الطالب</th>
                                    <th scope="col">الحالة</th>
                                    <th scope="col">ملاحظات</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0; ?>
                                @foreach ($sectionStudents as $sectionStudent)
                                    <tr>
                                        <?php $i++; ?>
                                        <td>{{ $i }}</td>
                                        <td>{{ $sectionStudent->name }}</td>
                                        <td scope="col">
                                            <label class="block text-gray-500 font-semibold sm:border-r sm:pr-4">
                                                <input name="attendences[{{ $sectionStudent->id }}]" checked
                                                    class="leading-tight" type="radio" value="1"
                                                    @if (isset(
                                                            $sectionStudent->attendance()->where('attendance_date', date('Y-m-d'))->where('section_id', session()->get('section'))->where('subject_id', $classTable->subject_id)->where('lucture_number', $classTable->lucture_number)->first()->state)) {{ $sectionStudent->attendance()->where('attendance_date', date('Y-m-d'))->where('section_id', session()->get('section'))->where('subject_id', $classTable->subject_id)->where('lucture_number', $classTable->lucture_number)->first()->state == 1? 'checked': '' }} @endif>
                                                <span class="text-success">حضور</span>
                                            </label>

                                            <label class="ml-4 block text-gray-500 font-semibold">
                                                <input name="attendences[{{ $sectionStudent->id }}]"
                                                    class="leading-tight" type="radio" value="0"
                                                    @if (isset(
                                                            $sectionStudent->attendance()->where('attendance_date', date('Y-m-d'))->where('section_id', session()->get('section'))->where('subject_id', $classTable->subject_id)->where('lucture_number', $classTable->lucture_number)->first()->state)) {{ $sectionStudent->attendance()->where('attendance_date', date('Y-m-d'))->where('section_id', session()->get('section'))->where('subject_id', $classTable->subject_id)->where('lucture_number', $classTable->lucture_number)->first()->state == 0? 'checked': '' }} @endif>
                                                <span class="text-danger">غياب</span>
                                            </label>

                                            <label class="ml-4 block text-gray-500 font-semibold">
                                                <input name="attendences[{{ $sectionStudent->id }}]"
                                                    class="leading-tight" type="radio" value="2"
                                                    @if (isset(
                                                            $sectionStudent->attendance()->where('attendance_date', date('Y-m-d'))->where('section_id', session()->get('section'))->where('subject_id', $classTable->subject_id)->where('lucture_number', $classTable->lucture_number)->first()->state)) {{ $sectionStudent->attendance()->where('attendance_date', date('Y-m-d'))->where('section_id', session()->get('section'))->where('subject_id', $classTable->subject_id)->where('lucture_number', $classTable->lucture_number)->first()->state == 2? 'checked': '' }} @endif>
                                                <span class="text-warning">متأخر</span>
                                            </label>
                                            {{-- @endif --}}
                                        </td>
                                        <td scope="col">
                                            <label class="block text-gray-500 font-semibold sm:border-r sm:pr-4">
                                                <textarea style="width: 300px;" name="notes[{{ $sectionStudent->id }}]" id="{{ $sectionStudent->id }}">
                                        @if (isset(
                                                $sectionStudent->attendance()->where('attendance_date', date('Y-m-d'))->where('section_id', session()->get('section'))->where('subject_id', $classTable->subject_id)->where('lucture_number', $classTable->lucture_number)->first()->state))
{{ $sectionStudent->attendance()->where('attendance_date', date('Y-m-d'))->where('section_id', session()->get('section'))->where('subject_id', $classTable->subject_id)->where('lucture_number', $classTable->lucture_number)->first()->notes }}
@endif
                                    </textarea>
                                            </label>
                                        </td>
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
