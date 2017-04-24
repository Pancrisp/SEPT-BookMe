<?php

namespace App\Http\Controllers;


use App\Booking;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BookingController
{
    public function getBookingsByBusiness(Request $request)
    {
        $businessID = $request['id'];
        $today = Carbon::now()->toDateString();

        $allBookings = Booking::join('customers', 'bookings.customer_id', 'customers.customer_id')
            ->where('bookings.business_id', $businessID)
            ->where('bookings.date', '>=', $today)
            ->orderBy('bookings.date')
            ->orderBy('bookings.start_time')
            ->get();

        $newBookings = Booking::join('customers', 'bookings.customer_id', 'customers.customer_id')
            ->where('bookings.business_id', $businessID)
            ->whereDate('bookings.created_at', $today)
            ->orderBy('bookings.date')
            ->orderBy('bookings.start_time')
            ->get();

        return view('bookingSummary', compact('allBookings', 'newBookings', 'businessID'));
    }

}