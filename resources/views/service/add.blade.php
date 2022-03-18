

@if (isset($data->id))
    @section('title', 'Update Service')
@else
    @section('title', 'Add Service')
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
                            <h4 class="card-title">{{ isset($data->id)? 'Update':'Add' }} Service Detail</h4>
                        </div>
                        <div class="card-body">
                            @if (Session::has('message'))
                                <div class="alert alert-success"><b>Success: </b>{{ Session::get('message') }}</div>
                            @endif
                            @if (Session::has('error_message'))
                                <div class="alert alert-danger"><b>Sorry: </b>{{ Session::get('error_message') }}</div>
                            @endif

                            @if (isset($data->id))
                                <form class="form" action="{{ route('service.update', $data->id) }}" method="post" enctype="multipart/form-data">
                                @method('PUT')
                                
                            @else
                                <form class="form" action="{{ route('service.store') }}" method="POST" enctype="multipart/form-data">
                                
                            @endif
                                @csrf
                                <div class="row">
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="service_name">Name</label>
                                            <input value="{{old('service_name', isset($data->service_name)? $data->service_name: '')}}" type="text" id="service_name" class="form-control @error('service_name') is-invalid @enderror" placeholder="Enter service name" name="service_name">
                                            @error('service_name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="service_image">Image</label>
                                            <input type="file" id="service_image" class="form-control @error('service_image') is-invalid @enderror" name="service_image">
                                            @error('service_image')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="service_description">Service Description</label>
                                            <textarea id="service_description" class="form-control @error('service_description') is-invalid @enderror" placeholder="Enter service description" name="service_description">{{old('service_description', isset($data->service_description)? $data->service_description: '')}}</textarea>
                                            @error('service_description')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    @if (isset($data->id))
                                        <div class="col-md-6 col-12">
                                            <div class="form-group text-right">
                                                <img src="{{ is_image_exist($data->service_image) }}" class="img-thumbnail" alt="{{ $data->service_name }}" width="304" height="236">
                                            </div>
                                        </div>
                                    @endif
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
