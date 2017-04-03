@extends('layouts.template')

@section('title')
    Restaurant Booking App
@endsection

@section('content')
    @include('includes.header')

    <div class="box">
        <h1>New Staff Registration</h1>
        <form class="" action="/addstaff" method="post">
            {{ csrf_field() }}
            <input type="text" name="business_id" value="{{ $data['id'] }}" hidden>
            <input type="text" name="fullname" placeholder="Full Name" value="{!! old('fullname') !!}">
            <input type="number" name="taxfileno" placeholder="TFN" value="{!! old('TFN') !!}">
            <input type="number" name="phone" placeholder="Contact No" value="{!! old('phone') !!}">
            <input type="text" name="role" placeholder="Role" value="{!! old('role') !!}">
            <input type="number" name="availability" placeholder="Availability" value="{!! old('availability') !!}">

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
