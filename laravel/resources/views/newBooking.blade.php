@extends('layouts.template')

@section('title')
    Booking App - Make a New Booking for Customer
@endsection

@section('content')

    <div class="box">
        <h1>Place an order for a customer</h1>

        <form action="" method="post">
            <label for="">Customer username</label>
            <input id="username" type="text" name="username">
            <label for="date">Date</label>
            <input id="date" type="text" name="date">
            <label for="time">Time</label>
            <input id="time" type="time" name="time" min="09:00" max="18:00" step="1800" placeholder="09:00">

            <!-- displays a drop down list of available services by this business -->
            <label for="services">Service required</label>
            <select id="services" name="services">
                <option value="" selected disabled>Choose service</option>
            </select>

            <!-- allows business owners or customers to select a preferred personnel -->
            <label for="employee">Choose preferred employee</label>
            <select id="employee" name="employee_id">
                <option value="" selected disabled>Choose employee</option>
                <!-- lists all available employees -->
                @foreach($employees as $employee)
                    <option value="{{ $employee['employee_id'] }}">{{ $employee['employee_name'] }}</option>
                @endforeach
            </select>

            <button type="submit">Make Booking</button>
        </form>
    </div>

@endsection

@section('pageSpecificJs')
    <script src="{{ asset('js/form.js') }}"></script>
@endsection
