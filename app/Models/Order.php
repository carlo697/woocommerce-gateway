<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $table = 'orders';
    protected $primaryKey = 'orden_id';
    public $incrementing = false;
    protected $casts = [
        "processed_resource" => "array",
    ];

    protected $fillable = ['order_id', 'invoice_number', 'original_resource', 'processed_resource'];
}
