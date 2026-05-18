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
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .marks-card-header {
            background: #f8f9fa;
            padding: 15px 25px;
            font-weight: bold;
            font-size: 16px;
            color: #333;
            border-bottom: 2px solid #667eea;
        }

        .table thead th {
            background: #667eea;
            color: #fff;
            font-weight: 600;
            font-size: 13px;
            white-space: nowrap;
            vertical-align: middle;
        }

        .table tbody td {
            vertical-align: middle;
            font-size: 13px;
        }

        .subject-header {
            background: #e8eaf6 !important;
            color: #333 !important;
            font-weight: bold !important;
            text-align: center;
        }

        .badge-score {
            padding: 4px 10px;
            border-radius: 12px;
            font-weight: bold;
            font-size: 12px;
        }

        .score-high {
            background: #d4edda;
            color: #155724;
        }

        .score-mid {
            background: #fff3cd;
            color: #856404;
        }

        .score-low {
            background: #f8d7da;
            color: #721c24;
        }

        .total-row {
            background: #f0f0f0;
            font-weight: bold;
        }

        @media print {
            @page {
                size: landscape;
                margin: 10mm;
            }

            .print_btn {
                display: none !important;
            }

            .back_btn {
                display: none !important;
            }

            body {
                /* -webkit-print-color-adjust: exact;
                                                        print-color-adjust: exact; */
            }

            .no-print {
                display: none !important;
            }

            .marks-card {
                box-shadow: none !important;
                border: 1px solid #ddd !important;
                margin-top: 20px !important;
            }

            .table-responsive {
                overflow: visible !important;
            }

            .table th,
            .table td {
                padding: 0.1rem !important;
                font-size: 11px !important;
                white-space: nowrap !important;
            }

            .report-header-bg {
                background: #667eea !important;
                color: #000 !important;
            }

            .table thead th {
                background: #667eea !important;
                color: #000 !important;
            }

            .subject-header {
                background: #e8eaf6 !important;
            }

            .badge-score {
                border: none !important;
            }

            .score-high {
                background: #fff !important;
                color: #155724 !important;
            }

            .score-mid {
                background: #fff !important;
                color: #856404 !important;
            }

            .score-low {
                background: #fff !important;
                color: #721c24 !important;
            }

            .total-row {
                background: #fff !important;
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
            <a href="{{ route('section_marks_report') }}" class="btn btn-secondary print_btn">
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
                <div class="marks-card-header">
                    <i class="fa fa-table"></i> جدول الدرجات
                </div>
                <div class="table-responsive" style="padding: 15px;">
                    <table class="table table-bordered table-hover table-sm text-center" id="marksTable">
                        <thead>
                            <tr>
                                <th rowspan="2" style="vertical-align: middle;">#</th>
                                <th rowspan="2" style="vertical-align: middle;">اسم الطالب</th>
                                <th rowspan="2" style="vertical-align: middle;">رقم القيد</th>
                                @foreach ($examsBySubject as $subjectId => $subjectExams)
                                    <th colspan="{{ $subjectExams->count() }}" class="subject-header">
                                        {{ $subjectExams->first()->subject->name ?? '-' }}
                                    </th>
                                @endforeach
                                <th rowspan="2" style="vertical-align: middle;">المجموع</th>
                                <th rowspan="2" style="vertical-align: middle;">النسبة</th>
                            </tr>
                            <tr>
                                @foreach ($examsBySubject as $subjectId => $subjectExams)
                                    @foreach ($subjectExams as $exam)
                                        <th title="{{ $exam->name }}">
                                            {{ $exam->name }}
                                            <br><small>({{ $exam->full_mark }})</small>
                                        </th>
                                    @endforeach
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($students as $index => $student)
                                @php
                                    $totalScore = 0;
                                    $totalFullMark = 0;
                                @endphp
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td class="text-right" style="white-space: nowrap;">{{ $student->name }}</td>
                                    <td>{{ $student->NoKnow ?? '-' }}</td>
                                    @foreach ($examsBySubject as $subjectId => $subjectExams)
                                        @foreach ($subjectExams as $exam)
                                            @php
                                                $degree = isset($studentDegrees[$student->id])
                                                    ? $studentDegrees[$student->id][$exam->id] ?? null
                                                    : null;
                                                $score = $degree ? $degree->score : null;
                                                if ($score !== null) {
                                                    $totalScore += $score;
                                                    $totalFullMark += $exam->full_mark;
                                                    $pct = $exam->full_mark > 0 ? ($score / $exam->full_mark) * 100 : 0;
                                                }
                                            @endphp
                                            <td>
                                                @if ($score !== null)
                                                    <span
                                                        class="badge-score {{ $pct >= 75 ? 'score-high' : ($pct >= 50 ? 'score-mid' : 'score-low') }}">
                                                        {{ $score }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        @endforeach
                                    @endforeach
                                    <td class="total-row">
                                        {{ $totalScore }} / {{ $totalFullMark }}
                                    </td>
                                    <td class="total-row">
                                        @php
                                            $totalPct =
                                                $totalFullMark > 0 ? round(($totalScore / $totalFullMark) * 100, 1) : 0;
                                        @endphp
                                        <span
                                            class="badge-score {{ $totalPct >= 75 ? 'score-high' : ($totalPct >= 50 ? 'score-mid' : 'score-low') }}">
                                            {{ $totalPct }}%
                                        </span>
                                    </td>
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

    var clone = table.cloneNode(true);

    var thCells = clone.querySelectorAll('thead th');
    for (var i = 0; i < thCells.length; i++) {
        var isSubject = thCells[i].classList.contains('subject-header');
        var bg = isSubject ? '#e8eaf6' : '#667eea';
        var color = isSubject ? '#333' : '#fff';
        thCells[i].setAttribute('style', 'background-color:' + bg + '; border:1px solid #dee2e6; font-weight:bold; text-align:center; vertical-align:middle; padding:5px; font-family:Arial; font-size:13px; color:' + color + ';');
    }

    var tdCells = clone.querySelectorAll('tbody td');
    for (var i = 0; i < tdCells.length; i++) {
        var bg = tdCells[i].classList.contains('total-row') ? '#f0f0f0' : '#FFFFFF';
        tdCells[i].setAttribute('style', 'background-color:' + bg + '; border:1px solid #dee2e6; text-align:center; vertical-align:middle; padding:5px; font-family:Arial; font-size:13px; color:#000;');
    }

    var sectionName = '@php echo $section->Name_Section ?? "report"; @endphp';
    var fileName = 'تقرير_درجات_' + sectionName + '.xls';

    var xTag = 'x';
    var html = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:' + xTag + '="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
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

    var blob = new Blob([html], { type: 'application/vnd.ms-excel;charset=utf-8' });
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
