<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PostAssetsController extends Controller
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
    public function destroy($id)
    {
        if ($id != '' || $id != 0) {

            $posted_data = array();
            $posted_data['id'] = $id;
            $data = $this->PostAssetsObj->getPostAssets($posted_data);

            $response = delete_files_from_storage($data[0]->filepath);
            if ($response['action']) {
                $this->PostAssetsObj->deletePostAssets($id);
                $this->FilepondObj->deleteRecord($data[0]->filepond_id);
                return true;
            }
            else {
                echo "Line no @"."<br>";
                echo "<pre>";
                print_r($response);
                echo "</pre>";
                exit("@@@@");
            }

            // else
            //     return false;
        }
        else 
            return false;
    }
}
