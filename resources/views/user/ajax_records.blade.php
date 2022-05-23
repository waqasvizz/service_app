<div class="table-responsive">
    @if (isset($data['users']) && count($data['users'])>0)
        <table class="table">
            <thead>
                <tr>
                    <th>Sr #</th>
                    <th>Profile Image</th>
                    <th>Name</th>
                    <th>Email</th>

                    @if (isset($data['users_mode']) && $data['users_mode'] == 'Contractor')
                        <th>Paid Status</th>
                        <th>Documents</th>
                    @endif

                    <th>Created At</th>
                    <th>Active Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data['users'] as $key=>$item)
                    @php
                        $sr_no = $key + 1;
                        // echo '<pre>';
                        // print_r($item->Role->name);
                        // exit;
                        if ($data['users']->currentPage()>1) {
                            $sr_no = ($data['users']->currentPage()-1)*$data['users']->perPage();
                            $sr_no = $sr_no + $key + 1;
                        }
                        // $user_role_color = '#6e6b7b';
                        // if($item['role'] == 1){
                        //     $user_role_color = 'red';
                        // }else if($item['role'] == 2){
                        //     $user_role_color = 'green';
                        // }else if($item['role'] == 3){
                        //     $user_role_color = 'blue';
                        // }
                    @endphp
                    <tr>
                        <td>{{ $sr_no }}</td>
                        <td><div class="display_images"><a data-fancybox="demo" data-src="{{ is_image_exist($item->profile_image) }}"><img title="{{ $item->name }}" src="{{ is_image_exist($item->profile_image) }}" height="100"></a></div></td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->email }}</td>
                        {{-- <td><span style="color: {{ $user_role_color }}">{{ $item['role_name'] }}</span></td> --}}
                        
                        @if (isset($data['users_mode']) && $data['users_mode'] == 'Contractor')
                            <td>{{ strtoupper($item->account_status) }}</span></td>
                            <td>
                                @php
                                    $docs_path = config('app.url').'/storage/'.$item->license_document;
                                    $extension = pathinfo($docs_path, PATHINFO_EXTENSION);
                                    $extension = strtolower($extension);
                                @endphp
                                
                                @if ($extension == 'pdf')
                                    <div class="display_images">
                                        <a href="{{ is_image_exist($item->license_document) }}" target="_blank">
                                            <img title="{{ $item->license_document }}" src="{{ is_image_exist('default-images/download_pdfs.jpg') }}" height="100">
                                        </a>
                                    </div>
                                @else
                                    <div class="display_images">
                                    <a data-fancybox="demo" data-src="{{ is_image_exist($item->license_document) }}">
                                        <img title="{{ $item->name }}" src="{{ is_image_exist($item->license_document) }}" height="100">
                                    </a>
                                </div>
                                @endif

                                @php
                                    $docs_path = config('app.url').'/storage/'.$item->insurance_document;
                                    $extension = pathinfo($docs_path, PATHINFO_EXTENSION);
                                    $extension = strtolower($extension);
                                @endphp

                                @if ($extension == 'pdf')
                                    <div class="display_images">
                                        <a href="{{ is_image_exist($item->insurance_document) }}" target="_blank">
                                            <img title="{{ $item->insurance_document }}" src="{{ is_image_exist('default-images/download_pdfs.jpg') }}" height="100">
                                        </a>
                                    </div>
                                @else
                                    <div class="display_images">
                                    <a data-fancybox="demo" data-src="{{ is_image_exist($item->insurance_document) }}">
                                        <img title="{{ $item->name }}" src="{{ is_image_exist($item->insurance_document) }}" height="100">
                                    </a>
                                @endif
                            </td>
                        @endif

                        <td>{{ date('M d, Y H:i A', strtotime($item->created_at)) }}</td>
                        @if ($item->active_status == 'yes')
                            <td>Active</td>
                        @elseif ($item->active_status == 'no' OR $item->active_status == '')
                            <td>Blocked</td>
                        @endif
                        
                        <td>
                            <div class="dropdown">
                                @if ( $item->role != 1 )
                                    <button type="button" class="btn btn-sm dropdown-toggle hide-arrow waves-effect waves-float waves-light" data-toggle="dropdown">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="{{ url('user')}}/{{$item['id']}}/edit">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 mr-50"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>
                                            <span>Edit</span>
                                        </a>
                                        
                                        <a class="dropdown-item" id="delButton" data-attr="{{ url('/user' . '/' .$item->id) }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash mr-50"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                            <span>Delete</span>
                                        </a>


                                        <form action="{{ url('update_user')}}" method="Post" enctype="multipart/form-data">
                                            @method('POST')
                                            @csrf
                                            @if ( $item->active_status == 'yes' )
                                            <a class="dropdown-item">
                                                <input type="hidden" name="update_id" value="{{$item->id}}">
                                                <input type="hidden" name="active_status" value="no">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user-x"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="18" y1="8" x2="23" y2="13"></line><line x1="23" y1="8" x2="18" y2="13"></line></svg>
                                                <button type="submit" style="color: #6e6b7b; border: none; background: transparent;">Block</button>
                                            </a>                                                  
                                            @else
                                            <a class="dropdown-item">
                                                <input type="hidden" name="update_id" value="{{$item->id}}">
                                                <input type="hidden" name="active_status" value="yes">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user-check"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><polyline points="17 11 19 13 23 9"></polyline></svg>
                                                <button type="submit" style="color: #6e6b7b; border: none; background: transparent;">Unblock</button>
                                            </a>
                                            @endif
                                        </form>

                                        {{--<a class="dropdown-item" href="{{ url('user')}}/{{$item->id}}/edit">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 mr-50"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>
                                            <span>Edit</span>
                                        </a>
                                        
                                        <a class="dropdown-item" id="delButton" data-attr="{{ url('/user' . '/' .$item->id) }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash mr-50"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                            <span>Delete</span>
                                        </a>--}}
                                    </div>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="users_links">
            {{-- {!! $data['users']->links() !!} --}}
            {{ $data['users']->links('vendor.pagination.bootstrap-4') }}
        </div>
    @endif

</div>