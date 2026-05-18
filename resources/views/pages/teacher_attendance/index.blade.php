@extends('layouts.master')
@section('css')

@section('title')
    تصفح|السنوات الدراسية|الاقسام|المستويات|الشعب|الاعدادات|الطلاب
@stop
@endsection
@section('content')



{{-- start title --}}
<div class="title mb-3">
    <nav class="navbar navbar-light bg-light p-2" style="    justify-content: space-between; align-items: center;">
        <p class="m-0"> التحضير للمدرسين</p>
        {{-- <a class="mx-2" style="font-size: 14px; text-decoration: underline" href="{{ route('attendanceIndex') }}">الروجوع
            للمواد</a> --}}
    </nav>
</div>

<div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">هل انت متاكد من حذف؟</h5>
            </div>
            <div class="modal-body">
                <form action="{{ route('teacherAttendenceDelete') }}" method="POST">
                    @csrf
                    <div class="form-group mb-3">
                        <p id="model-body"></p>
                        <input type="hidden" readonly name="date" class="form-control red" id="model-date"
                            aria-describedby="emailHelp"
                            @if ($date != null) value="{{ $date }}"
            @else
                value="<?php echo date('Y-m-d'); ?>" @endif>
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

{{-- empty-box --}}
<div class="box p-3 my-3">
    @php
        $i = 1;
    @endphp
    <form action="{{ route('saveAttendenceTeachers') }}" method="POST">
        <div style="font-size: 30px; margin: 10px;">
            <label for="date">التحضير لتاريخ</label>
            <input type="date" id="date" name="date"
                @if ($date != null) value="{{ $date }}"
            @else
                value="<?php echo date('Y-m-d'); ?>" @endif>
            <a href="{{ route('createAttendenceTeachers') }}/" id="updateRoute" type="submit"
                class="btn btn-primary">تحديث</a>
            <a href="{{ route('createAttendenceTeachers') }}/" id="updateRoute2" class="hidden"
                style="display: none"></a>
            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#delete">
                <i class="fas fa-trash"></i>
            </button>
        </div>
        @csrf
        <table id="datatable" id="level-table" class="table  table-hover table-sm table-bordered p-0"
            data-page-length="50">
            <thead class="thead-color">
                <tr>
                    <th scope="col">المدرس</th>
                    <th scope="col">الدخول</th>
                    <th scope="col">الخروج</th>
                    <th scope="col">الحالة</th>
                    <th scope="col">ملاحظات</th>
                </tr>
            </thead>
            @if ($taechers->count() > 0)
                <tbody>
                    @foreach ($taechers as $taecher)
                        <tr>
                            <td scope="col">{{ $taecher->Name }}</td>
                            <td scope="col">
                                <label class="block text-gray-500 font-semibold sm:border-r sm:pr-4">
                                    <input name="enter[{{ $taecher->id }}]" class="form-control" type="time"
                                        @if ($date != null) @if (isset($taecher->attendances()->where('attendence_date', $date)->first()->state)) value="{{ $taecher->attendances()->where('attendence_date', $date)->first()->enter }}" @endif
                                    @else
                                        @if (isset($taecher->attendances()->where('attendence_date', date('Y-m-d'))->first()->state)) value="{{ $taecher->attendances()->where('attendence_date', date('Y-m-d'))->first()->enter }}" @endif
                                        @endif>
                                </label>
                            </td>
                            <td scope="col">
                                <label class="block text-gray-500 font-semibold sm:border-r sm:pr-4">
                                    <input name="out[{{ $taecher->id }}]" class="form-control" type="time"
                                        @if ($date != null) @if (isset($taecher->attendances()->where('attendence_date', $date)->first()->state)) value="{{ $taecher->attendances()->where('attendence_date', $date)->first()->out }}" @endif
                                    @else
                                        @if (isset($taecher->attendances()->where('attendence_date', date('Y-m-d'))->first()->state)) value="{{ $taecher->attendances()->where('attendence_date', date('Y-m-d'))->first()->out }}" @endif
                                        @endif>
                                </label>
                            </td>
                            <td scope="col">
                                <label class="block text-gray-500 font-semibold sm:border-r sm:pr-4">
                                    <input name="attendences[{{ $taecher->id }}]" checked class="leading-tight"
                                        type="radio" value="1"
                                        @if ($date != null) @if (isset($taecher->attendances()->where('attendence_date', $date)->first()->state)) 
                                            {{ $taecher->attendances()->where('attendence_date', $date)->first()->state == 1 ? 'checked' : '' }} @endif
                                    @else
                                        @if (isset($taecher->attendances()->where('attendence_date', date('Y-m-d'))->first()->state)) {{ $taecher->attendances()->where('attendence_date', date('Y-m-d'))->first()->state == 1 ? 'checked' : '' }} @endif
                                        @endif>
                                    <span class="text-success">حضور</span>
                                </label>

                                <label class="ml-4 block text-gray-500 font-semibold">
                                    <input name="attendences[{{ $taecher->id }}]" class="leading-tight" type="radio"
                                        value="0"
                                        @if ($date != null) @if (isset($taecher->attendances()->where('attendence_date', $date)->first()->state)) 
                                            {{ $taecher->attendances()->where('attendence_date', $date)->first()->state == 0 ? 'checked' : '' }} @endif
                                    @else
                                        @if (isset($taecher->attendances()->where('attendence_date', date('Y-m-d'))->first()->state)) {{ $taecher->attendances()->where('attendence_date', date('Y-m-d'))->first()->state == 0 ? 'checked' : '' }} @endif
                                        @endif>
                                    <span class="text-danger">غياب</span>
                                </label>

                                <label class="ml-4 block text-gray-500 font-semibold">
                                    <input name="attendences[{{ $taecher->id }}]" class="leading-tight" type="radio"
                                        value="2"
                                        @if ($date != null) @if (isset($taecher->attendances()->where('attendence_date', $date)->first()->state)) 
                                            {{ $taecher->attendances()->where('attendence_date', $date)->first()->state == 2 ? 'checked' : '' }} @endif
                                    @else
                                        @if (isset($taecher->attendances()->where('attendence_date', date('Y-m-d'))->first()->state)) {{ $taecher->attendances()->where('attendence_date', date('Y-m-d'))->first()->state == 2 ? 'checked' : '' }} @endif
                                        @endif
                                    >
                                    <span class="text-warning">متأخر</span>
                                </label>
                            </td>
                            <td scope="col">
                                <label class="block text-gray-500 font-semibold sm:border-r sm:pr-4">
                                    <textarea style="width: 300px;" name="notes[{{ $taecher->id }}]" id="{{ $taecher->id }}">
                                        @if ($date != null)
                                            @if (isset($taecher->attendances()->where('attendence_date', $date)->first()->state))
                                            {{ $taecher->attendances()->where('attendence_date', $date)->first()->notes }}
                                            @endif
@else
@if (isset($taecher->attendances()->where('attendence_date', date('Y-m-d'))->first()->state))
                                            {{ $taecher->attendances()->where('attendence_date', date('Y-m-d'))->first()->notes }}
                                            @endif
                                        @endif
                                    </textarea>
                                </label>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2" style="text-align: left">
                            <button type="submit" class="btn btn-success">حفظ</button>
                        </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tfoot>
            @else
                <td colspan="3" class="text-center">لا توجد بيانات</td>
            @endif
        </table>
    </form>


</div>






@endsection

@section('js')
<script>
    document.querySelector('#model-body').textContent = "التاريخ:" + document.querySelector('#model-date')
        .value;
    document.querySelector('#date').addEventListener('change', function() {
        document.querySelector('#updateRoute').href = document.querySelector('#updateRoute2').href;
        document.querySelector('#updateRoute').href += this.value
        document.querySelector('#model-date').value = document.querySelector("#date").value;
        document.querySelector('#model-body').textContent = "التاريخ:" + document.querySelector('#model-date')
            .value;
    });
</script>
@endsection
