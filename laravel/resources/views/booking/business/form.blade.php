@extends('layouts.template')

@section('title')
    New Booking for Customer - BookMe
@endsection

@section('content')
    @include('includes.return')

    <div class="dashboard">
        <h2>Fill out the form below to make a booking for customer</h2>

        <form action="/book" method="post">
            {{ csrf_field() }}
            <input id="business" name="business" value="{{ \Illuminate\Support\Facades\Auth::user()['foreign_id'] }}" hidden>

            <!-- input field to fill up customer's username -->
            <div class="error">{{ $errors->first('username') }}</div>
            <input type="text" name="username" placeholder="Customer username" value="{!! old('username') !!}">

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
