<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Customer;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CustomersImport;
use Maatwebsite\Excel\Exceptions\NoTypeDetectedException;
use Illuminate\Database\QueryException;

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
            'customers' => Customer::paginate(10),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $request->validate(Customer::rules());
  
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
        if ($request->has('id')) {
            $request->validate(Customer::rules($request->input('id')));
            Customer::find($request->input('id'))->update($request->all());
            return redirect()->back()
                ->with('success', 'Customer Updated Successfully.');
        }
        return redirect()->back()->withErrors([
            'id'=>'No ID provided.'
        ]);
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
            return redirect()->back()
                ->with('success', 'Customer Deleted Successfully.');
        }
    }

    /**
     * Import XLSX.
     *
     * @return Response
     */
    public function importXlsx(Request $request)
    {
        try {
            Excel::import(new CustomersImport, $request->file('selectedFile')->store('temp'), null, \Maatwebsite\Excel\Excel::XLSX);
        } catch (NoTypeDetectedException $e) {
            return redirect()->back()->withErrors([
                'selectedFile'=>'Sorry you are using a wrong format to upload files.'
            ]);
        } catch (QueryException $e) {
            return redirect()->back()->withErrors(['selectedFile' => 'Incorrect file fields.']);
        }
        return redirect()->back()
            ->with('success', 'Customer(s) Imported Successfully.');
    }
}
