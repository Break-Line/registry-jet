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

    // rules
    public static function rules ($id='', $merge=[]) {
        return array_merge(
            [
                'email' => 'required|email|max:255|unique:customers,email' . ",$id",
                'business_name' => 'required|max:255',
                'address' => 'required|max:255',
                'postal_code' => 'required|numeric|digits:5',
                'city' => 'required|max:255',
                'province' => 'required|max:255',
                'region' => 'required|max:255'
            ], 
            $merge
        );
    }
}
