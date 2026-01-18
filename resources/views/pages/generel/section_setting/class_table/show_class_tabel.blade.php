@extends('layouts.master')
@section('css')
    <style>
        a:hover {
            color: white !important;
            background-color: green;
            transition: all 0.5s;
        }

        .select-perant .nice-select.fancyselect {
            width: 100%
        }
    </style>
    @toastr_css
@section('title')
    جدول الحصص
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
            href="{{ route('show_sections_settings', session()->get('section')) }}">الرجوع للقسم</a>
    </div>
</div>
<!-- breadcrumb -->
@section('PageTitle')
    جدول الحصص
@stop
<!-- breadcrumb -->
@endsection
@section('content')
<!-- row -->
<div class="row">
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
                <h4>العدد الاقصى للمحاضرات في اليوم الواحد:
                    {{ App\Models\Term::findOrFail(session()->get('term'))->max_lectures_per_day }}</h4>
                <form action="{{ route('save_class_tabel') }}" method="post">
                    @csrf
                    <div class="table-responsive">
                        <table id="" class="table table-hover table-sm table-bordered p-0"
                            data-page-length="50" style="text-align: center">
                            <thead>
                                <tr>
                                    <th>اليوم</th>
                                    @for ($i = 0; $i < App\Models\Term::findOrFail(session()->get('term'))->max_lectures_per_day; $i++)
                                        <th>الحصة {{ $i + 1 }}</th>
                                    @endfor
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>السبت</td>
                                    @for ($i = 0; $i < App\Models\Term::findOrFail(session()->get('term'))->max_lectures_per_day; $i++)
                                        <td>
                                            <div style="height: 50px;" class="select-perant">
                                                <select class="fancyselect" name="satSubjects[{{ $i + 1 }}]"
                                                    id="">
                                                    <option selected>ـــــــــــــــــــــ</option>
                                                    @foreach ($subjects as $subject)
                                                        <option {{-- {{ $classTable->satSubject1 == $subject->id ? 'selected' : '' }} --}}
                                                            @foreach ($classTableSat as $classTableSat2)
                                                        @if ($classTableSat2->lucture_number == $i + 1)
                                                        @if ($classTableSat2->subject_id == $subject->id)
                                                           {{ 'selected' }}
                                                        @endif
                                                        @endif @endforeach
                                                            value="{{ $subject->id }}">
                                                            {{ $subject->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mt-1" style="width: 100%;">
                                                البداية<input type="time" class="mx-1"
                                                    @foreach ($subjects as $subject)
                                                        @foreach ($classTableSat as $classTableSat2)
                                                        @if ($classTableSat2->lucture_number == $i + 1)
                                                        @if ($classTableSat2->subject_id == $subject->id)
                                                           value="{{ $classTableSat2->start }}"
                                                        @endif
                                                        @endif @endforeach
                                                    @endforeach
                                                name="startSat[{{ $i + 1 }}]" id=""><br>
                                                النهاية<input type="time" class="mx-1"
                                                    @foreach ($subjects as $subject)
                                                        @foreach ($classTableSat as $classTableSat2)
                                                        @if ($classTableSat2->lucture_number == $i + 1)
                                                        @if ($classTableSat2->subject_id == $subject->id)
                                                           value="{{ $classTableSat2->end }}"
                                                        @endif
                                                        @endif @endforeach
                                                    @endforeach

                                                name="endSat[{{ $i + 1 }}]"
                                                id="">
                                            </div>
                                            @foreach ($classTableSat as $classTableSat2)
                                                @if ($classTableSat2->lucture_number == $i + 1)
                                                    @isset(App\Models\teacher_subject::where('subject_id', $classTableSat2->subject_id)->first()->teacher->Name)
                                                        <p style="margin: 0">
                                                            المدرس:
                                                            {{ App\Models\teacher_subject::where('subject_id', $classTableSat2->subject_id)->first()->teacher->Name }}
                                                        </p>
                                                    @endisset
                                                @endif
                                            @endforeach
                                        </td>
                                    @endfor
                                </tr>
                                <tr>
                                    <td>الاحد</td>
                                    @for ($i = 0; $i < App\Models\Term::findOrFail(session()->get('term'))->max_lectures_per_day; $i++)
                                        <td>
                                            <div style="height: 50px;" class="select-perant">
                                                <select class="fancyselect" name="sanSubjects[{{ $i + 1 }}]"
                                                    id="">
                                                    <option selected>ـــــــــــــــــــــ</option>
                                                    @foreach ($subjects as $subject)
                                                        <option {{-- {{ $classTable->satSubject1 == $subject->id ? 'selected' : '' }} --}}
                                                            @foreach ($classTableSun as $classTableSun2)
                                                        @if ($classTableSun2->lucture_number == $i + 1)
                                                        @if ($classTableSun2->subject_id == $subject->id)
                                                           {{ 'selected' }}
                                                        @endif
                                                        @endif @endforeach
                                                            value="{{ $subject->id }}">
                                                            {{ $subject->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mt-1" style="width: 100%;">
                                                البداية<input type="time" class="mx-1"
                                                    @foreach ($subjects as $subject)
                                                        @foreach ($classTableSun as $classTableSun2)
                                                        @if ($classTableSun2->lucture_number == $i + 1)
                                                        @if ($classTableSun2->subject_id == $subject->id)
                                                           value="{{ $classTableSun2->start }}"
                                                        @endif
                                                        @endif @endforeach
                                                    @endforeach
                                                name="startSan[{{ $i + 1 }}]" id=""><br>
                                                النهاية<input type="time" class="mx-1"
                                                    @foreach ($subjects as $subject)
                                                        @foreach ($classTableSun as $classTableSun2)
                                                        @if ($classTableSun2->lucture_number == $i + 1)
                                                        @if ($classTableSun2->subject_id == $subject->id)
                                                           value="{{ $classTableSun2->end }}"
                                                        @endif
                                                        @endif @endforeach
                                                    @endforeach

                                                name="endSan[{{ $i + 1 }}]"
                                                id="">
                                            </div>
                                            @foreach ($classTableSun as $classTableSun2)
                                                @if ($classTableSun2->lucture_number == $i + 1)
                                                    @isset(App\Models\teacher_subject::where('subject_id', $classTableSun2->subject_id)->first()->teacher->Name)
                                                        <p style="margin: 0">
                                                            المدرس:
                                                            {{ App\Models\teacher_subject::where('subject_id', $classTableSun2->subject_id)->first()->teacher->Name }}
                                                        </p>
                                                    @endisset
                                                @endif
                                            @endforeach
                                        </td>
                                    @endfor
                                </tr>
                                <tr>
                                    <td>الاثنين</td>
                                    @for ($i = 0; $i < App\Models\Term::findOrFail(session()->get('term'))->max_lectures_per_day; $i++)
                                        <td>
                                            <div style="height: 50px;" class="select-perant">
                                                <select class="fancyselect" name="monSubjects[{{ $i + 1 }}]"
                                                    id="">
                                                    <option selected>ـــــــــــــــــــــ</option>
                                                    @foreach ($subjects as $subject)
                                                        <option {{-- {{ $classTable->satSubject1 == $subject->id ? 'selected' : '' }} --}}
                                                            @foreach ($classTableMon as $classTableMon2)
                                                        @if ($classTableMon2->lucture_number == $i + 1)
                                                        @if ($classTableMon2->subject_id == $subject->id)
                                                           {{ 'selected' }}
                                                        @endif
                                                        @endif @endforeach
                                                            value="{{ $subject->id }}">
                                                            {{ $subject->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mt-1" style="width: 100%;">
                                                البداية<input type="time" class="mx-1"
                                                    @foreach ($subjects as $subject)
                                                        @foreach ($classTableMon as $classTableMon2)
                                                        @if ($classTableMon2->lucture_number == $i + 1)
                                                        @if ($classTableMon2->subject_id == $subject->id)
                                                           value="{{ $classTableMon2->start }}"
                                                        @endif
                                                        @endif @endforeach
                                                    @endforeach
                                                name="startMon[{{ $i + 1 }}]" id=""><br>
                                                النهاية<input type="time" class="mx-1"
                                                    @foreach ($subjects as $subject)
                                                        @foreach ($classTableMon as $classTableMon2)
                                                        @if ($classTableMon2->lucture_number == $i + 1)
                                                        @if ($classTableMon2->subject_id == $subject->id)
                                                           value="{{ $classTableMon2->end }}"
                                                        @endif
                                                        @endif @endforeach
                                                    @endforeach

                                                name="endMon[{{ $i + 1 }}]"
                                                id="">
                                            </div>
                                            @foreach ($classTableMon as $classTableMon2)
                                                @if ($classTableMon2->lucture_number == $i + 1)
                                                    @isset(App\Models\teacher_subject::where('subject_id', $classTableMon2->subject_id)->first()->teacher->Name)
                                                        <p style="margin: 0">
                                                            المدرس:
                                                            {{ App\Models\teacher_subject::where('subject_id', $classTableMon2->subject_id)->first()->teacher->Name }}
                                                        </p>
                                                    @endisset
                                                @endif
                                            @endforeach
                                        </td>
                                    @endfor
                                </tr>
                                <tr>
                                    <td>الثلاثاء</td>
                                    @for ($i = 0; $i < App\Models\Term::findOrFail(session()->get('term'))->max_lectures_per_day; $i++)
                                        <td>
                                            <div style="height: 50px;" class="select-perant">
                                                <select class="fancyselect" name="tueSubjects[{{ $i + 1 }}]"
                                                    id="">
                                                    <option selected>ـــــــــــــــــــــ</option>
                                                    @foreach ($subjects as $subject)
                                                        <option {{-- {{ $classTable->satSubject1 == $subject->id ? 'selected' : '' }} --}}
                                                            @foreach ($classTableTue as $classTableTue2)
                                                        @if ($classTableTue2->lucture_number == $i + 1)
                                                        @if ($classTableTue2->subject_id == $subject->id)
                                                           {{ 'selected' }}
                                                        @endif
                                                        @endif @endforeach
                                                            value="{{ $subject->id }}">
                                                            {{ $subject->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mt-1" style="width: 100%;">
                                                البداية<input type="time" class="mx-1"
                                                    @foreach ($subjects as $subject)
                                                        @foreach ($classTableTue as $classTableTue2)
                                                        @if ($classTableTue2->lucture_number == $i + 1)
                                                        @if ($classTableTue2->subject_id == $subject->id)
                                                           value="{{ $classTableTue2->start }}"
                                                        @endif
                                                        @endif @endforeach
                                                    @endforeach
                                                name="startTue[{{ $i + 1 }}]" id=""><br>
                                                النهاية<input type="time" class="mx-1"
                                                    @foreach ($subjects as $subject)
                                                        @foreach ($classTableTue as $classTableTue2)
                                                        @if ($classTableTue2->lucture_number == $i + 1)
                                                        @if ($classTableTue2->subject_id == $subject->id)
                                                           value="{{ $classTableTue2->end }}"
                                                        @endif
                                                        @endif @endforeach
                                                    @endforeach

                                                name="endTue[{{ $i + 1 }}]"
                                                id="">
                                            </div>
                                            @foreach ($classTableTue as $classTableTue2)
                                                @if ($classTableTue2->lucture_number == $i + 1)
                                                    @isset(App\Models\teacher_subject::where('subject_id', $classTableTue2->subject_id)->first()->teacher->Name)
                                                        <p style="margin: 0">
                                                            المدرس:
                                                            {{ App\Models\teacher_subject::where('subject_id', $classTableTue2->subject_id)->first()->teacher->Name }}
                                                        </p>
                                                    @endisset
                                                @endif
                                            @endforeach
                                        </td>
                                    @endfor
                                </tr>
                                <tr>
                                    <td>الاربعاء</td>
                                    @for ($i = 0; $i < App\Models\Term::findOrFail(session()->get('term'))->max_lectures_per_day; $i++)
                                        <td>
                                            <div style="height: 50px;" class="select-perant">
                                                <select class="fancyselect" name="thuSubjects[{{ $i + 1 }}]"
                                                    id="">
                                                    <option selected>ـــــــــــــــــــــ</option>
                                                    @foreach ($subjects as $subject)
                                                        <option {{-- {{ $classTable->satSubject1 == $subject->id ? 'selected' : '' }} --}}
                                                            @foreach ($classTableThu as $classTableThu2)
                                                        @if ($classTableThu2->lucture_number == $i + 1)
                                                        @if ($classTableThu2->subject_id == $subject->id)
                                                           {{ 'selected' }}
                                                        @endif
                                                        @endif @endforeach
                                                            value="{{ $subject->id }}">
                                                            {{ $subject->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mt-1" style="width: 100%;">
                                                البداية<input type="time" class="mx-1"
                                                    @foreach ($subjects as $subject)
                                                        @foreach ($classTableThu as $classTableThu2)
                                                        @if ($classTableThu2->lucture_number == $i + 1)
                                                        @if ($classTableThu2->subject_id == $subject->id)
                                                           value="{{ $classTableThu2->start }}"
                                                        @endif
                                                        @endif @endforeach
                                                    @endforeach
                                                name="startThu[{{ $i + 1 }}]" id=""><br>
                                                النهاية<input type="time" class="mx-1"
                                                    @foreach ($subjects as $subject)
                                                        @foreach ($classTableThu as $classTableThu2)
                                                        @if ($classTableThu2->lucture_number == $i + 1)
                                                        @if ($classTableThu2->subject_id == $subject->id)
                                                           value="{{ $classTableThu2->end }}"
                                                        @endif
                                                        @endif @endforeach
                                                    @endforeach

                                                name="endThu[{{ $i + 1 }}]"
                                                id="">
                                            </div>
                                            @foreach ($classTableThu as $classTableThu2)
                                                @if ($classTableThu2->lucture_number == $i + 1)
                                                    @isset(App\Models\teacher_subject::where('subject_id', $classTableThu2->subject_id)->first()->teacher->Name)
                                                        <p style="margin: 0">
                                                            المدرس:
                                                            {{ App\Models\teacher_subject::where('subject_id', $classTableThu2->subject_id)->first()->teacher->Name }}
                                                        </p>
                                                    @endisset
                                                @endif
                                            @endforeach
                                        </td>
                                    @endfor
                                </tr>
                                <tr>
                                    <td>الخميس</td>
                                    @for ($i = 0; $i < App\Models\Term::findOrFail(session()->get('term'))->max_lectures_per_day; $i++)
                                        <td>
                                            <div style="height: 50px;" class="select-perant">
                                                <select class="fancyselect" name="wendSubjects[{{ $i + 1 }}]"
                                                    id="">
                                                    <option selected>ـــــــــــــــــــــ</option>
                                                    @foreach ($subjects as $subject)
                                                        <option {{-- {{ $classTable->satSubject1 == $subject->id ? 'selected' : '' }} --}}
                                                            @foreach ($classTableWend as $classTableWend2)
                                                        @if ($classTableWend2->lucture_number == $i + 1)
                                                        @if ($classTableWend2->subject_id == $subject->id)
                                                           {{ 'selected' }}
                                                        @endif
                                                        @endif @endforeach
                                                            value="{{ $subject->id }}">
                                                            {{ $subject->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mt-1" style="width: 100%;">
                                                البداية<input type="time" class="mx-1"
                                                    @foreach ($subjects as $subject)
                                                        @foreach ($classTableWend as $classTableWend2)
                                                        @if ($classTableWend2->lucture_number == $i + 1)
                                                        @if ($classTableWend2->subject_id == $subject->id)
                                                           value="{{ $classTableWend2->start }}"
                                                        @endif
                                                        @endif @endforeach
                                                    @endforeach
                                                name="startWend[{{ $i + 1 }}]" id=""><br>
                                                النهاية<input type="time" class="mx-1"
                                                    @foreach ($subjects as $subject)
                                                        @foreach ($classTableWend as $classTableWend2)
                                                        @if ($classTableWend2->lucture_number == $i + 1)
                                                        @if ($classTableWend2->subject_id == $subject->id)
                                                           value="{{ $classTableWend2->end }}"
                                                        @endif
                                                        @endif @endforeach
                                                    @endforeach

                                                name="endWend[{{ $i + 1 }}]"
                                                id="">
                                            </div>
                                            @foreach ($classTableWend as $classTableWend2)
                                                @if ($classTableWend2->lucture_number == $i + 1)
                                                    @isset(App\Models\teacher_subject::where('subject_id', $classTableWend2->subject_id)->first()->teacher->Name)
                                                        <p style="margin: 0">
                                                            المدرس:
                                                            {{ App\Models\teacher_subject::where('subject_id', $classTableWend2->subject_id)->first()->teacher->Name }}
                                                        </p>
                                                    @endisset
                                                @endif
                                            @endforeach
                                        </td>
                                    @endfor
                                </tr>
                                <tr>
                                    <td>الجمعة</td>
                                    @for ($i = 0; $i < App\Models\Term::findOrFail(session()->get('term'))->max_lectures_per_day; $i++)
                                        <td>
                                            <div style="height: 50px;" class="select-perant">
                                                <select class="fancyselect" name="friSubjects[{{ $i + 1 }}]"
                                                    id="">
                                                    <option selected>ـــــــــــــــــــــ</option>
                                                    @foreach ($subjects as $subject)
                                                        <option {{-- {{ $classTable->satSubject1 == $subject->id ? 'selected' : '' }} --}}
                                                            @foreach ($classTableFri as $classTableFri2)
                                                        @if ($classTableFri2->lucture_number == $i + 1)
                                                        @if ($classTableFri2->subject_id == $subject->id)
                                                           {{ 'selected' }}
                                                        @endif
                                                        @endif @endforeach
                                                            value="{{ $subject->id }}">
                                                            {{ $subject->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mt-1" style="width: 100%;">
                                                البداية<input type="time" class="mx-1"
                                                    @foreach ($subjects as $subject)
                                                        @foreach ($classTableFri as $classTableFri2)
                                                        @if ($classTableFri2->lucture_number == $i + 1)
                                                        @if ($classTableFri2->subject_id == $subject->id)
                                                           value="{{ $classTableFri2->start }}"
                                                        @endif
                                                        @endif @endforeach
                                                    @endforeach
                                                name="startFri[{{ $i + 1 }}]" id=""><br>
                                                النهاية<input type="time" class="mx-1"
                                                    @foreach ($subjects as $subject)
                                                        @foreach ($classTableFri as $classTableFri2)
                                                        @if ($classTableFri2->lucture_number == $i + 1)
                                                        @if ($classTableFri2->subject_id == $subject->id)
                                                           value="{{ $classTableFri2->end }}"
                                                        @endif
                                                        @endif @endforeach
                                                    @endforeach

                                                name="endFri[{{ $i + 1 }}]"
                                                id="">
                                            </div>
                                            @foreach ($classTableFri as $classTableFri2)
                                                @if ($classTableFri2->lucture_number == $i + 1)
                                                    @isset(App\Models\teacher_subject::where('subject_id', $classTableFri2->subject_id)->first()->teacher->Name)
                                                        <p style="margin: 0">
                                                            المدرس:
                                                            {{ App\Models\teacher_subject::where('subject_id', $classTableFri2->subject_id)->first()->teacher->Name }}
                                                        </p>
                                                    @endisset
                                                @endif
                                            @endforeach
                                        </td>
                                    @endfor
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <button class="btn btn-success my-3" type="submit" style="width: 100%">حفظ</button>
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
