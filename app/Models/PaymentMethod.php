<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $fillable = [
        'name',
        'type',
        'description',
        'status',
    ];

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
}
