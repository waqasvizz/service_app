@if (isset($data->id))
    @section('title', 'Update Mosque')
@else
    @section('title', 'Add Mosque')
@endif
@extends('layouts.admin')

@section('content')

<div class="content-wrapper">
    <div class="content-header row">
        
    </div>
    <div class="content-body">
        <section id="multiple-column-form">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{ isset($data->id)? 'Update':'Add' }} Mosque Detail</h4>
                        </div>
                        <div class="card-body">
                            @if (Session::has('message'))
                                <div class="alert alert-success"><b>Success: </b>{{ Session::get('message') }}</div>
                            @endif
                            @if (Session::has('error_message'))
                                <div class="alert alert-danger"><b>Sorry: </b>{{ Session::get('error_message') }}</div>
                            @endif

                            @if (isset($data->id))
                                <form class="form" action="{{ route('mosque.update', $data->id) }}" method="post">
                                @method('PUT')
                                
                            @else
                                <form class="form" action="{{ route('mosque.store') }}" method="POST">
                                
                            @endif
                                @csrf
                                <div class="row">
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="name">Name</label>
                                            <input value="{{old('name', isset($data->name)? $data->name: '')}}" type="text" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="Name" name="name">
                                            @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="website_link">Website Link</label>
                                            <input value="{{old('website_link', isset($data->website_link)? $data->website_link: '')}}" type="text" id="website_link" class="form-control @error('website_link') is-invalid @enderror" name="website_link" placeholder="Website Link">
                                            @error('website_link')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="zip_code">Zip Code</label>
                                            <input value="{{old('zip_code', isset($data->zip_code)? $data->zip_code: '')}}" type="number" id="zip_code" class="form-control @error('zip_code') is-invalid @enderror" placeholder="Zip Code" name="zip_code">
                                            @error('zip_code')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="phone_number">Phone Number</label>
                                            <input value="{{old('phone_number', isset($data->phone_number)? $data->phone_number: '')}}" type="text" id="phone_number" class="form-control @error('phone_number') is-invalid @enderror" name="phone_number" placeholder="Phone Number">
                                            @error('phone_number')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="address">Address</label>
                                            <textarea type="text" id="address" class="form-control @error('address') is-invalid @enderror" placeholder="Address" name="address">{{old('address', isset($data->address)? $data->address: '')}}</textarea>
                                            @error('address')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary mr-1 waves-effect waves-float waves-light">{{ isset($data->id)? 'Update':'Add' }}</button>
                                        <button type="reset" class="btn btn-outline-secondary waves-effect">Reset</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
