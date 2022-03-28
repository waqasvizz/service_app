<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use Validator;
use App\Models\Bid;

class BidController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $posted_data = $request->all();
        $posted_data['paginate'] = isset($posted_data['per_page'])? $posted_data['per_page']:10;
        
        $bids = Bid::getBids($posted_data);
        $message = count($bids) > 0 ? 'Bids retrieved successfully.' : 'Bids not found against your query.';

        return $this->sendResponse($bids, $message);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request_data = $request->all(); 
   
        $validator = Validator::make($request_data, [
            'description' => 'required',
            'price' => 'required',
            'post_id' => 'required',
            'provider_id' => 'required'
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        
        $bid = Bid::saveUpdateBid($request_data);
   
        return $this->sendResponse($bid, 'Bid created successfully.');
    } 
   
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $bid = Bid::find($id);
  
        if (is_null($bid)) {
            return $this->sendError('Bid not found.');
        }
   
        return $this->sendResponse($bid, 'Bid retrieved successfully.');
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
        $request_data = $request->all();
        $validator = Validator::make($request_data, [
            'description' => 'required',
            'price' => 'required',
            // 'post_id' => 'required',
            // 'provider_id' => 'required'
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $posted_data = array();
        $posted_data['detail'] = true;
        $posted_data['id'] = $id;
        $bid = Bid::getBids($posted_data);
        if(!$bid){
            return $this->sendError('This Bid cannot found');
        }
        
        $request_data['update_id'] = $id;
 
        $bid = Bid::saveUpdateBid($request_data); 
   
        return $this->sendResponse($bid, 'Bid updated successfully.');
    }
   
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(Bid::find($id)){
            Bid::deleteBid($id); 
            return $this->sendResponse([], 'Bid deleted successfully.');
        }else{
            return $this->sendError('Bid does not found.');
            // return $this->sendError('Bid already deleted.');
        } 
    }
}