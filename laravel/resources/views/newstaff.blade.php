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
            <input type="text" name="role" placeholder="Role" value="{!! old('role') !!}">
            <input type="checkbox" id="box-1" name="" value="1">
            <label for="box-1">Monday</label>
            <input type="checkbox" id="box-2" name="" value="2">
            <label for="box-2">Tuesday</label>
            <input type="checkbox" id="box-3" name="" value="3">
            <label for="box-3">Wednesday</label>
            <input type="checkbox" id="box-4" name="" value="4">
            <label for="box-4">Thursday</label>
            <input type="checkbox" id="box-5" name="" value="5">
            <label for="box-5">Friday</label>
            <input type="checkbox" id="box-6" name="" value="6">
            <label for="box-6">Saturday</label>
            <input type="checkbox" id="box-7" name="" value="7">
            <label for="box-7">Sunday</label>

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
