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
                            
                                <form id="billingForm" action="javascript:(void);" role="form" method="POST" class="form list-view product-checkout require-validation" data-cc-on-file="false" data-stripe-publishable-key="<?php echo isset($stripe_credentials['stripe_publish_key']) ? $stripe_credentials['stripe_publish_key'] : '' ?>" id="payment-form">
                                
                            @endif
                                @csrf
                                <div class="row">
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="state">Address</label>
                                            <input value="{{old('address', isset($data->address)? $data->address: 'USAA Blvd E')}}" type="text" id="address" class="form-control @error('address') is-invalid @enderror" placeholder="Your Address" name="address">
                                            @error('address')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="state">State</label>
                                            <input value="{{old('state', isset($data->state)? $data->state: 'TX')}}" type="text" id="state" class="form-control @error('state') is-invalid @enderror" placeholder="Your State" name="state">
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
                                            <input value="{{old('city', isset($data->city)? $data->city: 'San Antonio')}}" type="text" id="city" class="form-control @error('city') is-invalid @enderror" name="city" placeholder="Your City">
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
                                            <input value="{{old('country', isset($data->country)? $data->country: 'US')}}" type="text" id="country" class="form-control @error('country') is-invalid @enderror" name="country" placeholder="Your Country">
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
                                            <input value="{{old('postal_code', isset($data->postal_code)? $data->postal_code: '78288')}}" type="text" id="postal_code" class="OnlyNumbers form-control @error('postal_code') is-invalid @enderror" name="postal_code" placeholder="Your Postal Code">
                                            @error('postal_code')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="phone_numbers">Phone Number</label>
                                            <input value="{{old('phone_numbers', isset($data->phone_numbers)? $data->phone_numbers: '1234567890')}}" type="text" id="phone_numbers" class="OnlyNumbers form-control @error('phone_numbers') is-invalid @enderror" name="phone_numbers" placeholder="Your Phone Number">
                                            @error('phone_numbers')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <input class="form-control card-name" type="text" name="card_name" placeholder="Name on Card" value="John Doe">
                                            {{-- <input class="form-control card-name" type="text" name="card_name" placeholder="Name on Card"> --}}
                                            <span class="text-danger error-text card_name-error"></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            {{-- <input class="form-control card-number" type="text" name="card_number" placeholder="Card Number"> --}}
                                            <input class="form-control card-number OnlyNumbers" maxlength="20" type="text" value="4242424242424242" name="card_number" placeholder="Card Number">
                                            <span class="text-danger error-text card_number-error"></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            {{-- <input class="form-control card-cvc" type="text" name="cvc" placeholder="Security Code (CVC)"> --}}
                                            <input class="form-control card-cvc OnlyNumbers" maxlength="10" value="123" type="text" name="cvc" placeholder="Security Code (CVC)">
                                            <span class="text-danger error-text cvc-error"></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            {{-- <input class="form-control card-expiry-month" type="text" name="expiration_month" placeholder="Expiration Month"> --}}
                                            <input class="form-control card-expiry-month OnlyNumbers" maxlength="2" value="08" type="text" name="expiration_month" placeholder="Expiration Month (MM)">
                                            <span class="text-danger error-text expiration_month-error"></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            {{-- <input class="form-control card-expiry-year" type="text" name="expiration_year" placeholder="Expiration Year"> --}}
                                            <input class="form-control card-expiry-year OnlyNumbers" maxlength="4" type="text" value="2022" name="expiration_year" placeholder="Expiration Year (YYYY)">
                                            <span class="text-danger error-text expiration_year-error"></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            {{-- <input class="form-control card-expiry-year" type="text" name="amount" placeholder="Charged Amount"> --}}
                                            <input class="form-control card-expiry-year OnlyNumbers" maxlength="4" type="text" value="5" name="amount" placeholder="Charged Amount (YYYY)">
                                            <span class="text-danger error-text expiration_year-error"></span>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" id="gateway" name="gateway" value="stripe">

                                <div class="row" style="justify-content: center;">
                                    <div class="col-sm-6 col-md-2 col-12">
                                        <button type="submit" style="width: 100%" class="btn btn-primary mr-1 waves-effect waves-float waves-light">{{ isset($data->id)? 'Update':'Pay with Stripe' }}</button>
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