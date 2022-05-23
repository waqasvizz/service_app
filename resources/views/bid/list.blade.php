@section('title', 'User List')
@extends('layouts.admin')

@section('content')

<div class="content-wrapper">
    <div class="content-header row">
        
    </div>
    <div class="content-body">

        <!-- Select2 Start  -->
        <section class="basic-select2">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Filter Bids</h4>
                        </div>
                        
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-1">
                                    <div id="all_services">
                                        @include('service.ajax_services_list')
                                    </div>
                                </div>
                                <div class="col-md-6 mb-1">
                                    
                                    {{--<div id="all_bids">
                                        @include('bid.ajax_records')
                                    </div>--}}

                                    <label class="form-label" for="select2-basic">Posts</label>
                                    <select class="select2 form-select" name="posts" id="select2-posts">
                                        <option value="0"> ---- Choose Post ---- </option>
                                        @foreach ($data['posts_list'] as $key => $post_obj) 
                                            <option value="{{$post_obj['id']}}">{{$post_obj['title']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Select2 End -->

        @if (Session::has('message'))
            <div class="alert alert-success"><b>Success: </b>{{ Session::get('message') }}</div>
        @endif
        @if (Session::has('error_message'))
            <div class="alert alert-danger"><b>Sorry: </b>{{ Session::get('error_message') }}</div>
        @endif

        <div id="all_bids">
            @include('bid.ajax_records')
        </div>

    </div>
</div>
@endsection
