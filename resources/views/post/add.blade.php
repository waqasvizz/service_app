@if (isset($data->id))
@section('title', 'Update Post')
@else
@section('title', 'Add Post')
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
                            <h4 class="card-title">{{ isset($data->id)? 'Update':'Add' }} Post Detail</h4>
                        </div>
                        <div class="card-body">
                            @if (Session::has('message'))
                            <div class="alert alert-success"><b>Success: </b>{{ Session::get('message') }}</div>
                            @endif
                            @if (Session::has('error_message'))
                            <div class="alert alert-danger"><b>Sorry: </b>{{ Session::get('error_message') }}</div>
                            @endif

                            @if (isset($data->id))
                            <form class="form" action="{{ route('post.update', $data->id) }}" method="post" enctype="multipart/form-data">
                            @method('PUT')

                            @php
                                $html_images = '';
                                $html_videos = '';
                            @endphp

                            @foreach ($data['post_asset'] as $key => $value )
                                                
                                @php
                                if ($value->asset_type == 'image'){
                                    $html_images .= '
                                    <li id="post_asset_'.$value->id.'" class="filepond--item" style="position: relative;" id="filepond--item-npa2jis0z" style="transform: translate3d(0px, 0px, 0px) scale3d(1, 1, 1); opacity: 1; height: 39px;" data-filepond-item-state="processing-complete">
                                        <fieldset class="filepond--file-wrapper">
                                            <legend> '.$value->filename.' </legend>
                                            <div class="filepond--file">
                                                <button class="filepond--file-action-button filepond--action-abort-item-load" type="button" data-align="right" disabled="disabled" style="transform: translate3d(0px, 0px, 0px) scale3d(1, 1, 1); opacity: 0; visibility: hidden; pointer-events: none;"><span>Abort</span></button>
                                                <button class="filepond--file-action-button filepond--action-retry-item-load" type="button" data-align="right" disabled="disabled" style="transform: translate3d(0px, 0px, 0px) scale3d(1, 1, 1); opacity: 0; visibility: hidden; pointer-events: none;">
                                                    <svg width="26" height="26" viewBox="0 0 26 26" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M10.81 9.185l-.038.02A4.997 4.997 0 0 0 8 13.683a5 5 0 0 0 5 5 5 5 0 0 0 5-5 1 1 0 0 1 2 0A7 7 0 1 1 9.722 7.496l-.842-.21a.999.999 0 1 1 .484-1.94l3.23.806c.535.133.86.675.73 1.21l-.804 3.233a.997.997 0 0 1-1.21.73.997.997 0 0 1-.73-1.21l.23-.928v-.002z" fill="currentColor" fill-rule="nonzero"></path>
                                                    </svg>
                                                    <span>Retry</span>
                                                </button>
                                                <button class="filepond--file-action-button filepond--action-remove-item" type="button" data-align="left" disabled="disabled" style="transform: translate3d(0px, 0px, 0px) scale3d(1, 1, 1); opacity: 0; visibility: hidden; pointer-events: none;">
                                                    <svg width="26" height="26" viewBox="0 0 26 26" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M11.586 13l-2.293 2.293a1 1 0 0 0 1.414 1.414L13 14.414l2.293 2.293a1 1 0 0 0 1.414-1.414L14.414 13l2.293-2.293a1 1 0 0 0-1.414-1.414L13 11.586l-2.293-2.293a1 1 0 0 0-1.414 1.414L11.586 13z" fill="currentColor" fill-rule="nonzero"></path>
                                                    </svg>
                                                    <span>Remove</span>
                                                </button>
                                                <button class="filepond--file-action-button filepond--action-process-item" type="button" data-align="right" disabled="disabled" style="transform: translate3d(0px, 0px, 0px) scale3d(1, 1, 1); opacity: 0; visibility: hidden; pointer-events: none;">
                                                    <svg width="26" height="26" viewBox="0 0 26 26" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M14 10.414v3.585a1 1 0 0 1-2 0v-3.585l-1.293 1.293a1 1 0 0 1-1.414-1.415l3-3a1 1 0 0 1 1.414 0l3 3a1 1 0 0 1-1.414 1.415L14 10.414zM9 18a1 1 0 0 1 0-2h8a1 1 0 0 1 0 2H9z" fill="currentColor" fill-rule="evenodd"></path>
                                                    </svg>
                                                    <span>Upload</span>
                                                </button>
                                                <button class="filepond--file-action-button filepond--action-abort-item-processing" type="button" data-align="right" style="transform: translate3d(0px, 0px, 0px) scale3d(1, 1, 1); opacity: 0; visibility: hidden; pointer-events: none;" disabled="disabled"><span>Cancel</span></button>
                                                <button class="filepond--file-action-button filepond--action-retry-item-processing" type="button" data-align="right" disabled="disabled" style="transform: translate3d(0px, 0px, 0px) scale3d(1, 1, 1); opacity: 0; visibility: hidden; pointer-events: none;">
                                                    <svg width="26" height="26" viewBox="0 0 26 26" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M10.81 9.185l-.038.02A4.997 4.997 0 0 0 8 13.683a5 5 0 0 0 5 5 5 5 0 0 0 5-5 1 1 0 0 1 2 0A7 7 0 1 1 9.722 7.496l-.842-.21a.999.999 0 1 1 .484-1.94l3.23.806c.535.133.86.675.73 1.21l-.804 3.233a.997.997 0 0 1-1.21.73.997.997 0 0 1-.73-1.21l.23-.928v-.002z" fill="currentColor" fill-rule="nonzero"></path>
                                                    </svg>
                                                    <span>Retry</span>
                                                </button>
                                                <button id="delButton" data-mode="ajax" data-id="'.$value->id.'" data-attr="'.url('/filepond'.'/'.$value->id).'" class="filepond--file-action-button filepond--action-revert-item-processing" type="button" data-align="right" style="transform: translate3d(0px, 0px, 0px) scale3d(1, 1, 1); opacity: 1;">
                                                    <svg width="26" height="26" viewBox="0 0 26 26" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M11.586 13l-2.293 2.293a1 1 0 0 0 1.414 1.414L13 14.414l2.293 2.293a1 1 0 0 0 1.414-1.414L14.414 13l2.293-2.293a1 1 0 0 0-1.414-1.414L13 11.586l-2.293-2.293a1 1 0 0 0-1.414 1.414L11.586 13z" fill="currentColor" fill-rule="nonzero"></path>
                                                    </svg>
                                                    <span>Remove</span>
                                                </button>
                                                <div class="filepond--processing-complete-indicator" data-align="right" style="transform: scale3d(0.75, 0.75, 1); opacity: 0; visibility: hidden; pointer-events: none;">
                                                    <svg width="26" height="26" viewBox="0 0 26 26" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M18.293 9.293a1 1 0 0 1 1.414 1.414l-7.002 7a1 1 0 0 1-1.414 0l-3.998-4a1 1 0 1 1 1.414-1.414L12 15.586l6.294-6.293z" fill="currentColor" fill-rule="nonzero"></path>
                                                    </svg>
                                                </div>
                                                <div class="filepond--file-info" style="transform: translate3d(0px, 0px, 0px);"><span class="filepond--file-info-main" aria-hidden="true">'.$value->filename.' </span></div>
                                                <div class="filepond--file-status" style="transform: translate3d(0px, 0px, 0px); opacity: 1;"><span class="filepond--file-status-main">Want to Remove?</span><span class="filepond--file-status-sub">tap to remove</span></div>
                                                <div class="filepond--progress-indicator filepond--load-indicator" style="opacity: 0; visibility: hidden; pointer-events: none;">
                                                    <svg>
                                                        <path stroke-width="2" stroke-linecap="round"></path>
                                                    </svg>
                                                </div>
                                                <div class="filepond--progress-indicator filepond--process-indicator" style="opacity: 0; visibility: hidden; pointer-events: none;" data-align="right">
                                                    <svg>
                                                        <path stroke-width="2" stroke-linecap="round" d="M 8.495915929819052 2.0000012830485305 A 6.5 6.5 0 1 0 8.5 2" stroke-opacity="1"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                        </fieldset>
                                        <div class="filepond--panel filepond--item-panel" data-scalable="true">
                                            <div class="filepond--panel-top filepond--item-panel"></div>
                                            <div class="filepond--panel-center filepond--item-panel" style="transform: translate3d(0px, 7px, 0px) scale3d(1, 0.25, 1);"></div>
                                            <div class="filepond--panel-bottom filepond--item-panel" style="transform: translate3d(0px, 32px, 0px);"></div>
                                        </div>
                                    </li>';
                                }
                                else{
                                    $html_videos .= '
                                    <li id="post_asset_'.$value->id.'" class="filepond--item" style="position: relative;" id="filepond--item-npa2jis0z" style="transform: translate3d(0px, 0px, 0px) scale3d(1, 1, 1); opacity: 1; height: 39px;" data-filepond-item-state="processing-complete">
                                        <fieldset class="filepond--file-wrapper">
                                            <legend> '.$value->filename.' </legend>
                                            <div class="filepond--file">
                                                <button class="filepond--file-action-button filepond--action-abort-item-load" type="button" data-align="right" disabled="disabled" style="transform: translate3d(0px, 0px, 0px) scale3d(1, 1, 1); opacity: 0; visibility: hidden; pointer-events: none;"><span>Abort</span></button>
                                                <button class="filepond--file-action-button filepond--action-retry-item-load" type="button" data-align="right" disabled="disabled" style="transform: translate3d(0px, 0px, 0px) scale3d(1, 1, 1); opacity: 0; visibility: hidden; pointer-events: none;">
                                                    <svg width="26" height="26" viewBox="0 0 26 26" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M10.81 9.185l-.038.02A4.997 4.997 0 0 0 8 13.683a5 5 0 0 0 5 5 5 5 0 0 0 5-5 1 1 0 0 1 2 0A7 7 0 1 1 9.722 7.496l-.842-.21a.999.999 0 1 1 .484-1.94l3.23.806c.535.133.86.675.73 1.21l-.804 3.233a.997.997 0 0 1-1.21.73.997.997 0 0 1-.73-1.21l.23-.928v-.002z" fill="currentColor" fill-rule="nonzero"></path>
                                                    </svg>
                                                    <span>Retry</span>
                                                </button>
                                                <button class="filepond--file-action-button filepond--action-remove-item" type="button" data-align="left" disabled="disabled" style="transform: translate3d(0px, 0px, 0px) scale3d(1, 1, 1); opacity: 0; visibility: hidden; pointer-events: none;">
                                                    <svg width="26" height="26" viewBox="0 0 26 26" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M11.586 13l-2.293 2.293a1 1 0 0 0 1.414 1.414L13 14.414l2.293 2.293a1 1 0 0 0 1.414-1.414L14.414 13l2.293-2.293a1 1 0 0 0-1.414-1.414L13 11.586l-2.293-2.293a1 1 0 0 0-1.414 1.414L11.586 13z" fill="currentColor" fill-rule="nonzero"></path>
                                                    </svg>
                                                    <span>Remove</span>
                                                </button>
                                                <button class="filepond--file-action-button filepond--action-process-item" type="button" data-align="right" disabled="disabled" style="transform: translate3d(0px, 0px, 0px) scale3d(1, 1, 1); opacity: 0; visibility: hidden; pointer-events: none;">
                                                    <svg width="26" height="26" viewBox="0 0 26 26" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M14 10.414v3.585a1 1 0 0 1-2 0v-3.585l-1.293 1.293a1 1 0 0 1-1.414-1.415l3-3a1 1 0 0 1 1.414 0l3 3a1 1 0 0 1-1.414 1.415L14 10.414zM9 18a1 1 0 0 1 0-2h8a1 1 0 0 1 0 2H9z" fill="currentColor" fill-rule="evenodd"></path>
                                                    </svg>
                                                    <span>Upload</span>
                                                </button>
                                                <button class="filepond--file-action-button filepond--action-abort-item-processing" type="button" data-align="right" style="transform: translate3d(0px, 0px, 0px) scale3d(1, 1, 1); opacity: 0; visibility: hidden; pointer-events: none;" disabled="disabled"><span>Cancel</span></button>
                                                <button class="filepond--file-action-button filepond--action-retry-item-processing" type="button" data-align="right" disabled="disabled" style="transform: translate3d(0px, 0px, 0px) scale3d(1, 1, 1); opacity: 0; visibility: hidden; pointer-events: none;">
                                                    <svg width="26" height="26" viewBox="0 0 26 26" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M10.81 9.185l-.038.02A4.997 4.997 0 0 0 8 13.683a5 5 0 0 0 5 5 5 5 0 0 0 5-5 1 1 0 0 1 2 0A7 7 0 1 1 9.722 7.496l-.842-.21a.999.999 0 1 1 .484-1.94l3.23.806c.535.133.86.675.73 1.21l-.804 3.233a.997.997 0 0 1-1.21.73.997.997 0 0 1-.73-1.21l.23-.928v-.002z" fill="currentColor" fill-rule="nonzero"></path>
                                                    </svg>
                                                    <span>Retry</span>
                                                </button>
                                                <button id="delButton" data-mode="ajax" data-id="'.$value->id.'" data-attr="'.url('/filepond'.'/'.$value->id).'" class="filepond--file-action-button filepond--action-revert-item-processing" type="button" data-align="right" style="transform: translate3d(0px, 0px, 0px) scale3d(1, 1, 1); opacity: 1;">
                                                    <svg width="26" height="26" viewBox="0 0 26 26" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M11.586 13l-2.293 2.293a1 1 0 0 0 1.414 1.414L13 14.414l2.293 2.293a1 1 0 0 0 1.414-1.414L14.414 13l2.293-2.293a1 1 0 0 0-1.414-1.414L13 11.586l-2.293-2.293a1 1 0 0 0-1.414 1.414L11.586 13z" fill="currentColor" fill-rule="nonzero"></path>
                                                    </svg>
                                                    <span>Remove</span>
                                                </button>
                                                <div class="filepond--processing-complete-indicator" data-align="right" style="transform: scale3d(0.75, 0.75, 1); opacity: 0; visibility: hidden; pointer-events: none;">
                                                    <svg width="26" height="26" viewBox="0 0 26 26" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M18.293 9.293a1 1 0 0 1 1.414 1.414l-7.002 7a1 1 0 0 1-1.414 0l-3.998-4a1 1 0 1 1 1.414-1.414L12 15.586l6.294-6.293z" fill="currentColor" fill-rule="nonzero"></path>
                                                    </svg>
                                                </div>
                                                <div class="filepond--file-info" style="transform: translate3d(0px, 0px, 0px);"><span class="filepond--file-info-main" aria-hidden="true">'.$value->filename.' </span></div>
                                                <div class="filepond--file-status" style="transform: translate3d(0px, 0px, 0px); opacity: 1;"><span class="filepond--file-status-main">Want to Remove?</span><span class="filepond--file-status-sub">tap to remove</span></div>
                                                <div class="filepond--progress-indicator filepond--load-indicator" style="opacity: 0; visibility: hidden; pointer-events: none;">
                                                    <svg>
                                                        <path stroke-width="2" stroke-linecap="round"></path>
                                                    </svg>
                                                </div>
                                                <div class="filepond--progress-indicator filepond--process-indicator" style="opacity: 0; visibility: hidden; pointer-events: none;" data-align="right">
                                                    <svg>
                                                        <path stroke-width="2" stroke-linecap="round" d="M 8.495915929819052 2.0000012830485305 A 6.5 6.5 0 1 0 8.5 2" stroke-opacity="1"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                        </fieldset>
                                        <div class="filepond--panel filepond--item-panel" data-scalable="true">
                                            <div class="filepond--panel-top filepond--item-panel"></div>
                                            <div class="filepond--panel-center filepond--item-panel" style="transform: translate3d(0px, 7px, 0px) scale3d(1, 0.25, 1);"></div>
                                            <div class="filepond--panel-bottom filepond--item-panel" style="transform: translate3d(0px, 32px, 0px);"></div>
                                        </div>
                                    </li>';
                                }
                                @endphp
                                                    
                            @endforeach

                            @else
                            <form class="form" action="{{ route('post.store') }}" method="POST" enctype="multipart/form-data">

                                @endif
                                @csrf
                                <div class="row">
                                    
                                    {{-- <div class="col-md-12 col-12">
                                        <div class="row">
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label for="profile_image">Profile Image
                                                        <div class="display_images preview_profile_image">
                                                            @if (isset($data->profile_image) && !empty($data->profile_image))
                                                            <a data-fancybox="demo" data-src="{{ is_image_exist($data->profile_image) }}"><img title="{{ $data->name }}" src="{{ is_image_exist($data->profile_image) }}" height="100"></a>
                                                            @endif
                                                        </div>
                                                    </label>
                                                    <input type="file" id="profile_image" data-img-val="preview_profile_image" class="form-control @error('profile_image') is-invalid @enderror" placeholder="Profile Image" name="profile_image"> --}}
                                                    {{-- <input type="file" id="profile_image" class="form-control @error('profile_image') is-invalid @enderror" placeholder="Profile Image" name="profile_image[]" multiple>--}}
                                                    {{-- <div class="preview"></div>  --}}
                                                    {{--
                                                    @error('profile_image')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div> --}}

                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="service_id">Services</label>
                                            <select class="form-control @error('service_id') is-invalid @enderror" name="service_id" id="service_id">
                                                <option value="">------ Choose Service ------</option>
                                                @if (isset($data['all_services']) && count($data['all_services'])>0)
                                                    @foreach ($data['all_services'] as $item)
                                                        <option {{ old('service_id')==$item['id'] || (isset($data['service_id']) && $data['service_id'] == $item['id'])? 'selected': '' }} value="{{ $item['id'] }}">{{ $item['service_name'] }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            @error('service_id')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="customer_id">Customer</label>
                                            <select class="form-control @error('customer_id') is-invalid @enderror" name="customer_id" id="customer_id">
                                                <option value="">------ Choose Customer ------</option>
                                                @if (isset($data['all_customers']) && count($data['all_customers'])>0)
                                                    @foreach ($data['all_customers'] as $item)
                                                        <option {{ old('customer_id')==$item['id'] || (isset($data['customer_id']) && $data['customer_id'] == $item['id'])? 'selected': '' }} value="{{ $item['id'] }}">{{ $item['name'] }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            @error('customer_id')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="images">Images</label>
                                            <input type="file" name="images[]" multiple />
                                            <p class="help-block">{{ $errors->first('images') }}</p>
                                            @error('images')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                            @if (isset($data['post_asset']) && count($data['post_asset'])>0)
                                                <ul class="filepond--list" role="list" style="position: inherit; padding-top: 5px;">
                                                @php
                                                    echo $html_images;
                                                @endphp
                                                </ul>
                                            @endif
                                                                                            
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="videos">Videos</label>
                                            <input type="file" name="videos[]" multiple />
                                            <p class="help-block">{{ $errors->first('videos') }}</p>
                                            @error('videos')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                            @if (isset($data['post_asset']) && count($data['post_asset'])>0)
                                                <ul class="filepond--list" role="list" style="position: inherit; padding-top: 5px;">
                                                @php
                                                    echo $html_videos;
                                                @endphp
                                                </ul>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                

                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="title">Title</label>
                                            <input value="{{old('title', isset($data->title) ? $data->title : '')}}" type="text" id="title" class="form-control @error('title') is-invalid @enderror" placeholder="User Name" name="title">
                                            @error('title')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="price">Price</label>
                                            <input value="{{old('price', isset($data->price) ? $data->price : '')}}" type="text" id="price" class="form-control @error('price') is-invalid @enderror" placeholder="User Name" name="price">
                                            @error('price')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="description">Description</label>
                                            <input value="{{old('description', isset($data->description) ? $data->description : '')}}" type="text" id="description" class="form-control @error('description') is-invalid @enderror" placeholder="User Name" name="description">
                                            @error('description')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>


                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="description">Pay with</label>
                                            
                                            <select name="pay_with" id="pay_with" class="form-control @error('pay_with') is-invalid @enderror" required="">
                                                <option value="0">------ Choose Payment ------</option>
                                                <option {{ old('pay_with')==$item['id'] || (isset($data['pay_with']) && $data['pay_with'] == 1)? 'selected': '' }} value="1"> {{ get_gayment_name(1) }} </option>
                                                <option {{ old('pay_with')==$item['id'] || (isset($data['pay_with']) && $data['pay_with'] == 2)? 'selected': '' }} value="2"> {{ get_gayment_name(2) }} </option>
                                                <option {{ old('pay_with')==$item['id'] || (isset($data['pay_with']) && $data['pay_with'] == 3)? 'selected': '' }} value="3"> {{ get_gayment_name(3) }} </option>
                                            </select>

                                            @error('description')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="description">Status</label>
                                            
                                            <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required="">
                                                <option value="0">------ Choose Status ------</option>
                                                <option {{ old('status')==$item['id'] || (isset($data['status']) && $data['status'] == 1)? 'selected': '' }} value="1"> {{ get_status_name(1) }} </option>
                                                <option {{ old('status')==$item['id'] || (isset($data['status']) && $data['status'] == 2)? 'selected': '' }} value="2"> {{ get_status_name(2) }} </option>
                                                <option {{ old('status')==$item['id'] || (isset($data['status']) && $data['status'] == 3)? 'selected': '' }} value="3"> {{ get_status_name(3) }} </option>
                                            </select>

                                            @error('description')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    {{--
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="user_role">User Role</label>
                                            <sel ect class="form-control @error('user_role') is-invalid @enderror" name="user_role" id="user_role">
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
                                    --}}

                                    {{-- <div class="col-md-6 col-12 for_provider_role" style="display: {{ (old('user_role') == 2) || (isset($data->role) && $data->role == 2)? 'block':'none' }}"> --}}
                                    {{--
                                    <div class="col-md-6 col-12 for_provider_role">
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
                                    --}}


                                    <div class="col-12" style="text-align: center">
                                        <button type="submit" id="add_edit_posts" class="btn btn-primary mr-1 waves-effect waves-float waves-light">{{ isset($data->id)? 'Update':'Add' }}</button>
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