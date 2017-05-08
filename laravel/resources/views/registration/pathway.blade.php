@extends('layouts.template')

@section('title')
    Sign up for an account - Booking App
@endsection

@section('content')

    <div class='box'>
        <h1>Booking App</h1>

        <div class="reg-selection">
            <div class="card-register">
                <a class="card-link" href="/register/customer">
                    <div class="card-bg">
                        <img src="/img/customer-bg.png" alt="">
                    </div>
                    <div class="card-info">
                        <h2>Customer</h2>
                        <ul>
                            <li><p>Place bookings and make appointments</p></li>
                            <li><p>View booking summary and history</p></li>
                        </ul>
                    </div>
                </a>
            </div>
            <div class="card-register">
                <a class="card-link" href="/register/business">
                    <div class="card-bg">
                        <img src="/img/business-bg.jpg" alt="">
                    </div>
                    <div class="card-info">
                        <h2>Business Owner</h2>
                        <ul>
                            <li><p>Manage your online bookings</p></li>
                            <li><p>Arrange working schedules for your staff</p></li>
                        </ul>
                    </div>
                </a>
            </div>
        </div>

        <div class="login">
            <p>Already have an account? <a href="/">Sign in here</a></p>
        </div>
    </div>

@endsection
