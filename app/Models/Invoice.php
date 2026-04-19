<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'payment_id', 'invoice_number', 'subtotal',
        'tax', 'total_amount', 'status', 'issue_date', 'due_date'
    ];

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}