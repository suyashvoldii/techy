<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB; 
use Illuminate\Validation\Rule; 


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $company = DB::table('products')
            ->select('product_id','name','price')
            ->get();
      
        if(count($company) != 0){
            return response()->json([
                'status'=> 'success',
                'message' => 'products found',
                'data' => $company,
            ],Response::HTTP_OK);
        }
     
        else{
            return response()->json([
                'status'=> 'success',
                'message' => 'no products found',
                'data' => $company,
            ],Response::HTTP_OK);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Validate data
        $data = $request->only( 'name','price','description');
        $validator = Validator::make($data, [
            'name' => 'required|regex:/^[a-zA-ZÑñ\s]+$/|unique:products|min:2|max:115',
            'price' => 'required|regex:/^[-0-9\+]+$/|max:10|min:1',
            'description' => 'required|regex:/^[a-zA-ZÑñ\s]+$/|min:2|max:115',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        //Request is valid, create new user
        $company = Product::create([
        	'name' => $request->name,
        	'price' => $request->price,
        	'description' => $request->description,
        ]);

        //User created, return success response
        return response()->json([
            'status' => 'success',
            'message' => 'product created successfully',
            'data' => $company
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
          // $company = Company::find($company_id);
          $company = DB::table('products')
          ->where('product_id', '=', $id)
          ->first();
          
          if($company)
              return $company;
          else{
              return response()->json([
                  'Failure'=> 'NO Companies Found'
              ],Response::HTTP_OK);
          }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function update(Request $request, $id)
    // {
    
        // //fetch old data 
        // if(!$companydetail = Company::find($id)){
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Company Not Found',
        //     ], Response::HTTP_NOT_FOUND);  
        // }  
        // //validate input 
        // $data = $request->only( 'name','contact','country','state',
        //                         'city',
        //                         'pincode',
        //                         'department',
        //                         'branch',
        //                         'address');
        // $validator = Validator::make($data, [
        //     'name' => ['required',Rule::unique('companies')->ignore($companydetail),'regex:/^[a-zA-ZÑñ\s]+$/','min:2','max:115'],
        //     'contact' => 'required|regex:/^[-0-9\+]+$/|max:10|min:10',
        //     'country' => 'required|regex:/^[a-zA-ZÑñ\s]+$/|min:2|max:75',
        //     'state' => 'required|regex:/^[a-zA-ZÑñ\s]+$/|min:2|max:75',
        //     'city' => 'required|regex:/^[a-zA-ZÑñ\s]+$/|min:2|max:75',
        //     'pincode' => 'required|regex:/^[-0-9\+]+$/|min:5|max:6',
        //     'department' => 'required|regex:/^[a-zA-ZÑñ\s]+$/|min:2|max:75',
        //     'branch' => 'required|regex:/^[a-zA-ZÑñ\s]+$/|min:2|max:75',
        //     'address' => 'required|regex:/^[a-zA-ZÑñ\s]+$/|min:2|max:200',
    //     ]);

        

        //Send failed response if request is not valid
    //     if ($validator->fails()) {
      
    //         return response()->json(['error' => $validator->messages()], 400);
    //     }

    //     //update company
    //     $companydetail->name = $request->name;
    //     $companydetail->contact = $request->contact;
    //     $companydetail->country = $request->country;
    //     $companydetail->state = $request->state; 
    //     $companydetail->city = $request->city; 
    //     $companydetail->pincode = $request->pincode; 
    //     $companydetail->department = $request->department; 
    //     $companydetail->branch = $request->branch; 
    //     $companydetail->address = $request->address; 
    //     $companydetail->update();
       
    //     if($companydetail){
    //       //company updated, return success response
    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Company updated successfully',
    //         'data' => $companydetail
    //     ], Response::HTTP_OK);  
    //     }
    //     else{
    //         return response()->json([
    //             'Failure'=> 'NO Companies Found'
    //         ],Response::HTTP_NOT_FOUND);
    //     }
    // }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $company = Product::find($id);
        if(!$company){
            return response()->json([
                'success' => false,
                'message' => 'Company Not Found',
            ], Response::HTTP_NOT_FOUND);  
        }
        $deleted = $company->delete();
        if($deleted){
            return response()->json([
                'success' => true,
                'message' => 'Company Deleted successfully',
            ], Response::HTTP_OK);  
        }
    }

}
