<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReceiptStudent extends Model
{
    public function student()
    {
        return $this->belongsTo('App\Models\Student', 'student_id');
    }
    public function invoice()
    {
        return $this->belongsTo(Fee_invoice::class, 'fee_invoice_id');
    }
}
