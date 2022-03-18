<?php

namespace App\Http\Controllers\Api; 

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use Validator;
use App\Models\Post;
use App\Models\PostAssets;
use App\Models\Filepond as Filepond_Model;
use RahulHaque\Filepond\Models\Filepond;

class PostController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function index(Request $request)
    {
        $params = $request->all();
        // $assets_array = array();

        $posted_data =  $params;
        $posted_data['paginate'] = 10;

        if (isset($params['post_id']))
            $posted_data['id'] = $params['post_id'];
        if (isset($params['post_title']))
            $posted_data['title'] = $params['post_title'];
        if (isset($params['per_page']))
            $posted_data['paginate'] = $params['per_page'];
        
        $posts = Post::getPost($posted_data)->ToArray();
        // $posts = Post::getPost($posted_data);

        if (count($posts) > 0) {
            foreach ($posts['data'] as $key => $item) {
                // foreach ($posts as $key => $item) {
                $posts['data'][$key]['images'] = PostAssets::getPostAssets(['post_id' => $item['id'], 'asset_type' => 'image'])->ToArray();
                $posts['data'][$key]['videos'] = PostAssets::getPostAssets(['post_id' => $item['id'], 'asset_type' => 'video'])->ToArray();

                // echo '<pre>';
                // print_r($item->PostAssets);
                // exit;
                // $post = Post::find($item->id);
                // $posts[$key]['images'] = $post->PostAssets;
                
                // $posts[$key]['images'] = PostAssets::whereHas('post', function($q) {
                //     $q->where('asset_type', 'image');
                // })->where('post_id', $item->id)->get();
                
                // $posts[$key]['video'] = PostAssets::whereHas('post', function($q) {
                //     $q->where('asset_type', 'video');
                // })->where('post_id', $item->id)->get();

                // echo '<pre>';
                // print_r($posts[$key]['service_id']);
                // exit;
        
                // $posts['data'][$key]['images'] = Post::PostAssets();

                // $assets_array = [];
                // foreach ($posts_assets as $posts_key => $posts_item) {
                //     if (isset($posts_item['asset_type'])) {
                //         $assets_array['asset_id'] = $posts_item['id'];
                //         $assets_array['asset_type'] = $posts_item['asset_type'];
                //         $assets_array['filename'] = $posts_item['filename'];
                //         $assets_array['filepath'] = $posts_item['filepath'];
                //     }

                //     if ($posts_item['asset_type'] == 'image')
                //         $posts['data'][$key]['images'][] = $assets_array;
                //     else if ($posts_item['asset_type'] == 'video')
                //         $posts['data'][$key]['videos'][] = $assets_array;
                // }
            }
        }

        $message = count($posts) > 0 ? 'Posts retrieved successfully.' : 'Posts not found against your query.';

        return $this->sendResponse($posts, $message);
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
        $images_arr = array();
        $videos_arr = array();

        $posted_data = $request->all(); 
        $validator = Validator::make($posted_data, [
            'service_id' => 'required',
            'customer_id' => 'required',
            'price' => 'required',
            'title' => 'required',
            'description' => 'required',
            'pay_with' => 'required',
            // 'post_images' => 'required',
            // 'post_videos' => 'required',
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $post_id = $this->PostObj->saveUpdatePost($posted_data);
        
        $message = ($post_id) > 0 ? 'Post is successfully added.' : 'Something went wrong during post adding.';
        if ($post_id) {

            if (isset($request->post_images)) {
                $allowedfileExtension = ['jpeg','jpg','png'];
                foreach($request->post_images as $mediaFiles) {

                    $extension = $mediaFiles->getClientOriginalExtension();

                    $check = in_array($extension, $allowedfileExtension);
                    if($check) {
                        $response = upload_files_to_storage($request, $mediaFiles, 'post_assets');

                        if( isset($response['action']) && $response['action'] == true ) {
                            $arr = [];
                            $arr['file_name'] = isset($response['file_name']) ? $response['file_name'] : "";
                            $arr['file_path'] = isset($response['file_path']) ? $response['file_path'] : "";
                        }

                        $filepond = Filepond::create([
                            'filepath' => $arr['file_path'],
                            'filename' => $arr['file_name'],
                            'extension' => $mediaFiles->getClientOriginalExtension(),
                            'mimetypes' => $mediaFiles->getClientMimeType(),
                            'disk' => 'public',
                            'created_by' => auth()->id(),
                            'expires_at' => now()->addMinutes(config('filepond.expiration', 30))
                        ]);
                        $arr['asset_id'] = $filepond->id;

                        $images_arr[] = $arr;
                    }
                    else {
                        return response()->json(['invalid_file_format'], 422);
                    }
                }
            }

            if (isset($request->post_videos)) {
                $allowedfileExtension = ['mp4','mov'];
                foreach($request->post_videos as $mediaFiles) {

                    $extension = $mediaFiles->getClientOriginalExtension();

                    $check = in_array($extension, $allowedfileExtension);
                    if($check) {
                        $response = upload_files_to_storage($request, $mediaFiles, 'post_assets');

                        if( isset($response['action']) && $response['action'] == true ) {
                            $arr = [];
                            $arr['file_name'] = isset($response['file_name']) ? $response['file_name'] : "";
                            $arr['file_path'] = isset($response['file_path']) ? $response['file_path'] : "";
                        }

                        $filepond = Filepond::create([
                            'filepath' => $arr['file_path'],
                            'filename' => $arr['file_name'],
                            'extension' => $mediaFiles->getClientOriginalExtension(),
                            'mimetypes' => $mediaFiles->getClientMimeType(),
                            'disk' => 'public',
                            'created_by' => auth()->id(),
                            'expires_at' => now()->addMinutes(config('filepond.expiration', 30))
                        ]);
                        $arr['asset_id'] = $filepond->id;

                        $videos_arr[] = $arr;
                    }
                    else {
                        return response()->json(['invalid_file_format'], 422);
                    }
                }
            }
            
            foreach ($images_arr as $key => $item) {
                PostAssets::saveUpdatePostAssets([
                    'post_id' => $post_id,
                    'filepond_id' => $item['asset_id'],
                    'asset_type' => 'image',
                ]);
            }

            foreach ($videos_arr as $key => $item) {
                PostAssets::saveUpdatePostAssets([
                    'post_id' => $post_id,
                    'filepond_id' => $item['asset_id'],
                    'asset_type' => 'video',
                ]);
            }
            return $this->sendResponse([], $message);
        }
        else
            return $this->sendError($message);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id = 0)
    {
        if ($id != 0) {
            $images_arr = array();
            $videos_arr = array();
    
            $posted_data = $request->all(); 
            $validator = Validator::make($posted_data, [
            //     // 'service_id'    => 'required',
            //     // 'customer_id'   => 'required',
            //     // 'price'         => 'required',
            //     // 'title'         => 'required',
            //     // 'description'   => 'required',
            //     // 'pay_with'      => 'required',
            //     // 'post_images' => 'required',
            //     // 'post_videos' => 'required',
            ]);
       
            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors());
            }

            $post_data = array();
            $post_data['detail'] = true;
            $post_data['id'] = $id;
            $post_record = Post::getPost($post_data);
            if(!$post_record){
                return $this->sendError('This Post cannot found');
            }
            
            if (isset($request->post_images)) {
                $allowedfileExtension = ['jpeg','jpg','png'];

                foreach($request->post_images as $mediaFiles) {

                    $extension = $mediaFiles->getClientOriginalExtension();
    
                    $check = in_array($extension, $allowedfileExtension);
                    if($check) {
                        $response = upload_files_to_storage($request, $mediaFiles, 'post_assets');
    
                        if( isset($response['action']) && $response['action'] == true ) {
                            $arr = [];
                            $arr['file_name'] = isset($response['file_name']) ? $response['file_name'] : "";
                            $arr['file_path'] = isset($response['file_path']) ? $response['file_path'] : "";
                        }
    
                        $filepond = Filepond::create([
                            'filepath' => $arr['file_path'],
                            'filename' => $arr['file_name'],
                            'extension' => $mediaFiles->getClientOriginalExtension(),
                            'mimetypes' => $mediaFiles->getClientMimeType(),
                            'disk' => 'public',
                            'created_by' => auth()->id(),
                            'expires_at' => now()->addMinutes(config('filepond.expiration', 30))
                        ]);
                        $arr['asset_id'] = $filepond->id;
    
                        $images_arr[] = $arr;
                    }
                    else {
                        return response()->json(['invalid_file_format'], 422);
                    }
                }
            }

            if (isset($request->post_videos)) {
                $allowedfileExtension = ['mp4','mov'];
                
                foreach($request->post_videos as $mediaFiles) {

                    $extension = $mediaFiles->getClientOriginalExtension();

                    $check = in_array($extension, $allowedfileExtension);
                    if($check) {
                        $response = upload_files_to_storage($request, $mediaFiles, 'post_assets');

                        if( isset($response['action']) && $response['action'] == true ) {
                            $arr = [];
                            $arr['file_name'] = isset($response['file_name']) ? $response['file_name'] : "";
                            $arr['file_path'] = isset($response['file_path']) ? $response['file_path'] : "";
                        }

                        $filepond = Filepond::create([
                            'filepath' => $arr['file_path'],
                            'filename' => $arr['file_name'],
                            'extension' => $mediaFiles->getClientOriginalExtension(),
                            'mimetypes' => $mediaFiles->getClientMimeType(),
                            'disk' => 'public',
                            'created_by' => auth()->id(),
                            'expires_at' => now()->addMinutes(config('filepond.expiration', 30))
                        ]);
                        $arr['asset_id'] = $filepond->id;

                        $videos_arr[] = $arr;
                    }
                    else {
                        return response()->json(['invalid_file_format'], 422);
                    }
                }
            }

            foreach ($images_arr as $key => $item) {
                PostAssets::saveUpdatePostAssets([
                    'post_id' => $post_record->id,
                    'filepond_id' => $item['asset_id'],
                    'asset_type' => 'image',
                ]);
            }

            foreach ($videos_arr as $key => $item) {
                PostAssets::saveUpdatePostAssets([
                    'post_id' => $post_record->id,
                    'filepond_id' => $item['asset_id'],
                    'asset_type' => 'video',
                ]);
            }

            $posted_data['update_id'] = $post_record->id;
            $post = Post::saveUpdatePost($posted_data);

            $message = ($post) ? 'Post is successfully updated.' : 'Something went wrong during post update.';
            return $this->sendResponse([], $message);
        }
        else {
            return $this->sendError('You have entered a wrong post id.');
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
        if ($id != '' || $id != 0) {

            $posted_data = array();
            $posted_data['post_id'] = $id;
            $data = PostAssets::getPostAssets($posted_data);

            foreach ($data as $key => $value) {
                delete_files_from_storage($value->filepath);
                Filepond_Model::deleteRecord($value->filepond_id);
            }
            
            Post::deletePost($id);
            return $this->sendResponse([], 'Post is deleted successfully.');
        }
        else 
            return $this->sendError('Post is not found OR already deleted.');
    }
}