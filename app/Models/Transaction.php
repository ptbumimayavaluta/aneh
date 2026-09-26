<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'trx_code',
        'customer_name',
        'id_type',
        'id_number',
        'country',
        'address',
        'type',
        'currency_id',
        'amount',
        'rate',
        'total_idr',
        'user_id',
        'updated_by',
        'deleted_by',
    ];

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    // Kasir/Admin pembuat
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // User yang mengedit
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // User yang menghapus (menggantungkan data)
    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
}