<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PostAssets;
use App\Models\Filepond as Filepond_Model;
use App\Http\Controllers\Api\BaseController as BaseController;

class PostAssetsController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        //
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id = 0)
    {
        if ($id != '' || $id != 0) {

            $posted_data = array();
            $posted_data['id'] = $id;
            $data = PostAssets::getPostAssets($posted_data);

            foreach ($data as $key => $value) {
                delete_files_from_storage($value['filepond']['filepath']);
                Filepond_Model::deleteRecord($value['filepond']['id']);
                return $this->sendResponse([], 'Post asset is deleted successfully.');
            }
            $error_message['error'] = 'The post asset not found OR already deleted.';
            return $this->sendError($error_message['error'], $error_message);
        }
        else 
            $error_message['error'] = 'Something went wrong during query data.';
            return $this->sendError($error_message['error'], $error_message);
        }
}
