@extends('layouts.template')

@section('title')
    Restaurant Booking App
@endsection

@section('content')
    @include('includes.header')

    <div class="box">
        <h1>New Staff Registration</h1>
        <form class="" action="" method="post">
            <input type="text" name="name" placeholder="Name">
            <input type="number" name="mobile" placeholder="Contact No">
            <input type="text" name="role" placeholder="Role">

            <button type="submit" name="submit">Submit</button>
        </form>
    </div>

@endsection
