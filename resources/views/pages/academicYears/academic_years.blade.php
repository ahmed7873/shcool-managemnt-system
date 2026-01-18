@extends('layouts.master')
@section('css')
    @toastr_css
@section('title')
    {{ trans('Academic_Years_trans.title_page') }}
@stop
@endsection
@section('page-header')
<!-- breadcrumb -->
@section('PageTitle')
    {{ trans('main_trans.Grades') }}
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
                    {{ trans('Academic_Years_trans.add_Grade') }}
                </button>
                <br><br>

                <div class="table-responsive">
                    <table id="datatable" class="table  table-hover table-sm table-bordered p-0" data-page-length="50"
                        style="text-align: center">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ trans('Academic_Years_trans.Name') }}</th>
                                <th>{{ trans('Academic_Years_trans.Processes') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 0; ?>
                            @foreach ($academicYears as $academicYear)
                                <tr>
                                    <?php $i++; ?>
                                    <td>{{ $i }}</td>
                                    <td>{{ $academicYear->academicyear }}</td>
                                    <td>
                                        <button type="button" class="btn btn-info btn-sm" data-toggle="modal"
                                            data-target="#edit{{ $academicYear->id }}"
                                            title="{{ trans('Academic_Years_trans.Edit') }}"><i
                                                class="fa fa-edit"></i></button>
                                        <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                            data-target="#delete{{ $academicYear->id }}"
                                            title="{{ trans('Academic_Years_trans.Delete') }}"><i
                                                class="fa fa-trash"></i></button>
                                    </td>
                                </tr>

                                <!-- edit_modal_Grade -->
                                <div class="modal fade" id="edit{{ $academicYear->id }}" tabindex="-1" role="dialog"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 style="font-family: 'Cairo', sans-serif;" class="modal-title"
                                                    id="exampleModalLabel">
                                                    {{ trans('Academic_Years_trans.edit_Grade') }}
                                                </h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <!-- add_form -->
                                                <form action="{{ route('academic_years_update') }}" method="post">
                                                    {{ method_field('patch') }}
                                                    @csrf
                                                    <div class="row">
                                                        <div class="col">
                                                            <label for="Name"
                                                                class="mr-sm-2">{{ trans('Academic_Years_trans.stage_name_ar') }}
                                                                :</label>
                                                            <input id="Name" type="text" name="Name"
                                                                class="form-control"
                                                                value="{{ $academicYear->getTranslation('academicyear', 'ar') }}"
                                                                required>
                                                            <input id="id" type="hidden" name="id"
                                                                class="form-control" value="{{ $academicYear->id }}">
                                                        </div>
                                                        <div class="col">
                                                            <label for="Name_en"
                                                                class="mr-sm-2">{{ trans('Academic_Years_trans.stage_name_en') }}
                                                                :</label>
                                                            <input type="text" class="form-control"
                                                                value="{{ $academicYear->getTranslation('academicyear', 'en') }}"
                                                                name="Name_en" required>
                                                        </div>
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
                                <div class="modal fade" id="delete{{ $academicYear->id }}" tabindex="-1"
                                    role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                                <form action="{{ route('academic_years_delete') }}" method="post">
                                                    {{ method_field('Delete') }}
                                                    @csrf
                                                    {{ $academicYear->academicyear }}
                                                    {{ trans('Academic_Years_trans.Warning_Grade') }}
                                                    <input id="id" type="hidden" name="id"
                                                        class="form-control" value="{{ $academicYear->id }}">
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
                    <form action="{{ route('academic_years_store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col">
                                <label for="Name"
                                    class="mr-sm-2">{{ trans('Academic_Years_trans.stage_name_ar') }}
                                    :</label>
                                <input id="Name" type="text" name="Name" class="form-control">
                            </div>
                            <div class="col">
                                <label for="Name_en"
                                    class="mr-sm-2">{{ trans('Academic_Years_trans.stage_name_en') }}
                                    :</label>
                                <input type="text" class="form-control" name="Name_en">
                            </div>
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
