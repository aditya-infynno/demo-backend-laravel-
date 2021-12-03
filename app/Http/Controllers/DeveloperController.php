<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDeveloperRequest;
use App\Http\Requests\UpdateDeveloperRequest;
use Illuminate\Http\Request;
use App\Models\Developer;
use Auth;
use Exception;
use Storage;

class DeveloperController extends Controller
{

	public function __construct(private Developer $developer){}
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	try {
    		
    		$developers = $this->developer
    		->whereUserId(Auth::id())
			->get();	

			return response()->json([
				'status' => true,
				'data' => $developers
			],200);

    	} catch (Exception $e) {
    		
    		return response()->json([
    			'status' => false,
    			'error' => $e->getMessage()
    		],500);
    	}
		
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreDeveloperRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDeveloperRequest $request)
    {
        try {
        	
        	$this->props($request)->save();

        	return response()->json([
				'status' => true,
				'data' => $this->developer
			],200);

        } catch (Exception $e) {
        	
        	return response()->json([
    			'status' => false,
    			'error' => $e->getMessage()
    		],500);
        }
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Developer  $developer
     * @return \Illuminate\Http\Response
     */
    public function edit(Developer $developer)
    {
        return $developer;
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateDeveloperRequest  $request
     * @param  \App\Models\Developer  $developer
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateDeveloperRequest $request, Developer $developer)
    {
        try {
        	
        	$this->developer = $developer;

        	$this->props($request)->save();

        	return response()->json([
				'status' => true,
				'data' => $this->developer
			],200);

        } catch (Exception $e) {
        	
        	return response()->json([
    			'status' => false,
    			'error' => $e->getMessage()
    		],500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Developer  $developer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Developer $developer)
    {
    	$this->developer = $developer;

    	$developer->delete();

    	if ($this->developer->avatar) {

        	Storage::disk('public')->delete($this->developer->avatar);
    	}

    	return response()->json([
    		'status' => true,
    	],200);
    }
    /**
     * Remove multiple the specified resource from storage.
     *
     * @param  \App\Models\Developer  $developer
     * @return \Illuminate\Http\Response
     */
    public function destroyMany(Request $request)
    {
    	$request->validate([
    		'developer_id.*' => ['required','exists:developers,id']
    	]);

    	foreach ($request->developer_id as $id) {
    		
    		$developer = Developer::find($id);
    		
    		$avatar = $developer->avatar;
    		$developer->delete();
	    	
	    	if ($avatar) {

	        	Storage::disk('public')->delete($avatar);
	    	}
    	}


    	return response()->json([
    		'status' => true,
    	],200);
    }
    /**
     * Set Props to store request to database
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    private function props(Request $request): Object
    {
        $this->developer->user_id = Auth::id();
        $this->developer->first_name = $request->first_name;
        $this->developer->last_name = $request->last_name;
        $this->developer->email = $request->email;
        $this->developer->phone_number = $request->phone_number;
        $this->developer->address = $request->address;
        if ($request->hasFile('avatar')) {
        	$this->developer->avatar = $request->avatar->store('developer/avatar','public');	
        }

        return $this;
    }
   	/**
     * Storing requested props in developers table
     *
     * @return \Illuminate\Http\Response
     */
    private function save(): Object
    {
        $this->developer->save();
        return $this;
    }
}
