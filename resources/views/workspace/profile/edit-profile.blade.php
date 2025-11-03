@extends('layouts.app')
@section('content')
<!--Page header-->

<!--End Page header-->

<!-- Row -->
<div class="row">
    <div class="col-xl-4 col-lg-5">
        <div class="card box-widget widget-user">
            <div class="widget-user-image mx-auto mt-5 text-center"><img alt="User Avatar" class="rounded-circle" src="../public/assets/images/users/16.jpg"></div>
            <div class="card-body text-center">
                <div class="pro-user">
                    <h3 class="pro-user-username text-dark mb-1">Jenna Side</h3>
                    <h6 class="pro-user-desc text-muted">Web Designer</h6>
                    <a href="profile.html" class="btn btn-primary mt-3">View Profile</a>
                </div>
            </div>
            <div class="card-footer p-0">
                <div class="row">
                    <div class="col-sm-6 border-right text-center">
                        <div class="description-block p-4">
                            <h5 class="description-header mb-1 font-weight-bold">689k</h5>
                            <span class="text-muted">Followers</span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="description-block text-center p-4">
                            <h5 class="description-header mb-1 font-weight-bold">3,765</h5>
                            <span class="text-muted">Following</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="card-title">Edit Password</div>
            </div>
            <div class="card-body">
                <div class="text-center mb-5">
                    <img alt="User Avatar" class="rounded-circle  mr-3" src="../public/assets/images/users/16.jpg">
                    <div class="mt-4 ml-0 ml-sm-auto ">
                        <a href="#" class="btn btn-primary mb-1">Edit profile</a>
                        <a href="#" class="btn btn-danger mb-1">Delete profile</a>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Change Password</label>
                    <input type="password" class="form-control" value="password">
                </div>
                <div class="form-group">
                    <label class="form-label">New Password</label>
                    <input type="password" class="form-control" value="password">
                </div>
                <div class="form-group">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" value="password">
                </div>
            </div>
            <div class="card-footer text-right">
                <a href="#" class="btn btn-primary">Updated</a>
                <a href="#" class="btn btn-danger">Cancle</a>
            </div>
        </div>
    </div>
    <div class="col-xl-8 col-lg-7">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Edit Profile</div>
            </div>
            <div class="card-body">
                <div class="card-title font-weight-bold">Basci info:</div>
                <div class="row">
                    <div class="col-sm-6 col-md-6">
                        <div class="form-group">
                            <label class="form-label">Company Name</label>
                            <input type="text" class="form-control" placeholder="First Name">
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6">
                        <div class="form-group">
                            <label class="form-label">Trading Name</label>
                            <input type="text" class="form-control" placeholder="Last Name">
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6">
                        <div class="form-group">
                            <label class="form-label">Office Phone</label>
                            <input type="number" class="form-control" placeholder="Number">
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6">
                        <div class="form-group">
                            <label class="form-label">Mobile Phone</label>
                            <input type="number" class="form-control" placeholder="Number">
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6">
                        <div class="form-group">
                            <label class="form-label">Official Email</label>
                            <input type="email" class="form-control" placeholder="Email">
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6">
                        <div class="form-group">
                            <label class="form-label">Business Category</label>
                            <select class="form-control select2-show-search" data-placeholder="Choose one (with searchbox)">
                                                <optgroup label="Mountain Time Zone">
                                                    <option value="AZ">Arizona</option>
                                                    <option value="CO">Colorado</option>
                                                    <option value="ID">Idaho</option>
                                                    <option value="MT">Montana</option><option value="NE">Nebraska</option>
                                                    <option value="NM">New Mexico</option>
                                                    <option value="ND">North Dakota</option>
                                                    <option value="UT">Utah</option>
                                                    <option value="WY">Wyoming</option>
                                                </optgroup>
                                                <optgroup label="Central Time Zone">
                                                    <option value="AL">Alabama</option>
                                                    <option value="AR">Arkansas</option>
                                                    <option value="IL">Illinois</option>
                                                    <option value="IA">Iowa</option>
                                                    <option value="KS">Kansas</option>
                                                    <option value="KY">Kentucky</option>
                                                    <option value="LA">Louisiana</option>
                                                    <option value="MN">Minnesota</option>
                                                    <option value="MS">Mississippi</option>
                                                    <option value="MO">Missouri</option>
                                                    <option value="OK">Oklahoma</option>
                                                    <option value="SD">South Dakota</option>
                                                    <option value="TX">Texas</option>
                                                    <option value="TN">Tennessee</option>
                                                    <option value="WI">Wisconsin</option>
                                                </optgroup>
                                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-label">Address</label>
                            <input type="text" class="form-control" placeholder="Home Address">
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <div class="form-group">
                            <label class="form-label">Registration Number</label>
                            <input type="text" class="form-control" placeholder="City">
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <div class="form-group">
                            <label class="form-label">TIN Number</label>
                            <input type="number" class="form-control" placeholder="ZIP Code">
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <div class="form-group">
                            <label class="form-label">Registration Date</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <svg class="svg-icon" xmlns="http://www.w3.org/2000/svg" height="18" viewBox="0 0 24 24" width="18"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M20 3h-1V1h-2v2H7V1H5v2H4c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 2v3H4V5h16zM4 21V10h16v11H4z"/><path d="M4 5.01h16V8H4z" opacity=".3"/></svg>
                                    </div>
                                </div><input class="form-control fc-datepicker" placeholder="MM/DD/YYYY" type="text">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-title font-weight-bold mt-5">Other Informations</div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-label">Business Location</label>
                            <input type="text" class="form-control" placeholder="https://www.facebook.com/">
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6">
                        <div class="form-group">
                            <label class="form-label">Website</label>
                            <input type="text" class="form-control" placeholder="https://www.google.com/">
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6">
                        <div class="form-group">
                            <label class="form-label">Company Colour</label>
                            <input id="colorpicker1" type="text">
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6">
                        <div class="form-group">
                            <label class="form-label">Other Colour</label>
                            <input id="showAlpha" type="text">
                        </div>
                    </div>
                </div>
                <div class="card-title font-weight-bold mt-5">About:</div>
                <div class="row">
                    <div class="col-md-8 col-sm-12">
                        <div class="form-group">
                            <label class="form-label">About Me</label>
                            <textarea rows="5" class="form-control" placeholder="Enter About your description"></textarea>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
    <input type="file" class="dropify" data-default-file="https://codeigniter.spruko.com/Dashtic/DASHTIC-LTR/public/assets/images/photos/media1.jpg" data-height="180"  />
</div>
                </div>
            </div>
            <div class="card-footer text-right">
                <a href="#" class="btn btn-lg btn-primary">Updated</a>
                <a href="#" class="btn btn-lg btn-danger">Cancle</a>
            </div>
        </div>
    </div>
</div>
<!-- End Row-->
@endsection