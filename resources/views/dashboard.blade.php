@section('title', 'Dashboard')
@extends('layouts.admin')

@section('content')

<div class="content-wrapper">
    <div class="content-header row">
        
    </div>
    <div class="content-body">
        <!-- Dashboard Analytics Start -->
        <section id="dashboard-analytics">
            <div class="row match-height">
                <!-- Greetings Card starts -->
                <div class="col-lg-6 col-md-12 col-sm-12">
                    <div class="card card-congratulations">
                        <div class="card-body text-center">
                            <img src="{{ asset('app-assets/images/elements/decore-left.png') }}" class="congratulations-img-left" alt="card-img-left"/>
                            <img src="{{ asset('app-assets/images/elements/decore-right.png') }}" class="congratulations-img-right" alt="card-img-right" />
                            <div class="avatar avatar-xl bg-primary shadow">
                                <div class="avatar-content">
                                    <i data-feather="award" class="font-large-1"></i>
                                </div>
                            </div>
                            <div class="text-center">
                                <h1 class="mb-1 text-white">Congratulations John,</h1>
                                <p class="card-text m-auto w-75">
                                    You have done <strong>57.6%</strong> more sales today. Check your new badge in your profile.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Greetings Card ends -->

                <!-- Subscribers Chart Card starts -->
                <div class="col-lg-3 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-header flex-column align-items-start pb-0">
                            <div class="avatar bg-light-primary p-50 m-0">
                                <div class="avatar-content">
                                    <i data-feather="users" class="font-medium-5"></i>
                                </div>
                            </div>
                            <h2 class="font-weight-bolder mt-1">92.6k</h2>
                            <p class="card-text">Subscribers Gained</p>
                        </div>
                        <div id="gained-chart"></div>
                    </div>
                </div>
                <!-- Subscribers Chart Card ends -->

                <!-- Orders Chart Card starts -->
                <div class="col-lg-3 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-header flex-column align-items-start pb-0">
                            <div class="avatar bg-light-warning p-50 m-0">
                                <div class="avatar-content">
                                    <i data-feather="package" class="font-medium-5"></i>
                                </div>
                            </div>
                            <h2 class="font-weight-bolder mt-1">38.4K</h2>
                            <p class="card-text">Orders Received</p>
                        </div>
                        <div id="order-chart"></div>
                    </div>
                </div>
                <!-- Orders Chart Card ends -->
            </div>

            <div class="row match-height">
                <!-- Avg Sessions Chart Card starts -->
                <div class="col-lg-6 col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row pb-50">
                                <div class="col-sm-6 col-12 d-flex justify-content-between flex-column order-sm-1 order-2 mt-1 mt-sm-0">
                                    <div class="mb-1 mb-sm-0">
                                        <h2 class="font-weight-bolder mb-25">2.7K</h2>
                                        <p class="card-text font-weight-bold mb-2">Avg Sessions</p>
                                        <div class="font-medium-2">
                                            <span class="text-success mr-25">+5.2%</span>
                                            <span>vs last 7 days</span>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-primary">View Details</button>
                                </div>
                                <div class="col-sm-6 col-12 d-flex justify-content-between flex-column text-right order-sm-2 order-1">
                                    <div class="dropdown chart-dropdown">
                                        <button class="btn btn-sm border-0 dropdown-toggle p-50" type="button" id="dropdownItem5" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Last 7 Days
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownItem5">
                                            <a class="dropdown-item" href="javascript:void(0);">Last 28 Days</a>
                                            <a class="dropdown-item" href="javascript:void(0);">Last Month</a>
                                            <a class="dropdown-item" href="javascript:void(0);">Last Year</a>
                                        </div>
                                    </div>
                                    <div id="avg-sessions-chart"></div>
                                </div>
                            </div>
                            <hr />
                            <div class="row avg-sessions pt-50">
                                <div class="col-6 mb-2">
                                    <p class="mb-50">Goal: $100000</p>
                                    <div class="progress progress-bar-primary" style="height: 6px">
                                        <div class="progress-bar" role="progressbar" aria-valuenow="50" aria-valuemin="50" aria-valuemax="100" style="width: 50%"></div>
                                    </div>
                                </div>
                                <div class="col-6 mb-2">
                                    <p class="mb-50">Users: 100K</p>
                                    <div class="progress progress-bar-warning" style="height: 6px">
                                        <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="60" aria-valuemax="100" style="width: 60%"></div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <p class="mb-50">Retention: 90%</p>
                                    <div class="progress progress-bar-danger" style="height: 6px">
                                        <div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="70" aria-valuemax="100" style="width: 70%"></div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <p class="mb-50">Duration: 1yr</p>
                                    <div class="progress progress-bar-success" style="height: 6px">
                                        <div class="progress-bar" role="progressbar" aria-valuenow="90" aria-valuemin="90" aria-valuemax="100" style="width: 90%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Avg Sessions Chart Card ends -->

                <!-- Support Tracker Chart Card starts -->
                <div class="col-lg-6 col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between pb-0">
                            <h4 class="card-title">Support Tracker</h4>
                            <div class="dropdown chart-dropdown">
                                <button class="btn btn-sm border-0 dropdown-toggle p-50" type="button" id="dropdownItem4" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Last 7 Days
                                </button>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownItem4">
                                    <a class="dropdown-item" href="javascript:void(0);">Last 28 Days</a>
                                    <a class="dropdown-item" href="javascript:void(0);">Last Month</a>
                                    <a class="dropdown-item" href="javascript:void(0);">Last Year</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-2 col-12 d-flex flex-column flex-wrap text-center">
                                    <h1 class="font-large-2 font-weight-bolder mt-2 mb-0">163</h1>
                                    <p class="card-text">Tickets</p>
                                </div>
                                <div class="col-sm-10 col-12 d-flex justify-content-center">
                                    <div id="support-trackers-chart"></div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mt-1">
                                <div class="text-center">
                                    <p class="card-text mb-50">New Tickets</p>
                                    <span class="font-large-1 font-weight-bold">29</span>
                                </div>
                                <div class="text-center">
                                    <p class="card-text mb-50">Open Tickets</p>
                                    <span class="font-large-1 font-weight-bold">63</span>
                                </div>
                                <div class="text-center">
                                    <p class="card-text mb-50">Response Time</p>
                                    <span class="font-large-1 font-weight-bold">1d</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Support Tracker Chart Card ends -->
            </div>
        </section>
        <!-- Dashboard Analytics end -->

    </div>
</div>
@endsection
