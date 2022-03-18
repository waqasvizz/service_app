<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Models\Mosque;
use Validator;
use App\Http\Resources\Mosque as MosqueResource;

class MosqueController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $mosques = Mosque::all();
        $mosques = Mosque::paginate(1);
        
        $count = Mosque::count();
    
        return $this->sendResponse(MosqueResource::collection($mosques), 'Mosques retrieved successfully.', $count);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
   
        $validator = Validator::make($input, [
            'name' => 'required',
            'address' => 'required',
            'zip_code' => 'required',
            'phone_number' => 'required',
            'website_link' => 'required'
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
   
        $mosque = Mosque::create($input);
   
        return $this->sendResponse(new MosqueResource($mosque), 'Mosque created successfully.');
    } 
   
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $mosque = Mosque::find($id);
  
        if (is_null($mosque)) {
            return $this->sendError('Mosque not found.');
        }
   
        return $this->sendResponse(new MosqueResource($mosque), 'Mosque retrieved successfully.');
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Mosque $mosque)
    {
        $input = $request->all();
   
        $validator = Validator::make($input, [
            'name' => 'required',
            'address' => 'required',
            'zip_code' => 'required',
            'phone_number' => 'required',
            'website_link' => 'required'
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
   
        $mosque->name = $input['name'];
        $mosque->address = $input['address'];
        $mosque->zip_code = $input['zip_code'];
        $mosque->phone_number = $input['phone_number'];
        $mosque->website_link = $input['website_link'];
        $mosque->save();
   
        return $this->sendResponse(new MosqueResource($mosque), 'Mosque updated successfully.');
    }
   
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Mosque $mosque)
    {
        $mosque->delete();
   
        return $this->sendResponse([], 'Mosque deleted successfully.');
    }
}