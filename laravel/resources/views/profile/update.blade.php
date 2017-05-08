@extends('layouts.template')

@section('title')
    Profile Settings - Booking App
@endsection

@section('content')

    <div class='box'>
        <form action="/profile/update/submit" method="post">
            <!--
            form will update:
            - customer e-mail
            - customer phone number
            - customer address
            -->
        </form>
    </div>

@endsection
