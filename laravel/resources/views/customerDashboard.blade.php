@extends('layouts.template')

@section('title')
    Booking App
@endsection

@section('content')
    @include('includes.return')
    
    <div class="dashboard">
        <div id="greeting">Hello, {{ $user['customer_name'] }}!</div>
        <h2>Please fill out the form below to make a booking with us</h2>

        <form action="/book" method="post">
            {{ csrf_field() }}
            <input id="'customer" name="customer" value="{{ $user['customer_id'] }}" hidden>

            <!-- displays a drop down list of businesses -->
            <select id="business" name="business" required>
                <option value="" selected disabled>Find a Place</option>
                <div class="error">{{ $errors->first('business') }}</div>
                @foreach($businesses as $business)
                    <option value="{{ $business['business_id'] }}">{{ $business['business_name'] }}</option>
                @endforeach
            </select>

            <!-- displays a calendar for date picking -->
            <label for="date">Date</label>
            <input id="roster-date" type="text" placeholder="Select date" value="">
            <input id="dateHidden" type="hidden" name="date" value="">

            <button type="submit">Next</button>
        </form>
    </div>
@endsection

@section('pageSpecificJs')
    <script src="{{ asset('js/form.js') }}"></script>
@endsection
