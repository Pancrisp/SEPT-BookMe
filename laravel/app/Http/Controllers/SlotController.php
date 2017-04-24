<?php

namespace App\Http\Controllers;


use App\Slot;
use Illuminate\Http\Request;

class SlotController
{
    /**
     * return all slots for a selected date and business
     *
     * @param Request $request
     */
    public function getSlotsByDate(Request $request)
    {
        $bookings = Slot::join('bookings', 'bookings.booking_id', 'slots.booking_id')
            ->where('bookings.business_id', $request['id'])
            ->where('bookings.date', $request['date'])
            ->get();

        print_r(json_encode($bookings));
    }

}