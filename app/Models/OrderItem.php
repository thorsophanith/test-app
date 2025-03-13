<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;
    public function product(){
        return $this->belongsTo(Products::class);
    }

    public function order(){
        return $this->belongsTo(Order::class);
    }
}
