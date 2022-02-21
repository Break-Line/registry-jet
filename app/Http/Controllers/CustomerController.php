<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Customer;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function index()
    {
        $data = Customer::all();
        return Inertia::render('Customers', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|unique:customers|email|max:255',
            'business_name' => 'required|max:255',
            'address' => 'required|max:255',
            'postal_code' => 'required|numeric|digits:5',
            'city' => 'required|max:255',
            'province' => 'required|max:255',
            'region' => 'required|max:255'
        ]);
        if ($validator->fails()) {
            return Inertia::render('Customers', ['errors' => $validator->errors()->all()]);
        }
  
        Customer::create($request->all());
  
        return redirect()->back()
            ->with('success', 'Customer Created Successfully.');
    }
  
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|unique:customers|email|max:255',
            'business_name' => 'required|max:255',
            'address' => 'required|max:255',
            'postal_code' => 'required|numeric|digits:5',
            'city' => 'required|max:255',
            'province' => 'required|max:255',
            'region' => 'required|max:255'
        ]);
        if ($validator->fails()) {
            return Inertia::render('Customers', ['errors' => $validator->errors()->all()]);
        }
  
        if ($request->has('id')) {
            Customer::find($request->input('id'))->update($request->all());
            return redirect()->back()
                ->with('success', 'Post Updated Successfully.');
        }
    }
  
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function destroy(Request $request)
    {
        if ($request->has('id')) {
            Customer::find($request->input('id'))->delete();
            return redirect()->back();
        }
    }
}
