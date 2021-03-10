<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class JobController extends Controller
{
    protected $user;
    
    
    public function __construct(){
        //$this->middleware('auth:api');
        $this->user = $this->guard()->user();
    }
    public function listAll(){
        
        return Job::all();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $jobs = $this->user->jobs()->get([
            'id',
            'title', 
            'description', 
            'location', 
            'category', 
            'benefits',
            'salary',
            'type',
            'work_condition',
            'created_by'
             ]);
        return response()->json($jobs->toArray());
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
        $validator = Validator::make($request->all(), [
            'title' => 'required|string', 
            'description' => 'required|string', 
            'location' => 'required|string', 
            'category' => 'required|string', 
            'benefits' => 'required|string',
            'salary' => 'required|string',
            'type' => 'required|string',
            'work_condition' => 'required|string',
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 400);
        }

        $job = new Job();
        $job->title = $request->title;
        $job->description = $request->description;
        $job->location = $request->location;
        $job->category = $request->category;
        $job->benefits = $request->benefits;
        $job->salary = $request->salary;
        $job->type = $request->type;
        $job->work_condition = $request->work_condition;
        

        if($this->user->jobs()->save($job)){
            return response()->json([
                'status'=>true,
                'job'=>$job

            ]);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>'new job could not be saved'
            ]);
        }
    }//end store

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function search($search)
    {
        $query = Job::select( 
            'id',
            'title', 
            'description', 
            'location', 
            'category', 
            'benefits',
            'salary',
            'type',
            'work_condition',
            'created_by');
        $search = $request->input('search',null);
        $query = is_null($search)  ? $query : $query->where('title','LIKE','%'.$search.'%')->orWhere('description','LIKE','%'.$search.'%')->orWhereHas('company', function ($q) use ($search)
            {
                $q->where('title','LIKE','%'.$search.'%');
            });


        //return $jobs= $query->paginate(5);
        return $jobs= $query;
    }//end search

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function edit(Job $job)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Job $job)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string', 
            'description' => 'required|string', 
            'location' => 'required|string', 
            'category' => 'required|string', 
            'benefits' => 'required|string',
            'salary' => 'required|string',
            'type' => 'required|string',
            'work_condition' => 'required|string',
        
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 400);
        }

        $job->title = $request->title;
        $job->description = $request->description;
        $job->location = $request->location;
        $job->category = $request->category;
        $job->benefits = $request->benefits;
        $job->salary = $request->salary;
        $job->type = $request->type;
        $job->work_condition = $request->work_condition;
        

        if($this->user->jobs()->save($job)){
            return response()->json([
                'status'=>true,
                'job'=>$job

            ]);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>'the job could not be updated'
            ]);
        }
    }//end update

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function destroy(Job $job)
    {
        if($job->delete()){
            return response()->json([
                'status'=>true,
                'job'=>$job,

            ]);
        }else {
            return response()->json([
                'status'=>false,
                'message'=>'Oops, the job could not be deleted'
            ]);
        }
    }

    protected function guard(){
        return Auth::guard();
    }
}
