<?php

namespace App\Http\Controllers;


use App\Booking;
use Illuminate\Http\Request;

class BookingController
{
    /**
     * return all bookings for a selected date
     */
    public function getBookingsByDate(Request $request)
    {
        $data = $request->all();

        $bookings = Booking::where('date', $data['date'])->get();

        return $bookings;
    }

    public function getBookingsByBusiness(Request $request, $id)
    {
        $businessID = $id;

        $bookings = Booking::join('customers', 'bookings.customer_id', 'customers.customer_id')
            ->where('bookings.business_id', $businessID)
            ->get();

        return view('bookingSummary', compact('bookings'));
    }

}