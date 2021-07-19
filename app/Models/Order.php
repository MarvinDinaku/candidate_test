<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['title', 'customer_id', 'cost', 'description'];

    public function customer()
    {
        return $this->belongsTo(Order::class);
    }
}
