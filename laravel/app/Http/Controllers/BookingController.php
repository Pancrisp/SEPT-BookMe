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

}