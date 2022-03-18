@section('title', 'Payment Page')
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
                            <h4 class="card-title">{{ isset($data->id)? 'Update':'Add' }} Item Detail</h4>
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
                                
                                
                            @endif
                            @csrf
                            <div class="row">
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="item_name">Item Name</label>
                                        <input value="{{old('item_name', isset($data->item_name)? $data->item_name: '')}}" type="text" id="item_name" class="form-control @error('item_name') is-invalid @enderror" placeholder="Item Name" name="item_name">
                                        @error('item_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="item_price">Item Price</label>
                                        <input value="{{old('item_price', isset($data->item_price)? $data->item_price: '')}}" type="text" id="item_price" class="form-control @error('item_price') is-invalid @enderror" name="item_price" placeholder="Website Link">
                                        @error('item_price')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="item_category">Item Category</label>
                                        <input value="{{old('item_category', isset($data->item_category)? $data->item_category: '')}}" type="text" id="item_category" class="form-control @error('item_category') is-invalid @enderror" name="item_category" placeholder="Item Category">
                                        @error('item_category')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="item_price">Item Price</label>
                                        <textarea type="text" id="item_price" class="form-control @error('item_price') is-invalid @enderror" placeholder="Item Price" name="item_price">{{old('item_price', isset($data->item_price)? $data->item_price: '')}}</textarea>
                                        @error('item_price')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div id="paypal-button-container"></div>
                                    <a href="{{ url('items-stripe') }}"><button type="text" name="gateway" value="stripe" style="width: 100%" class="btn btn-primary mr-1 waves-effect waves-float waves-light">{{ isset($data->id)? 'Update':'Pay with Stripe' }}</button> </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection

@section('scripts')
    <script src="https://www.paypal.com/sdk/js?client-id={{ $paypal_credentials['client_id'] }}&currency=USD"></script>

    <script>
        paypal.Buttons({

            // Sets up the transaction when a payment button is clicked
            createOrder: function(data, actions) {
                return actions.order.create({
                    purchase_units: [{
                    amount: {
                        value: '5' // Can reference variables or functions. Example: `value: document.getElementById('...').value`
                    }
                    }]
                });
            },

            // Finalize the transaction after payer approval
            onApprove: function(data, actions) {
                return actions.order.capture().then(function(orderData) {
                    // Successful capture! For dev/demo purposes:
                        console.log('Capture result', orderData, JSON.stringify(orderData, null, 2));
                        var transaction = orderData.purchase_units[0].payments.captures[0];
                        // alert('Transaction '+ transaction.status + ': ' + transaction.id + '\n\nSee console for all available details');

                        $.ajax({
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            type: "POST",
                            url: "{{ URL::to('save_payment_response') }}",
                            data: orderData,
                            // processData: false,
                            // contentType: false,
                            cache: false,
                            // timeout: 600000,
                            dataType: 'json',
                            // beforeSend: function() {
                            //     $(document).find('span.error-text').text('');
                            // },
                            success: function (data) {
                    
                                // $('.css_loader2').fadeOut();
                                // $("#result").text(data);
                                // console.log(data);

                                /*
                                $('span.error-text').text('');
                                if(data.status){
                                    // window.location.href = data.redirect_url;
                                    startConfetti();
                                    setTimeout(function () {
                                        stopConfetti();
                                    }, 5000);
                                    setTimeout(function () {
                                        $(".success_message").html("<b>Success: </b> "+data.message).show();
                                        $('#accessAllModules').show();
                                    }, 500);
                                    $('#confirmModelPayment_old_btn_yes').hide();

                                }else{
                                    $.each(data.error, function(prefix, val){
                                        $('span.'+prefix+'-error').text(val[0]);
                                    });
                                    if(data.message){
                                        $(".error_message").html("<b>Sorry: </b> "+data.message).show();
                                    }
                                }

                                $("#confirmModelPayment_old_btn_yes").html('Confirm');
                                $("#confirmModelPayment_old_btn_yes").prop("disabled", false);
                                // $("#confirmModelPayment_old_btn_yes").html('Begin Training');

                                */
                    
                            },
                            error: function (e) {
                                // console.log("ERROR : ", e.responseJSON.message);
                                $("#confirmModelPayment_old_btn_yes").html('Confirm');
                                $("#confirmModelPayment_old_btn_yes").prop("disabled", false);
                                // $("#confirmModelPayment_old_btn_yes").html('Begin Training');
                                $(".error_message").html("<b>Sorry: </b> Something went wrong please try again later.").show();
                    
                            }
                        });

                    // When ready to go live, remove the alert and show a success message within this page. For example:
                    // var element = document.getElementById('paypal-button-container');
                    // element.innerHTML = '';
                    // element.innerHTML = '<h3>Thank you for your payment!</h3>';
                    // Or go to another URL:  actions.redirect('thank_you.html');
                });
            }
        }).render('#paypal-button-container');

    </script>
@endsection