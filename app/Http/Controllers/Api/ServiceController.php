<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use Validator;
use App\Models\Service;
// use App\Http\Resources\Service as ServiceResource;

class ServiceController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $params = $request->all();

        $posted_data =  array();
        $posted_data['paginate'] = 10;

        if (isset($params['service_id']))
            $posted_data['id'] = $params['service_id'];
        if (isset($params['service_name']))
            $posted_data['service_name'] = $params['service_name'];
        if (isset($params['per_page']))
            $posted_data['paginate'] = $params['per_page'];
        
        $services = Service::getServices($posted_data);
        $message = count($services) > 0 ? 'Services retrieved successfully.' : 'Services not found against your query.';

        return $this->sendResponse($services, $message);
        
        // $posted_data['count'] = true;
        // $count = Service::getServices($posted_data);
    
        // return $this->sendResponse($services, 'Services retrieved successfully.', $count);
        // return $this->sendResponse(ServiceResource::collection($services), 'Services retrieved successfully.', $count);
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
            'service_name' => 'required',
            'service_image' => 'required',
            'service_description' => 'required'
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }


        $base_url = public_path();
        if($request->file('service_image')) {
            $extension = $request->service_image->getClientOriginalExtension();
            if($extension == 'jpg' || $extension == 'jpeg' || $extension == 'png'){

                $file_name = time().'_'.$request->service_image->getClientOriginalName();
                $filePath = $request->file('service_image')->storeAs('service_image', $file_name, 'public');
                $request_data['service_image'] = 'storage/service_image/'.$file_name;
            
            }else{
                return $this->sendError('Service image format is not correct ( jpg | jpeg | png )!');
            }
        }else{
            return $this->sendError('Please upload service image');
        }
         
        $service = Service::saveUpdateService($request_data);   
        // $service = Service::create($request_data);
   
        return $this->sendResponse($service, 'Service created successfully.');
        // return $this->sendResponse(new ServiceResource($service), 'Service created successfully.');
    } 
   
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $service = Service::find($id);
  
        if (is_null($service)) {
            return $this->sendError('Service not found.');
        }
   
        return $this->sendResponse($service, 'Service retrieved successfully.');
        // return $this->sendResponse(new ServiceResource($service), 'Service retrieved successfully.');
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function update(Request $request, Service $service)
    public function update(Request $request, $id)
    {
        $request_data = $request->all();
        $validator = Validator::make($request_data, [
            'service_name' => 'required',
            'service_description' => 'required'
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $posted_data = array();
        $posted_data['detail'] = true;
        $posted_data['id'] = $id;
        $service = Service::getServices($posted_data);
        if(!$service){
            return $this->sendError('This Service cannot found');
        }
   
        // $service->name = $request_data['name'];
        // $service->save();

        $base_url = public_path();
        if($request->file('service_image')) {
            $extension = $request->service_image->getClientOriginalExtension();
            if($extension == 'jpg' || $extension == 'jpeg' || $extension == 'png'){

                if ($service && !empty($service->service_image)) {
                    $url = $service->service_image;

                    $url = $_SERVER['DOCUMENT_ROOT'].'/'.$service->service_image;
                    if (file_exists($url)) {
                        unlink($url);
                    }
                }   
                
                $file_name = time().'_'.$request->service_image->getClientOriginalName();
                $filePath = $request->file('service_image')->storeAs('service_image', $file_name, 'public');
                $request_data['service_image'] = 'storage/service_image/'.$file_name;
            }else{
                return $this->sendError('Service image format is not correct ( jpg | jpeg | png )!');
            }
        }
        $request_data['update_id'] = $id;
        $service = Service::saveUpdateService($request_data); 
   
        return $this->sendResponse($service, 'Service updated successfully.');
    }

    public function filter(Request $request) {
        echo "Line no deedeee@"."<br>";
        echo "<pre>";
        print_r('Nowww');
        echo "</pre>";
        exit("@@@@");
    }
   
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function destroy(Service $service)
    public function destroy($id)
    {
        if(Service::find($id)){
            Service::deleteService($id); 
            return $this->sendResponse([], 'Service deleted successfully.');
        }else{
            return $this->sendError('Service already deleted.');
        } 
    }
}