<?php

namespace App\Http\Controllers;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ApplicationController extends Controller
{
    protected $guest;
    
    public function __construct(){
       $this->guest = $this->guard('guest')->guest();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function apply(Request $request, $job_id)
    {
        $validator = Validator::make($request->all(), [ 
            'firstname' => 'required|string', 
            'lastname' => 'required|string', 
            'email' => 'required|string', 
            'phone' => 'required|string',
            'location' => 'required|string',
            'cv' => 'required|string',
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 400);
        }

        $application = new Application();
        $application ->firstname = $request->firstname;
        $application ->lastname = $request->lastname;
        $application ->email = $request->email;
        $application ->phone = $request->phone;
        $application ->location = $request->location;
        $application ->cv = $request->cv;

        if($this->guest->applications()->save($application)){
            return response()->json([
                'status'=>true,
                'application'=>$application 

            ]);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>'new job could not be saved'
            ]);
        }
    }//end apply

    protected function guard(){
        return Auth::guard();
    }
}
