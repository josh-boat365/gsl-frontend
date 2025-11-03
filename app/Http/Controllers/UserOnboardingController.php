<?php

namespace App\Http\Controllers;

use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserOnboardingController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function create()
    {
        // Branch code map
        $branch_codes = [
            0 => 'HEAD OFFICE',
            5 => 'KEJETIA',
            3 => 'ABOSSEY OKAI',
            4 => 'ACCRA NEWTOWN',
            2 => 'MAKOLA',
            7 => 'MADINA',
            8 => 'ASHAIMAN',
            9 => 'TAKORADI MARKET CIRCLE',
            10 => 'SUAME',
            1 => 'MILE 7',
            6 => 'KASOA NEW MARKET',
            11 => 'TEMA COMMUNITY 1',
            12 => 'ADUM',
            13 => 'TAMALE',
            14 => 'ODORKOR',
            15 => 'EAST LEGON',
            16 => 'SUNYANI',
            18 => 'EJISU',
            17 => 'AGONA SWEDRU BRANCH',
        ];
        $roles = [
            2 => 'CSO',
            3 => 'Agent',
            4 => 'Business',
            5 => 'Manager',
            7 => 'Admin',
        ];
        return view('workspace.profile.onboard-user', compact('branch_codes', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required',
            'role_id' => 'required',
            'branch_code' => 'required',
            'email' => 'required|email',
            'gender' => 'required',
            'date_of_birth' => 'required|date',
            'staff_number' => 'required',
            'status' => 'required',
            'password' => 'required',
            'user_group' => 'required',
        ]);

        $PATH = null;
        if ($request->hasFile('sig')) {
            $PATH = $request->file('sig')->store('attachment');
            //$file = $request->file('sig');
            //$sigPath = $file->store('signatures', 'public');
        }

        $user = new User();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->phone = $request->phone;
        $user->role_id = $request->role_id;
        $user->branch_code = $request->branch_code;
        $user->sig = $PATH;
        $user->email = $request->email;
        $user->gender = $request->gender;
        $user->date_of_birth = $request->date_of_birth;
        $user->staff_number = $request->staff_number;
        $user->status = $request->status;
        $user->user_group = $request->user_group;
        $user->password = Hash::make($request->password);
        $user->created_by = Auth::id();
        $user->save();

        Toastr::success('User onboarded successfully', 'Success', ['positionClass' => 'toast-bottom-right']);
        return redirect()->route('user.onboard.create');
    }
}
