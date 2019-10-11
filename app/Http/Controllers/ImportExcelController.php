<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Excel;
// use Maatwebsite\Excel\Facades\Excel;

class ImportExcelController extends Controller
{
    function index()
    {
     $data = DB::table('tbl_customer')->orderBy('id', 'DESC')->get();
     return view('import_excel', compact('data'));
    }

    function import(Request $request)
    {
       // dd($request->all());
     $this->validate($request, [
      'select_file'  => 'required|mimes:xls,xlsx'
     ]);

     $path = $request->file('select_file')->getRealPath();

     $data = Excel::load($path)->get();
     // dd($data->all());

     if($data->count() > 0)
     {
      foreach($data->toArray() as $key => $value)
      {
      	// dd($value);
       foreach($value as $rahul)
       {
       	// dd($rahul);
        $insert_data[] = array(
         'CustomerName'  => $value['customer_name'],
         'Gender'   => $value['gender'],
         'Address'   => $value['address'],
         'City'    => $value['city'],
         'PostalCode'  => $value['postal_code'],
         'Country'   => $value['country']
        );
       }
      }

      if(!empty($insert_data))
      {
       DB::table('tbl_customer')->insert($insert_data);
      }
     }
     return back()->with('success', 'Excel Data Imported successfully.');
    }
}
