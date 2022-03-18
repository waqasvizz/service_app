

@if (isset($data->id))
    @section('title', 'Update User')
@else
    @section('title', 'Add User')
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
                            <h4 class="card-title">{{ isset($data->id)? 'Update':'Add' }} User Detail</h4>
                        </div>
                        <div class="card-body">
                            @if (Session::has('message'))
                                <div class="alert alert-success"><b>Success: </b>{{ Session::get('message') }}</div>
                            @endif
                            @if (Session::has('error_message'))
                                <div class="alert alert-danger"><b>Sorry: </b>{{ Session::get('error_message') }}</div>
                            @endif

                            @if (isset($data->id))
                                <form class="form" action="{{ route('user.update', $data->id) }}" method="post" enctype="multipart/form-data">
                                @method('PUT')
                                
                            @else
                                <form class="form" action="{{ route('user.store') }}" method="POST" enctype="multipart/form-data">
                                
                            @endif
                                @csrf
                                <div class="row">
                                    <div class="col-md-12 col-12">
                                        <div class="row">
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label for="profile_image">Profile Image 
                                                        <div class="display_images preview_profile_image">
                                                            @if (isset($data->profile_image) && !empty($data->profile_image))
                                                            <a data-fancybox="demo" data-src="{{ is_image_exist($data->profile_image) }}"><img title="{{ $data->name }}" src="{{ is_image_exist($data->profile_image) }}" height="100"></a>
                                                            @endif
                                                        </div></label>
                                                    <input type="file" id="profile_image" data-img-val="preview_profile_image" class="form-control @error('profile_image') is-invalid @enderror" placeholder="Profile Image" name="profile_image">
                                                    {{-- <input type="file" id="profile_image" class="form-control @error('profile_image') is-invalid @enderror" placeholder="Profile Image" name="profile_image[]" multiple>--}}
                                                    {{-- <div class="preview"></div>  --}}
                                                    @error('profile_image')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="user_name">User Name</label>
                                            <input value="{{old('user_name', isset($data->name)? $data->name: '')}}" type="text" id="user_name" class="form-control @error('user_name') is-invalid @enderror" placeholder="User Name" name="user_name">
                                            @error('user_name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input value="{{old('email', isset($data->email)? $data->email: '')}}" type="email" id="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email" name="email">
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="password">Password</label>
                                            <input type="password" id="password" value="{{old('password')}}" class="form-control @error('password') is-invalid @enderror" placeholder="Password" name="password">
                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="phone_number">Confirm password</label>
                                            <input type="password" id="confirm_password" value="{{old('confirm_password')}}" class="form-control @error('confirm_password') is-invalid @enderror" placeholder="Confirm password" name="confirm_password">
                                            @error('confirm_password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="user_role">User Role</label>
                                            <select class="form-control @error('user_role') is-invalid @enderror" name="user_role" id="user_role">
                                                <option value="">Choose an option</option>
                                                @if (isset($data['roles']) && count($data['roles'])>0)
                                                    @foreach ($data['roles'] as $item)
                                                        <option {{ old('user_role')==$item['id'] || (isset($data->role) && $data->role==$item['id'])? 'selected': '' }} value="{{ $item['id'] }}">{{ $item['name'] }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            @error('user_role')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12 for_provider_role for_customer_role" style="display: {{ (old('user_role') == 2 || old('user_role') == 3) || (isset($data->role) && ($data->role == 2 || $data->role == 3))? 'block':'none' }}">
                                        <div class="form-group">
                                            <label for="phone_number">Phone Number</label>
                                            <input onkeyup="addHyphen(this)" class="form-control @error('phone_number') is-invalid @enderror" id="phone_number" name="phone_number" value="{{old('phone_number', isset($data->phone_number)? $data->phone_number: '')}}" type="text">
                                            @error('phone_number')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12 for_provider_role for_customer_role" style="display: {{ (old('user_role') == 2 || old('user_role') == 3) || (isset($data->role) && ($data->role == 2 || $data->role == 3))? 'block':'none' }}">
                                        <div class="form-group">
                                            <label for="address">Address</label>
                                            <input type="text" id="address" class="form-control @error('address') is-invalid @enderror" placeholder="Address" name="address" value="{{ old('address', isset($data->address)? $data->address: '')}}">
                                            <input type="hidden" id="latitude" class="form-control" name="latitude" value="{{ old('latitude', isset($data->latitude)? $data->latitude: '')}}">
                                            <input type="hidden" id="longitude" class="form-control" name="longitude" value="{{ old('longitude', isset($data->longitude)? $data->longitude: '')}}">
                                            @error('address')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12 for_provider_role" style="display: {{ (old('user_role') == 2) || (isset($data->role) && $data->role == 2)? 'block':'none' }}">
                                        <div class="form-group">
                                            <label for="service">Service</label>
                                            <select class="form-control @error('service') is-invalid @enderror" id="service" name="service">
                                                <option value="">Choose an option</option>
                                                @if (isset($data['services']) && count($data['services'])>0)
                                                    @foreach ($data['services'] as $item)
                                                        <option {{ old('service')==$item['id'] || (isset($data['assign_service'][0]['service_id']) && $data['assign_service'][0]['service_id'] == $item['id'])? 'selected': '' }} value="{{ $item['id'] }}">{{ $item['service_name'] }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            @error('service')
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
