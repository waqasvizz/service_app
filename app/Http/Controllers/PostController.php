<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use RahulHaque\Filepond\Facades\Filepond;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $posted_data = array();
        $posted_data['paginate'] = 10;
            // $posted_data['web_paginate'] = true;

        $data = $this->PostObj->getPost($posted_data);

        return view('post.list', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $posted_data = array();
        $posted_data['orderBy_name'] = 'service_name';
        $posted_data['orderBy_value'] = 'asc';
        $data['all_services'] = $this->ServiceObj->getServices($posted_data);
        
        $posted_data = array();
        $posted_data['role'] = 3;
        $posted_data['orderBy_name'] = 'name';
        $posted_data['orderBy_value'] = 'asc';
        $data['all_customers'] = $this->UserObj->getUser($posted_data);

        return view('post.add', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = array(
            'task_completion_date' => 'required',
            'pay_with' => 'required',
            'description' => 'required',
            'price' => 'numeric|required',
            'title' => 'required',
            'customer' => 'required',
            'service' => 'required',
        );

        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {            
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $request_all = $request->all();
            $post_id = $this->PostObj->saveUpdatePost($request_all);

            if ($post_id) {
        
                if ( isset($request_all['images']) && count($request_all['images']) > 0 ) {
                    foreach ($request_all['images'] as $key => $value) {
                        if(!empty($value)) {
                            $filepond_id = Crypt::decrypt($value);
                            if($filepond_id != 0 ) {
                                $this->PostAssetsObj->saveUpdatePostAssets([
                                    'post_id' => $post_id,
                                    'filepond_id' => $filepond_id['id'],
                                    'asset_type' => 'image',
                                ]);
                            }
                        }
                    }
                }

                if ( isset($request_all['videos']) && count($request_all['videos']) > 0 ) {
                    foreach ($request_all['videos'] as $key => $value) {
                        if(!empty($value)) {
                            $filepond_id = Crypt::decrypt($value);
                            if($filepond_id != 0 ) {
                                $this->PostAssetsObj->saveUpdatePostAssets([
                                    'post_id' => $post_id,
                                    'filepond_id' => $filepond_id['id'],
                                    'asset_type' => 'video',
                                ]);
                            }
                        }
                    }
                }

                \Session::flash('message', 'Post is successfully created!');
                return redirect('/post');
            }
        }

        // $imagesInfo = Filepond::field($request->images);
        // $videoInfo = Filepond::field($request->videos);

        // exit("@@@@");
            // ->moveTo('avatars/' . $avatarName);

        // dd($fileInfo);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function ajax_get_posts(Request $request) {
        
        $posted_data = $request->all();

        if ($request->ajax()) {
            if ( isset($posted_data['method']) ) unset($posted_data['method']);
            if ( isset($posted_data['url']) ) unset($posted_data['url']);
                
            if (!( isset($posted_data['service_id']) && $posted_data['service_id'] != '' && $posted_data['service_id'] != 0 ))
                unset($posted_data['service_id']);
        }
        else {
            // without ajax data here
        }   
        
        $data = $this->PostObj->getPost($posted_data);

        if ($request->ajax()) {
            // if (!( isset($posted_data['module']) && $posted_data['module'] == 'bids' ))
            //     return view('bid.ajax_records', compact('data'));
            // else
                return response()->json(['data' => $data]);
        }
        else {
            return view('post.list', compact('data'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $posted_data = array();
        $posted_data['id'] = $id;
        $posted_data['detail'] = true;
        $data = $this->PostObj->getPost($posted_data);

        $data['all_services'] = $this->ServiceObj->getServices();

        $posted_data = array();
        $posted_data['role'] = 3;
        $data['all_customers'] = $this->UserObj->getUser($posted_data);

        $posted_data = array();
        $posted_data['post_id'] = $id;
        $data['post_asset'] = $this->PostAssetsObj->getPostAssets($posted_data);

        return view('post.add', compact('data'));
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
        $rules = array(
            'task_completion_date' => 'required',
            'pay_with' => 'required',
            'description' => 'required',
            'price' => 'numeric|required',
            'title' => 'required',
            'customer' => 'required',
            'service' => 'required',
        );

        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {            
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $posted_data = $request->all();
            $posted_data['update_id'] = $id;
            $post_id = $this->PostObj->saveUpdatePost($posted_data);

            if ($post_id) {        
                if ( isset($posted_data['images']) && count($posted_data['images']) > 0 ) {
                    foreach ($posted_data['images'] as $key => $value) {
                        if(!empty($value)){
                            $filepond_id = Crypt::decrypt($value);
                            if($filepond_id != 0 ) {
                                $this->PostAssetsObj->saveUpdatePostAssets([
                                    'post_id' => $post_id,
                                    'filepond_id' => $filepond_id['id'],
                                    'asset_type' => 'image',
                                ]);
                            }
                        }
                    }
                }

                if ( isset($posted_data['videos']) && count($posted_data['videos']) > 0 ) {
                    foreach ($posted_data['videos'] as $key => $value) {
                        if(!empty($value)){
                            $filepond_id = Crypt::decrypt($value);
                            if($filepond_id != 0 ) {
                                $this->PostAssetsObj->saveUpdatePostAssets([
                                    'post_id' => $post_id,
                                    'filepond_id' => $filepond_id['id'],
                                    'asset_type' => 'video',
                                ]);
                            }
                        }
                    }
                }

                \Session::flash('message', 'Post is updated successfully!');
                return redirect('/post');
            }
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
            $data = $this->PostAssetsObj->getPostAssets($posted_data);

            foreach ($data as $key => $value) {
                delete_files_from_storage($value->filepath);
                $this->FilepondObj->deleteRecord($value->filepond_id);
            }
            
            $this->PostObj->deletePost($id);

            \Session::flash('message', 'Post is deleted successfully!');
            return redirect('/post');
        }
        else 
            return false;
    }
}