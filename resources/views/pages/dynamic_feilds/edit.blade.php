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
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ route('dynamicFeilds.update', $field) }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label>Name</label>

                        <input type="text" name="name" class="form-control"
                            value="{{ old('name', $field->name ?? '') }}">
                    </div>

                    <div class="mb-3">
                        <label>Type</label>

                        <select name="type" class="form-control" style="height: 50px;">

                            <option value="text">Text</option>
                            <option value="number">Number</option>
                            <option value="date">Date</option>

                        </select>
                    </div>

                    <div class="form-check">
                        <input type="checkbox" name="is_required" class="form-check-input"
                            {{ old('is_required', $field->is_required ?? false) ? 'checked' : '' }}>

                        <label class="form-check-label">
                            Required
                        </label>
                    </div>
                    <button class="btn btn-primary">save</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- row closed -->
@endsection
@section('js')

@endsection
