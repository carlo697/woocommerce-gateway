<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileProduct extends Model
{
    use HasFactory;
    protected $tables = "file_products"; 
    protected $fillable = ['file', 'status' ];
    protected $casts = ["status" => "string"];
    
}
