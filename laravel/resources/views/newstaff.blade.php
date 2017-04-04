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
            <input type="text" name="fullname" placeholder="Full Name" value="{!! old('fullname') !!}">
            <input type="text" name="taxfileno" placeholder="TFN" value="{!! old('TFN') !!}">
            <input type="text" name="phone" placeholder="Contact No" value="{!! old('phone') !!}">
            <select name="role" placeholder="Role">
                <option value="" selected disabled>Select role</option>
                <option value="{!! old('role') !!}">Waiter</option>
            </select>
            <h4>Available working days</h4>
            <div class="days-selection">
                <div class="days">
                    <label><input type="checkbox" name="Monday" value="Mon">Monday</label>
                    <label><input type="checkbox" name="Tuesday" value="Tue">Tuesday</label>
                    <label><input type="checkbox" name="Wednesday" value="wed">Wednesday</label>
                    <label><input type="checkbox" name="Thursday" value="Thu">Thursday</label>
                </div>
                <div class="days">
                    <label><input type="checkbox" name="Friday" value="Fri">Friday</label>
                    <label><input type="checkbox" name="Saturday" value="Sat">Saturday</label>
                    <label><input type="checkbox" name="Sunday" value="Sun">Sunday</label>
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
