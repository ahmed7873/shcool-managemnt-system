@extends('layouts.master')
@section('css')
    @toastr_css
    <style>
        .filter-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .filter-card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            padding: 18px 25px;
            font-size: 18px;
            font-weight: bold;
        }

        .filter-card-body {
            padding: 30px;
        }

        .form-group label {
            font-weight: 600;
            color: #444;
            margin-bottom: 6px;
        }

        .form-control {
            border-radius: 8px;
            border: 1px solid #ddd;
            padding: 10px 15px;
            transition: border-color 0.3s;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.15);
        }

        .btn-report {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 12px 40px;
            font-size: 16px;
            font-weight: bold;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .btn-report:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
            color: #fff;
        }

        .alert-validation {
            border-radius: 8px;
        }

        select {
            height: 60px !important;
        }
    </style>
@section('title')
    تقرير درجات الشعبة
@stop
@endsection
@section('page-header')
@section('PageTitle')
    تقرير درجات الشعبة
@stop
@endsection
@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">

        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="alert alert-danger alert-validation">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="filter-card">
            <div class="filter-card-header">
                <i class="fa fa-filter"></i> اختيار الشعبة لعرض تقرير الدرجات
            </div>
            <div class="filter-card-body">
                <form action="{{ route('section_marks_appreciation3_report.show') }}" method="POST">
                    @csrf

                    <div class="row">
                        {{-- Academic Year --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="fa fa-calendar"></i> السنة الدراسية <span
                                        class="text-danger">*</span></label>
                                <select name="academic_year_id" id="report_academic_year_id" class="form-control"
                                    required>
                                    <option value="">-- اختر السنة الدراسية --</option>
                                    @foreach ($academicYears as $year)
                                        <option value="{{ $year->id }}"
                                            {{ old('academic_year_id') == $year->id ? 'selected' : '' }}>
                                            {{ $year->academicyear }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Grade --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="fa fa-layer-group"></i> المرحلة <span
                                        class="text-danger">*</span></label>
                                <select name="grade_id" id="report_grade_id" class="form-control" required>
                                    <option value="">-- اختر المرحلة --</option>
                                    @foreach ($grades as $grade)
                                        <option value="{{ $grade->id }}"
                                            {{ old('grade_id') == $grade->id ? 'selected' : '' }}>
                                            {{ $grade->Name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Classroom --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="fa fa-building"></i> الصف <span class="text-danger">*</span></label>
                                <select name="classroom_id" id="report_classroom_id" class="form-control" required>
                                    <option value="">-- اختر الصف --</option>
                                </select>
                            </div>
                        </div>

                        {{-- Term --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="fa fa-bookmark"></i> الفصل <span class="text-danger">*</span></label>
                                <select name="term_id" id="report_term_id" class="form-control" required>
                                    <option value="">-- اختر الفصل --</option>
                                </select>
                            </div>
                        </div>

                        {{-- Section --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="fa fa-users"></i> الشعبة <span class="text-danger">*</span></label>
                                <select name="section_id" id="report_section_id" class="form-control" required>
                                    <option value="">-- اختر الشعبة --</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <hr>
                    <div class="text-center">
                        <button type="submit" class="btn btn-report">
                            <i class="fa fa-search"></i> عرض التقرير
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
@toastr_js
@toastr_render
<script>
    $(document).ready(function() {
        // Grade → Classroom
        $('#report_grade_id').on('change', function() {
            var grade_id = $(this).val();
            $('#report_classroom_id').empty().append('<option value="">-- اختر الصف --</option>');
            $('#report_term_id').empty().append('<option value="">-- اختر الفصل --</option>');
            $('#report_section_id').empty().append('<option value="">-- اختر الشعبة --</option>');
            if (grade_id) {
                $.ajax({
                    url: "{{ URL::to('Get_classrooms') }}/" + grade_id,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        $.each(data, function(key, value) {
                            $('#report_classroom_id').append('<option value="' +
                                key + '">' + value + '</option>');
                        });
                    }
                });
            }
        });

        // Academic Year + Classroom → Term
        function loadTerms() {
            var academic_year_id = $('#report_academic_year_id').val();
            var classroom_id = $('#report_classroom_id').val();
            $('#report_term_id').empty().append('<option value="">-- اختر الفصل --</option>');
            $('#report_section_id').empty().append('<option value="">-- اختر الشعبة --</option>');
            if (academic_year_id && classroom_id) {
                $.ajax({
                    url: "{{ URL::to('get_terms') }}/" + academic_year_id + "/" + classroom_id,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        $.each(data, function(key, value) {
                            $('#report_term_id').append('<option value="' + key + '">' +
                                value + '</option>');
                        });
                    }
                });
            }
        }

        $('#report_academic_year_id').on('change', loadTerms);
        $('#report_classroom_id').on('change', loadTerms);

        // Term → Section
        $('#report_term_id').on('change', function() {
            var term_id = $(this).val();
            $('#report_section_id').empty().append('<option value="">-- اختر الشعبة --</option>');
            if (term_id) {
                $.ajax({
                    url: "{{ URL::to('get_sections_by_term') }}/" + term_id,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        $.each(data, function(key, value) {
                            $('#report_section_id').append('<option value="' + key +
                                '">' + value + '</option>');
                        });
                    }
                });
            }
        });
    });
</script>
@endsection
