<?php

namespace App\Http\Controllers;


use App\Booking;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class BookingController
{
    /**
     * return all bookings for a selected date
     */
    public function getBookingsByDate(Request $request)
    {
        $bookings = Booking::where('business_id', $request['id'])
            ->where('date', $request['date'])
            ->get();

        print_r(json_encode($bookings));
    }

    public function getBookingsByBusiness(Request $request)
    {
        
	// Checking session existence
	if ($request->session()->has('user')) { 
	
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
	else{
		return Redirect::to('/');
	}
    }

}
