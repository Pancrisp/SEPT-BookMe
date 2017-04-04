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
            <input type="text" name="fullname" placeholder="Full Name" value="{!! old('fullname') !!}" required>
            <input type="text" name="taxfileno" placeholder="TFN" value="{!! old('TFN') !!}" required>
            <input type="text" name="phone" placeholder="Contact No" value="{!! old('phone') !!}" required>
            <select name="role" placeholder="Role" required>
                <option value="" selected disabled>Select role</option>
                <option value="{!! old('role') !!}">Waiter</option>
            </select>
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

            <button type="submit" name="submit">Submit</button>
        </form>
    </div>

    <div class="col-lg-4">
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

@endsection
