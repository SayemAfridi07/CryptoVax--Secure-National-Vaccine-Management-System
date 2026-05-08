@extends('layouts.frontend_app')

@section('title', 'Vaccine Management Home')
@section('app_content')

<section id="hero_section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="row hero_card">
                    <div class="col-md-7">
                        <div class="card">
                            <div class="card-body">
                                <div class="top_part">
                                    <div class="d-flex">
                                        <div class="" style="margin-right: 50px">
                                            <i class="fa fa-users usr_icon"></i>
                                        </div>
                                        <div class="align-self-center">
                                            <strong style="font-size: 34px">Register for Vaccination</strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="bottom_part text-center" style="margin-top: 50px">
                                    <h5>Required Information for Registration</h5>
                                    <div class="items mt-3">
                                        <div class="d-flex justify-content-between">
                                            <div class="mx-3">
                                                <h5><i class="fa fa-contact-card"></i></h5>
                                                <h6>NID</h6>
                                                <i class="fa fa-angle-right"></i>
                                            </div>
                                            <div class="mx-3">
                                                <h5><i class="fa fa-phone"></i></h5>
                                                <h6>PHONE</h6>
                                                <i class="fa fa-angle-right"></i>
                                            </div>
                                            <div class="mx-3">
                                                <h5><i class="fa fa-info-circle"></i></h5>
                                                <h6>INFO</h6>
                                                <i class="fa fa-angle-right"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-5">
                        <div class="card mb-2">
                            <div class="card-body">
                                <div>
                                    <h4>
                                        <i class="fa-solid fa-circle-info mr-5 d-inline-block"></i>
                                        <div class="ml-5 d-inline">
                                            <span><a href="{{ route('front.registration.status') }}">Registration Status</a></span>
                                        </div>
                                    </h4>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-2">
                            <div class="card-body">
                                <div>
                                    <h4>
                                        <i class="fa-solid fa-address-card mr-5 d-inline-block"></i>
                                        <div class="ml-3 d-inline">
                                            <span><a href="{{ route('front.vaccine.card') }}">Vaccine Card Collection</a></span>
                                        </div>
                                    </h4>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-2">
                            <div class="card-body">
                                <div>
                                    <h4>
                                        <i class="fa-solid fa-certificate mr-5 d-inline-block"></i>
                                        <div class="ml-3 d-inline">
                                            <span><a href="{{ route('front.vaccine.certificate') }}">Vaccine Certificate Collection</a></span>
                                        </div>
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="center_section">
    <div class="container">
        <div class="row">
            <div class="col-md-3 mb-2">
                <div class="card text-center">
                    <div class="card-body">
                        <h4>121</h4>
                        <h6>Hotline</h6>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-2">
                <div class="card text-center">
                    <div class="card-body">
                        <h4>123</h4>
                        <h6>National Call Center</h6>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-2">
                <div class="card text-center">
                    <div class="card-body">
                        <h4>963</h4>
                        <h6>Health Portal</h6>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-2">
                <div class="card text-center">
                    <div class="card-body">
                        <h4>9999</h4>
                        <h6>IEDCR</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<hr>

<section id="fourth_section">
    <div class="container">
        <div class="card">
            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-md-11">
                        <h3 class="mb-3 px-2" style="border-left: 5px solid yellow">COVID-19 Vaccine Process</h3>
                        <div class="row mt-5">
                            <div class="col-md-4 text-center mb-2">
                                <img src="{{ asset('img/img1.png') }}" alt="">
                                <h4 class="my-3">Online Registration</h4>
                                <p>First, complete the online registration through this portal by verifying your National ID and phone number.</p>
                            </div>
                            <div class="col-md-4 text-center mb-2">
                                <img src="{{ asset('img/img2.png') }}" alt="">
                                <h4 class="my-3">SMS Notification</h4>
                                <p>After online registration, you'll receive an SMS with the vaccination date and center details after verification.</p>
                            </div>
                            <div class="col-md-4 text-center mb-2">
                                <img src="{{ asset('img/img3.png') }}" alt="">
                                <h4 class="my-3">Receive Vaccine at Center</h4>
                                <p>After receiving the SMS, go to the vaccination center on the specified date with your ID card and consent form to get the vaccine.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="fifth_section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 text-center">
                <div class="row">
                    <div class="col-md-6 align-self-center">
                        <h4 class="my-3"><a href="https://play.google.com/store/apps/details?id=com.codersbucket.surokkha_app&hl=bn&pli=1">Download the App</a></h4>
                    </div>
                    <div class="col-md-6">
                        <img src="{{ asset('img/playstore.png') }}" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="fourth_section">
    <div class="container">
        <div class="card">
            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-md-11">
                        <h3 class="mb-3 px-2" style="border-left: 5px solid yellow">Mujib 100</h3>
                        <div class="row mt-5">
                            <div class="col-md-4 text-center mb-2">
                                <img src="{{ asset('img/mujib.png') }}" alt="" class="w-100">
                                <h4 class="my-3">Mujib 100 Portal</h4>
                            </div>
                            <div class="col-md-4 text-center mb-2 align-self-center">
                                <img src="{{ asset('img/playstore.png') }}" alt="">
                                <h4 class="my-3"><a href="https://play.google.com/store/apps/details?id=com.codersbucket.surokkha_app&hl=bn&pli=1">Android App</a></h4>
                            </div>
                            <div class="col-md-4 text-center mb-2 align-self-center">
                                <img src="{{ asset('img/ios.png') }}" alt="">
                                <h4 class="my-3"><a href="https://surokkha.gov.bd/">iOS App</a></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('css')
    <style>
        /* Animation for Hero Section */
        #hero_section {
            animation: fadeIn 2s ease-in-out;
        }

        @keyframes fadeIn {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }

        .hero_card .card {
            animation: slideUp 1.5s ease-out;
        }

        @keyframes slideUp {
            0% { transform: translateY(50px); opacity: 0; }
            100% { transform: translateY(0); opacity: 1; }
        }

        .card {
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-10px);
        }
    </style>
@endpush
