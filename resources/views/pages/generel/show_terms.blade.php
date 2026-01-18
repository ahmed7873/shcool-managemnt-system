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
    الفصول الدراسية
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
    </nav>
</div>
<!-- breadcrumb -->
@section('PageTitle')
    الفصول الدراسية
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
                    اضافة فصل
                </button>
                <br><br>

                <div class="table-responsive">
                    <table id="datatable" class="table  table-hover table-sm table-bordered p-0" data-page-length="50"
                        style="text-align: center">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>الفصل الدراسي</th>
                                <th>الصف</th>
                                <th>السنة الدراسية</th>
                                <th>العمليات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 0; ?>
                            @foreach ($terms as $term)
                                <tr>
                                    <?php $i++; ?>
                                    <td>{{ $i }}</td>
                                    <td>
                                        <a href="{{ route('show_sections', $term->id) }}" class="btn btn-success">
                                            {{ $term->name }}
                                        </a>
                                    </td>
                                    <td>{{ $term->classroom->Name_Class }}</td>
                                    <td>{{ $term->academicyear->academicyear }}</td>
                                    <td>
                                        <button type="button" class="btn btn-info btn-sm" data-toggle="modal"
                                            data-target="#edit{{ $term->id }}"
                                            title="{{ trans('Academic_Years_trans.Edit') }}"><i
                                                class="fa fa-edit"></i></button>
                                        <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                            data-target="#delete{{ $term->id }}"
                                            title="{{ trans('Academic_Years_trans.Delete') }}"><i
                                                class="fa fa-trash"></i></button>
                                    </td>
                                </tr>

                                <!-- edit_modal_Grade -->
                                <div class="modal fade" id="edit{{ $term->id }}" tabindex="-1" role="dialog"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 style="font-family: 'Cairo', sans-serif;" class="modal-title"
                                                    id="exampleModalLabel">
                                                    تعديل الفصل الدراسي
                                                </h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <!-- add_form -->
                                                <form action="{{ route('terms_update') }}" method="post">
                                                    {{ method_field('patch') }}
                                                    @csrf
                                                    <div class="row">
                                                        <div class="col">
                                                            <label for="Name" class="mr-sm-2">الفصل الدراسي عربي
                                                                :</label>
                                                            <input id="Name" type="text" name="Name"
                                                                class="form-control"
                                                                value="{{ $term->getTranslation('name', 'ar') }}"
                                                                required>
                                                        </div>
                                                        <div class="col">
                                                            <label for="Name_en" class="mr-sm-2">الفصل الدراسي انجليزي
                                                                :</label>
                                                            <input type="text" class="form-control"
                                                                value="{{ $term->getTranslation('name', 'en') }}"
                                                                name="Name_en" required>
                                                        </div>
                                                        <input id="id" type="hidden" name="id"
                                                            class="form-control" value="{{ $term->id }}">
                                                    </div>
                                                    <div class="col">
                                                        <label for="max_lectures_per_day" class="mr-sm-2">العدد
                                                            الاقصى للمحاضرات لليوم الواحد
                                                            :</label>
                                                        <input value="{{ $term->max_lectures_per_day }}" type="number" class="form-control"
                                                            name="max_lectures_per_day">
                                                    </div>
                                                    <br><br>

                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">{{ trans('Academic_Years_trans.Close') }}</button>
                                                        <button type="submit"
                                                            class="btn btn-success">{{ trans('Academic_Years_trans.submit') }}</button>
                                                    </div>
                                                </form>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- delete_modal_Grade -->
                                <div class="modal fade" id="delete{{ $term->id }}" tabindex="-1" role="dialog"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 style="font-family: 'Cairo', sans-serif;" class="modal-title"
                                                    id="exampleModalLabel">
                                                    {{ trans('Academic_Years_trans.delete_Grade') }}
                                                </h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ route('terms_delete') }}" method="post">
                                                    {{ method_field('Delete') }}
                                                    @csrf
                                                    {{ $term->name }}
                                                    {{ trans('Academic_Years_trans.Warning_Grade') }}
                                                    <input id="id" type="hidden" name="id"
                                                        class="form-control" value="{{ $term->id }}">
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">{{ trans('Academic_Years_trans.Close') }}</button>
                                                        <button type="submit"
                                                            class="btn btn-danger">{{ trans('Academic_Years_trans.submit') }}</button>
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


    <!-- add_modal_Grade -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 style="font-family: 'Cairo', sans-serif;" class="modal-title" id="exampleModalLabel">
                        {{ trans('Academic_Years_trans.add_Grade') }}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- add_form -->
                    <form action="{{ route('terms_store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col">
                                <label for="Name" class="mr-sm-2">الفصل الدراسي عربي
                                    :</label>
                                <input id="Name" type="text" name="Name" class="form-control">
                            </div>
                            <div class="col">
                                <label for="Name_en" class="mr-sm-2">الفصل الدراسي انجليزي
                                    :</label>
                                <input type="text" class="form-control" name="Name_en">
                            </div>
                            <input type="hidden" value="{{ session()->get('academicYear') }}" class="form-control"
                                name="academicyear_id">
                            <input type="hidden" value="{{ session()->get('classroom') }}" class="form-control"
                                name="classrooms_id">
                        </div>
                        <div class="col">
                            <label for="max_lectures_per_day" class="mr-sm-2">العدد الاقصى للمحاضرات لليوم الواحد
                                :</label>
                            <input type="number" value="7" class="form-control" name="max_lectures_per_day">
                        </div>
                        <br><br>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-dismiss="modal">{{ trans('Academic_Years_trans.Close') }}</button>
                    <button type="submit"
                        class="btn btn-success">{{ trans('Academic_Years_trans.submit') }}</button>
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
