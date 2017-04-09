<?php

namespace App\Http\Controllers;


use App\Booking;
use Carbon\Carbon;
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

    public function getBookingsByBusiness(Request $request)
    {
        $businessID = $request['id'];
        $today = Carbon::now()->toDateString();

        $allBookings = Booking::join('customers', 'bookings.customer_id', 'customers.customer_id')
            ->where('bookings.business_id', $businessID)
            ->where('bookings.date', '>=', $today)
            ->get();

        $newBookings = Booking::join('customers', 'bookings.customer_id', 'customers.customer_id')
            ->where('bookings.business_id', $businessID)
            ->whereDate('bookings.created_at', $today)
            ->get();

        return view('bookingSummary', compact('allBookings', 'newBookings'));
    }

}