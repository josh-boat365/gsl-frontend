<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function userProfile()
    {
        //return view('pages.settings.profile.view-profile', ['data' => User::where('uuid', $uuid)->first()]);
        return view('workspace.profile.view-profile');
    }

    public function updateProfile(Request $request)
    {
        //return$request;
       $request->validate([
            '_token' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|string|email|max:255',
            'phone' => 'required|numeric'
        ]);

       $formData = [
                'first_name'=>$request->first_name,
                'last_name'=>$request->last_name,
                'email'=>$request->email,
                'phone'=>$request->phone
            ];

       $getUpdateInfo = User::whereId(Auth::user()->id)->update($formData);
       if($getUpdateInfo)
       {
            Toastr::success('User profile updated successfully', 'Profile Update', ['positionClass' => 'toast-bottom-right']);
       }

       return redirect()->back();

    }

    public function updatePassword(Request $request)
    {

        //return $request;
        $request->validate([
            '_token' => 'required',
            'password' => 'required|nullable|required_with:password_confirmation|string|confirmed|min:6',
            'current_password' => 'required',
        ]);

        $getUserInfo = User::where('id',Auth::user()->id)->first();

        if ( !Hash::check($request->current_password, $getUserInfo->password) ) {
            $validator = \Validator::make([], []);
            $validator->errors()->add('current_password', 'Your current password is incorrect.');
            throw new \Illuminate\Validation\ValidationException($validator);
        }

        $formData = [
            'password'=>Hash::make($request->password)
         ];

        $getUpdateInfo = User::whereId(Auth::user()->id)->update($formData);
        if($getUpdateInfo)
        {
            Toastr::success('User password updated successfully', 'Password Update', ['positionClass' => 'toast-bottom-right']);
        }
            return redirect()->back();
    }
}
