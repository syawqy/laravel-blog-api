<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BlogController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        try {
            /** @var \App\Models\User $user **/
            $user = auth()->user();
            
            switch($user->role){
                case "admin":
                case "manager":
                    $blogs = Blog::all();
                    break;
                case "user":
                    $blogs = Blog::where('user_id',$user->id)->get();
                    break;
                default:
                    $blogs = [];
            }

            return response()->json([
                "success" => true,
                "message" => "BLog List",
                "data" => $blogs,
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
            $user = auth()->user();

            $input = $request->all();
            $input['user_id'] = $user->id;

            $validator = Validator::make($input, [
                'title' => 'required',
                'body' => 'required',
            ]);
            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors());       
            }
            $blog = Blog::create($input);
            return response()->json([
                "success" => true,
                "message" => "Blog created successfully.",
                "data" => $blog
            ], 201);
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
            $user = auth()->user();

            switch($user->role){
                case "admin":
                case "manager":
                    $blog = Blog::findOrFail($id);
                    break;
                case "user":
                    $blog = Blog::where('user_id',$user->id)->where('id',$id)->firstOrFail();
                    break;
                default:
                    return $this->sendError('Error.', 'role not supported');   
            }
            
            return response()->json([
                "success" => true,
                "message" => "blog retrieved successfully.",
                "data" => $blog
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
        try {
            $user = auth()->user();

            $input = $request->all();            
            $validator = Validator::make($input, [
                'title' => 'required',
                'body' => 'required',
            ]);
            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors());       
            }

            switch($user->role){
                case "admin":
                case "manager":
                    $blog = Blog::findOrFail($id);
                    break;
                case "user":
                    $blog = Blog::where('user_id',$user->id)->where('id',$id)->firstOrFail();
                    break;
                default:
                    return $this->sendError('Error.', 'role not supported');   
            }
            
            $blog->title = $input['title'];
            $blog->body = $input['body'];
            $blog->save();
            return response()->json([
                "success" => true,
                "message" => "blog updated successfully.",
                "data" => $blog
            ], 200);
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
            $blog = Blog::findOrFail($id);
            $blog->delete();
            return response()->json([
                "success" => true,
                "message" => "blog deleted successfully.",
                "data" => $blog
            ], 204);
        } 
        catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
