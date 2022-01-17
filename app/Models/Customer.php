<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'address_1',
        'address_2',
        'city',
        'state',
        'zipcode',
        'country'
    ];

    public function makeEmpty()
    {
        $empty = [];
        $_fillable = (new Customer())->getFillable();
        foreach ($_fillable as $value) {
            $empty[$value] = null;
        }
        return $empty;
    }
}
