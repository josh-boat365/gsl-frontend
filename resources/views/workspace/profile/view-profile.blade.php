@extends('layouts.dashboard')
@section('content')
<div class="row mt-8">
        <div class="col-xl-4 col-lg-4">
            <div class="card box-widget widget-user">
                <div class="widget-user-image mx-auto mt-5 text-center"><img alt="User Avatar" class="rounded-circle" src="{{asset('theme/assets/images/users/16.jpg')}}"></div>
                <div class="card-body text-center mb-md-2">
                    <div class="pro-user">
                        <h3 class="pro-user-username text-dark">{{ Auth::user()->first_name.' '.Auth::user()->last_name  }}</h3>
                        <h6 class="pro-user-desc text-muted">{{ Auth::user()->email }}</h6>
                        <h6 class="pro-user-desc text-muted">{{ Auth::user()->phone }}</h6>
                        <span wire:click="updateProfile()" class="badge badge-primary badge-pill">{{-- {{ Auth::user()->type }} --}}</span>
                        {{-- <a href="profile.html" class="btn btn-primary mt-3">View Profile</a> --}}
                    </div>

                </div>
                <div class="row ml-4 mr-4">
                    <div class="col-sm-12">
                <form method="POST" action="" id="changeBranchForm">
                  @csrf
           <div class="form-group">
                                        
                                            <div class="input-group">        
                <select name="branch" class="branch form-control select2" data-placeholder="Choose one (with optgroup)">

                    @if(isset(Auth::user()->branch_code))
                        <option selected
                                value="{{Auth::user()->branch_code}}">
                            {{getBranchName(Auth::user()->branch_code)->branch_name}}
                        </option>
                        @else
                        <option selected value="">Select Branch</option>

                        @endif
                    @foreach(getBranches() as $branch)
            <option value="{{$branch->branch_code}}">{{$branch->branch_name}}</option>
                        @endforeach


                </select>
                <span class="input-group-append">
                                                    <button class="btn btn-primary" type="submit">Switch</button>
                                                </span>
                                            </div>
                                        </div>

            </form>
            </div>
                </div>
                <div class="card-footer p-0">
                    <div class="row">
                        <div class="col-sm-6 border-right text-center">
                            <div class="description-block p-4">
                                <span class="text-muted">Position</span>
                                <h5 class="description-header mb-1 font-weight-bold">{{ Auth::user()->position }}</h5>

                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="description-block text-center p-4">
                                <span class="text-muted">Staff Number</span>
                                <h5 class="description-header mb-1 font-weight-bold">{{ Auth::user()->uuid }}</h5>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-4">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Edit Profile</div>
                </div>
                <form method="POST" action="{{ url('update-profile') }}">
                    @csrf
                    <div class="card-body">
                        {{-- <div class="card-title font-weight-bold">Basci info:</div> --}}
                        <div class="row">
                            <div class="col-6">
                               <div class="form-group">
                                <label class="form-label">First Name</label>
                                <input type="text" name="first_name" class="form-control" value="{{ Auth::user()->first_name ?? old('first_name') }}">  
                            @if ($errors->has('first_name'))
                                <span class="help-block">
                                    <strong style="color: red;">{{ $errors->first('first_name') }}</strong>
                                </span>
                            @endif
                            </div> 
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                <label class="form-label">Last Name</label>
                                <input type="text" name="last_name" class="form-control" value="{{ Auth::user()->last_name ?? old('last_name') }}">  
                            @if ($errors->has('last_name'))
                                <span class="help-block">
                                    <strong style="color: red;">{{ $errors->first('last_name') }}</strong>
                                </span>
                            @endif
                            </div>
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="form-label">Phone Number</label>
                            <input type="text" name="phone" maxlength="10"a class="form-control" value="{{ Auth::user()->phone ?? old('phone') }}">
                            @if ($errors->has('phone'))
                                <span class="help-block">
                                    <strong style="color: red;">{{ $errors->first('phone') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="text" name="email" class="form-control" value="{{ Auth::user()->email ?? old('email') }}">
                            @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong style="color: red;">{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>

                    </div>
                    <div class="card-footer text-right">
                        <input type="hidden" name="user_id" class="form-control" value="{{ Auth::user()->id }}">
                        <button type="submit" class="btn btn-primary">Updated</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-xl-4 col-lg-4">
            <form method="POST" action="{{ url('update-password') }}">
                @csrf
             <div class="card">
                <div class="card-header">
                    <div class="card-title">Edit Password</div>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label">Old Password</label>
                        <input type="password" id="current-password" name="current_password" value="{{ old('current_password') }}" class="form-control" >
                        @if ($errors->has('current_password'))
                            <span class="help-block">
                                <strong style="color: red;">{{ $errors->first('current_password') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label class="form-label">New Password</label>
                        <input type="password" name="password" value="{{ old('password') }}" class="form-control" >
                        @if ($errors->has('password'))
                            <span class="help-block">
                                <strong style="color: red;">{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" id="password-confirm" name="password_confirmation" value="{{ old('password_confirmation') }}" class="form-control" >
                    </div>
                </div>
                <div class="card-footer text-right">
                    <input type="hidden" name="user_id"  class="form-control" value="{{ Auth::user()->id }}">
                    <input type="hidden" name="email" class="form-control" value="{{ Auth::user()->email }}">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </div>
            </form>
        </div>
    </div>

@endsection
@push('page_js')
    <script type="text/javascript">
        $('select[name="branch"]').on('change', function(){
            $('#changeBranchForm').attr('action', 'change-branch/'+ $(this).val());
        });
    </script>
@endpush