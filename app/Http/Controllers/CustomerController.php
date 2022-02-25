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
        return Inertia::render('Customers', [
            'customers' => Customer::paginate(10)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|unique:customers|email|max:255',
            'business_name' => 'required|max:255',
            'address' => 'required|max:255',
            'postal_code' => 'required|numeric|digits:5',
            'city' => 'required|max:255',
            'province' => 'required|max:255',
            'region' => 'required|max:255'
        ]);
  
        Customer::create($request->all());

        return redirect()->back()
                ->with('success', 'Post Created Successfully.');
    }
  
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function update(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255|unique:customers,email,'.$request->input('id'),
            'business_name' => 'required|max:255',
            'address' => 'required|max:255',
            'postal_code' => 'required|numeric|digits:5',
            'city' => 'required|max:255',
            'province' => 'required|max:255',
            'region' => 'required|max:255'
        ]);
  
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
