@extends('layouts.template')

@section('title')
    Restaurant Booking App
@endsection

@section('content')
    @include('includes.header')
    <div class="dashboard">
        <h1>View available booking times on</h1>
        <form class="form-booking" action="" method="post">
            <div class="date picker">
                <select id='date' name="booking-date">
                    <option value="Monday">Monday</option>
                    <option value="Tuesday">Tuesday</option>
                    <option value="Wednesday">Wednesday</option>
                    <option value="Thursday">Thursday</option>
                    <option value="Friday">Friday</option>
                </select>
            </div>
            <div class="timeslot picker">
                <select id='time' name="booking-time">
                    <option value="09:00">9:00 AM</option>
                    <option value="10:00">10:00 AM</option>
                    <option value="11:00">11:00 AM</option>
                    <option value="12:00">12:00 PM</option>
                    <option value="13:00">1:00 PM</option>
                    <option value="14:00">2:00 PM</option>
                    <option value="15:00">3:00 PM</option>
                    <option value="16:00">4:00 PM</option>
                    <option value="17:00">5:00 PM</option>
                </select>
            </div>

            <div id="sessions">

            </div>
        </form>
    </div>

@endsection

@section('pageSpecificJs')
    <script src="{{ asset('js/view-slots.js') }}"></script>
@endsection
