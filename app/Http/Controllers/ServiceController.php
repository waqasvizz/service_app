<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use Validator;
use Auth;

class ServiceController extends Controller
{
    public $ServiceObj;

    public function __construct()
    {
        $this->ServiceObj = new Service();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $ervices = Service::all();
        // $data = Service::Paginate(10);
        $posted_data = array();
        $posted_data['paginate'] = 10;
        $data = $this->ServiceObj->getServices($posted_data);
    
        return view('service.list', compact('data'));
    }



    public function create()
    {
        return view('service.add');
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
            'service_name' => 'required',
            'service_image' => 'required',
            'service_description' => 'required'
        );

        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            
                return redirect()->back()->withErrors($validator)->withInput();
            // ->withInput($request->except('password'));
        } else {
                $posted_data = $request->all();

                $base_url = public_path();
                if($request->file('service_image')) {
                    $extension = $request->service_image->getClientOriginalExtension();
                    if($extension == 'jpg' || $extension == 'jpeg' || $extension == 'png'){ 
                        
                        // $file_name = time().'_'.$request->service_image->getClientOriginalName();
                        // $filePath = $request->file('service_image')->storeAs('service_image', $file_name, 'public');
                        // $posted_data['service_image'] = 'storage/service_image/'.$file_name;

                        $response = upload_files_to_storage($request, $request->service_image, 'service_image');

                        if( isset($response['action']) && $response['action'] == true ) {
                            $arr = [];
                            $arr['file_name'] = isset($response['file_name']) ? $response['file_name'] : "";
                            $arr['file_path'] = isset($response['file_path']) ? $response['file_path'] : "";
                        }
                        
                        $posted_data['service_image'] = $arr['file_path'];

                    }else{
                        \Session::flash('error_message', 'Service image format is not correct ( jpg | jpeg | png )!');
                        return redirect()->back()->withInput();   
                    }
                }

                $this->ServiceObj->saveUpdateService($posted_data);

                \Session::flash('message', 'Service created successfully!');
                return redirect('/service');
        }
    } 
   
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function ajax_get_services(Request $request) {
        
        $posted_data = $request->all();

        if ($request->ajax()) {
            if ( isset($posted_data['method']) ) unset($posted_data['method']);
            if ( isset($posted_data['url']) ) unset($posted_data['url']);
            if ( isset($posted_data['datatype']) ) unset($posted_data['datatype']);
                
            // if (!( isset($posted_data['service_id']) && $posted_data['service_id'] != '' && $posted_data['service_id'] != 0 ))
            //     unset($posted_data['service_id']);
        }
        else {
            // without ajax data here
        }   
        
        $data['services_list'] = $this->ServiceObj->getServices($posted_data);
        
        if ($request->ajax()) {
            if ( isset($posted_data['module']) && $posted_data['module'] == 'services' )
                return view('service.ajax_services_list', compact('data'));
            // else
                // return response()->json(['data' => $data]);
        }
        else {
            return view('service.list', compact('data'));
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

        $data = $this->ServiceObj->getServices($posted_data);

        return view('service.add',compact('data'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Service $service)
    {
        $input = $request->all();
   
        $validator = Validator::make($input, [
            'service_name' => 'required',
            'service_description' => 'required'
        ]);
   
        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();   
        }
    

        $base_url = public_path();
        if($request->file('service_image')) {
            $extension = $request->service_image->getClientOriginalExtension();
            if($extension == 'jpg' || $extension == 'jpeg' || $extension == 'png'){

                if ($service->service_image && !empty($service->service_image)) {
                    $url = $base_url.'/'.$service->service_image;
                    if (file_exists($url)) {
                        unlink($url);
                    }
                }
                
                $file_name = time().'_'.$request->service_image->getClientOriginalName();
                $filePath = $request->file('service_image')->storeAs('service_image', $file_name, 'public');
                $service->service_image = 'service_image/'.$file_name;
            }else{
                \Session::flash('error_message', 'Service image format is not correct ( jpg | jpeg | png )!');
                return redirect()->back()->withInput();   
            }
        }

        $service->service_name = $input['service_name'];
        $service->service_description = $input['service_description'];
        $service->save();

        \Session::flash('message', 'Service updated successfully!');
        return redirect('/service');
    }
   
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Service $service)
    {
        $service->delete();

        \Session::flash('message', 'Service deleted successfully!');
        return redirect('/service');
    }
}