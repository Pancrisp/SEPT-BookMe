@extends('layouts.template')

@section('title')
    Add new staff - BookMe
@endsection

@section('nav')
    @include('nav.dashboard')
    @include('nav.logout')
@endsection

@section('content')

    <div class="box">
        <h1>New Employee Registration</h1>

        <div class="success">{{ $errors->first('result') }}</div>

        <form action="/staff/add/submit" method="post">
            {{ csrf_field() }}
            <input type="text" name="business_id" value="{{ $businessID }}" hidden>
            <div class="error">{{ $errors->first('fullname') }}</div>
            <input type="text" name="fullname" placeholder="Full Name" value="{!! old('fullname') !!}" required>
            <div class="error">{{ $errors->first('taxfileno') }}</div>
            <input type="text" name="taxfileno" placeholder="TFN" value="{!! old('taxfileno') !!}" required>
            <div class="error">{{ $errors->first('phone') }}</div>
            <input type="text" name="phone" placeholder="Contact No" value="{!! old('phone') !!}" required>
            <div class="error">{{ $errors->first('activity') }}</div>

            <select name="activity" placeholder="Activity" required>
                <option value="" selected disabled>Select Activity in Charge</option>
                @foreach($activities as $activity)
                <option value="{{ $activity['activity_id'] }}">{{ $activity['activity_name'] }}</option>
                @endforeach
            </select>

            <div class="error">{{ $errors->first('availability') }}</div>
            <h4>Available working days</h4>
            <div class="flex-container">
                <div class="flex">
                    <label><input type="checkbox" name="availability[]" value="Mon">Monday</label>
                    <label><input type="checkbox" name="availability[]" value="Tue">Tuesday</label>
                    <label><input type="checkbox" name="availability[]" value="Wed">Wednesday</label>
                    <label><input type="checkbox" name="availability[]" value="Thu">Thursday</label>
                    <label><input type="checkbox" name="availability[]" value="Fri">Friday</label>
                </div>
                <div class="flex">
                    <label><input type="checkbox" name="availability[]" value="Sat">Saturday</label>
                    <label><input type="checkbox" name="availability[]" value="Sun">Sunday</label>
                </div>
            </div>

            <button type="submit" name="submit">Submit</button>
        </form>

    </div>

@endsection
