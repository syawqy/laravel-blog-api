<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $user = User::all();
            return response()->json([
                "success" => true,
                "message" => "User List",
                "data" => $user
            ]);
        } 
        catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
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
        try{
            $input = $request->all();
            $validator = Validator::make($input, [
                'name' => 'required|min:4',
                'email' => 'required|email',
                'password' => 'required|min:8',
                'role' => 'required|max:15',
            ]);
            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors());       
            }
            $user = User::create([
                'name' => $input['name'],
                'email' => $input['email'],
                'password' => bcrypt($input['password']),
                'role' => $request->role
            ]);
            return response()->json([
                "success" => true,
                "message" => "user created successfully.",
                "data" => $user
            ]);
        }
        catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $user = User::findOrFail($id);
            
            return response()->json([
                "success" => true,
                "message" => "user retrieved successfully.",
                "data" => $user
            ]);
        } 
        catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
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
        try{
            $input = $request->all();
            $validator = Validator::make($input, [
                'name' => 'required|min:4',
                'email' => 'required|email',
                'password' => 'required|min:8',
                'role' => 'required|max:15',
            ]);
            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors());       
            }
            $user = User::findOrFail($id);
            $user->name = $input['name'];
            $user->email = $input['email'];
            $user->password = bcrypt($input['password']);
            $user->role = $input['role'];
            $user->save();
            return response()->json([
                "success" => true,
                "message" => "user updated successfully.",
                "data" => $user
            ]);
        }
        catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            return response()->json([
                "success" => true,
                "message" => "user deleted successfully.",
                "data" => $user
            ], 204);
        } 
        catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
