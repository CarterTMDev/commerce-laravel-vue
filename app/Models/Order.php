<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'customer_id',
        'isShipped',
        'initial_cost',
        'shipping_cost'
    ];

    public function customer()
    {
        return $this->belongsTo(User::class);
    }

    public function makeEmpty()
    {
        $empty = [];
        $_fillable = (new Order())->getFillable();
        foreach ($_fillable as $value) {
            $empty[$value] = null;
        }
        return $empty;
    }
}
