@extends('layouts.master')
@section('css')
    @toastr_css
    <style>
        .report-header-card {
            border: none;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            margin-bottom: 25px;
        }

        .report-header-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            padding: 25px 30px;
        }

        .report-header-bg h4 {
            margin: 0;
            font-weight: bold;
        }

        .report-header-bg p {
            margin: 5px 0 0;
            opacity: 0.9;
        }

        .info-badges span {
            display: inline-block;
            background: rgba(255, 255, 255, 0.2);
            padding: 4px 14px;
            border-radius: 20px;
            margin: 4px;
            font-size: 13px;
        }

        .marks-card {
            border: none;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            background: #fff;
            padding: 10px;
        }

        .table-custom {
            border-collapse: collapse;
            width: 100%;
            border: 2px solid #000;
        }

        .table-custom th,
        .table-custom td {
            border: 2px solid #000 !important;
            font-weight: bold;
            vertical-align: middle !important;
            text-align: center;
            padding: 5px;
            color: #000 !important;
            white-space: nowrap;
        }

        .table-custom thead th {
            background-color: #00FF00 !important;
            font-size: 14px;
        }

        .table-custom tbody td {
            background-color: #FFFF00 !important;
            font-size: 14px;
        }

        .bg-orange {
            background-color: #FFA500 !important;
        }

        .vertical-text {
            writing-mode: vertical-rl;
            white-space: nowrap;
            padding: 10px 0 !important;
        }

        @media print {
            @page {
                size: landscape;
                margin: 5mm;
            }

            .print_btn,
            .back_btn,
            .report-header-card,
            .no-print {
                display: none !important;
            }

            body {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .table-responsive {
                overflow: visible !important;
            }

            .table-custom {
                width: 100% !important;
            }

            .table-custom th,
            .table-custom td {
                font-size: 8px !important;
                padding: 2px 1px !important;
                border: 1px solid #000 !important;
            }

            .table-custom thead th {
                background-color: #00FF00 !important;
                color: #000 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .table-custom tbody td {
                background-color: #FFFF00 !important;
                color: #000 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .bg-orange {
                background-color: #FFA500 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .vertical-text {
                padding: 2px 0 !important;
                font-size: 8px !important;
            }
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
    <div class="col-md-12">

        {{-- Action Buttons --}}
        <div class="d-flex justify-content-between align-items-center mb-3 no-print">
            <a href="{{ route('section_marks_custom_report') }}" class="btn btn-secondary back_btn">
                <i class="fa fa-arrow-right"></i> العودة لاختيار شعبة أخرى
            </a>
            <div>
                <button onclick="exportToExcel()" class="btn btn-success mr-2">
                    <i class="fa fa-file-excel"></i> تصدير إلى Excel
                </button>
                <button onclick="window.print()" class="btn btn-primary print_btn">
                    <i class="fa fa-print"></i> طباعة التقرير
                </button>
            </div>
        </div>

        {{-- Section Info Header --}}
        <div class="report-header-card">
            <div class="report-header-bg">
                <h4><i class="fa fa-chart-bar"></i> تقرير درجات الشعبة: {{ $section->Name_Section }}</h4>
                <div class="info-badges mt-2">
                    <span><i class="fa fa-calendar"></i> {{ $section->term->academicyear->academicyear ?? '-' }}</span>
                    <span><i class="fa fa-layer-group"></i> {{ $section->term->classroom->Grades->Name ?? '-' }}</span>
                    <span><i class="fa fa-building"></i> {{ $section->term->classroom->Name_Class ?? '-' }}</span>
                    <span><i class="fa fa-bookmark"></i> {{ $section->term->name ?? '-' }}</span>
                    <span><i class="fa fa-users"></i> عدد الطلاب: {{ $students->count() }}</span>
                    <span><i class="fa fa-file-alt"></i> عدد الاختبارات: {{ $exams->count() }}</span>
                </div>
            </div>
        </div>

        {{-- Marks Table --}}
        @if ($students->count() > 0 && $exams->count() > 0)
            <div class="marks-card">
                <div class="table-responsive">
                    <table class="table-custom" id="marksTable">
                        <thead>
                            <tr>
                                <th rowspan="4">الرقم</th>
                                <th rowspan="4">اسم الطالب</th>
                                @php $total_hours = 0; @endphp
                                @foreach ($examsBySubject as $subjectId => $subjectExams)
                                    @php $total_hours += $subjectExams->first()->subject->hours ?? 0; @endphp
                                    <th colspan="5">{{ $subjectExams->first()->subject->name ?? '-' }}</th>
                                @endforeach
                                <th colspan="2">مجموع الوحدات</th>
                                <th colspan="3">موقف الطالب</th>
                                <th rowspan="4">توصية مجلس الكلية</th>
                            </tr>
                            <tr>
                                @foreach ($examsBySubject as $subjectId => $subjectExams)
                                    <th colspan="5">{{ $subjectExams->first()->subject->hours ?? 0 }} وحدات الدراسية
                                    </th>
                                @endforeach
                                <th colspan="2">{{ $total_hours }}</th>
                                <th rowspan="3" class="vertical-text">للدور الأول</th>
                                <th rowspan="3" class="vertical-text">للدور الثاني</th>
                                <th rowspan="3" class="vertical-text">للتصفية</th>
                            </tr>
                            <tr>
                                @foreach ($examsBySubject as $subjectId => $subjectExams)
                                    <th>السعي</th>
                                    <th>الدور الأول</th>
                                    <th>الدور الثاني</th>
                                    <th>التصفية</th>
                                    <th rowspan="2">القرار</th>
                                @endforeach
                                <th rowspan="2">مجموع<br>الرسوب<br>في<br>المقررات</th>
                                <th rowspan="2">نسبة<br>الرسوب<br>في<br>المقررات</th>
                            </tr>
                            <tr>
                                @foreach ($examsBySubject as $subjectId => $subjectExams)
                                    <th>40</th>
                                    <th>60</th>
                                    <th>100</th>
                                    <th>100</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($students as $index => $student)
                                @php
                                    $total_fails = 0;
                                    $total_subjects = $examsBySubject->count();
                                @endphp
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td class="text-right" style="white-space: nowrap;">{{ $student->name }}</td>

                                    @foreach ($examsBySubject as $subjectId => $subjectExams)
                                        @php
                                            $mohassalh = null;
                                            $final1 = null;
                                            $final2 = null;
                                            $final3 = null;

                                            foreach ($subjectExams as $exam) {
                                                $degree = isset($studentDegrees[$student->id])
                                                    ? $studentDegrees[$student->id][$exam->id] ?? null
                                                    : null;
                                                $score = $degree ? $degree->score : null;

                                                if ($score !== null) {
                                                    if ($exam->type == 'mohassalh') {
                                                        $mohassalh = is_numeric($score)
                                                            ? (is_numeric($mohassalh) ? $mohassalh : 0) + $score
                                                            : $score;
                                                    } elseif ($exam->type == 'final1') {
                                                        $final1 = is_numeric($score)
                                                            ? (is_numeric($final1) ? $final1 : 0) + $score
                                                            : $score;
                                                    } elseif ($exam->type == 'final2') {
                                                        $final2 = is_numeric($score)
                                                            ? (is_numeric($final2) ? $final2 : 0) + $score
                                                            : $score;
                                                    } elseif ($exam->type == 'final3') {
                                                        $final3 = is_numeric($score)
                                                            ? (is_numeric($final3) ? $final3 : 0) + $score
                                                            : $score;
                                                    }
                                                }
                                            }

                                            $final_mark = 0;
                                            if ($final3 !== null) {
                                                $final_mark = is_numeric($final3) ? $final3 : 0;
                                            } elseif ($final2 !== null) {
                                                $final_mark = is_numeric($final2) ? $final2 : 0;
                                            } else {
                                                $m = is_numeric($mohassalh) ? $mohassalh : 0;
                                                $f1 = is_numeric($final1) ? $final1 : 0;
                                                $final_mark = $m + $f1;
                                            }

                                            $decision = '';
                                            if ($final_mark < 50) {
                                                $decision = 'ر';
                                                $total_fails++;
                                            } else {
                                                $decision = $final_mark;
                                            }

                                            $mo_class = $mohassalh === 'غ' || $mohassalh === 'ر' ? 'bg-orange' : '';
                                            $f1_class = $final1 === 'غ' || $final1 === 'ر' ? 'bg-orange' : '';
                                            $f2_class = $final2 === 'غ' || $final2 === 'ر' ? 'bg-orange' : '';
                                            $f3_class = $final3 === 'غ' || $final3 === 'ر' ? 'bg-orange' : '';
                                            $dec_class = $decision === 'ر' || $decision === 'غ' ? 'bg-orange' : '';
                                        @endphp
                                        <td class="{{ $mo_class }}">{{ $mohassalh ?? '' }}</td>
                                        <td class="{{ $f1_class }}">{{ $final1 ?? '' }}</td>
                                        <td class="{{ $f2_class }}">{{ $final2 ?? '' }}</td>
                                        <td class="{{ $f3_class }}">{{ $final3 ?? '' }}</td>
                                        <td class="{{ $dec_class }}">{{ $decision }}</td>
                                    @endforeach

                                    @php
                                        $fail_percentage =
                                            $total_subjects > 0 ? round(($total_fails / $total_subjects) * 100, 1) : 0;
                                        $status_final1 = $fail_percentage >= 50 ? 'فصل' : '';
                                    @endphp

                                    <td>{{ $total_fails }}</td>
                                    <td>{{ $fail_percentage }}</td>
                                    <td>{{ $status_final1 }}</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @elseif($students->count() == 0)
            <div class="alert alert-warning text-center" style="border-radius: 12px;">
                <i class="fa fa-exclamation-triangle fa-2x mb-2"></i>
                <br>لا يوجد طلاب مسجلين في هذه الشعبة
            </div>
        @else
            <div class="alert alert-info text-center" style="border-radius: 12px;">
                <i class="fa fa-info-circle fa-2x mb-2"></i>
                <br>لا يوجد اختبارات مسجلة لهذه الشعبة
            </div>
        @endif

    </div>
</div>
@endsection
@section('js')
@toastr_js
@toastr_render

<script>
    function exportToExcel() {
        var table = document.getElementById('marksTable');
        if (!table) {
            alert('لا توجد بيانات للتصدير');
            return;
        }

        // Clone the table so we don't modify the original
        var clone = table.cloneNode(true);

        // Apply inline styles to all header cells (green)
        var thCells = clone.querySelectorAll('thead th');
        for (var i = 0; i < thCells.length; i++) {
            thCells[i].setAttribute('style',
                'background-color:#00FF00; border:2px solid #000; font-weight:bold; text-align:center; vertical-align:middle; padding:5px; font-family:Arial; font-size:14px; color:#000;'
            );
        }

        // Apply inline styles to all body cells (yellow, or orange for bg-orange)
        var tdCells = clone.querySelectorAll('tbody td');
        for (var i = 0; i < tdCells.length; i++) {
            var bgColor = tdCells[i].classList.contains('bg-orange') ? '#FFA500' : '#FFFF00';
            tdCells[i].setAttribute('style', 'background-color:' + bgColor +
                '; border:2px solid #000; font-weight:bold; text-align:center; vertical-align:middle; padding:5px; font-family:Arial; font-size:14px; color:#000;'
            );
        }

        var sectionName = '@php echo $section->Name_Section ?? "report"; @endphp';
        var fileName = 'تقرير_درجات_' + sectionName + '.xls';

        var xTag = 'x';
        var html = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:' + xTag +
            '="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
        html += '<head>';
        html += '<meta charset="utf-8">';
        html += '<!--[if gte mso 9]><xml>';
        html += '<' + xTag + ':ExcelWorkbook>';
        html += '<' + xTag + ':ExcelWorksheets>';
        html += '<' + xTag + ':ExcelWorksheet>';
        html += '<' + xTag + ':Name>Sheet1</' + xTag + ':Name>';
        html += '<' + xTag + ':WorksheetOptions>';
        html += '<' + xTag + ':DisplayRightToLeft/>';
        html += '<' + xTag + ':DisplayGridlines/>';
        html += '</' + xTag + ':WorksheetOptions>';
        html += '</' + xTag + ':ExcelWorksheet>';
        html += '</' + xTag + ':ExcelWorksheets>';
        html += '</' + xTag + ':ExcelWorkbook>';
        html += '</xml><![endif]-->';
        html += '</head><body>';
        html += '<table dir="rtl" style="border-collapse:collapse; direction:rtl; width:100%;">';
        html += clone.innerHTML;
        html += '</table>';
        html += '</body></html>';

        var blob = new Blob([html], {
            type: 'application/vnd.ms-excel;charset=utf-8'
        });
        var link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = fileName;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        URL.revokeObjectURL(link.href);
    }
</script>

@endsection
