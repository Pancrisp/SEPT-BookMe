<?php

namespace App\Http\Controllers;


use App\Activity;
use App\Business;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class ActivityController
{
    public function businessActivity()
    {

    }

    public function registerBusinessActivityForm()
    {
        // redirect to login page if not authenticated, or incorrect user type
        if ( ! Auth::check() || Auth::user()['user_type'] != 'business')
            return Redirect::to('/login');

        // get auth and business ID
        $auth = Auth::user();
        $businessID = $auth['foreign_id'];

        // get business and slot period from DB
        $business = Business::find($businessID);
        $slotPeriod = $business->slot_period;

        return view('business.setup.activity', compact('business', 'slotPeriod'));
    }

    public function registerBusinessActivity(Request $request, $action)
    {
        // get auth and business ID
        $auth = Auth::user();
        $businessID = $auth['foreign_id'];
        $request['business_id'] = $businessID;

        // this validates the data
        $validator = $this->registerValidator($request->all());

        // when validation fails, redirect back with input and error messages
        if($validator->fails()) {
            return Redirect::back()
                ->withInput()
                ->withErrors($validator);
        }

        // redirect according to action after saving to DB
        if($this->save($request->all())) {
            if($action == 'next')
                return Redirect::back()
                    ->withErrors(array('result' => 'Service added successfully! Please enter the next one:'));
            elseif($action == 'done')
            {
                // set business ready and redirect back to dashboard
                $this->setBusinessReady($businessID);
                return Redirect::to('/');
            }
        }
    }

    public function updateBusinessActivity()
    {

    }

    private function registerValidator(array $data)
    {
        return Validator::make($data, [
            'slot_period'   => 'required',
            'num_of_slots'  => 'required|numeric',
            'activity_name' => 'required'
        ]);
    }

    private function updateValidator(array $data)
    {

    }

    private function save(array $data)
    {
        // get business from DB and save slot period
        $business = Business::find($data['business_id']);
        $business->slot_period = $data['slot_period'];
        $business->save();

        // create and save activity to DB
        return Activity::create([
            'activity_name' => $data['activity_name'],
            'num_of_slots'  => $data['num_of_slots'],
            'business_id'   => $data['business_id']
        ]);
    }

    private function update(array $data)
    {

    }

    private function setBusinessReady($id)
    {
        // get business from DB
        $business = Business::find($id);

        // set business ready and save to DB
        $business->ready = true;
        $business->save();
    }
}