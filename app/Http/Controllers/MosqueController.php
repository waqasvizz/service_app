<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mosque;
use Validator;
use Auth;

class MosqueController extends Controller
{
    public $MosqueObj;

    public function __construct()
    {
        $this->MosqueObj = new Mosque();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $mosques = Mosque::all();
        // $data = Mosque::Paginate(10);
        $posted_data = array();
        $posted_data['paginate'] = 10;
        $data = $this->MosqueObj->getMosque($posted_data);
    
        return view('mosque.list', compact('data'));
    }



    public function create()
    {
        return view('mosque.add');
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
            'name' => 'required',
            'address' => 'required',
            'zip_code' => 'required',
            'phone_number' => 'required',
            'website_link' => 'required'
        );

        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            
                return redirect()->back()->withErrors($validator)->withInput();
            // ->withInput($request->except('password'));
        } else {
                $posted_data = $request->all();

                $this->MosqueObj->saveUpdateMosque($posted_data);

                \Session::flash('message', 'Mosque created successfully!');
                return redirect('/mosque');
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
        $mosque = Mosque::find($id);
  
        if (is_null($mosque)) {
            return $this->sendError('Mosque not found.');
        }
   
        return $this->sendResponse(new MosqueResource($mosque), 'Mosque retrieved successfully.');
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

        $data = $this->MosqueObj->getMosque($posted_data);

        return view('mosque.add',compact('data'));
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
            return redirect()->back()->withErrors($validator)->withInput();   
        }
   
        $mosque->name = $input['name'];
        $mosque->address = $input['address'];
        $mosque->zip_code = $input['zip_code'];
        $mosque->phone_number = $input['phone_number'];
        $mosque->website_link = $input['website_link'];
        $mosque->save();

        \Session::flash('message', 'Mosque updated successfully!');
        return redirect('/mosque');
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

        \Session::flash('message', 'Mosque deleted successfully!');
        return redirect('/mosque');
    }
}