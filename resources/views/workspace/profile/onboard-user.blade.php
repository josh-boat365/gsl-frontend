@extends('layouts.dashboard')
@section('content')
<div class="container">
    <h2>Onboard New User</h2>
    <form action="{{ route('user.onboard.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="first_name">First Name</label>
            <input type="text" class="form-control" id="first_name" name="first_name" required>
        </div>
        <div class="form-group">
            <label for="last_name">Last Name</label>
            <input type="text" class="form-control" id="last_name" name="last_name" required>
        </div>
        <div class="form-group">
            <label for="phone">Phone</label>
            <input type="text" class="form-control" id="phone" name="phone" maxlength="10" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="role_id">Role</label>
            <select class="form-control" id="role_id" name="role_id" required>
                <option value="">Select Role</option>
                @foreach($roles as $id => $role)
                    <option value="{{ $id }}">{{ $role }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="branch_code">Branch</label>
            <select class="form-control" id="branch_code" name="branch_code" required>
                <option value="">Select Branch</option>
                @foreach($branch_codes as $code => $name)
                    <option value="{{ $code }}">{{ $name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="sig">Signature Image</label>
            <input type="file" class="form-control" id="sig" name="sig" accept="image/*">
        </div>
        <div class="form-group">
            <label for="gender">Gender</label>
            <select class="form-control" id="gender" name="gender" required>
                <option value="">Select Gender</option>
                <option value="M">Male</option>
                <option value="F">Female</option>
            </select>
        </div>
        <div class="form-group">
            <label for="date_of_birth">Date of Birth</label>
            <input type="date" class="form-control" id="date_of_birth" name="date_of_birth">
        </div>
        <div class="form-group">
            <label for="staff_number">Staff Number</label>
            <input type="number" class="form-control" id="staff_number" name="staff_number" required>
        </div>
        <div class="form-group">
            <label for="status">Status</label>
            <select class="form-control" id="status" name="status" required>
                <option value="ACTIVE">ACTIVE</option>
                <option value="LOCKED">LOCKED</option>
                <option value="DISABLED">DISABLED</option>
            </select>
        </div>
        <div class="form-group">
            <label for="user_group">User Group</label>
            <select class="form-control" id="user_group" name="user_group" required>
                <option value="external">External</option>
                <option value="internal">Internal</option>
            </select>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <div class="form-group">
            <label for="password_confirmation">Confirm Password</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
        </div>
        <button type="submit" class="btn btn-primary">Onboard User</button>
    </form>
</div>
@endsection
