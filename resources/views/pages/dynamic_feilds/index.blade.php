@extends('layouts.master')
@section('css')

@section('title')
    empty
@stop
@endsection
@section('page-header')
<!-- breadcrumb -->
@section('PageTitle')
    empty
@stop
<!-- breadcrumb -->
@endsection
@section('content')
<!-- row -->
<div class="row">
    <div class="col-md-12 mb-30">
        <div class="card card-statistics h-100">
            <div class="card-body">
                <div class="container">

                    <div class="d-flex justify-content-between mb-3">
                        <h3>Fields Management</h3>

                        <a href="{{ route('dynamicFeilds.create') }}" class="btn btn-primary">
                            Add Field
                        </a>
                    </div>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Required</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach ($fields as $field)
                                <tr>
                                    <td>{{ $field->name }}</td>
                                    <td>{{ $field->type }}</td>
                                    <td>
                                        {{ $field->is_required ? 'Yes' : 'No' }}
                                    </td>

                                    <td>
                                        <a href="{{ route('dynamicFeilds.edit', $field) }}"
                                            class="btn btn-warning btn-sm">
                                            Edit
                                        </a>

                                        <button class="btn btn-danger btn-sm"
                                            data-target="#Delete_Student{{ $field->id }}" data-toggle="modal"
                                            href="##Delete_Student{{ $field->id }}">
                                            حذف</button>
                                    </td>
                                </tr>


                                <div class="modal fade" id="Delete_Student{{ $field->id }}" tabindex="-1"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 style="font-family: 'Cairo', sans-serif;" class="modal-title"
                                                    id="exampleModalLabel">{{ trans('Students_trans.Deleted_Student') }}
                                                </h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ route('dynamicFeilds.destroy', $field) }}"
                                                    method="post">
                                                    @csrf
                                                    @method('DELETE')

                                                    <input type="hidden" name="id" value="{{ $field->id }}">

                                                    <h5 style="font-family: 'Cairo', sans-serif;">
                                                        {{ trans('Students_trans.Deleted_Student_tilte') }}</h5>
                                                    <input type="text" readonly value="{{ $field->name }}"
                                                        class="form-control">

                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">{{ trans('Students_trans.Close') }}</button>
                                                        <button
                                                            class="btn btn-danger">{{ trans('Students_trans.submit') }}</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>
<!-- row closed -->
@endsection
@section('js')

@endsection
