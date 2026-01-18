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
            href="{{ route('show_sections_students') }}">الرجوع للطلاب</a>
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

                <button type="button" class="button x-small" data-toggle="modal" data-target="#exampleModal">
                    اضافة اختبار
                </button>
                <br><br>

                <div class="table-responsive">
                    <table id="datatable" class="table  table-hover table-sm table-bordered p-0" data-page-length="50"
                        style="text-align: center">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>الاختبار</th>
                                <th>المادة</th>
                                <th>الدرجة</th>
                                <th>العمليات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 0; ?>
                            @foreach ($exams as $exam)
                                <tr>
                                    <?php $i++; ?>
                                    <td>{{ $i }}</td>
                                    <td>{{ $exam->name }}</td>
                                    <td>{{ $exam->subject->name }}</td>
                                    <td>{{ $exam->full_mark }}</td>
                                    <td>
                                        <button type="button" class="btn btn-info btn-sm" data-toggle="modal"
                                            data-target="#edit{{ $exam->id }}"
                                            title="{{ trans('Academic_Years_trans.Edit') }}"><i
                                                class="fa fa-edit"></i></button>
                                        <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                            data-target="#delete{{ $exam->id }}"
                                            title="{{ trans('Academic_Years_trans.Delete') }}"><i
                                                class="fa fa-trash"></i></button>
                                    </td>
                                </tr>

                                <div class="modal fade" id="edit{{ $exam->id }}" tabindex="-1" role="dialog"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" style="font-family: 'Cairo', sans-serif;"
                                                    id="exampleModalLabel">
                                                    {{ trans('Sections_trans.edit_Section') }}
                                                </h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ route('update_sections_exams') }}" method="POST">
                                                    {{ csrf_field() }}
                                                    <div class="row">
                                                        <div class="col">
                                                            <input type="text" name="Name_Exam_Ar"
                                                                class="form-control"
                                                                value="{{ $exam->getTranslation('name', 'ar') }}">
                                                        </div>

                                                        <div class="col">
                                                            <input type="text" name="Name_Exam_En"
                                                                class="form-control"
                                                                value="{{ $exam->getTranslation('name', 'en') }}">
                                                            <input id="id" type="hidden" name="id"
                                                                class="form-control" value="{{ $exam->id }}">
                                                        </div>

                                                    </div><br>
                                                    <div class="col">
                                                        <label for="full_mark" class="mr-sm-2">العدد
                                                            الدرجة
                                                            :</label>
                                                        <input value="{{ $exam->full_mark }}" type="number"
                                                            class="form-control" name="full_mark">
                                                    </div>
                                                    <br>
                                                    <div class="col">
                                                        <label for="subject_id" class="control-label">المادة</label>
                                                        <select name="subject_id" class="fancyselect"
                                                            id="exampleFormControlSelect23">
                                                            @foreach ($subjects as $subject)
                                                                <option
                                                                    @if ($exam->subject_id == $subject->id) selected @endif
                                                                    value="{{ $subject->id }}">
                                                                    {{ $subject->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">{{ trans('Sections_trans.Close') }}</button>
                                                        <button type="submit"
                                                            class="btn btn-success">{{ trans('Sections_trans.submit') }}</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- delete_modal_Grade -->
                                <div class="modal fade" id="delete{{ $exam->id }}" tabindex="-1"
                                    role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 style="font-family: 'Cairo', sans-serif;" class="modal-title"
                                                    id="exampleModalLabel">
                                                    {{ trans('Sections_trans.delete_Section') }}
                                                </h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ route('delete_sections_exams') }}" method="post">
                                                    @csrf
                                                    {{ $exam->name }}
                                                    {{ trans('Sections_trans.Warning_Section') }}
                                                    <input id="id" type="hidden" name="id"
                                                        class="form-control" value="{{ $exam->id }}">
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">{{ trans('Sections_trans.Close') }}</button>
                                                        <button type="submit"
                                                            class="btn btn-danger">{{ trans('Sections_trans.submit') }}</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        </tbody>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>


    <!--اضافة قسم جديد -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" style="font-family: 'Cairo', sans-serif;" id="exampleModalLabel">
                        {{ trans('Sections_trans.add_section') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form action="{{ route('store_sections_exams') }}" method="POST">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col">
                                <input type="text" name="Name_Exam_Ar" class="form-control"
                                    placeholder="اسم الاختبار باللغة العربية">
                            </div>

                            <div class="col">
                                <input type="text" name="Name_Exam_En" class="form-control"
                                    placeholder="اسم الاختبار باللغة الانجليزية">
                            </div>
                            <input type="hidden" name="section_id" class="form-control"
                                value="{{ session()->get('section') }}">
                        </div>
                        <br>
                        <div class="col">
                            <label for="full_mark" class="mr-sm-2">
                                الدرجة
                                :</label>
                            <input value="0" type="number" class="form-control" name="full_mark">
                        </div>
                        <br>

                        <div class="col">
                            <label for="subject_id" class="control-label">المادة</label>
                            <select name="subject_id" class="fancyselect" id="exampleFormControlSelect23">
                                @foreach ($subjects as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                @endforeach
                            </select>
                        </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-dismiss="modal">{{ trans('Sections_trans.Close') }}</button>
                    <button type="submit" class="btn btn-success">{{ trans('Sections_trans.submit') }}</button>
                </div>
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
