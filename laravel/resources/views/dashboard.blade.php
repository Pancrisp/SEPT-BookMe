@extends('layouts.template')

@section('title')
    Restaurant Booking App
@endsection

@section('content')
    @include('includes.header')
    <div class="">
        Welcome, {{ $names->customer_name }}. This is your dashboard.
    </div>
@endsection
