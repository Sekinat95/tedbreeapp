<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    //
    public function __construct()
    {
        //$this->middleware('assign.guard:api', ['except'=>['login','register']]);
        //$this->middleware('assign.guard:guest', ['except'=>['login','register']]);

    }
    /**
     * login method for business users
     */
    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6'
        ]);
        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }
        $token_validity = 24 * 60;//1 day
        $this -> guard()->factory()->setTTL($token_validity);
        if(!$token = $this->guard()->attempt($validator->validated())){
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }//end login

    /***
     * login method for guest users
     */
    public function guestLogin(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6'
        ]);
        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }
        $token_validity = 24 * 60;//1 day
        $this ->guard('guest')->factory()->setTTL($token_validity);
        if(!$token = $this->guard('guest')->attempt($validator->validated())){
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }//end guest login
    

    /**
     * register method for business
     */
    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:6'
        ]);
        if($validator->fails()) {
            return response()->json([
                $validator->errors()
            ], 422);
        }
        
        $user = User::create(array_merge(
            $validator->validated(),
            ['password'=> bcrypt($request->password) ]
        ));

        return response()->json(['message'=>'user created successfully', 'user'=> $user]);

    }//end register

    /**
     * logout method for business
     */
    public function logout(){
        $this->guard()->logout();
        return response()->json(['message', 'user logged out successfully']);
    }//end logout

    public function profile(){
        return response()->json($this->guard()->user());
    }//end profile
    
    public function refresh(){
        return $this->respondWithToken($this->guard()->refresh());
    }//end refresh

    /**
     * utility function for json response
     */
    protected function respondWithToken($token){
        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'token_validity' => $this->guard()->factory()->getTTL() * 60
        ]);
    }

    protected function guard(){
        return Auth::guard(); 
    }
    
}
