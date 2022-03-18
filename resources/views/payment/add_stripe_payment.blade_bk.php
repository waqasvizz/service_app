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
                                <form class="form" action="{{ route('payment') }}" method="POST">
                                
                            @endif
                                @csrf
                                <div class="row">
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="state">State</label>
                                            <input value="{{old('state', isset($data->state)? $data->state: '')}}" type="text" id="state" class="form-control @error('state') is-invalid @enderror" placeholder="Your State" name="state">
                                            @error('state')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="city">City</label>
                                            <input value="{{old('city', isset($data->city)? $data->city: '')}}" type="text" id="city" class="form-control @error('city') is-invalid @enderror" name="city" placeholder="Your City">
                                            @error('city')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="country">Country</label>
                                            <input value="{{old('country', isset($data->country)? $data->country: '')}}" type="text" id="country" class="form-control @error('country') is-invalid @enderror" name="country" placeholder="Your Country">
                                            @error('country')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="postal_code">Postal Code</label>
                                            <textarea type="text" id="postal_code" class="form-control @error('postal_code') is-invalid @enderror" placeholder="Your Postal Code" name="postal_code">{{old('postal_code', isset($data->postal_code)? $data->postal_code: '')}}</textarea>
                                            @error('postal_code')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="phone_number">Phone Number</label>
                                            <textarea type="text" id="phone_number" class="form-control @error('phone_number') is-invalid @enderror" placeholder="Your Phone Number" name="phone_number">{{old('phone_number', isset($data->phone_number)? $data->phone_number: '')}}</textarea>
                                            @error('phone_number')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row" style="justify-content: center;">
                                    <div class="col-sm-6 col-md-2 col-12">
                                        <button type="submit" name="gateway" value="stripe" style="width: 100%" class="btn btn-primary mr-1 waves-effect waves-float waves-light">{{ isset($data->id)? 'Update':'Pay with Stripe' }}</button>
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

@section('scripts')
    <script src="https://www.paypal.com/sdk/js?client-id={{ $stripe_credentials['stripe_publish_key'] }}&currency=USD"></script>

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