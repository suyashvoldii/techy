<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB; 
use Symfony\Component\HttpFoundation\Response;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $company = DB::table('products')
        //     ->select('product_id','name','price')
        //     ->get();
        // return $company;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
         $data = $request->only( 'product_id',"user_id");
         $validator = Validator::make($data, [
             'product_id' => 'required|numeric|exists:products',
             'user_id' => 'required|numeric|exists:users',
         ]);
 
         //Send failed response if request is not valid
         if ($validator->fails()) {
             return response()->json([ 
                 'status' => 'failure',
                 'message' => 'order not placed',
                 'error' => $validator->errors()
             ], 400);
         }
 
         //Request is valid, create new user
         $company = Order::create([
             'product_id' => $request->product_id,
             'user_id' => $request->user_id,
         ]);
 
         //User created, return success response
         return response()->json([
             'status' => 'success',
             'message' => 'order placed',
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
        $company = Order::all();
        return $company;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $company = Order::find($id);
        if(!$company){
            return response()->json([
                'success' => 'failure',
                'message' => 'order not found',
            ], Response::HTTP_OK);  
        }
        $deleted = $company->delete();
        if($deleted){
            return response()->json([
                'success' => 'success',
                'message' => 'order cancelled',
            ], Response::HTTP_OK);  
        }
    }
}
