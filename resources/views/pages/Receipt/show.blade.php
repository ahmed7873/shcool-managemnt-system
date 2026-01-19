@extends('layouts.master')
@section('css')
    <style>
        .invoice {
            margin: auto;
            background: #fff;
            padding: 30px;
            border: 1px solid #ddd;
        }

        .header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }

        .header h2 {
            margin: 0;
            color: #333;
        }

        .company-details {
            text-align: left;
        }

        .invoice-details {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th,
        table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        table th {
            background: #f0f0f0;
        }

        .total {
            margin-top: 20px;
            width: 100%;
            display: flex;
            justify-content: flex-end;
        }

        .total table {
            width: 300px;
        }

        .total td {
            padding: 8px;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 14px;
            color: #555;
        }

        @media print {
            .invoice {
            margin: auto !important;
            background: #fff !important;
            padding: 30px !important;
            border: 1px solid #ddd !important;
        }

        .header {
            display: flex !important;
            justify-content: space-between !important;
            margin-bottom: 30px !important;
        }

        .header h2 {
            margin: 0 !important;
            color: #333 !important;
        }

        .company-details {
            text-align: left !important;
        }

        .invoice-details {
            margin-bottom: 20px !important;
        }

        table {
            width: 100% !important;
            border-collapse: collapse !important;
        }

        table th,
        table td {
            border: 1px solid #ddd !important;
            padding: 10px !important;
            text-align: center !important;
        }

        table th {
            background: #f0f0f0 !important;
        }

        .total {
            margin-top: 20px !important;
            width: 100% !important;
            display: flex !important;
            justify-content: flex-end !important;
        }

        .total table {
            width: 300px !important;
        }

        .total td {
            padding: 8px !important;
        }

        .footer {
            text-align: center !important;
            margin-top: 30px !important;
            font-size: 14px !important;
            color: #555 !important;
        }
        }
    </style>
@section('title')
    تعديل رسوم دراسية
@stop
@endsection
@section('page-header')
<!-- breadcrumb -->
@section('PageTitle')
    تعديل رسوم دراسية
@stop
<!-- breadcrumb -->
@endsection
@section('content')
<!-- row -->
<div class="row">
    <div class="col-md-12 mb-30">
        <div class="card card-statistics h-100">
            <div class="card-body">
                <div class="invoice print d-print-block">
                    <div class="header">
                        <div>
                            <h2>فاتورة</h2>
                            <p><strong>رقم الفاتورة:</strong> {{ $recept->id }}</p>
                            <p><strong>تاريخ الفاتورة:</strong> {{ $recept->date }}</p>
                        </div>

                        <div class="company-details">
                            <h3>{{ $setting['setting']['school_name'] }}</h3>
                            <p>{{ $setting['setting']['address'] }}</p>
                            <p>Phone: {{ $setting['setting']['phone'] }}</p>
                            <p>Email: {{ $setting['setting']['school_email'] }}</p>
                        </div>
                    </div>

                    <div class="invoice-details">
                        <p><strong>الفاتورة الى:</strong></p>
                        <p>{{ $recept->student->name }}</p>
                        <p>رقم ولي الامر: {{ $recept->student->myparent->Phone_Father }}</p>
                    </div>

                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>الرسوم</th>
                                <th>المبلغ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>{{ $recept->invoice->fees->title }}</td>
                                <td>{{ $recept->Debit }}</td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="total">
                        <table>
                            <tr>
                                <td>المجموع</td>
                                <td>{{ $recept->Debit }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- row closed -->
@endsection
@section('js')
@endsection
