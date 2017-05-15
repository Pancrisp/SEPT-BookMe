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
    /**
     * display all the business services
     * allowing update specific service
     * also add new service
     * only accessible by business owner
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function businessActivity()
    {
        // redirect to login page if not authenticated, or incorrect user type
        if ( ! Auth::check() || Auth::user()['user_type'] != 'business')
            return Redirect::to('/login');

        // get auth and business
        $auth = Auth::user();
        $businessID = $auth['foreign_id'];
        $business = Business::find($businessID);

        // get all activities of this business
        $activities
            = Activity::where('business_id', $businessID)
            ->get();

        return view('business.display.activity', compact('activities', 'business'));
    }

    /**
     * to show the business service registration form when
     * 1. business is 1st registered
     * 2. adding new service
     * only accessible by business owner
     *
     * @param $action
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addBusinessActivityForm($action)
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

        return view('business.'.$action.'.activity', compact('business', 'slotPeriod'));
    }

    /**
     * called when business service registration form is submitted
     * validate incoming input
     * redirect differently according to action chosen by user
     *
     * @param Request $request
     * @param $action
     * @return mixed
     */
    public function addBusinessActivity(Request $request, $action)
    {
        // get auth and business ID
        $auth = Auth::user();
        $businessID = $auth['foreign_id'];
        $request['business_id'] = $businessID;

        // this validates the data
        $validator = $this->validator($request->all());

        // when validation fails, redirect back with input and error messages
        if($validator->fails())
        {
            return Redirect::back()
                ->withInput()
                ->withErrors($validator);
        }

        // redirect according to action after saving to DB
        if($this->save($request->all()))
        {
            if($action == 'next')
                return Redirect::back()
                    ->withErrors(array('result' => 'Service added successfully! Please enter the next one:'));
            elseif($action == 'done')
            {
                // set business ready and redirect back to dashboard
                $this->setBusinessReady($businessID);
                return Redirect::to('/');
            }
            elseif($action == 'add')
                return Redirect::to('/business/activity');
        }
    }

    /**
     * accessed when click update or delete specific service
     * only accessible by business owner
     * return the update form or delete form
     *
     * @param $action
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function changeBusinessActivityForm($action, $id)
    {
        // redirect to login page if not authenticated, or incorrect user type
        if ( ! Auth::check() || Auth::user()['user_type'] != 'business')
            return Redirect::to('/login');

        // get auth and business
        $auth = Auth::user();
        $businessID = $auth['foreign_id'];
        $business = Business::find($businessID);

        // get the activity from DB
        $activity = Activity::find($id);

        return view('business.'.$action.'.activity', compact('activity', 'business'));
    }

    /**
     * called when update service form is submitted
     * validate inputs
     * go back to the activity page when updated successfully
     *
     * @param Request $request
     * @return mixed
     */
    public function updateBusinessActivity(Request $request)
    {
        // get auth and business ID
        $auth = Auth::user();
        $businessID = $auth['foreign_id'];
        $request['business_id'] = $businessID;

        // this validates the data
        $validator = $this->validator($request->all());

        // when validation fails, redirect back with input and error messages
        if($validator->fails())
        {
            return Redirect::back()
                ->withInput()
                ->withErrors($validator);
        }

        // update DB and redirect back to the activity page
        if($this->update($request->all()))
            return Redirect::to('/business/activity');
    }

    public function deleteBusinessActivity(Request $request)
    {
        // retrieve activity id from form submitted
        $activityID = $request['activity'];

        // remove this activity from DB
        Activity::find($activityID)->delete();

        // redirect back to activity page
        return Redirect::to('/business/activity');
    }

    /**
     * validate incoming business service
     * 1. registration data
     * 2. updating data
     * 3. add new data
     *
     * @param array $data
     * @return Validator
     */
    private function validator(array $data)
    {
        return Validator::make($data, [
            'slot_period'   => 'required',
            'num_of_slots'  => 'required|numeric',
            'activity_name' => 'required'
        ]);
    }

    /**
     * update slot period in businesses
     * and create new activity
     *
     * @param array $data
     * @return mixed
     */
    private function save(array $data)
    {
        $this->updateSlotPeriod($data);

        // create and save activity to DB
        return Activity::create([
            'activity_name' => $data['activity_name'],
            'num_of_slots'  => $data['num_of_slots'],
            'business_id'   => $data['business_id']
        ]);
    }

    /**
     * update slot period and activity
     *
     * @param array $data
     * @return bool
     */
    private function update(array $data)
    {
        $this->updateSlotPeriod($data);

        // get activity from DB and update data
        $activity = Activity::find($data['activity_id']);
        $activity->activity_name = $data['activity_name'];
        $activity->num_of_slots = $data['num_of_slots'];
        $activity->save();

        return true;
    }

    /**
     * update that the business is ready
     * so when log in next time, will redirect to dashboard straight
     *
     * @param $id
     */
    private function setBusinessReady($id)
    {
        // get business from DB
        $business = Business::find($id);

        // set business ready and save to DB
        $business->ready = true;
        $business->save();
    }

    /**
     * update slot period
     *
     * @param array $data
     */
    private function updateSlotPeriod(array $data)
    {
        // get business from DB and update slot period
        $business = Business::find($data['business_id']);
        $business->slot_period = $data['slot_period'];
        $business->save();
    }
}