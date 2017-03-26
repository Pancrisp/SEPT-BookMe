<?php

namespace App\Http\Controllers;


use App\Booking;
use Illuminate\Http\Request;

class BookingController
{
    /*
     * return all bookings for a selected date
     */
    public function getBookingsByDate(Request $request)
    {
        $data = $request->all();

        $bookings = Booking::where('date', $data['date'])->get();

        return $bookings;
    }

    public function getBookingsByBusiness(Request $request)
    {
        $businessID = 1; //atm there is only one business

        $bookings = Booking::where('business_id', $businessID)->latest()->get();

        return view('viewAllBookings', compact($bookings));
    }

}