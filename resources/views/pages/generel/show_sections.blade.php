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
                    اضافة قسم
                </button>
                <a href="{{ route('show_term_subjects') }}" class="btn btn-warning mx-3">
                    مواد الفصل
                </a>
                <br><br>

                <div class="table-responsive">
                    <table id="datatable" class="table  table-hover table-sm table-bordered p-0" data-page-length="50"
                        style="text-align: center">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>القسم</th>
                                <th>الحالة</th>
                                <th>الفصل الدراسي</th>
                                <th>الصف</th>
                                <th>السنة الدراسية</th>
                                <th>العمليات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 0; ?>
                            @foreach ($sections as $section)
                                <tr>
                                    <?php $i++; ?>
                                    <td>{{ $i }}</td>
                                    <td>
                                        <a href="{{ route('show_sections_settings', $section) }}"
                                            class="btn btn-primary">
                                            {{ $section->Name_Section }}
                                        </a>
                                    </td>
                                    <td>
                                        @if ($section->Status === 1)
                                            <label
                                                class="badge badge-success">{{ trans('Sections_trans.Status_Section_AC') }}</label>
                                        @else
                                            <label
                                                class="badge badge-danger">{{ trans('Sections_trans.Status_Section_No') }}</label>
                                        @endif

                                    </td>
                                    <td>{{ $section->term->name }}</td>
                                    <td>{{ $section->term->classroom->Name_Class }}</td>
                                    <td>{{ $section->term->academicyear->academicyear }}</td>
                                    <td>
                                        <button type="button" class="btn btn-info btn-sm" data-toggle="modal"
                                            data-target="#edit{{ $section->id }}"
                                            title="{{ trans('Academic_Years_trans.Edit') }}"><i
                                                class="fa fa-edit"></i></button>
                                        <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                            data-target="#delete{{ $section->id }}"
                                            title="{{ trans('Academic_Years_trans.Delete') }}"><i
                                                class="fa fa-trash"></i></button>
                                    </td>
                                </tr>

                                <div class="modal fade" id="edit{{ $section->id }}" tabindex="-1" role="dialog"
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

                                                <form action="{{ route('Sections.update', 'test') }}" method="POST">
                                                    {{ method_field('patch') }}
                                                    {{ csrf_field() }}
                                                    <div class="row">
                                                        <div class="col">
                                                            <input type="text" name="Name_Section_Ar"
                                                                class="form-control"
                                                                value="{{ $section->getTranslation('Name_Section', 'ar') }}">
                                                        </div>

                                                        <div class="col">
                                                            <input type="text" name="Name_Section_En"
                                                                class="form-control"
                                                                value="{{ $section->getTranslation('Name_Section', 'en') }}">
                                                            <input id="id" type="hidden" name="id"
                                                                class="form-control" value="{{ $section->id }}">
                                                        </div>

                                                    </div>
                                                    <br>
                                                    <div class="col">
                                                        <div class="form-check">

                                                            @if ($section->Status === 1)
                                                                <input type="checkbox" checked class="form-check-input"
                                                                    name="Status" id="exampleCheck1">
                                                            @else
                                                                <input type="checkbox" class="form-check-input"
                                                                    name="Status" id="exampleCheck1">
                                                            @endif
                                                            <label class="form-check-label"
                                                                for="exampleCheck1">{{ trans('Sections_trans.Status') }}</label><br>

                                                            <div class="col">
                                                                <label for="inputName"
                                                                    class="control-label">{{ trans('Sections_trans.Name_Teacher') }}</label>
                                                                <select multiple name="teacher_id[]"
                                                                    class="form-control"
                                                                    id="exampleFormControlSelect2">
                                                                    @foreach ($teachers as $teacher)
                                                                        <option
                                                                            @foreach ($section->teachers as $selectedTeacher)
                                                                            @if ($selectedTeacher->id == $teacher->id)
                                                                                selected
                                                                            @endif @endforeach
                                                                            value="{{ $teacher->id }}">
                                                                            {{ $teacher->Name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>


                                            </div>
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


                                <!-- delete_modal_Grade -->
                                <div class="modal fade" id="delete{{ $section->id }}" tabindex="-1"
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
                                                <form action="{{ route('Sections.destroy', 'test') }}"
                                                    method="post">
                                                    {{ method_field('Delete') }}
                                                    @csrf
                                                    {{ $section->Name_Section }}
                                                    {{ trans('Sections_trans.Warning_Section') }}
                                                    <input id="id" type="hidden" name="id"
                                                        class="form-control" value="{{ $section->id }}">
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

                    <form action="{{ route('Sections.store') }}" method="POST">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col">
                                <input type="text" name="Name_Section_Ar" class="form-control"
                                    placeholder="{{ trans('Sections_trans.Section_name_ar') }}">
                            </div>

                            <div class="col">
                                <input type="text" name="Name_Section_En" class="form-control"
                                    placeholder="{{ trans('Sections_trans.Section_name_en') }}">
                            </div>
                            <input type="hidden" name="term_id" class="form-control"
                                value="{{ session()->get('term') }}">
                        </div>
                        <br>

                        <div class="col">
                            <label for="inputName"
                                class="control-label">{{ trans('Sections_trans.Name_Teacher') }}</label>
                            <select multiple name="teacher_id[]" class="form-control" id="exampleFormControlSelect2">
                                @foreach ($teachers as $teacher)
                                    <option value="{{ $teacher->id }}">{{ $teacher->Name }}</option>
                                @endforeach
                            </select>
                        </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-dismiss="modal">{{ trans('Sections_trans.Close') }}</button>
                    <button type="submit" class="btn btn-danger">{{ trans('Sections_trans.submit') }}</button>
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
