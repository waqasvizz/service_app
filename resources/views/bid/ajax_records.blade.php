<div class="table-responsive">
    @if (isset($data['bids']) && count($data['bids'])>0)
        <table class="table">
            <thead>
                <tr>
                    <th>Sr #</th>
                    <th>Service Provide</th>
                    <th>Service Title</th>
                    <th>Post Title</th>
                    <th>Price</th>
                    <th>Details</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                
                @foreach ($data['bids'] as $key=>$item)
                    @php
                        $sr_no = $key + 1;
                        if ($data['bids']->currentPage()>1) {
                            $sr_no = ($data['bids']->currentPage()-1)*$data['bids']->perPage();
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
                        {{-- <td><div class="display_images"><a data-fancybox="demo" data-src="{{ is_image_exist($item->profile_image) }}"><img title="{{ $item->name }}" src="{{ is_image_exist($item->profile_image) }}" height="100"></a></div></td> --}}
                        <td>{{ $item->user->name }}</td>
                        <td>{{ $item->post->service->service_name }}</td>
                        <td>{{ $item->post->title }}</td>
                        <td>{{ $item->price }}</td>
                        <td>{{ $item->detail }}</td>
                        <td>{{ date('M d, Y', strtotime($item->created_at)) }}</td>
                        {{--<td>{{ get_gayment_name($item->pay_with) }}</td>--}}
                        {{-- <td><span style="color: {{ $user_role_color }}">{{ $item->role_name }}</span></td> --}}
                        {{-- <td>{{ date('M d, Y H:i A', strtotime($item->created_at)) }}</td> --}}
                        {{--
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn btn-sm dropdown-toggle hide-arrow waves-effect waves-float waves-light" data-toggle="dropdown">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ url('post')}}/{{$item->id}}/edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 mr-50"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>
                                        <span>Edit</span>
                                    </a>
                                    
                                    <a class="dropdown-item" id="delButton" data-attr="{{ url('/post' . '/' .$item->id) }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash mr-50"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                        <span>Delete</span>
                                    </a>
                                </div>
                            </div>
                        </td>
                        --}}
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="bids_links">
            {{-- {!! $data['bids']->links() !!} --}}
            {{ $data['bids']->links('vendor.pagination.bootstrap-4') }}
        </div>
    @endif
    
</div>