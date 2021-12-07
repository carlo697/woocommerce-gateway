<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $table = 'orders';
    protected $primaryKey = 'order_id';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $casts = [
        "processed_resource" => "array",
    ];

    protected $fillable = ['order_id', 'invoice_number','invoiced', 'original_resource', 'processed_resource'];
}
