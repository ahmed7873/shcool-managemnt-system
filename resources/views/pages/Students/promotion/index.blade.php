@extends('layouts.master')
@section('css')
    @toastr_css
@section('title')
    {{ trans('main_trans.Students_Promotions') }}
@stop
@endsection
@section('page-header')
<!-- breadcrumb -->
@section('PageTitle')
    {{ trans('main_trans.Students_Promotions') }}
@stop
<!-- breadcrumb -->
@endsection
@section('content')
<!-- row -->
<div class="row">

    <div class="col-md-12 mb-30">
        <div class="card card-statistics h-100">
            <div class="card-body">

                @if (Session::has('error_promotions'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>{{ Session::get('error_promotions') }}</strong>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <h6 style="color: red;font-family: Cairo">المرحلة الدراسية القديمة</h6><br>

                <form method="post" action="{{ route('Promotion.store') }}">
                    @csrf
                    <div class="form-row">
                        <div class="form-group col">
                            <label for="inputState">{{ trans('Students_trans.Grade') }}</label>
                            <select class="custom-select mr-sm-2" name="Grade_id" required>
                                <option selected disabled>{{ trans('Parent_trans.Choose') }}...</option>
                                @foreach ($Grades as $Grade)
                                    <option value="{{ $Grade->id }}">{{ $Grade->Name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col">
                            <label for="Classroom_id">{{ trans('Students_trans.classrooms') }} : <span
                                    class="text-danger">*</span></label>
                            <select class="custom-select mr-sm-2" name="Classroom_id" required>

                            </select>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="academic_year">{{ trans('Students_trans.academic_year') }} : <span
                                        class="text-danger">*</span></label>
                                <select class="custom-select mr-sm-2" name="academic_year" required>
                                    <option selected disabled>{{ trans('Parent_trans.Choose') }}...</option>
                                    @foreach ($academicYears as $year)
                                        <option value="{{ $year->id }}">{{ $year->academicyear }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group col">
                            <label for="term_id">{{ trans('Students_trans.term') ?? 'الفصل' }} : </label>
                            <select class="custom-select mr-sm-2" name="term_id" required>

                            </select>
                        </div>

                        <div class="form-group col">
                            <label for="section_id">{{ trans('Students_trans.section') }} : </label>
                            <select class="custom-select mr-sm-2" name="section_id" required>

                            </select>
                        </div>





                    </div>
                    <br>
                    <h6 style="color: red;font-family: Cairo">المرحلة الدراسية الجديدة</h6><br>

                    <div class="form-row">
                        <div class="form-group col">
                            <label for="inputState">{{ trans('Students_trans.Grade') }}</label>
                            <select class="custom-select mr-sm-2" name="Grade_id_new">
                                <option selected disabled>{{ trans('Parent_trans.Choose') }}...</option>
                                @foreach ($Grades as $Grade)
                                    <option value="{{ $Grade->id }}">{{ $Grade->Name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col">
                            <label for="Classroom_id">{{ trans('Students_trans.classrooms') }}: <span
                                    class="text-danger">*</span></label>
                            <select class="custom-select mr-sm-2" name="Classroom_id_new">

                            </select>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="academic_year_new">{{ trans('Students_trans.academic_year') }} : <span
                                        class="text-danger">*</span></label>
                                <select class="custom-select mr-sm-2" name="academic_year_new" required>
                                    <option selected disabled>{{ trans('Parent_trans.Choose') }}...</option>
                                    @foreach ($academicYears as $year)
                                        <option value="{{ $year->id }}">{{ $year->academicyear }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group col">
                            <label for="term_id_new">{{ trans('Students_trans.term') ?? 'الفصل' }} : </label>
                            <select class="custom-select mr-sm-2" name="term_id_new" required>

                            </select>
                        </div>

                        <div class="form-group col">
                            <label for="section_id_new">:{{ trans('Students_trans.section') }} </label>
                            <select class="custom-select mr-sm-2" name="section_id_new" required>

                            </select>
                        </div>




                    </div>
                    <button type="submit" class="btn btn-primary">تاكيد</button>
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

<script>
    $(document).ready(function() {
        // Unbind global handlers that might fetch sections from classrooms
        $('select[name="Classroom_id"]').off('change');
        $('select[name="Classroom_id_new"]').off('change');

        // OLD STAGE
        $('select[name="Classroom_id"], select[name="academic_year"]').on('change', function() {
            var Classroom_id = $('select[name="Classroom_id"]').val();
            var academic_year_id = $('select[name="academic_year"]').val();
            if (Classroom_id && academic_year_id) {
                $.ajax({
                    url: "{{ url('get_terms') }}/" + academic_year_id + "/" + Classroom_id,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        $('select[name="term_id"]').empty();
                        $('select[name="term_id"]').append(
                            '<option selected disabled >{{ trans('Parent_trans.Choose') }}...</option>'
                        );
                        $.each(data, function(key, value) {
                            $('select[name="term_id"]').append('<option value="' +
                                key + '">' + value + '</option>');
                        });
                    },
                });
            }
        });

        $('select[name="term_id"]').on('change', function() {
            var term_id = $(this).val();
            if (term_id) {
                $.ajax({
                    url: "{{ url('get_sections_by_term') }}/" + term_id,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        $('select[name="section_id"]').empty();
                        $('select[name="section_id"]').append(
                            '<option selected disabled >{{ trans('Parent_trans.Choose') }}...</option>'
                        );
                        $.each(data, function(key, value) {
                            $('select[name="section_id"]').append(
                                '<option value="' + key + '">' + value +
                                '</option>');
                        });
                    },
                });
            }
        });

        // NEW STAGE
        $('select[name="Classroom_id_new"], select[name="academic_year_new"]').on('change', function() {
            var Classroom_id = $('select[name="Classroom_id_new"]').val();
            var academic_year_id = $('select[name="academic_year_new"]').val();
            if (Classroom_id && academic_year_id) {
                $.ajax({
                    url: "{{ url('get_terms') }}/" + academic_year_id + "/" + Classroom_id,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        $('select[name="term_id_new"]').empty();
                        $('select[name="term_id_new"]').append(
                            '<option selected disabled >{{ trans('Parent_trans.Choose') }}...</option>'
                        );
                        $.each(data, function(key, value) {
                            $('select[name="term_id_new"]').append(
                                '<option value="' + key + '">' + value +
                                '</option>');
                        });
                    },
                });
            }
        });

        $('select[name="term_id_new"]').on('change', function() {
            var term_id = $(this).val();
            if (term_id) {
                $.ajax({
                    url: "{{ url('get_sections_by_term') }}/" + term_id,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        $('select[name="section_id_new"]').empty();
                        $('select[name="section_id_new"]').append(
                            '<option selected disabled >{{ trans('Parent_trans.Choose') }}...</option>'
                        );
                        $.each(data, function(key, value) {
                            $('select[name="section_id_new"]').append(
                                '<option value="' + key + '">' + value +
                                '</option>');
                        });
                    },
                });
            }
        });

    });
</script>

@endsection
