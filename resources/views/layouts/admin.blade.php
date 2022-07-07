<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0,minimal-ui">
    <meta name="description"
        content="{{ config('app.name') }} admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities.">
    <meta name="keywords"
        content="admin template, {{ config('app.name') }} admin template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="PIXINVENT">
    <title>{{ config('app.name') }} - @yield('title')</title>
    <link rel="apple-touch-icon" href="{{ asset('app-assets/images/ico/apple-icon-120.png') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('app-assets/images/ico/favicon.ico') }}">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600"
        rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/vendors.min.css') }}">
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/charts/apexcharts.css') }}"> --}}
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/extensions/toastr.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('app-assets/vendors/css/tables/datatable/datatables.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('app-assets/vendors/css/tables/datatable/responsive.bootstrap.min.css') }}">
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/bootstrap-extended.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/colors.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/components.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/themes/dark-layout.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/themes/bordered-layout.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/themes/semi-dark-layout.css') }}">

    <!-- BEGIN: Select2 CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/forms/select/select2.min.css') }}">
    <!-- BEGIN: Select2 CSS-->

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/core/menu/menu-types/vertical-menu.css') }}">
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/plugins/charts/chart-apex.css') }}"> --}}
    <link rel="stylesheet" type="text/css"
        href="{{ asset('app-assets/css/plugins/extensions/ext-component-toastr.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/pages/app-invoice-list.css') }}">
    <!-- END: Page CSS-->

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.css" />
    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/assets/css/style.css') }}">
    <!-- END: Custom CSS-->

    <link href="https://unpkg.com/filepond@^4/dist/filepond.css" rel="stylesheet" />

    <style>
    .alert {
        padding: 15px;
    }

    .preview {
        /* background: lightgray; */
        display: none;
        border-radius: 10px;
        margin: 10px 0px;
        padding: 15px;
        border: 1px solid lightgray;
        box-shadow: 0 4px 24px 0 rgba(34, 41, 47, 0.25);
    }

    .preview img {
        border-radius: 50%;
        height: 60px;
        width: 60px;
        margin: 5px;
        border: 2px solid #7367f0c4;
    }

    .display_images img {
        border-radius: 50%;
        height: 60px;
        width: 60px;
        margin: 5px;
        border: 2px solid #7367f0c4;
    }

    .show_role_name_td {
        padding: 10px;
        background: #f3f2f7;
        border: 1px solid lightgray;
        border-radius: 10px;
    }
    </style>
</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu-modern  navbar-floating footer-static  " data-open="click"
    data-menu="vertical-menu-modern" data-col="">

    <!-- BEGIN: Header-->
    <nav class="header-navbar navbar navbar-expand-lg align-items-center floating-nav navbar-light navbar-shadow">
        <div class="navbar-container d-flex content">
            <div class="bookmark-wrapper d-flex align-items-center">
                <ul class="nav navbar-nav d-xl-none">
                    <li class="nav-item"><a class="nav-link menu-toggle" href="javascript:void(0);"><i class="ficon"
                                data-feather="menu"></i></a></li>
                </ul>
            </div>
            <ul class="nav navbar-nav align-items-center ml-auto">
                <li class="nav-item d-none d-lg-block"><a class="nav-link nav-link-style"><i class="ficon"
                            data-feather="moon"></i></a></li>


                <li class="nav-item dropdown dropdown-user"><a class="nav-link dropdown-toggle dropdown-user-link"
                        id="dropdown-user" href="javascript:void(0);" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        <div class="user-nav d-sm-flex d-none"><span
                                class="user-name font-weight-bolder">{{ Auth::user()->name }}</span><span
                                class="user-status">Admin</span></div><span class="avatar"><img class="round"
                                src="{{ is_image_exist(Auth::user()->profile_image) }}" alt="avatar" height="40"
                                width="40"><span class="avatar-status-online"></span></span>
                        {{-- <div class="user-nav d-sm-flex d-none"><span class="user-name font-weight-bolder">{{ Auth::user()->name }}</span><span
                            class="user-status">Admin</span>
        </div><span class="avatar"><img class="round"
                src="{{ asset('app-assets/images/portrait/small/avatar-s-11.jpg') }}" alt="avatar" height="40"
                width="40"><span class="avatar-status-online"></span></span> --}}
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown-user">
            <a class="dropdown-item" href="{{ url('profile') }}"><i class="mr-50" data-feather="user"></i> Profile</a>
            <a class="dropdown-item" href="{{ url('logout') }}"><i class="mr-50" data-feather="power"></i> Logout</a>
        </div>
        </li>
        </ul>
        </div>
    </nav>
    <!-- END: Header-->


    <!-- BEGIN: Main Menu-->
    <div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
        <div class="navbar-header">
            <ul class="nav navbar-nav flex-row">
                <li class="nav-item mr-auto">
                    <a class="navbar-brand" href="{{url('/dashboard')}}">
                        <span class="brand-logo">
                            <img src="{{ is_image_exist('app-assets/images/default-images/app-logo.png') }}"
                                class="congratulations-img-left" alt="card-img-left" />
                            {{--<svg viewbox="0 0 139 95" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" height="24">
                                <defs>
                                    <lineargradient id="linearGradient-1" x1="100%" y1="10.5120544%" x2="50%" y2="89.4879456%">
                                        <stop stop-color="#000000" offset="0%"></stop>
                                        <stop stop-color="#FFFFFF" offset="100%"></stop>
                                    </lineargradient>
                                    <lineargradient id="linearGradient-2" x1="64.0437835%" y1="46.3276743%" x2="37.373316%" y2="100%">
                                        <stop stop-color="#EEEEEE" stop-opacity="0" offset="0%"></stop>
                                        <stop stop-color="#FFFFFF" offset="100%"></stop>
                                    </lineargradient>
                                </defs>
                                <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <g id="Artboard" transform="translate(-400.000000, -178.000000)">
                                        <g id="Group" transform="translate(400.000000, 178.000000)">
                                            <path class="text-primary" id="Path" d="M-5.68434189e-14,2.84217094e-14 L39.1816085,2.84217094e-14 L69.3453773,32.2519224 L101.428699,2.84217094e-14 L138.784583,2.84217094e-14 L138.784199,29.8015838 C137.958931,37.3510206 135.784352,42.5567762 132.260463,45.4188507 C128.736573,48.2809251 112.33867,64.5239941 83.0667527,94.1480575 L56.2750821,94.1480575 L6.71554594,44.4188507 C2.46876683,39.9813776 0.345377275,35.1089553 0.345377275,29.8015838 C0.345377275,24.4942122 0.230251516,14.560351 -5.68434189e-14,2.84217094e-14 Z" style="fill:currentColor"></path>
                                            <path id="Path1" d="M69.3453773,32.2519224 L101.428699,1.42108547e-14 L138.784583,1.42108547e-14 L138.784199,29.8015838 C137.958931,37.3510206 135.784352,42.5567762 132.260463,45.4188507 C128.736573,48.2809251 112.33867,64.5239941 83.0667527,94.1480575 L56.2750821,94.1480575 L32.8435758,70.5039241 L69.3453773,32.2519224 Z" fill="url(#linearGradient-1)" opacity="0.2"></path>
                                            <polygon id="Path-2" fill="#000000" opacity="0.049999997" points="69.3922914 32.4202615 32.8435758 70.5039241 54.0490008 16.1851325"></polygon>
                                            <polygon id="Path-21" fill="#000000" opacity="0.099999994" points="69.3922914 32.4202615 32.8435758 70.5039241 58.3683556 20.7402338"></polygon>
                                            <polygon id="Path-3" fill="url(#linearGradient-2)" opacity="0.099999994" points="101.428699 0 83.0667527 94.1480575 130.378721 47.0740288"></polygon>
                                        </g>
                                    </g>
                                </g>
                            </svg>--}}
                        </span>
                        <h2 class="brand-text" style="padding-left: 5px; font-size: 17px !important;">
                            {{ config('app.name') }}</h2>
                    </a>
                </li>
                <li class="nav-item nav-toggle"><a class="nav-link modern-nav-toggle pr-0" data-toggle="collapse"><i
                            class="d-block d-xl-none text-primary toggle-icon font-medium-4" data-feather="x"></i><i
                            class="d-none d-xl-block collapse-toggle-icon font-medium-4  text-primary"
                            data-feather="disc" data-ticon="disc"></i></a></li>
            </ul>
        </div>
        <div class="shadow-bottom"></div>
        <div class="main-menu-content">
            <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">

                <li class="{{ Request::path() == 'dashboard' ? 'active' : '' }} nav-item"><a
                        class="d-flex align-items-center" href="{{ url('dashboard') }}"><i data-feather="home"></i><span
                            class="menu-title text-truncate" data-i18n="Dashboards">Dashboards</span></a>

                <li class=" navigation-header"><span data-i18n="Apps &amp; Pages">Apps &amp; Pages</span><i
                        data-feather="more-horizontal"></i>
                </li>
                {{-- <li class=" nav-item"><a class="d-flex align-items-center" href="#"><i data-feather="file-text"></i><span class="menu-title text-truncate" data-i18n="Role">Role</span></a>
                    <ul class="menu-content">
                        <li class="{{ Request::path() == 'role' ? 'active' : '' }}"><a
                    class="d-flex align-items-center" href="{{ url('role') }}"><i data-feather="circle"></i><span
                        class="menu-item text-truncate" data-i18n="List">List</span></a>
                </li>
                <li class="{{ Request::path() == 'role/create' ? 'active' : '' }}"><a class="d-flex align-items-center"
                        href="{{ url('role/create') }}"><i data-feather="circle"></i><span
                            class="menu-item text-truncate" data-i18n="Add">Add</span></a>
                </li>
            </ul>
            </li> --}}
            <li class=" nav-item"><a class="d-flex align-items-center" href="#"><i data-feather="briefcase"></i><span
                        class="menu-title text-truncate" data-i18n="Service">Service</span></a>
                <ul class="menu-content">
                    <li class="{{ Request::path() == 'service' ? 'active' : '' }}"><a class="d-flex align-items-center"
                            href="{{ url('service') }}"><i data-feather="circle"></i><span
                                class="menu-item text-truncate" data-i18n="List">List</span></a>
                    </li>
                    <li class="{{ Request::path() == 'service/create' ? 'active' : '' }}"><a
                            class="d-flex align-items-center" href="{{ url('service/create') }}"><i
                                data-feather="circle"></i><span class="menu-item text-truncate"
                                data-i18n="Add">Add</span></a>
                    </li>
                </ul>
            </li>
            <li class=" nav-item"><a class="d-flex align-items-center" href="#"><i data-feather="user"></i><span
                        class="menu-title text-truncate" data-i18n="User">User</span></a>
                <ul class="menu-content">
                    {{-- <li class="{{ Request::path() == 'items' ? 'active' : '' }}"><a
                        class="d-flex align-items-center" href="{{ url('items') }}"><i data-feather="circle"></i><span
                            class="menu-item text-truncate" data-i18n="List">Cart</span></a>
            </li> --}}
            <li class="{{ Request::path() == 'user' ? 'active' : '' }}"><a class="d-flex align-items-center"
                    href="{{ url('user') }}"><i data-feather="circle"></i><span class="menu-item text-truncate"
                        data-i18n="List">List</span></a>
            </li>
            <li class="{{ Request::path() == 'user/create' ? 'active' : '' }}"><a class="d-flex align-items-center"
                    href="{{ url('user/create') }}"><i data-feather="circle"></i><span class="menu-item text-truncate"
                        data-i18n="Add">Add</span></a>
            </li>
            </ul>
            </li>
            {{-- <li class=" nav-item"><a class="d-flex align-items-center" href="#"><i data-feather="file-text"></i><span class="menu-title text-truncate" data-i18n="Category">Category</span></a>
                    <ul class="menu-content">
                        <li class="{{ Request::path() == 'category' ? 'active' : '' }}"><a
                class="d-flex align-items-center" href="{{ url('category') }}"><i data-feather="circle"></i><span
                    class="menu-item text-truncate" data-i18n="List">List</span></a>
            </li>
            <li class="{{ Request::path() == 'category/create' ? 'active' : '' }}"><a class="d-flex align-items-center"
                    href="{{ url('category/create') }}"><i data-feather="circle"></i><span
                        class="menu-item text-truncate" data-i18n="Add">Add</span></a>
            </li>
            </ul>
            </li> --}}
            <li class=" nav-item"><a class="d-flex align-items-center" href="#"><i data-feather="copy"></i><span
                        class="menu-title text-truncate" data-i18n="Post">Post</span></a>
                <ul class="menu-content">
                    <li class="{{ Request::path() == 'post' ? 'active' : '' }}"><a class="d-flex align-items-center"
                            href="{{ url('post') }}"><i data-feather="circle"></i><span class="menu-item text-truncate"
                                data-i18n="List">List</span></a>
                    </li>
                    {{--<li class="{{ Request::path() == 'post/create' ? 'active' : '' }}"><a
                        class="d-flex align-items-center" href="{{ url('post/create') }}"><i
                            data-feather="circle"></i><span class="menu-item text-truncate"
                            data-i18n="Add">Add</span></a>
            </li>--}}
            </ul>
            </li>
            <li class=" nav-item"><a class="d-flex align-items-center" href="#"><i data-feather="file-text"></i><span
                        class="menu-title text-truncate" data-i18n="Bid">Bid</span></a>
                <ul class="menu-content">
                    <li class="{{ Request::path() == 'bid' ? 'active' : '' }}"><a class="d-flex align-items-center"
                            href="{{ url('bid') }}"><i data-feather="circle"></i><span class="menu-item text-truncate"
                                data-i18n="List">List</span></a>
                    </li>
                    {{--<li class="{{ Request::path() == 'bid/create' ? 'active' : '' }}"><a
                        class="d-flex align-items-center" href="{{ url('bid/create') }}"><i
                            data-feather="circle"></i><span class="menu-item text-truncate"
                            data-i18n="Add">Add</span></a>
            </li>--}}
            </ul>
            </li>
            </ul>
        </div>
    </div>
    <!-- END: Main Menu-->

    <!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        @yield('content')
    </div>
    <!-- END: Content-->

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <!-- BEGIN: Footer-->
    <footer class="footer footer-static footer-light">
        <p class="clearfix mb-0"><span class="float-md-left d-block d-md-inline-block mt-25">COPYRIGHT &copy; 2021
                {{-- <a class="ml-25" href="https://1.envato.market/pixinvent_portfolio" target="_blank">Pixinvent</a> --}}
                <span class="d-none d-sm-inline-block">, All rights Reserved</span></span>
            {{-- <span class="float-md-right d-none d-md-block">Hand-crafted & Made with<i data-feather="heart"></i></span> --}}
        </p>
    </footer>
    <button class="btn btn-primary btn-icon scroll-top" type="button"><i data-feather="arrow-up"></i></button>
    <!-- END: Footer-->


    <!-- Delete modal -->
    <div class="modal fade" id="delModal" tabindex="-1" role="dialog" aria-labelledby="delModalLabel" aria-hidden="true"
        style="display: none">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="smallBody">
                    <form id="delForm" action="" method="post">
                        <div class="modal-body">
                            @csrf
                            @method('DELETE')
                            <h5 class="text-center">Are you sure you want to delete?</h5>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button id="form_delete_Btn" type="submit" class="btn btn-danger">Yes, Delete</button>
                            <button id="ajax_delete_Btn" style="display: none;" type="button"
                                class="btn btn-danger">Yes, Delete</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- BEGIN: Vendor JS-->
    <script src="{{ asset('app-assets/vendors/js/vendors.min.js') }}"></script>
    <!-- BEGIN Vendor JS-->


    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.umd.js"></script>

    <!-- BEGIN: Page Vendor JS-->
    {{-- <script src="{{ asset('app-assets/vendors/js/charts/apexcharts.min.js') }}"></script> --}}
    <script src="{{ asset('app-assets/vendors/js/extensions/toastr.min.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/extensions/moment.min.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/tables/datatable/datatables.min.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/tables/datatable/datatables.buttons.min.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/tables/datatable/datatables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/tables/datatable/responsive.bootstrap.min.js') }}"></script>
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Theme JS-->
    <script src="{{ asset('app-assets/js/core/app-menu.js') }}"></script>
    <script src="{{ asset('app-assets/js/core/app.js') }}"></script>
    <!-- END: Theme JS-->

    <!-- BEGIN: Page JS-->
    <script src="{{ asset('app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
    <script src="{{ asset('app-assets/js/scripts/forms/form-select2.js') }}"></script>
    <!-- END: Page JS-->

    <!-- BEGIN: Page JS-->
    {{-- <script src="{{ asset('app-assets/js/scripts/pages/dashboard-analytics.js') }}"></script> --}}
    <script src="{{ asset('app-assets/js/scripts/pages/app-invoice-list.js') }}"></script>
    <script src="{{ asset('app-assets/js/script.js') }}"></script>
    <!-- END: Page JS-->

    <script src="https://unpkg.com/filepond@^4/dist/filepond.js"></script>
    <script>
    // Set default FilePond options
    FilePond.setOptions({
        server: {
            url: "{{ config('filepond.server.url') }}",
            headers: {
                'X-CSRF-TOKEN': "{{ @csrf_token() }}",
            }
        }
    });

    // Create the FilePond instance
    // FilePond.create(document.querySelector('input[name="images"]'));
    // FilePond.create(document.querySelector('input[name="videos"]'));

    FilePond.create(document.querySelector('input[name="images[]"]'), {
        chunkUploads: true
    });

    FilePond.create(document.querySelector('input[name="videos[]"]'), {
        chunkUploads: true
    });
    </script>

    <script type="text/javascript" src="https://js.stripe.com/v2/"></script>
    <script>
    let autocomplete;
    let address1Field;
    // let address2Field;
    let postalField;

    function initAutocomplete() {
        address1Field = document.querySelector("#address");
        //   address2Field = document.querySelector("#address2");
        postalField = document.querySelector("#postal_code");
        // Create the autocomplete object, restricting the search predictions to
        // addresses in the US and Canada.
        autocomplete = new google.maps.places.Autocomplete(address1Field, {
            // componentRestrictions: { country: ["us", "ca"] },
            fields: ["address_components", "geometry"],
            types: ["address"],
        });
        //   address1Field.focus();
        // When the user selects an address from the drop-down, populate the
        // address fields in the form.
        autocomplete.addListener("place_changed", fillInAddress);
    }

    function fillInAddress() {
        // console.log(postalField);
        address1Field.value = "";
        if (postalField != null) {
            postalField.value = "";
            document.querySelector("#city").value = "";
            document.querySelector("#state").value = "";
            document.querySelector("#country").value = "";
        }

        // Get the place details from the autocomplete object.
        const place = autocomplete.getPlace();
        let address1 = "";
        let postcode = "";

        var latitude = place.geometry.location.lat();
        var longitude = place.geometry.location.lng();

        document.querySelector("#latitude").value = latitude;
        document.querySelector("#longitude").value = longitude;

        //   console.log(place);
        //   console.log(latitude);
        //   console.log(longitude);
        // Get each component of the address from the place details,
        // and then fill-in the corresponding field on the form.
        // place.address_components are google.maps.GeocoderAddressComponent objects
        // which are documented at http://goo.gle/3l5i5Mr
        for (const component of place.address_components) {
            const componentType = component.types[0];
            // console.log(component);

            switch (componentType) {
                case "street_number": {
                    address1 = `${component.long_name} ${address1}`;
                    break;
                }

                case "route": {
                    address1 += component.short_name;
                    break;
                }

                case "postal_code": {
                    postcode = `${component.long_name}${postcode}`;
                    break;
                }

                case "postal_code_suffix": {
                    postcode = `${postcode}-${component.long_name}`;
                    break;
                }
                case "locality":
                    if (postalField != null) {
                        document.querySelector("#city").value = component.long_name;
                    }
                    break;

                case "administrative_area_level_1": {
                    if (postalField != null) {
                        document.querySelector("#state").value = component.short_name;
                    }
                    break;
                }
                case "country":
                    if (postalField != null) {
                        document.querySelector("#country").value = component.long_name;
                    }
                    break;
            }
        }
        address1Field.value = address1;
        if (postalField != null) {
            postalField.value = postcode;
        }
        // After filling the form with address components from the Autocomplete
        // prediction, set cursor focus on the second address line to encourage
        // entry of subpremise information such as apartment, unit, or floor number.
        //   address2Field.focus();
    }

    $(document).ready(function() {
        $('form.require-validation').bind('submit', function(e) {
            var card_type = $('#payment_activity').val();

            // if (card_type == 'new_card') {
            var $form = $(".require-validation"),
                inputSelector = ['input[type=email]', 'input[type=password]', 'input[type=text]',
                    'input[type=file]', 'textarea'
                ].join(', '),
                $inputs = $form.find('.required').find(inputSelector),
                $errorMessage = $form.find('div.error'),
                valid = true;
            $errorMessage.addClass('hide');
            $('.has-error').removeClass('has-error');
            $inputs.each(function(i, el) {
                var $input = $(el);
                if ($input.val() === '') {
                    $input.parent().addClass('has-error');
                    $errorMessage.removeClass('hide');
                    e.preventDefault();
                }
            });
            if (!$form.data('cc-on-file')) {
                e.preventDefault();
                Stripe.setPublishableKey($form.data('stripe-publishable-key'));
                Stripe.createToken({
                    number: $('.card-number').val(),
                    cvc: $('.card-cvc').val(),
                    exp_month: $('.card-expiry-month').val(),
                    exp_year: $('.card-expiry-year').val()
                }, stripeResponseHandler);
                //   console.log(stripeResponseHandler);
            }
            // }
            // else {
            //PaymentBillingWithOldCard();
            // }
        });

        $(window).on('load', function() {
            if (feather) {
                feather.replace({
                    width: 14,
                    height: 14
                });
            }
        });
    });
    </script>

    <script type="text/javascript" src="https://js.stripe.com/v2/"></script>
    <script>
    $(document).ready(function() {

        setTimeout(function() {
            $(".alert-success").remove();
        }, 5000); // 5 secs

        var pathname = window.location.pathname;
        if (pathname == '/bid') {
            getAjaxData({
                'url': "{{ URL::to('get_services') }}",
                'method': 'GET',
                'module': 'services',
                'datatype': "html",
            });
        }

        if (pathname == '/user') {
            getAjaxData({
                'role': 3,
                'url': "{{ URL::to('get_users') }}",
                'method': 'POST',
                'module': 'users',
                'datatype': "html",
                'paginate': 10,
            });
        }

        $(".OnlyNumbers").keydown(function(event) {
            if (!(event.keyCode == 8 // backspace
                    ||
                    event.keyCode == 9 // tab
                    ||
                    event.keyCode == 17 // ctrl
                    ||
                    event.keyCode == 46 // delete
                    ||
                    (event.keyCode >= 35 && event.keyCode <= 40) // arrow keys/home/end
                    ||
                    (event.keyCode >= 48 && event.keyCode <= 57) // numbers on keyboard
                    ||
                    (event.keyCode >= 96 && event.keyCode <= 105) // number on keypad
                    ||
                    (event.keyCode == 65 && prevKey == 17 && prevControl == event.currentTarget.id)
                ) // ctrl + a, on same control
            ) {
                event.preventDefault(); // Prevent character input
            } else {
                prevKey = event.keyCode;
                prevControl = event.currentTarget.id;
            }
        });

        $(document).on('change', '#select2-services', function(event) {
            var service_id = $(this).val();

            $('#select2-posts option[value="0"]').prop('selected', true);
            $("#select2-posts").trigger('change.select2');

            getAjaxData({
                'service_id': service_id,
                'url': "{{ URL::to('get_posts') }}",
                'method': 'POST',
                'module': 'posts',
                'datatype': "json",
            });

            getAjaxData({
                'service_id': service_id,
                'url': "{{ URL::to('get_bids') }}",
                'method': 'POST',
                'module': 'bids',
                'datatype': "html",
                'paginate': 10,
            });

        });

        $(document).on('change', '#select2-posts', function(event) {
            var post_id = $(this).val();
            var service_id = $('#select2-services').val();

            getAjaxData({
                'post_id': post_id,
                'service_id': service_id,
                'url': "{{ URL::to('get_bids') }}",
                'method': 'POST',
                'module': 'bids',
                'datatype': "html",
                'paginate': 10,
            });
        });

        $(document).on('change', '#select2-roles', function(event) {
            var role_id = $(this).val();
            var account_status = $('#select2-account-status').val();

            getAjaxData({
                'role': role_id,
                'active_status': account_status,
                'url': "{{ URL::to('get_users') }}",
                'method': 'POST',
                'module': 'users',
                'datatype': "html",
                'paginate': 10,
            });
        });

        $(document).on('change', '#select2-account-status', function(event) {
            var role_id = $('#select2-roles').val();
            var account_status = $(this).val();

            getAjaxData({
                'role': role_id,
                'active_status': account_status,
                'url': "{{ URL::to('get_users') }}",
                'method': 'POST',
                'module': 'users',
                'datatype': "html",
                'paginate': 10,
            });
        });

        $(document).on('click', '.bids_links .pagination a', function(event) {
            event.preventDefault();

            var page = $(this).attr('href').split('page=')[1];
            var post_id = $('#select2-posts').val();
            var service_id = $('#select2-services').val();

            getAjaxData({
                'post_id': post_id,
                'service_id': service_id,
                'url': "{{ URL::to('get_bids') }}",
                'method': 'POST',
                'module': 'bids',
                'datatype': "html",
                'paginate': 10,
                'page': page,
            });
        });

        $(document).on('click', '.users_links .pagination a', function(event) {
            event.preventDefault();

            var page = $(this).attr('href').split('page=')[1];
            var role_id = $('#select2-roles').val();
            var account_status = $('#select2-account-status').val();

            getAjaxData({
                'role': role_id,
                'active_status': account_status,
                'url': "{{ URL::to('get_users') }}",
                'method': 'POST',
                'module': 'users',
                'datatype': "html",
                'paginate': 10,
                'page': page,
            });
        });
    });

    function getAjaxData(data) {

        // console.log(data);

        // if ("service_id" in data && data["service_id"] !== null && data["service_id"] != '' && data["service_id"] != null)
        //     var service_id = data["service_id"];

        if (data["method"] == "POST") {
            data["_token"] = "{{ csrf_token() }}";
        }

        jQuery.ajax({
            url: data['url'],
            data: data,
            method: data['method'],
            dataType: data['datatype'],
            success: function(response) {

                if (data['module'] == "posts") {
                    $('#select2-posts').html('');
                    $('#select2-posts').append('<option value="0"> ---- Choose Post ---- </option>');

                    $.each(response['data'], function(index, val) {
                        $('#select2-posts').append('<option value="' + this.id + '"> ' + this
                            .title + ' </option>');
                    });
                } else if (data['module'] == "bids") {
                    $("#all_bids").empty().html(response);
                } else if (data['module'] == "services") {
                    $("#all_services").empty().html(response);
                    $("#select2-services").select2();
                } else if (data['module'] == "users") {
                    $("#all_users").empty().html(response);
                }
            }
        });
    }

    function stripeResponseHandler(status, response) {

        var $form = $(".require-validation");

        if (response.error) {
            $('.card_error').show();
            $('.error')
                .removeClass('hide')
                .find('.alert')
                .text(response.error.message);
        } else {
            /* token contains id, last4, and card type */
            var token = response['id'];
            $form.find('input[type=text]').empty();
            $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
            $form.get(0).submit();
            //   $('.css_loader2').fadeIn();
            $("#billing_btn").prop("disabled", true);
            $("#billing_btn").html('Begin Training <i class="fa fa-spinner fa-pulse"></i>');
            PaymentBillingForm();
        }
    }

    function PaymentBillingForm() {

        // $("#billingForm").on('submit', function() {
        // event.preventDefault();
        var form = $('#billingForm')[0];



        var data = new FormData(form);


        $("#confirmModelPayment_new_btn_yes").html('Confirm <i class="fa fa-spinner fa-pulse"></i>');
        $("#confirmModelPayment_new_btn_yes").prop("disabled", true);
        $('#confirmModelPayment_btn_back').hide();

        $(".success_message").html("<b>Success: </b>").hide();
        $(".error_message").html("<b>Success: </b>").hide();

        $.ajax({
            type: "POST",
            url: "{{ URL::to('payment') }}",
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            // timeout: 600000,
            dataType: 'json',
            beforeSend: function() {
                $(document).find('span.error-text').text('');
            },
            success: function(data) {

                // $('.css_loader2').fadeOut();
                // $("#result").text(data);
                // console.log(data);
                $('span.error-text').text('');
                if (data.status) {
                    // window.location.href = data.redirect_url;
                    startConfetti();
                    setTimeout(function() {
                        stopConfetti();
                    }, 5000);
                    setTimeout(function() {
                        $(".success_message").html("<b>Success: </b> " + data.message).show();
                    }, 1000);

                    setTimeout(function() {
                        $(".success_message").html("<b>Success: </b> " + data.message).show();
                        $('#accessAllModules').show();
                    }, 500);
                    $('#confirmModelPayment_new_btn_yes').hide();

                } else {
                    $.each(data.error, function(prefix, val) {
                        $('span.' + prefix + '-error').text(val[0]);
                    });
                    if (data.message) {
                        $(".error_message").html("<b>Sorry: </b> " + data.message).show();
                    }
                }
                $("#confirmModelPayment_new_btn_yes").html('Confirm');
                $("#confirmModelPayment_new_btn_yes").prop("disabled", false);
                // $("#confirmModelPayment_btn_yes").html('Begin Training');

            },
            error: function(e) {
                // console.log("ERROR : ", e.responseJSON.message);
                $("#confirmModelPayment_new_btn_yes").html('Confirm');
                $("#confirmModelPayment_new_btn_yes").prop("disabled", false);
                // $("#confirmModelPayment_btn_yes").html('Begin Training');
                $(".error_message").html("<b>Sorry: </b> Something went wrong please try again later.")
                    .show();

            }
        });
    }

    function delete_post_assets(id) {

        jQuery.ajax({
            url: "{{ URL::to('post_asset') }}" + '/' + id,
            data: {
                "_token": "{{ csrf_token() }}",
                'id': id
            },
            method: 'DELETE',
            dataType: 'json',
            success: function(data) {
                console.log(data);

                $('#post_asset_' + id).remove();
                // var msg_id = $('#message_id[value="'+chat_id+'"]');
                // msg_id.closest('.MsgSent').remove();
                $("#delModal").modal("hide");
            }
        });
    }

    function delete_posts(id) {

        jQuery.ajax({
            url: "{{ URL::to('post') }}" + '/' + id,
            data: {
                "_token": "{{ csrf_token() }}",
                'id': id
            },
            method: 'DELETE',
            dataType: 'json',
            success: function(data) {
                console.log(data);

                // $('#post_'+id).remove();
                // var msg_id = $('#message_id[value="'+chat_id+'"]');
                // msg_id.closest('.MsgSent').remove();
                $("#delModal").modal("hide");
            }
        });
    }
    </script>

    <script
        src="https://maps.googleapis.com/maps/api/js?key={{Config::get('constants.google_map_api')}}&callback=initAutocomplete&libraries=places&v=weekly"
        async></script>

    @yield('scripts')
</body>
<!-- END: Body-->

</html>