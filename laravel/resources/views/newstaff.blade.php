@extends('layouts.template')

@section('title')
    Restaurant Booking App
@endsection

@section('content')
    @include('includes.header')

    <div class="box">
        <h1>New Employee Registration</h1>
        <form action="/addstaff" method="post">
            {{ csrf_field() }}

            <input type="text" name="business_id" value="{{ $data['id'] }}" hidden>
            <div class="error">{{ $errors->first('fullname') }}</div>
            <input type="text" name="fullname" placeholder="Full Name" value="{!! old('fullname') !!}" required>
            <div class="error">{{ $errors->first('taxfileno') }}</div>
            <input type="text" name="taxfileno" placeholder="TFN" value="{!! old('TFN') !!}" required>
            <div class="error">{{ $errors->first('phone') }}</div>
            <input type="text" name="phone" placeholder="Contact No" value="{!! old('phone') !!}" required>
            <div class="error">{{ $errors->first('role') }}</div>
            <select name="role" placeholder="Role" required>
                <option value="" selected disabled>Select role</option>
                <option value="Waiter">Waiter</option>
            </select>
            <div class="error">{{ $errors->first('availability') }}</div>
            <h4>Available working days</h4>
            <div class="days-selection">
                <div class="days">
                    <label><input type="checkbox" name="availability[]" value="Mon">Monday</label>
                    <label><input type="checkbox" name="availability[]" value="Tue">Tuesday</label>
                    <label><input type="checkbox" name="availability[]" value="Wed">Wednesday</label>
                    <label><input type="checkbox" name="availability[]" value="Thu">Thursday</label>
                    <label><input type="checkbox" name="availability[]" value="Fri">Friday</label>
                </div>
                <div class="days">
                    <label><input type="checkbox" name="availability[]" value="Sat">Saturday</label>
                    <label><input type="checkbox" name="availability[]" value="Sun">Sunday</label>
                </div>
            </div>

            <div class="error">{{ $errors->first('result') }}</div>
            <button type="submit" name="submit">Submit</button>
        </form>
    </div>

@endsection
