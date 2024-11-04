<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    //
    public function getAllUsers(){

        //TRy and catch to avoid unnecessary exceptions throw
        try{
            $user = User::all();
            if($user){
                return response()->json(["success" => true, "message" => "All Users Fetched Succcessfully", "data" => $user], 200);
            }else{

            return response()->json(["success" => false, "message" => "An Error Occurred"], status: 500);
            }
        }catch(Exception $e){
            return response()->json(["success" => false, "message" => "An Error Occurred: ".$e->getMessage()], status: 500);
        }
    }


    public function createUser(Request $request){
        try{
            $request->validate([
            "name" => "required|string",
            "email" => "required|email|string",
            'password' => "required|string|min:6"
        ]);
        }catch (ValidationException $e) {
            // Return validation error messages
            return response()->json([
                'success' => false,
                'errors' => $e->validator->errors(),
            ], 422); // Unprocessable Entity status code
        }


        $user = User::create([
            "name" => $request['name'],
            "email" => $request['email'],
            'password' => Hash::make($request['password'])
        ]);
        return response()->json(["success" => true, "message" => "All Users Fetched Succcessfully", "data" => $user], 200);
    }
    public function get_all_products(){
       try{
         //1. Get all Users First
         $users = User::all();
         //loop over each user and consume the products get by id api
         foreach($users as $user){
             //Get the user id to pass to the api as a path variable
             $id = $user->id;

             //consume api default link http://localhost:2000
             $url = "http://localhost:2000/api/products/get_by_user/".$id;

             $response = Http::get($url);
             if(!$response->successful()){
                 return response()->json(["success" => false, "message" => "An Error Occurred"], 422);
             }
             $data = $response->json();
             //set the data for each user
             $user->products = $data['data'];

         }
         //return the formatted User array
         return response()->json(["success" => true, "message" => "All Users And Their Products Fetched Succcessfully", "data" => $users], 200);
       }catch(Exception $e){
        return response()->json(["success" => false, "message" => "An Error Occurred: ".$e->getMessage()], status: 500);
       }
    }
}

