<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    
    protected $table = 'customers';
    protected $fillable = [
        'email', 'business_name', 'address', 'postal_code', 'city', 'province', 'region'
    ];
    protected $dateTimeFormat = 'm-d-Y H:i';
    protected $casts = [
        'created_at' => 'datetime:d-m-Y H:i',
        'updated_at' => 'datetime:d-m-Y H:i'
    ];
}
