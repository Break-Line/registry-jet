<?php

namespace App\Imports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Validator;

class CustomersImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $input = [
            'email' => $row[0], 
            'business_name' => $row[1],
            'address' => $row[2],
            'postal_code' => $row[3],
            'city' => $row[4],
            'province' => $row[5],
            'region' => $row[6]
        ];
        $validator = Validator::make($input, Customer::rules())->validate();

        return Customer::create($input);
    }
}
