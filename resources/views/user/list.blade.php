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
                            <h4 class="card-title">Filter Users</h4>
                        </div>
                        
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-1">

                                    <label class="form-label" for="select2-basic">Roles</label>
                                    <select class="select2 form-select" name="roles" id="select2-roles">
                                        @foreach ($data['roles'] as $key => $role_obj)
                                            @if ($role_obj['id'] == 3)
                                                <option selected="selected" value="{{$role_obj['id']}}">{{$role_obj['name']}}</option>
                                            @else
                                                <option value="{{$role_obj['id']}}">{{$role_obj['name']}}</option>
                                            @endif 
                                        @endforeach
                                    </select>

                                </div>
                                <div class="col-md-6 mb-1">
                                    
                                    <label class="form-label" for="select2-basic">Account Status</label>
                                    <select class="select2 form-select" name="status" id="select2-account-status">
                                        <option value="0"> ---- All Statuses ---- </option>
                                        <option value="yes"> Active </option>
                                        <option value="no"> Blocked </option>
                                        {{--@foreach ($data['posts_list'] as $key => $post_obj) 
                                            <option value="{{$post_obj['id']}}">{{$post_obj['title']}}</option>
                                        @endforeach--}}
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

        <div id="all_users">
            @include('user.ajax_records')
        </div>

    </div>
</div>
@endsection
