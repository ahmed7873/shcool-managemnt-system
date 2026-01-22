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

                <br><br>

                <form action="{{ route('storeAttendenceExpect') }}" method="post">
                    @csrf
                    <div style="font-size: 30px; margin: 10px;">
                        <label for="date">التحضير لتاريخ</label>

                        <input class="form-control date" type="text" id="datepicker-action" name="date"
                            data-date-format="yyyy-mm-dd"
                            @if ($date != null && $subjectId != null && $luctureNumber != null) value="{{ $date }}"
            @else 
            value="<?php echo date('Y-m-d'); ?>" @endif>
                        <select style="width: 20%;" name="subject" id="subject" class="form-select my-3"
                            aria-label="Default select example">
                            <option value="" selected>المادة</option>
                            @foreach ($subjects as $subject)
                                <option
                                    @if ($date != null && $subjectId != null && $luctureNumber != null) {{ $subjectId == $subject->id ? 'selected' : '' }} @endif
                                    value="{{ $subject->id }}">
                                    {{ $subject->name }}</option>
                            @endforeach
                        </select>
                        <label for="date">رقم المحاضرة</label>
                        <input @if ($date != null && $subjectId != null && $luctureNumber != null) value="{{ $luctureNumber }}" @endif
                            class="form-control lucture_number" type="number" name="lucture_number">
                        <a href="{{ route('createAttendenceExpect') }}" id="updateRoute"
                            class="btn btn-primary">تحديث</a>
                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
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
                                @foreach ($students as $student)
                                    <tr>
                                        <?php $i++; ?>
                                        <td>{{ $i }}</td>
                                        <td>{{ $student->name }}</td>
                                        <td scope="col">

                                            <label class="block text-gray-500 font-semibold sm:border-r sm:pr-4">
                                                <input name="attendences[{{ $student->id }}]" class="leading-tight"
                                                    type="radio" value="1"
                                                    @if ($date != null && $subjectId != null && $luctureNumber != null) @if (isset(
                                                            $student->attendance()->where('attendance_date', $date)->where('section_id', session()->get('section'))->where('subject_id', $subjectId)->where('lucture_number', $luctureNumber)->first()->state)) {{ $student->attendance()->where('attendance_date', $date)->where('section_id', session()->get('section'))->where('subject_id', $subjectId)->where('lucture_number', $luctureNumber)->first()->state == 1? 'checked': '' }} @endif
                                                    @endif
                                                >
                                                <span class="text-success">حضور</span>
                                            </label>

                                            <label class="ml-4 block text-gray-500 font-semibold">
                                                <input name="attendences[{{ $student->id }}]" class="leading-tight"
                                                    type="radio" value="0"
                                                    @if ($date != null && $subjectId != null && $luctureNumber != null) @if (isset(
                                                            $student->attendance()->where('attendance_date', $date)->where('section_id', session()->get('section'))->where('subject_id', $subjectId)->where('lucture_number', $luctureNumber)->first()->state)) {{ $student->attendance()->where('attendance_date', $date)->where('section_id', session()->get('section'))->where('subject_id', $subjectId)->where('lucture_number', $luctureNumber)->first()->state == 0? 'checked': '' }} @endif
                                                    @endif
                                                >
                                                <span class="text-danger">غياب</span>
                                            </label>

                                            <label class="ml-4 block text-gray-500 font-semibold">
                                                <input name="attendences[{{ $student->id }}]" class="leading-tight"
                                                    type="radio" value="2"
                                                    @if ($date != null && $subjectId != null && $luctureNumber != null) @if (isset(
                                                            $student->attendance()->where('attendance_date', $date)->where('section_id', session()->get('section'))->where('subject_id', $subjectId)->where('lucture_number', $luctureNumber)->first()->state)) {{ $student->attendance()->where('attendance_date', $date)->where('section_id', session()->get('section'))->where('subject_id', $subjectId)->where('lucture_number', $luctureNumber)->first()->state == 2? 'checked': '' }} @endif
                                                    @endif
                                                >
                                                <span class="text-warning">متأخر</span>
                                            </label>
                                            {{-- @endif --}}
                                        </td>
                                        <td scope="col">
                                            <label class="block text-gray-500 font-semibold sm:border-r sm:pr-4">
                                                <textarea style="width: 300px;" name="notes[{{ $student->id }}]" id="{{ $student->id }}">
                                        @if ($date != null && $subjectId != null && $luctureNumber != null)
                                            @if (isset(
                                                    $student->attendance()->where('attendance_date', $date)->where('section_id', session()->get('section'))->where('subject_id', $subjectId)->where('lucture_number', $luctureNumber)->first()->state))
                                            {{ $student->attendance()->where('attendance_date', $date)->where('section_id', session()->get('section'))->where('subject_id', $subjectId)->where('lucture_number', $luctureNumber)->first()->notes }}
                                            @endif
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
            <div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">هل انت متاكد من حذف؟</h5>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('attendenceDelete') }}" method="POST">
                                @csrf
                                <div class="form-group mb-3">
                                    <p id="model-body"></p>
                                    <input type="hidden" readonly name="date" class="form-control red"
                                        id="model-date" aria-describedby="emailHelp">
                                    <input type="hidden" readonly name="subject" class="form-control red"
                                        id="model-subject" aria-describedby="emailHelp">
                                    <input type="hidden" readonly name="lucture_number" class="form-control red"
                                        id="model-lucture_number" aria-describedby="emailHelp">
                                </div>
                                <button type="submit" class="btn btn-outline-danger">حذف</button>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق</button>
                        </div>
                    </div>
                </div>
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
    const dateInput = document.querySelector('.date');
    const subjectSelect = document.getElementById('subject');
    const lucture_number = document.querySelector('.lucture_number');
    const link = document.getElementById('updateRoute');

    function updateLink() {
        const date = dateInput.value;
        const subjectId = subjectSelect.value;
        const lucture_number2 = lucture_number.value;

        // Only build URL when both values exist
        if (date && isNumber(parseInt(subjectId)) && isNumber(parseInt(lucture_number2))) {
            link.href = `/createAttendenceExpect/${date}/${subjectId}/${lucture_number2}`;
            document.querySelector('#model-body').textContent = "التاريخ:" + date + "المادة:" + subjectSelect.options[
                subjectSelect.selectedIndex].text + "المحاضرة: " + lucture_number2;
            document.querySelector('#model-date').value = date;
            document.querySelector('#model-subject').value = subjectId;
            document.querySelector('#model-lucture_number').value = lucture_number2;
        } else {
            link.href = `/createAttendenceExpect`;
            document.querySelector('#model-body').textContent = "اختر التاريخ والمادة";
            document.querySelector('#model-date').value = null;
            document.querySelector('#model-subject').value = null;
            document.querySelector('#model-lucture_number').value = null;
        }
    }

    dateInput.addEventListener('change', updateLink);
    subjectSelect.addEventListener('change', updateLink);
    lucture_number.addEventListener('change', updateLink);

    function isNumber(value) {
        return typeof value === 'number' && !isNaN(value);
    }
</script>
@endsection
