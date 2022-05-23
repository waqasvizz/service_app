<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use Validator;
use App\Models\Review;
use App\Models\User;

class ReviewController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $params = $request->all();

        $posted_data =  $params;
        $posted_data['paginate'] = 10;

        if (isset($params['sender_id']))
            $posted_data['sender_id'] = $params['sender_id'];
        if (isset($params['receiver_id']))
            $posted_data['receiver_id'] = $params['receiver_id'];
        if (isset($params['per_page']))
            $posted_data['paginate'] = $params['per_page'];
        
        $reviews = Review::getReviews($posted_data);
        $message = count($reviews) > 0 ? 'Reviews retrieved successfully.' : 'Reviews not found against your query.';

        return $this->sendResponse($reviews, $message);
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
            'receiver_id' => 'required',
            'sender_id' => 'required',
            'stars' => 'required',
            'description' => 'required'
        ]);
   
        if($validator->fails()){
            return $this->sendError('Please fill all the required fields.', ["error"=>$validator->errors()->first()]);
        }

        $response_data = Review::where([
            'sender_id' => $request_data['sender_id'],
            'receiver_id' => $request_data['receiver_id']
        ])->first();
        

        if (!$response_data) {
            $review = Review::saveUpdateReview($request_data);
            $response_data = Review::where('receiver_id', $request_data['receiver_id'])->get()->ToArray();
            
            if ( count($response_data) > 0 ) {
                $total_reviews = 0; $rating = 0;
                foreach ($response_data as $key => $value) {
                    $total_reviews++;
                    $rating += $value['stars'];
                }
    
                $avg_rating = round( ( ($rating/($total_reviews*5)) * 5 ) , 2 );

                User::saveUpdateUser([
                    'update_id' => $request_data['receiver_id'],
                    'avg_rating' => $avg_rating
                ]);
            }
    
            return $this->sendResponse($review, 'Review created successfully.');
        }
        else {
            $error_message['error'] = 'The review is already exist.';
            return $this->sendError($error_message['error'], $error_message);
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
        $review = Review::find($id);
  
        if (is_null($review)) {
            $error_message['error'] = 'The review is not found.';
            return $this->sendError($error_message['error'], $error_message);
        }
   
        return $this->sendResponse($review, 'Review retrieved successfully.');
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
            'receiver_id' => 'required',
            'sender_id' => 'required',
            'stars' => 'required',
            'description' => 'required'
        ]);
   
        if($validator->fails()){
            return $this->sendError('Pleaes fill all the required fields.', ["error"=>$validator->errors()->first()]);
        }

        $posted_data = array();
        $posted_data['detail'] = true;
        $posted_data['id'] = $id;
        $review = Review::getReviews($posted_data);
        if(!$review){
            $error_message['error'] = 'The review is not found.';
            return $this->sendError($error_message['error'], $error_message);
        }
        
        $request_data['update_id'] = $id;
        $review = Review::saveUpdateReview($request_data); 
   
        return $this->sendResponse($review, 'Review updated successfully.');
    }
   
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(Review::find($id)){
            Review::deleteReview($id); 
            return $this->sendResponse([], 'Review deleted successfully.');
        }else{
            $error_message['error'] = 'The review is already deleted.';
            return $this->sendError($error_message['error'], $error_message);
        } 
    }
}