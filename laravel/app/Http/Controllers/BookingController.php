<?php

namespace App\Http\Controllers;


use App\Booking;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class BookingController
{
    /**
     * show booking summary for certain business
     * only accessible when logged in
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showBookingSummary(Request $request)
    {
        // Checking if the session is set and business ID is set
        if (! $request->session()->has('user') || ! isset($request['id'])) { return Redirect::to('/'); }

        // get business ID from request sent
        $businessID = $request['id'];

        // get today's date using Carbon
        $today = Carbon::now()->toDateString();

        // get all bookings with customer details for this business from today onwards
        $allBookings = Booking::join('customers', 'bookings.customer_id', 'customers.customer_id')
            ->where('bookings.business_id', $businessID)
            ->where('bookings.date', '>=', $today)
            ->orderBy('bookings.date')
            ->orderBy('bookings.start_time')
            ->get();

        // get new bookings with customer details for this business made today
        $newBookings = Booking::join('customers', 'bookings.customer_id', 'customers.customer_id')
            ->where('bookings.business_id', $businessID)
            ->whereDate('bookings.created_at', $today)
            ->orderBy('bookings.date')
            ->orderBy('bookings.start_time')
            ->get();

        // return the view with data required
        return view('bookingSummary', compact('allBookings', 'newBookings', 'businessID'));
    }
}
