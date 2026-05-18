@extends('layouts.master')
@section('css')
    @toastr_css
    <style>
        .report-section {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            margin-bottom: 20px;
            overflow: hidden;
        }
        .report-section-header {
            padding: 12px 20px;
            color: #fff;
            font-weight: bold;
            font-size: 16px;
        }
        .report-section-body {
            padding: 20px;
        }
        .bg-info-header { background: #17a2b8; }
        .bg-success-header { background: #28a745; }
        .bg-primary-header { background: #007bff; }
        .bg-warning-header { background: #fd7e14; }
        .bg-danger-header { background: #dc3545; }
        .bg-purple-header { background: #6f42c1; }
        .info-label {
            font-weight: bold;
            color: #555;
            margin-bottom: 2px;
        }
        .info-value {
            color: #222;
            margin-bottom: 10px;
        }
        .stat-box {
            text-align: center;
            padding: 15px;
            border-radius: 8px;
            color: #fff;
            font-weight: bold;
        }
        .stat-present { background: #28a745; }
        .stat-absent { background: #dc3545; }
        .stat-late { background: #ffc107; color: #333; }
        .student-photo {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #007bff;
        }
        .no-photo {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #999;
            font-size: 40px;
        }
        @media print {
            .no-print { display: none !important; }
            .report-section { break-inside: avoid; }
        }
    </style>
@section('title')
    تقرير الطالب - {{ $student->name }}
@stop
@endsection
@section('page-header')
@section('PageTitle')
    تقرير الطالب
@stop
@endsection
@section('content')
<div class="row">
    <div class="col-md-12 mb-30">
        {{-- Action Buttons --}}
        <div class="d-flex justify-content-between align-items-center mb-3 no-print">
            <a href="{{ route('Students.index') }}" class="btn btn-secondary">
                <i class="fa fa-arrow-right"></i> العودة لقائمة الطلاب
            </a>
            <button onclick="window.print()" class="btn btn-primary">
                <i class="fa fa-print"></i> طباعة التقرير
            </button>
        </div>

        {{-- ============================================ --}}
        {{-- 1. Student Basic Information --}}
        {{-- ============================================ --}}
        <div class="report-section">
            <div class="report-section-header bg-primary-header">
                <i class="fa fa-user"></i> معلومات الطالب الأساسية
            </div>
            <div class="report-section-body">
                <div class="row">
                    <div class="col-md-2 text-center mb-3">
                        @if($student->images && $student->images->count() > 0)
                            <img src="{{ URL('attachments/students/' . $student->name . '/' . $student->images->first()->filename) }}"
                                 class="student-photo" alt="صورة الطالب">
                        @else
                            <div class="no-photo"><i class="fa fa-user"></i></div>
                        @endif
                    </div>
                    <div class="col-md-10">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="info-label">الاسم الكامل</p>
                                <p class="info-value">{{ $student->name }}</p>
                            </div>
                            <div class="col-md-3">
                                <p class="info-label">رقم القيد</p>
                                <p class="info-value">{{ $student->NoKnow ?? '-' }}</p>
                            </div>
                            <div class="col-md-3">
                                <p class="info-label">البريد الإلكتروني</p>
                                <p class="info-value">{{ $student->email ?? '-' }}</p>
                            </div>
                            <div class="col-md-3">
                                <p class="info-label">الجنس</p>
                                <p class="info-value">{{ $student->gender->Name ?? '-' }}</p>
                            </div>
                            <div class="col-md-3">
                                <p class="info-label">الجنسية</p>
                                <p class="info-value">{{ $student->Nationality->Name ?? '-' }}</p>
                            </div>
                            <div class="col-md-3">
                                <p class="info-label">تاريخ الميلاد</p>
                                <p class="info-value">{{ $student->Date_Birth ?? '-' }}</p>
                            </div>
                            <div class="col-md-3">
                                <p class="info-label">سنة الالتحاق</p>
                                <p class="info-value">{{ $student->academicYear->academicyear ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ============================================ --}}
        {{-- 2. Parent / Guardian Information --}}
        {{-- ============================================ --}}
        @if($student->myparent)
        <div class="report-section">
            <div class="report-section-header bg-info-header">
                <i class="fa fa-users"></i> معلومات ولي الأمر
            </div>
            <div class="report-section-body">
                <div class="row">
                    <div class="col-md-4">
                        <p class="info-label">اسم الأب</p>
                        <p class="info-value">{{ $student->myparent->Name_Father ?? '-' }}</p>
                    </div>
                    <div class="col-md-4">
                        <p class="info-label">اسم الأم</p>
                        <p class="info-value">{{ $student->myparent->Name_Mother ?? '-' }}</p>
                    </div>
                    <div class="col-md-4">
                        <p class="info-label">الهاتف</p>
                        <p class="info-value">{{ $student->myparent->Phone_Father ?? '-' }}</p>
                    </div>
                    <div class="col-md-4">
                        <p class="info-label">العنوان</p>
                        <p class="info-value">{{ $student->myparent->Address_Father ?? '-' }}</p>
                    </div>
                    <div class="col-md-4">
                        <p class="info-label">المهنة</p>
                        <p class="info-value">{{ $student->myparent->Job_Father ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- ============================================ --}}
        {{-- 3. Sections Enrolled --}}
        {{-- ============================================ --}}
        <div class="report-section">
            <div class="report-section-header bg-success-header">
                <i class="fa fa-graduation-cap"></i> الشُعب المسجل بها
            </div>
            <div class="report-section-body">
                @if($student->sections->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-sm text-center">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>السنة الدراسية</th>
                                <th>المرحلة</th>
                                <th>الصف</th>
                                <th>الفصل</th>
                                <th>الشعبة</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($student->sections as $index => $section)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $section->term->academicyear->academicyear ?? '-' }}</td>
                                <td>{{ $section->term->classroom->Grades->Name ?? '-' }}</td>
                                <td>{{ $section->term->classroom->Name_Class ?? '-' }}</td>
                                <td>{{ $section->term->name ?? '-' }}</td>
                                <td>{{ $section->Name_Section }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                    <p class="text-muted text-center">لا توجد شعب مسجلة</p>
                @endif
            </div>
        </div>

        {{-- ============================================ --}}
        {{-- 4. Teachers per Section --}}
        {{-- ============================================ --}}
        <div class="report-section">
            <div class="report-section-header bg-purple-header">
                <i class="fa fa-chalkboard-teacher"></i> المدرسين
            </div>
            <div class="report-section-body">
                @forelse($student->sections as $section)
                    <h6 class="mb-2" style="color: #6f42c1; font-weight: bold;">
                        <i class="fa fa-bookmark"></i>
                        {{ $section->Name_Section }}
                        ({{ $section->term->name ?? '' }} - {{ $section->term->academicyear->academicyear ?? '' }})
                    </h6>
                    @if(isset($sectionTeachers[$section->id]) && count($sectionTeachers[$section->id]) > 0)
                    <div class="table-responsive mb-3">
                        <table class="table table-bordered table-sm text-center">
                            <thead class="thead-light">
                                <tr>
                                    <th>المدرس</th>
                                    <th>المواد التي يدرّسها</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sectionTeachers[$section->id] as $item)
                                <tr>
                                    <td>{{ $item['teacher']->Name }}</td>
                                    <td>
                                        @foreach($item['subjects'] as $sub)
                                            <span class="badge badge-info p-1 m-1">{{ $sub->name }}</span>
                                        @endforeach
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                        <p class="text-muted mb-3">لا يوجد مدرسين مسجلين لهذه الشعبة</p>
                    @endif
                @empty
                    <p class="text-muted text-center">لا توجد شعب</p>
                @endforelse
            </div>
        </div>

        {{-- ============================================ --}}
        {{-- 5. Marks / Degrees --}}
        {{-- ============================================ --}}
        <div class="report-section">
            <div class="report-section-header bg-warning-header">
                <i class="fa fa-star"></i> الدرجات والعلامات
            </div>
            <div class="report-section-body">
                @php $hasMarks = false; @endphp
                @foreach($student->sections as $section)
                    @if(isset($degreesBySection[$section->id]) && $degreesBySection[$section->id]->count() > 0)
                        @php $hasMarks = true; @endphp
                        <h6 class="mb-2" style="color: #fd7e14; font-weight: bold;">
                            <i class="fa fa-bookmark"></i>
                            {{ $section->Name_Section }}
                            ({{ $section->term->name ?? '' }} - {{ $section->term->academicyear->academicyear ?? '' }})
                        </h6>
                        <div class="table-responsive mb-3">
                            <table class="table table-bordered table-hover table-sm text-center">
                                <thead class="thead-light">
                                    <tr>
                                        <th>الاختبار</th>
                                        <th>المادة</th>
                                        <th>الدرجة</th>
                                        <th>الدرجة الكاملة</th>
                                        <th>النسبة</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($degreesBySection[$section->id] as $degree)
                                    <tr>
                                        <td>{{ $degree->quizze->name ?? '-' }}</td>
                                        <td>{{ $degree->quizze->subject->name ?? '-' }}</td>
                                        <td>
                                            <strong>{{ $degree->score }}</strong>
                                        </td>
                                        <td>{{ $degree->quizze->full_mark }}</td>
                                        <td>
                                            @php
                                                $pct = $degree->quizze->full_mark > 0
                                                    ? round(($degree->score / $degree->quizze->full_mark) * 100, 1)
                                                    : 0;
                                            @endphp
                                            <span class="badge p-1 {{ $pct >= 50 ? 'badge-success' : 'badge-danger' }}">
                                                {{ $pct }}%
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                @endforeach
                @if(!$hasMarks)
                    <p class="text-muted text-center">لا توجد درجات مسجلة</p>
                @endif
            </div>
        </div>

        {{-- ============================================ --}}
        {{-- 6. Attendance --}}
        {{-- ============================================ --}}
        <div class="report-section">
            <div class="report-section-header bg-danger-header">
                <i class="fa fa-calendar-check"></i> الحضور والغياب
            </div>
            <div class="report-section-body">
                {{-- Overall Summary --}}
                <h6 class="mb-3" style="font-weight: bold; color: #333;">
                    <i class="fa fa-chart-bar"></i> الملخص العام
                </h6>
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="stat-box stat-present">
                            <i class="fa fa-check-circle fa-2x mb-2"></i>
                            <br>حضور
                            <br><span style="font-size: 28px;">{{ $totalPresent }}</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-box stat-absent">
                            <i class="fa fa-times-circle fa-2x mb-2"></i>
                            <br>غياب
                            <br><span style="font-size: 28px;">{{ $totalAbsent }}</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-box stat-late">
                            <i class="fa fa-clock fa-2x mb-2"></i>
                            <br>تأخير
                            <br><span style="font-size: 28px;">{{ $totalLate }}</span>
                        </div>
                    </div>
                </div>

                {{-- Per Section Breakdown --}}
                @foreach($student->sections as $section)
                    @if(isset($attendanceBySection[$section->id]))
                        @php $secAtt = $attendanceBySection[$section->id]; @endphp
                        <h6 class="mb-2" style="color: #dc3545; font-weight: bold;">
                            <i class="fa fa-bookmark"></i>
                            {{ $section->Name_Section }}
                            ({{ $section->term->name ?? '' }} - {{ $section->term->academicyear->academicyear ?? '' }})
                            <span class="badge badge-success">حضور: {{ $secAtt['present'] }}</span>
                            <span class="badge badge-danger">غياب: {{ $secAtt['absent'] }}</span>
                            <span class="badge badge-warning">تأخير: {{ $secAtt['late'] }}</span>
                        </h6>
                        @if(count($secAtt['subjects']) > 0)
                        <div class="table-responsive mb-3">
                            <table class="table table-bordered table-sm text-center">
                                <thead class="thead-light">
                                    <tr>
                                        <th>المادة</th>
                                        <th>عدد الحضور</th>
                                        <th>عدد الغياب</th>
                                        <th>عدد التأخير</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($secAtt['subjects'] as $subStat)
                                    <tr>
                                        <td>{{ $subStat['name'] }}</td>
                                        <td><span class="text-success font-weight-bold">{{ $subStat['present'] }}</span></td>
                                        <td><span class="text-danger font-weight-bold">{{ $subStat['absent'] }}</span></td>
                                        <td><span class="text-warning font-weight-bold">{{ $subStat['late'] }}</span></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                            <p class="text-muted mb-3">لا يوجد بيانات حضور لهذه الشعبة</p>
                        @endif
                    @endif
                @endforeach
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
