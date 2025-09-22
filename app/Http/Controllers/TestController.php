<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Helpers\DjangoHelper;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class TestController extends Controller
{
   public function index()
{
    // Get the Django token from the session
    $token = session('django_token');

 

    // If token does not exist in the session, return a 401 Unauthorized response
    if (!$token) {
        return response()->json(['error' => 'No Django token found in session'], 401);
    }

    // Make the API request to get users
    $response = Http::withHeaders(['Authorization' => 'Bearer ' . $token])
                    ->get('http://127.0.0.1:8000/api/users/');

      // Fetch categories
    $categoryResponse = Http::withHeaders(['Authorization' => 'Bearer ' . $token])
                            ->get('http://127.0.0.1:8000/api/categories/');
                            // Handle failed category request

    //Fetch Product
    $productResponse=Http::withHeaders(['Authorization' => 'Bearer ' . $token])
                            ->get('http://127.0.0.1:8000/api/products/');

    if (!$categoryResponse->successful()) {
        return response()->json([
            'error' => 'Failed to fetch categories',
            'details' => $categoryResponse->body()
        ], 500);
    }


    $categories = $categoryResponse->json();
    $products=$productResponse->json();
 
    // If the response is successful, process and return the data
    if ($response->successful()) {
        $data = $response->json();  // contains "users" and "total_users"
        
        return view('test', compact('data','categories','products'));
    }

    // If the request fails, return an error response with the details
    return response()->json([
        'error' => 'Failed to fetch users',
        'details' => $response->body()  // Log the body for better error understanding
    ], 500);
}


//For Category

public function createNewCategory(){
    $token=session('django_token');
    $data=[
       'name'=>'House',
       'slug'=>'house'
    ];

    $response=Http::withHeaders(['Authorization'=>'Bearer ' . $token])->post('http://127.0.0.1:8000/api/categories/',$data);
    if($response->successful()){
        return response()->json([
            "message"=>"Category created successfully",
            'data'=>$response->json()
        ]);
    }
    
    return response()->json([
        'message'=>'Category creation failed',
        'details'=>$response->json()
    ],$response->status());
}

//product 

public function createNewProduct(Request $request){
    $token=session('django_token');
   $data = $request->only(['category_id', 'title', 'slug', 'description', 'price', 'stock']);

    $response=Http::withHeaders(['Authorization'=> 'Bearer ' . $token])->post('http://127.0.0.1:8000/api/products/',$data);
    
    if(!$response->successful()){
        return response()->json([
            "error"=>"Product creation failed",
            "details"=>$response->json()
        ]);
    }

    return redirect()->route('django.users');
}   

public function deleteProduct(Request $request, $id)
{
    $token = session('django_token');

    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . $token
    ])->delete("http://127.0.0.1:8000/api/products/{$id}/");

    return redirect()->route('django.users');
}


public function updateProduct(Request $request, $id)
{
    $token = session('django_token');

    $data = $request->only(['category_id', 'title', 'slug', 'description', 'price', 'stock']);
 
    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . $token,
    ])->put("http://127.0.0.1:8000/api/products/{$id}/", $data);
log::info($response);

    if ($response->successful()) {
        return redirect()->route('django.users')->with('success', 'Product updated successfully.');
    }

    return redirect()->route('django.users')->with('error', 'Failed to update product.');
}


//orderlist for customer
public function orderList(){
    $token=Session('django_token');
    $response=Http::withHeaders([
        'Authorization' => 'Bearer ' . $token
    ])->get("http://127.0.0.1:8000/api/orders/");
    
 
    if(!$response->successful()){
       return response()->json([
        "error"=>"Failed to fetch order list",
         'details' => $response->body()
       ]);
       
    }
    
     $data=$response->json();
    return view('orderList',compact('data'));
    
}

public function order_Detail($id){
    
    $token=session('django_token');
    $response=Http::withHeaders([
        'Authorization' => 'Bearer ' . $token
    ])->get("http://127.0.0.1:8000/api/orders/{$id}");

    if(!$response->successful()){
         return response()->json([
        "error"=>"Failed to fetch order list",
         'details' => $response->body()
       ]);
    }

    $order=$response->json();
    
    return view('order_detail',compact('order'));
}


public function register(){
    $data=[
        'username'=>'Terry',
        'email'=>'terry@gmail.com',
        'password'=>'12345678'
    ];

     // Send POST request to Django registration endpoint
    $response = Http::post('http://127.0.0.1:8000/api/register/', $data);

    if ($response->successful()) {
        return response()->json([
            'message' => 'User successfully registered',
            'data' => $response->json() // optional: get the data returned by Django
        ]);
    }

    return response()->json([
        'error' => 'Registration failed',
        'details' => $response->body() // error info from Django
    ], 500);
}

public function login()
{
    $data = [
        'username' =>'test',
        'password' => '12345678'
    ];

    // Send POST request to Django login endpoint
    $response = Http::post('http://127.0.0.1:8000/api/login/', $data);

   

    // Check if the response was successful
    if ($response->successful()) {
        $token = $response->json()['access'];  // Get the access token from the response
        
        // Store the token in the session
        session(['django_token' => $token]);

        return redirect()->route('django.users');
    }

    // If login failed, return an error response with the details
    return response()->json(['error' => 'Failed to login', 'details' => $response->body()], 401);
}




}
