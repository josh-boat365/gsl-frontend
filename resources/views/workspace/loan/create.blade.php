@extends('layouts.dashboard')

@section('content')
<form method="POST" action="{{ route('store') }}" enctype="multipart/form-data" multiple="true">
@csrf
<div class="row">
    <div class="col-lg-12 col-xl-12 col-md-12 col-sm-12 mt-7">
    <div class="card">
        <div class="card-header">

            <div class="col-md-6">
                    <h3 class="card-title">GSL LOAN APPLICATION FORM</h3>
            </div>

            <div class="col-md-6">
                    <a href="{{url('list')}}">
                        <span class="float-md-right btn btn-group text-center bg-primary me-2" style="color: white;">
                            <i class="angle fa fa-backward mt-1"></i>&nbsp;&nbsp;Back</span>
                    </a>
        </div>
        </div>
        <div class="card-body">
@if(isset($data->user_id))
@if($data->stage_info =='stage_0')
<div class="row">
    <div class="col-sm-12 col-md-12">
		<div class="alert alert-danger">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			<strong>Amendment Request From: {{ user($data->amend_request_from)->first_name }} {{ user($data->amend_request_from)->last_name }} @ <i>({{$data->amendment_request_date}})</i></strong>
			<hr class="message-inner-separator">
			<p>{{$data->amendment_remark}}</p>
		</div>
	</div>
</div>
@endif
@endif

<div class="tab_wrapper first_tab">
	<ul class="tab_list">
		<li class="">APPLICANT</li>
		<li>IDENTIFICATION</li>
		<li>CONTACT</li>
        <li>REFERENCE</li>
        <li>EMPLOYMENT</li>
        <li>LOAN DETAILS</li>
        <li>PAYMENT DETAILS</li>
		<li>FILE UPLOADS</li>
	</ul>
	<div class="content_wrapper">
		<div class="tab_content active">
        <div class="row">
                                        <div class="form-group col-md-6 mb-0">
                                            <div class="form-group">
                                                <label class="form-label">Customer First Name <span class="text-red">*</span></label>
                                                <input
                                                        type="text"
                                                        name="first_name"
                                                        class="form-control"
                                                        id="first_name"
                                                        placeholder="First Name"
                                                        value="{{ $data->LoanCustomer->first_name ?? '' }}"
                                                        required
                                                />
                                            </div>
                                        </div>

                                        <div class="form-group col-md-6 mb-0">
                                            <div class="form-group">
                                                <label class="form-label">Customer Surname <span class="text-red">*</span></label>
                                                <input
                                                        type="text"
                                                        name="surname"
                                                        class="form-control"
                                                        id="surname"
                                                        placeholder="Surname"
                                                        value="{{ $data->LoanCustomer->surname ?? '' }}"
                                                        required
                                                />
                                            </div>
                                        </div>

                                        <div class="form-group col-md-6 mb-0">
                                            <div class="form-group">
                                                <label class="form-label">Customer Middle Name </label>
                                                <input
                                                        type="text"
                                                        name="middle_name"
                                                        class="form-control"
                                                        id="middle_name"
                                                        placeholder="Middle Name"
                                                        value="{{ $data->LoanCustomer->middle_name ?? '' }}"
                                                />
                                            </div>
                                        </div>

                                        <div class="form-group col-md-2 mb-0">
                                            <div class="form-group">
                                                <label class="form-label">Date of Birth <span class="text-red">*</span></label>
                                                <input
                                                        type="text"
                                                        name="date_of_birth"
                                                        class="form-control fc-datepicker"
                                                        id="date_of_birth"
                                                        placeholder="YYYY-MM-DD"
                                                        value="{{ \Carbon\Carbon::parse($data->LoanCustomer->date_of_birth ?? '')->format('Y-m-d') ?? '' }}"
                                                        onchange="calculateAge()"
                                                        required
                                                    />
                                            </div>
                                        </div>

                                        <div class="form-group col-md-2 mb-0">
                                            <div class="form-group">
                                                <label class="form-label">Age <span class="text-red">*</span></label>
                                                <input
                                                        type="number"
                                                        name="age"
                                                        class="form-control"
                                                        id="age"
                                                        placeholder="Age"
                                                        value="{{ $data->LoanCustomer->age ?? '' }}"
                                                        required
                                                />
                                            </div>
                                        </div>

                                        <div class="form-group col-md-2 mb-0">
                                            <div class="form-group">
                                                <label class="form-label">Gender <span class="text-red">*</span></label>
                                                <select name="gender" class="branch form-control search-box" data-placeholder="Select Brianch" required>
                                                    <option selected value="" disabled="disabled">Select Gender</option>
                                                        @if(($data->LoanCustomer->gender ?? '') != '' || ($data->LoanCustomer->gender ?? null) != null)
                                                        <option selected value="{{ $data->LoanCustomer->gender }}">{{ $data->LoanCustomer->gender }}</option>
                                                        @endif
                                                        <option value="FEMALE">FEMALE</option>
                                                        <option value="MALE">MALE</option>
                                                </select>
                                            </div>
                                        </div>

                                        














                                        {{-- <div class="form-group col-md-6 mb-0">
                                            <div class="form-group">
                                                <label class="form-label">Employee Account No.</label>
                                                <input
                                                        type="text"
                                                        name="c_account_number"
                                                        class="form-control"
                                                        id="c_account_number"
                                                        value="{{isset($data->user_id)?$data->LoanCustomer->c_account_number:''}}"
                                                        placeholder="Account Number"
                                                        required
                                                />
                                            </div>
                                        </div>

                                        <div class="form-group col-md-6 mb-0">
                                            <div class="form-group">
                                                <label class="form-label">Phone Number</label>
                                                <input
                                                        type="text"
                                                        name="c_phone"
                                                        class="form-control"
                                                        id="c_phone"
                                                        value="{{isset($data->user_id)?$data->LoanCustomer->c_phone:'0'.Auth::user()->phone}}"
                                                        placeholder="Phone Number"
                                                        required
                                                />
                                            </div>
                                        </div>

                                        <div class="form-group col-md-6 mb-0">
                                            <div class="form-group">
                                                <label class="form-label">Place of Residence</label>
                                                <input
                                                        type="text"
                                                        name="c_residential_address"
                                                        class="form-control"
                                                        id="c_residential_address"
                                                        value="{{isset($data->user_id)?$data->LoanCustomer->c_residential_address:''}}"
                                                        placeholder="Residence Address"
                                                        required
                                                />
                                            </div>
                                        </div>

                                        <div class="form-group col-md-6 mb-0">
                                            <div class="form-group">
                                                <label class="form-label">GPS / Permanent Address</label>
                                                <input
                                                        type="text"
                                                        name="c_gps_address"
                                                        class="form-control"
                                                        id="c_gps_address"
                                                        value="{{isset($data->user_id)?$data->LoanCustomer->c_gps_address:''}}"
                                                        placeholder="GPS Address"
                                                        required
                                                />
                                            </div>
                                        </div>

                                        <div class="form-group col-md-6 mb-0">
                                            <div class="form-group">
                                                <label class="form-label">Key Landmark</label>
                                                <input
                                                        type="text"
                                                        name="c_land_mark"
                                                        class="form-control"
                                                        id="c_land_mark"
                                                        value="{{isset($data->user_id)?$data->LoanCustomer->c_land_mark:''}}"
                                                        placeholder="Landmark"
                                                        required
                                                />
                                            </div>
                                        </div>

                                        <div class="form-group col-md-4 mb-0">
                                            <div class="form-group">
                                                <label class="form-label">Position</label>
                                                <select name="c_position" class=" form-control search-box" data-placeholder="Select Job Title" required>
                                                    <option selected value="" disabled="disabled">Select Position</option>
                                                    @foreach($jobTitles as $jobTitle)
                                                        @if($jobTitle->id == Auth::user()->position_id)
                                                        <option selected value="{{$jobTitle->id}}">{{$jobTitle->title_name}}</option>
                                                        @endif
                                                        <option value="{{$jobTitle->id}}">{{$jobTitle->title_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group col-md-4 mb-0">
                                            <div class="form-group">
                                                <label class="form-label">Department</label>
                                                <select name="c_department" class="branch form-control search-box" data-placeholder="Select Department" required>
                                                    <option selected value="" disabled="disabled">Select Department</option>
                                                    @foreach($departments as $department)
                                                        @if($department->id == Auth::user()->department_id)
                                                            <option selected value="{{$department->id}}">{{$department->department_name}}</option>
                                                        @endif
                                                        <option value="{{$department->id}}">{{$department->department_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group col-md-4 mb-0">
                                            <div class="form-group">
                                                <label class="form-label">Branch</label>
                                                <select name="c_branch" class="branch form-control search-box" data-placeholder="Select Brianch" required>
                                                    <option selected value="" disabled="disabled">Select Brianch</option>
                                                    @foreach($branches as $branch)
                                                        @if($branch->branch_code == Auth::user()->branch_code)
                                                            <option selected value="{{$branch->branch_code}}">{{$branch->branch_name}}</option>
                                                        @endif
                                                        <option value="{{$branch->branch_code}}">{{$branch->branch_name}}</option>
                                                    @endforeach

                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group col-md-4 mb-0">
                                            <div class="form-group">
                                                <label class="form-label">HOD/BM/Supervisor</label>
                                                <select name="supervisor_id" class="branch form-control search-box" data-placeholder="Select HOD/BM/Supervisor" required>
                                                    <option selected value="">Select HOD/BM/Supervisor</option>
                                                    @foreach($users as $user)
                                                        <option value="{{$user->id}}">{{$user->first_name}} {{$user->last_name}} ({{ getJobTitle($user->position_id)->title_name ?? '' }})</option>
                                                    @endforeach

                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group col-md-2 mb-0">
                                            <div class="form-group">
                                                <label class="form-label">Marital Status</label>
                                                <select name="c_marital_status" class="branch form-control search-box" data-placeholder="Select Brianch" required>
                                                    <option selected value="" disabled="disabled">Select Marital Status</option>
                                                    <option @if(isset($data->user_id))@if($data->LoanCustomer->c_marital_status == 'NEVER MARRIED') selected @endif @endif value="NEVER MARRIED">NEVER MARRIED</option>
                                                    <option @if(isset($data->user_id))@if($data->LoanCustomer->c_marital_status == 'MARRIED') selected @endif @endif value="MARRIED">MARRIED</option>
                                                    <option @if(isset($data->user_id))@if($data->LoanCustomer->c_marital_status == 'DIVORCED') selected @endif @endif value="DIVORCED">DIVORCED</option>
                                                    <option @if(isset($data->user_id))@if($data->LoanCustomer->c_marital_status == 'SEPARATED') selected @endif @endif value="SEPARATED">SEPARATED</option>
                                                    <option @if(isset($data->user_id))@if($data->LoanCustomer->c_marital_status == 'WIDOWED') selected @endif @endif value="WIDOWED">WIDOWED</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group col-md-2 mb-0">
                                            <div class="form-group">
                                                <label class="form-label">Gender</label>
                                                <select name="c_gender" class="branch form-control search-box" data-placeholder="Select Brianch" required>
                                                    <option selected value="" disabled="disabled">Select Gender</option>
                                                    @if(Auth::user()->gender == 'F')
                                                        <option selected value="FEMALE">FEMALE</option>
                                                        <option value="MALE">MALE</option>
                                                    @endif
                                                    @if(Auth::user()->gender == 'M')
                                                    <option value="FEMALE">FEMALE</option>
                                                        <option selected value="MALE">MALE</option>
                                                    @endif
                                                    @if(empty(Auth::user()->gender) || (Auth::user()->gender == NULL))
                                                    <option value="FEMALE">FEMALE</option>
                                                    <option value="MALE">MALE</option>
                                                    @endif

                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group col-md-2 mb-0">
                                            <div class="form-group">
                                                <label class="form-label">Date of Birth</label>
                                                <input
                                                        type="text"
                                                        name="c_dob"
                                                        class="form-control fc-datepicker"
                                                        id="c_dob"
                                                        value="{{isset($data->user_id)?$data->LoanCustomer->c_dob:''}}"
                                                        placeholder="YYYY-MM-DD"
                                                        required
                                                        readonly
                                                    />
                                            </div>
                                        </div>

                                        <div class="form-group col-md-2 mb-0">
                                            <div class="form-group">
                                                <label class="form-label">Number of Dependents</label>
                                                <select name="c_dependents" class="branch form-control search-box" data-placeholder="Select Brianch" required>
                                                    <option selected value="" disabled="disabled">Select Dependents</option>
                                                    <option @if(isset($data->user_id))@if($data->LoanCustomer->c_dependents == '0') selected @endif @endif value="0">None</option>
                                                    <option @if(isset($data->user_id))@if($data->LoanCustomer->c_dependents == '1') selected @endif @endif value="1">1</option>
                                                    <option @if(isset($data->user_id))@if($data->LoanCustomer->c_dependents == '2') selected @endif @endif value="2">2</option>
                                                    <option @if(isset($data->user_id))@if($data->LoanCustomer->c_dependents == '3') selected @endif @endif value="3">3</option>
                                                    <option @if(isset($data->user_id))@if($data->LoanCustomer->c_dependents == '4') selected @endif @endif value="4">4</option>
                                                    <option @if(isset($data->user_id))@if($data->LoanCustomer->c_dependents == '5') selected @endif @endif value="5">5</option>
                                                    <option @if(isset($data->user_id))@if($data->LoanCustomer->c_dependents == '6') selected @endif @endif value="6">6</option>
                                                    <option @if(isset($data->user_id))@if($data->LoanCustomer->c_dependents == '7') selected @endif @endif value="7">7</option>
                                                    <option @if(isset($data->user_id))@if($data->LoanCustomer->c_dependents == '8') selected @endif @endif value="8">8</option>
                                                    <option @if(isset($data->user_id))@if($data->LoanCustomer->c_dependents == '9') selected @endif @endif value="9">9</option>
                                                    <option @if(isset($data->user_id))@if($data->LoanCustomer->c_dependents == '10') selected @endif @endif value="10">10</option>
                                                </select>
                                            </div>
                                        </div> --}}

                </div>
		</div>
		<div class="tab_content">
			<div class="row">
                                        <div class="form-group col-md-3 mb-0">
                                            <div class="form-group">
                                                <label class="form-label">ID Type <span class="text-red">*</span></label>
                                                <select name="id_type" class="branch form-control search-box" data-placeholder="Select Brianch">
                                                        <option selected value="GHANA CARD">GHANA CARD</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group col-md-3 mb-0">
                                            <div class="form-group">
                                                <label class="form-label">ID Number <span class="text-red">*</span></label>
                                                <input
                                                        type="text"
                                                        name="id_number"
                                                        class="form-control"
                                                        id="id_number"
                                                        placeholder="ID Number"
                                                        value="{{ $data->LoanCustomer->id_number ?? '' }}"
                                                />
                                            </div>
                                        </div>

                                        <div class="form-group col-md-3 mb-0">
                                            <div class="form-group">
                                                <label class="form-label">Date of Issue <span class="text-red">*</span></label>
                                                <input
                                                        type="text"
                                                        name="date_of_issue"
                                                        class="form-control fc-datepicker"
                                                        id="date_of_issue"
                                                        placeholder="YYYY-MM-DD"
                                                        value="{{ \Carbon\Carbon::parse($data->LoanCustomer->date_of_issue ?? '')->format('Y-m-d') ?? '' }}"
                                                        readonly
                                                    />
                                            </div>
                                        </div>

                                        <div class="form-group col-md-3 mb-0">
                                            <div class="form-group">
                                                <label class="form-label">Expiry Date <span class="text-red">*</span></label>
                                                <input
                                                        type="text"
                                                        name="expiry_date"
                                                        class="form-control fc-datepicker"
                                                        id="expiry_date"
                                                        placeholder="YYYY-MM-DD"
                                                        value="{{ \Carbon\Carbon::parse($data->LoanCustomer->expiry_date ?? '')->format('Y-m-d') ?? '' }}"
                                                        readonly
                                                    />
                                            </div>
                                        </div>

                                        <div class="form-group col-md-6 mb-0">
                                            <div class="form-group">
                                                <label class="form-label">Place of Issue <span class="text-red">*</span></label>
                                                <input
                                                        type="text"
                                                        name="place_of_issue"
                                                        class="form-control"
                                                        id="place_of_issue"
                                                        placeholder="Place of Issue"
                                                        value="{{ $data->LoanCustomer->place_of_issue ?? '' }}"
                                                />
                                            </div>
                                        </div>

                                        <div class="form-group col-md-6 mb-0">
                                            <div class="form-group">
                                                <label class="form-label">Residential Address <span class="text-red">*</span></label>
                                                <input
                                                        type="text"
                                                        name="residential_address"
                                                        class="form-control"
                                                        id="residential_address"
                                                        placeholder="Residential Address"
                                                        value="{{ $data->LoanCustomer->residential_address ?? '' }}"
                                                />
                                            </div>
                                        </div>

                                        <div class="form-group col-md-6 mb-0">
                                            <div class="form-group">
                                                <label class="form-label">Residential Key Landmark <span class="text-red">*</span></label>
                                                <input
                                                        type="text"
                                                        name="residential_landmark"
                                                        class="form-control"
                                                        id="residential_landmark"
                                                        placeholder="Residential Key Landmark"
                                                        value="{{ $data->LoanCustomer->residential_landmark ?? '' }}"
                                                />
                                            </div>
                                        </div>

                                        <div class="form-group col-md-6 mb-0">
                                            <div class="form-group">
                                                <label class="form-label">Work Address <span class="text-red">*</span></label>
                                                <input
                                                        type="text"
                                                        name="work_address"
                                                        class="form-control"
                                                        id="work_address"
                                                        placeholder="Work Address"
                                                        value="{{ $data->LoanCustomer->work_address ?? '' }}"
                                                />
                                            </div>
                                        </div>

                                        <div class="form-group col-md-6 mb-0">
                                            <div class="form-group">
                                                <label class="form-label">Work Key Landmark <span class="text-red">*</span></label>
                                                <input
                                                        type="text"
                                                        name="work_landmark"
                                                        class="form-control"
                                                        id="work_landmark"
                                                        placeholder="Work Key Landmark"
                                                        value="{{ $data->LoanCustomer->work_landmark ?? '' }}"
                                                />
                                            </div>
                                        </div>

                                        

                                        























                                        {{-- <div class="form-group col-md-6 mb-0">
                                            <div class="form-group">
                                                <label class="form-label">Name of Guarantor</label>
                                                <input
                                                        type="text"
                                                        name="g_name"
                                                        class="form-control"
                                                        id="g_name"
                                                        value="{{isset($data->user_id)?$data->LoanGuarantor->g_name:''}}"
                                                        placeholder="Guarantor's Full Name"
                                                        required
                                                />
                                            </div>
                                        </div>

                                        <div class="form-group col-md-6 mb-0">
                                            <div class="form-group">
                                                <label class="form-label">Guarantor's Phone</label>
                                                <input
                                                        type="text"
                                                        name="g_phone"
                                                        class="form-control"
                                                        id="g_phone"
                                                        value="{{isset($data->user_id)?$data->LoanGuarantor->g_phone:''}}"
                                                        placeholder="Guarantor's Phone Number"
                                                        required
                                                />
                                            </div>
                                        </div>

                                        <div class="form-group col-md-6 mb-0">
                                            <div class="form-group">
                                                <label class="form-label">Guarantor's GPS Address</label>
                                                <input
                                                        type="text"
                                                        name="g_gps_address"
                                                        class="form-control"
                                                        id="g_gps_address"
                                                        value="{{isset($data->user_id)?$data->LoanGuarantor->g_gps_address:''}}"
                                                        placeholder="Guarantor's Digital Address"
                                                        required
                                                />
                                            </div>
                                        </div>

                                        <div class="form-group col-md-6 mb-0">
                                            <div class="form-group">
                                                <label class="form-label">Ghana Card Number</label>
                                                <input
                                                        type="text"
                                                        name="g_id_number"
                                                        class="form-control"
                                                        id="g_id_number"
                                                        value="{{isset($data->user_id)?$data->LoanGuarantor->g_id_number:''}}"
                                                        placeholder="Guarantor's Ghana Card Number"
                                                        required
                                                />
                                            </div>
                                        </div>

                                        <div class="form-group col-md-6 mb-0">
                                            <div class="form-group">
                                                <label class="form-label">Relationship to Client</label>
                                                <input
                                                        type="text"
                                                        name="g_relationship"
                                                        class="form-control"
                                                        id="g_relationship"
                                                        value="{{isset($data->user_id)?$data->LoanGuarantor->g_relationship:''}}"
                                                        placeholder="Guarantor's Relationship to Client"
                                                        required
                                                />
                                            </div>
                                        </div>

                                        <div class="form-group col-md-6 mb-0">
                                            <div class="form-group">
                                                <label class="form-label">Date of Birth</label>
                                                <input
                                                        type="text"
                                                        name="g_dob"
                                                        class="form-control fc-datepicker"
                                                        id="g_dob"
                                                        value="{{isset($data->user_id)?$data->LoanGuarantor->g_dob:''}}"
                                                        placeholder="YYYY-MM-DD"
                                                        required
                                                        readonly
                                                    />
                                            </div>
                                        </div>

                                        <div class="form-group col-md-6 mb-0">
                                            <div class="form-group">
                                                <label class="form-label">Guarantor's Business Type</label>
                                                <input
                                                        type="text"
                                                        name="g_business_type"
                                                        class="form-control"
                                                        id="g_business_type"
                                                        value="{{isset($data->user_id)?$data->LoanGuarantor->g_business_type:''}}"
                                                        placeholder="Guarantor's Business Type"
                                                        required
                                                />
                                            </div>
                                        </div>

                                        <div class="form-group col-md-6 mb-0">
                                            <div class="form-group">
                                                <label class="form-label">Guarantor's Own Business</label>
                                                <input
                                                        type="text"
                                                        name="g_own_business"
                                                        class="form-control"
                                                        id="g_own_business"
                                                        value="{{isset($data->user_id)?$data->LoanGuarantor->g_own_business:''}}"
                                                        placeholder="Guarantor's Own Business"
                                                        required
                                                />
                                            </div>
                                        </div>

                                        <div class="form-group col-md-6 mb-0">
                                            <div class="form-group">
                                                <label class="form-label">Direction to Business</label>
                                                <input
                                                        type="text"
                                                        name="g_business_direction"
                                                        class="form-control"
                                                        id="g_business_direction"
                                                        value="{{isset($data->user_id)?$data->LoanGuarantor->g_business_direction:''}}"
                                                        placeholder="Direction to Business"
                                                        required
                                                />
                                            </div>
                                        </div>

                                        <div class="form-group col-md-6 mb-0">
                                            <div class="form-group">
                                                <label class="form-label">Direction to Residence </label>
                                                <input
                                                        type="text"
                                                        name="g_residence_direction"
                                                        class="form-control"
                                                        id="g_residence_direction"
                                                        value="{{isset($data->user_id)?$data->LoanGuarantor->g_residence_direction:''}}"
                                                        placeholder="Direction to Residence"
                                                        required
                                                />
                                            </div>
                                        </div> --}}
            </div>
		</div>
		<div class="tab_content">
			<div class="row">
                                        <div class="form-group col-md-6 mb-0">
                                            <div class="form-group">
                                                <label class="form-label">Mobile Phone <span class="text-red">*</span></label>
                                                <input
                                                        type="text"
                                                        name="mobile"
                                                        class="form-control"
                                                        id="mobile"
                                                        placeholder="Mobile Phone"
                                                        value="{{ $data->LoanCustomer->mobile ?? '' }}"
                                                />
                                            </div>
                                        </div>

                                        <div class="form-group col-md-6 mb-0">
                                            <div class="form-group">
                                                <label class="form-label">Home Phone </label>
                                                <input
                                                        type="text"
                                                        name="home_phone"
                                                        class="form-control"
                                                        id="home_phone"
                                                        placeholder="Home Phone"
                                                        value="{{ $data->LoanCustomer->home_phone ?? '' }}"
                                                />
                                            </div>
                                        </div>

                                        <div class="form-group col-md-6 mb-0">
                                            <div class="form-group">
                                                <label class="form-label">Office Phone </label>
                                                <input
                                                        type="text"
                                                        name="work_phone"
                                                        class="form-control"
                                                        id="work_phone"
                                                        placeholder="Work Phone"
                                                        value="{{ $data->LoanCustomer->work_phone ?? '' }}"
                                                />
                                            </div>
                                        </div>

                                        <div class="form-group col-md-6 mb-0">
                                            <div class="form-group">
                                                <label class="form-label">Email </label>
                                                <input
                                                        type="email"
                                                        name="email"
                                                        class="form-control"
                                                        id="email"
                                                        placeholder="Email"
                                                        value="{{ $data->LoanCustomer->email ?? '' }}"
                                                />
                                            </div>
                                        </div>




















{{-- 
                                        <div class="form-group col-md-4 mb-0">
                                            <div class="form-group">
                                                <label class="form-label">Loan Type</label>
                                                <select name="loan_type" id="loan_type" class="branch form-control search-box" data-placeholder="Select Brianch" required>
                                                    <option selected value="" disabled="disabled">Select Loan Type</option>
                                                    <option @if(isset($data->user_id))@if($data->loan_type == 'PERSONAL LOAN') selected @endif @endif value="PERSONAL LOAN">PERSONAL LOAN</option>
                                                    <option @if(isset($data->user_id))@if($data->loan_type == 'EDUCATION LOAN') selected @endif @endif value="EDUCATION LOAN">EDUCATION LOAN</option>
                                                    <option @if(isset($data->user_id))@if($data->loan_type == 'RENT LOAN') selected @endif @endif value="RENT LOAN">RENT LOAN</option>
                                                    <option @if(isset($data->user_id))@if($data->loan_type == 'CAR LOAN') selected @endif @endif value="CAR LOAN">CAR LOAN</option>

                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group col-md-4 mb-0">
                                            <div class="form-group">
                                                <label class="form-label">Requested Amount</label>
                                                <input
                                                        type="number"
                                                        min="1"
                                                        name="requested_amount"
                                                        class="form-control"
                                                        id="requested_amount"
                                                        value="{{isset($data->user_id)?$data->requested_amount:''}}"
                                                        placeholder="0.00"
                                                        required
                                                />
                                            </div>
                                        </div>

                                        <div class="form-group col-md-4 mb-0">
                                            <div class="form-group">
                                                <label class="form-label">Tenor Requested (In Months)</label>
                                                <input
                                                        type="number"
                                                        name="loan_tenor"
                                                        class="form-control"
                                                        id="loan_tenor"
                                                        value="{{isset($data->user_id)?$data->loan_tenor:''}}"
                                                        placeholder="0"
                                                        required
                                                />
                                            </div>
                                        </div>

                                        <div class="form-group col-md-6 mb-0 carInfo">
                                            <div class="form-group">
                                                <label class="form-label">Make & Model of Car</label>
                                                <input
                                                        type="text"
                                                        name="model"
                                                        class="form-control"
                                                        id="model"
                                                        value=""
                                                        placeholder="Make & Model of Car"
                                                />
                                            </div>
                                        </div>

                                        <div class="form-group col-md-6 mb-0 carInfo">
                                            <div class="form-group">
                                                <label class="form-label">Year of Make</label>
                                                <input
                                                        type="text"
                                                        name="year_of_make"
                                                        class="form-control"
                                                        id="year_of_make"
                                                        value=""
                                                        placeholder="Year of Make"
                                                />
                                            </div>
                                        </div> --}}







            </div>
		</div>
		<div class="tab_content">
			<div class="row">

                                        <div class="form-group col-md-6 mb-0">
                                            <div class="form-group">
                                                <label class="form-label">Reference's Full Name <span class="text-red">*</span></label>
                                                <input
                                                        type="text"
                                                        name="g_full_name"
                                                        class="form-control"
                                                        id="g_full_name"
                                                        placeholder="Reference Full Name"
                                                        value="{{ $data->LoanCustomer->g_full_name ?? '' }}"
                                                />
                                            </div>
                                        </div>

                                        <div class="form-group col-md-6 mb-0">
                                            <div class="form-group">
                                                <label class="form-label">Relationship to Reference <span class="text-red">*</span></label>
                                                <input
                                                        type="text"
                                                        name="g_relationship"
                                                        class="form-control"
                                                        id="g_relationship"
                                                        placeholder="Relationship to Reference"
                                                        value="{{ $data->LoanCustomer->g_relationship ?? '' }}"
                                                />
                                            </div>
                                        </div>

                                        <div class="form-group col-md-3 mb-0">
                                            <div class="form-group">
                                                <label class="form-label">Mobile Phone <span class="text-red">*</span></label>
                                                <input
                                                        type="text"
                                                        name="g_mobile"
                                                        class="form-control"
                                                        id="g_mobile"
                                                        placeholder="Mobile Phone"
                                                        value="{{ $data->LoanCustomer->g_mobile ?? '' }}"
                                                />
                                            </div>
                                        </div>

                                        <div class="form-group col-md-3 mb-0">
                                            <div class="form-group">
                                                <label class="form-label">Home Phone </label>
                                                <input
                                                        type="text"
                                                        name="g_home_phone"
                                                        class="form-control"
                                                        id="g_home_phone"
                                                        placeholder="Home Phone"
                                                        value="{{ $data->LoanCustomer->g_home_phone ?? '' }}"
                                                        
                                                />
                                            </div>
                                        </div>

                                        <div class="form-group col-md-3 mb-0">
                                            <div class="form-group">
                                                <label class="form-label">Work Phone </label>
                                                <input
                                                        type="text"
                                                        name="g_work_phone"
                                                        class="form-control"
                                                        id="g_work_phone"
                                                        placeholder="Work Phone"
                                                        value="{{ $data->LoanCustomer->g_work_phone ?? '' }}"
                                                        
                                                />
                                            </div>
                                        </div>

                                        <div class="form-group col-md-3 mb-0">
                                            <div class="form-group">
                                                <label class="form-label">Email Address </label>
                                                <input
                                                        type="text"
                                                        name="g_email"
                                                        class="form-control"
                                                        id="g_email"
                                                        placeholder="Email"
                                                        value="{{ $data->LoanCustomer->g_email ?? '' }}"
                                                        
                                                />
                                            </div>
                                        </div>

                                        <div class="form-group col-md-6 mb-0">
                                            <div class="form-group">
                                                <label class="form-label">Residential Address <span class="text-red">*</span></label>
                                                <input
                                                        type="text"
                                                        name="g_residential_address"
                                                        class="form-control"
                                                        id="g_residential_address"
                                                        placeholder="Residential Address"
                                                        value="{{ $data->LoanCustomer->g_residential_address ?? '' }}"
                                                />
                                            </div>
                                        </div>

                                        <div class="form-group col-md-6 mb-0">
                                            <div class="form-group">
                                                <label class="form-label">Residential Landmark <span class="text-red">*</span></label>
                                                <input
                                                        type="text"
                                                        name="g_residential_landmark"
                                                        class="form-control"
                                                        id="g_residential_landmark"
                                                        placeholder="Residential Landmark"
                                                        value="{{ $data->LoanCustomer->g_residential_landmark ?? '' }}"
                                                />
                                            </div>
                                        </div>

                                        <div class="form-group col-md-6 mb-0">
                                            <div class="form-group">
                                                <label class="form-label">Work Address <span class="text-red">*</span></label>
                                                <input
                                                        type="text"
                                                        name="g_work_address"
                                                        class="form-control"
                                                        id="g_work_address"
                                                        placeholder="Work Address"
                                                        value="{{ $data->LoanCustomer->g_work_address ?? '' }}"
                                                />
                                            </div>
                                        </div>

                                        <div class="form-group col-md-6 mb-0">
                                            <div class="form-group">
                                                <label class="form-label">Work Landmark <span class="text-red">*</span></label>
                                                <input
                                                        type="text"
                                                        name="g_work_landmark"
                                                        class="form-control"
                                                        id="g_work_landmark"
                                                        placeholder="Work Landmark"
                                                        value="{{ $data->LoanCustomer->g_work_landmark ?? '' }}"
                                                />
                                            </div>
                                        </div>


















                {{-- <div class="form-group col-md-6 mb-4">
                    <div class="form-group">
                        <label class="form-label"><b>SCAN</b> or <b>ZIP</b> the following documents for Attachment: <b><i>1)</i></b>Third party guarantee contract, <b><i>2)</i></b>Benefit withdrawal form, <b><i>3)</i></b>Guarantor's ID card</label>
                        <input
                                type="file"
                                name="attachment"
                                class="form-control"
                                id="attachment"
                                placeholder="Attachment"
                        />
                    </div>
                </div>
                <div class="form-group col-md-11 mb-0">
                    <div class="form-group">
                        <label class="form-label"><b><i><u>EMPLOYEE DECLARATION:</u></i></b> I, declare that the information provided here is correct. Furthermore, I authorise Best Point Savings and Loans Limited to debit my account automatically with the agreed instalment. I also authorise Best Point to use my Provident Fund and other entitlements to absorb any unpaid balances upon separation.
                        </label>
                    </div>
                </div>

                <div class="form-group col-md-1 mb-0">
                    <div class="form-group">
                        <label class="custom-control custom-checkbox form-label">
                            <input type="checkbox" class="custom-control-input" id="declaration" name="declaration" value="AGREED " @if(isset($data->user_id))@if(!empty($data->declaration)) checked @endif @endif>
                            <span class="custom-control-label"> Check to agree</span>
                        </label>
                    </div>
                </div> --}}
            </div>
		</div>
        <div class="tab_content">
            <div class="row">
                
                                        <div class="form-group col-md-6 mb-0">
                                            <div class="form-group">
                                                <label class="form-label">Occupation <span class="text-red">*</span></label>
                                                <input
                                                        type="text"
                                                        name="occupation"
                                                        class="form-control"
                                                        id="occupation"
                                                        placeholder="Occupation"
                                                        value="{{ $data->LoanCustomer->occupation ?? '' }}"
                                                />
                                            </div>
                                        </div>

                                        <div class="form-group col-md-6 mb-0">
                                            <div class="form-group">
                                                <label class="form-label">Employer <span class="text-red">*</span></label>
                                                <input
                                                        type="text"
                                                        name="employer"
                                                        class="form-control"
                                                        id="employer"
                                                        placeholder="Employer"
                                                        value="{{ $data->LoanCustomer->employer ?? '' }}"
                                                />
                                            </div>
                                        </div>

                                        <div class="form-group col-md-6 mb-0">
                                            <div class="form-group">
                                                <label class="form-label">Department/Devision <span class="text-red">*</span></label>
                                                <input
                                                        type="text"
                                                        name="department"
                                                        class="form-control"
                                                        id="department"
                                                        placeholder="Department"
                                                        value="{{ $data->LoanCustomer->department ?? '' }}"
                                                />
                                            </div>
                                        </div>

                                        <div class="form-group col-md-6 mb-0">
                                            <div class="form-group">
                                                <label class="form-label">Employment Start Date <span class="text-red">*</span></label>
                                                <input
                                                        type="text"
                                                        name="employment_date"
                                                        class="form-control fc-datepicker"
                                                        id="employment_date"
                                                        placeholder="YYYY-MM-DD"
                                                        value="{{ $data->LoanCustomer->employment_date ?? '' }}"
                                                    />
                                            </div>
                                        </div>

                                        <div class="form-group col-md-6 mb-0">
                                            <div class="form-group">
                                                <label class="form-label">Employee Number <span class="text-red">*</span></label>
                                                <input
                                                        type="text"
                                                        name="employee_number"
                                                        class="form-control"
                                                        id="employee_number"
                                                        placeholder="Employee Number"
                                                        value="{{ $data->LoanCustomer->employee_number ?? '' }}"
                                                />
                                            </div>
                                        </div>

                                        <div class="form-group col-md-6 mb-0">
                                            <div class="form-group">
                                                <label class="form-label">Number of Years with Employer <span class="text-red">*</span></label>
                                                <input
                                                        type="text"
                                                        name="years_employed"
                                                        class="form-control"
                                                        id="years_employed"
                                                        placeholder="Number of Years with Employer"
                                                        value="{{ $data->LoanCustomer->years_employed ?? '' }}"
                                                />
                                            </div>
                                        </div>

    
            </div>
        </div>
        <div class="tab_content">
            <div class="row">
                
                                        <div class="form-group col-md-3 mb-0">
                                            <div class="form-group">
                                                <label class="form-label">Requested Amount(GHS) <span class="text-red">*</span></label>
                                                <input
                                                        type="number"
                                                        name="requested_amount"
                                                        class="form-control"
                                                        id="requested_amount"
                                                        step="0.01"
                                                        placeholder="Requested Amount"
                                                        value="{{ $data->requested_amount ?? '' }}"
                                                />
                                            </div>
                                        </div>

                                        <div class="form-group col-md-3 mb-0">
                                            <div class="form-group">
                                                <label class="form-label">Instalment Per Month(GHS) <span class="text-red">*</span></label>
                                                <input
                                                        type="number"
                                                        name="monthly_installment"
                                                        class="form-control"
                                                        id="monthly_installment"
                                                        step="0.01"
                                                        placeholder="Instalment Per Month"
                                                        value="{{ $data->monthly_installment ?? '' }}"
                                                />
                                            </div>
                                        </div>

                                        <div class="form-group col-md-2 mb-0">
                                            <div class="form-group">
                                                <label class="form-label">Net Salary(GHS) <span class="text-red">*</span></label>
                                                <input
                                                        type="number"
                                                        name="net_salary"
                                                        class="form-control"
                                                        id="net_salary"
                                                        step="0.01"
                                                        placeholder="Net Salary"
                                                        value="{{ $data->net_salary ?? '' }}"
                                                />
                                            </div>
                                        </div>

                                        <div class="form-group col-md-2 mb-0">
                                            <div class="form-group">
                                                <label class="form-label">Term (Months) <span class="text-red">*</span></label>
                                                <input
                                                        type="number"
                                                        name="tenure"
                                                        class="form-control"
                                                        id="tenure"
                                                        placeholder="Tenure"
                                                        value="{{ $data->tenure ?? '' }}"
                                                />
                                            </div>
                                        </div>

                                        <div class="form-group col-md-2 mb-0">
                                            <div class="form-group">
                                                <label class="form-label">Type of Request <span class="text-red">*</span></label>
                                                <select name="frequency" class="branch form-control search-box" data-placeholder="Select Frequency">
                                                        <option selected value="" disabled="disabled">Select Frequency</option>
                                                        @if(($data->frequency ?? '') != '' || ($data->frequency ?? null) != null)
                                                        <option selected value="{{ $data->frequency }}">{{ $data->frequency }}</option>
                                                        @endif
                                                        <option value="NEW">NEW</option>
                                                        <option value="TOP-UP">TOP-UP</option>
                                                </select>
                                            </div>
                                        </div>

    
            </div>
        </div>
        <div class="tab_content">
            <div class="row">
                
                                        <div class="form-group col-md-4 mb-0">
                                            <div class="form-group">
                                                <label class="form-label">Payment Type <span class="text-red">*</span></label>
                                                <select name="payment_type" id="payment_type" class="form-control search-box" data-placeholder="Select Payment Type">
                                                    <option selected value="" disabled="disabled">Select Payment Type</option>
                                                    @if(($data->payment_type ?? '') != '' || ($data->payment_type ?? null) != null)
                                                        <option selected value="{{ $data->payment_type }}">{{ $data->payment_type }}</option>
                                                    @endif
                                                    <option value="BANK ACCOUNT">BANK ACCOUNT</option>
                                                    <option value="MOBILE MONEY">MOBILE MONEY</option>
                                                    <option value="CASH">CASH</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group col-md-4 mb-0 MM">
                                            <div class="form-group">
                                                <label class="form-label">Mobile Network </label>
                                                <select name="network" id="network" class="form-control search-box" data-placeholder="Select Network" >
                                                    <option selected value="" disabled="disabled">Select Network</option>
                                                    @if(($data->network ?? '') != '' || ($data->network ?? null) != null)
                                                        <option selected value="{{ $data->network }}">{{ $data->network }}</option>
                                                    @endif
                                                    <option value="MTN">MTN</option>
                                                    <option value="TELECEL">TELECEL</option>
                                                    <option value="AIRTELTIGO">AIRTELTIGO</option>
                                                    <option value="GLO">GLO</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group col-md-4 mb-0 BK">
                                            <div class="form-group">
                                                <label class="form-label">Name of Bank </label>
                                                <input
                                                        type="text"
                                                        name="bank_name"
                                                        class="form-control"
                                                        id="bank_name"
                                                        placeholder="Name of Bank"
                                                        value="{{ $data->bank_name ?? '' }}"
                                                />
                                            </div>
                                        </div>

                                        <div class="form-group col-md-4 mb-0 BK">
                                            <div class="form-group">
                                                <label class="form-label">Bank Branch </label>
                                                <input
                                                        type="text"
                                                        name="bank_branch"
                                                        class="form-control"
                                                        id="bank_branch"
                                                        placeholder="Branch"
                                                        value="{{ $data->bank_branch ?? '' }}"
                                                />
                                            </div>
                                        </div>

                                        <div class="form-group col-md-4 mb-0 BK">
                                            <div class="form-group">
                                                <label class="form-label">Sort Code</label>
                                                <input
                                                        type="text"
                                                        name="sort_code"
                                                        class="form-control"
                                                        id="sort_code"
                                                        placeholder="Sort Code"
                                                        value="{{ $data->sort_code ?? '' }}"
                                                />
                                            </div>
                                        </div>

                                        <div class="form-group col-md-4 mb-0">
                                            <div class="form-group">
                                                <label class="form-label">Account Number</label>
                                                <input
                                                        type="text"
                                                        name="pay_account_number"
                                                        class="form-control"
                                                        id="pay_account_number"
                                                        placeholder="Account Number"
                                                        value="{{ $data->pay_account_number ?? '' }}"
                                                />
                                            </div>
                                        </div>

                                        <div class="form-group col-md-4 mb-0">
                                            <div class="form-group">
                                                <label class="form-label">Account Name</label>
                                                <input
                                                        type="text"
                                                        name="pay_account_name"
                                                        class="form-control"
                                                        id="pay_account_name"
                                                        placeholder="Account Name"
                                                        value="{{ $data->pay_account_name ?? '' }}"
                                                />
                                            </div>
                                        </div>

    
            </div>
        </div>
        <div class="tab_content">
            <div class="row mb-8">

                                        <div class="form-group col-md-6 mb-8">
                                            <div class="form-group">
                                                <label class="form-label">(Upload Passport Picture)</label>
                                                <input type="file" name="pp_attachment" id="pp_attachment" class="dropify" 
                                                @if(($data->stage_info ?? '') == 'stage_0_1')
                                                data-default-file="../theme/assets/images/passport_picture.jpg" 
                                                @else
                                                data-default-file="theme/assets/images/passport_picture.jpg"
                                                @endif

                                                data-height="200"/>
                                            </div>
                                        </div>

                                        <div class="form-group col-md-6 mb-8">
                                            <div class="form-group">
                                                <label class="form-label">(Upload ID Card Picture)</label>
                                                <input type="file" name="id_attachment" id="id_attachment" class="dropify" 
                                                @if(($data->stage_info ?? '') == 'stage_0_1')
                                                data-default-file="../theme/assets/images/Ghana_card_front.jpg" 
                                                @else
                                                data-default-file="theme/assets/images/Ghana_card_front.jpg" 
                                                @endif
                                                data-height="200"/>
                                            </div>
                                        </div>



                                        <div class="form-group col-md-12 mt-5">
                                            <div class="form-group">
                                                <label class="form-label"><b></b></label>
                                                
                                            </div>
                                        </div>




                                        <div class="form-group col-md-12 mt-3">
                                            <div class="table-responsive">
                                                <table class="table table-bordered card-table table-vcenter text-nowrap">
                                                    <thead class="bg-primary text-white">
                                                        <tr>
                                                            <th class="text-center" style="width: 30%;color: #fff;">Document Type</th>
                                                            <th class="text-center" style="color: #fff;">Upload File</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td class="align-middle">
                                                                <label class="form-label mb-0"><b>Direct Debit Form</b></label>
                                                            </td>
                                                            <td>
                                                                <input type="file" name="dd_attachment" class="form-control" id="dd_attachment" placeholder="Attachment" accept=".pdf,.png,.jpg,.jpeg"/>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="align-middle">
                                                                <label class="form-label mb-0"><b>Affordability Document</b></label>
                                                            </td>
                                                            <td>
                                                                <input type="file" name="affordability_doc" class="form-control" accept=".pdf,.png,.jpg,.jpeg">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="align-middle" rowspan="3">
                                                                <label class="form-label mb-0"><b>Payslip</b> <small class="text-muted">(Last 3 months)</small></label>
                                                            </td>
                                                            <td>
                                                                <div class="input-group">
                                                                    <span class="input-group-text bg-primary text-white">Month 1</span>
                                                                    <input type="file" name="payslip_1" class="form-control" accept=".pdf,.png,.jpg,.jpeg">
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div class="input-group">
                                                                    <span class="input-group-text bg-primary text-white">Month 2</span>
                                                                    <input type="file" name="payslip_2" class="form-control" accept=".pdf,.png,.jpg,.jpeg">
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div class="input-group">
                                                                    <span class="input-group-text bg-primary text-white">Month 3</span>
                                                                    <input type="file" name="payslip_3" class="form-control" accept=".pdf,.png,.jpg,.jpeg">
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="align-middle">
                                                                <label class="form-label mb-0"><b>Mandate Form</b></label>
                                                            </td>
                                                            <td>
                                                                <input type="file" name="mandate_form" class="form-control" accept=".pdf,.png,.jpg,.jpeg">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="align-middle">
                                                                <label class="form-label mb-0"><b>OTP Code</b> <span class="text-red">*</span></label>
                                                            </td>
                                                            <td>
                                                                <input type="text" name="otp_code" class="form-control" placeholder="Enter OTP Code" value="{{ $data->otp_code ?? '' }}">
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-12 mt-5 mb-0 text-center">
                                            <button class="btn btn-primary btn-lg" name="action" value="submit" id="btnSubmit22" type="submit">Submit</button>
                                        </div>

                                        {{-- <div class="form-group col-md-11 mb-0">
                                            <div class="form-group">
                                                <label class="form-label"><b><i><u>AUTHORIZATION BY APPLICANT:</u></i></b> declare and agree that: <b>1.</b> The information that I have provided in the application form is correct. 2. I have reviewed all the information in Part A setting out the full details of my repayments if the loan is granted and they are the same as those that I was shown before I signed this Agreement. 3. I understand the terms of this Agreement as explained to me in English. 4. I have read the Agreement or it has been read to me. 5. Where the instalments are deducted from my salary I acknowledge that I shall not unilaterally cancel the deduction until the loan has been repaid in full.
                                                </label>
                                            </div>
                                        </div>

                                        <div class="form-group col-md-1 mb-0">
                                            <div class="form-group">
                                                <label class="custom-control custom-checkbox form-label">
                                                    <input type="checkbox" class="custom-control-input" id="declaration" name="declaration" value="AGREED " @if(isset($data->user_id))@if(!empty($data->declaration)) checked @endif @endif>
                                                    <span class="custom-control-label"> Check to agree</span>
                                                </label>
                                            </div>
                                        </div> --}}

                                        


               
               
            {{-- <label class="form-label">Image (PNG, JPEG format only. Maximum file size 5MB) </label> --}}
                
                {{-- <div class="form-group col-md-11 mb-0">
                    <div class="form-group">
                        <label class="form-label"><b><i><u>14EMPLOYEE DECLARATION:</u></i></b> I, declare that the information provided here is correct. Furthermore, I authorise Best Point Savings and Loans Limited to debit my account automatically with the agreed instalment. I also authorise Best Point to use my Provident Fund and other entitlements to absorb any unpaid balances upon separation.
                        </label>
                    </div>
                </div> --}}

    
            </div>
        </div>
	</div>
</div>







            </div>
            <div class="card-footer pd-20 text-center mt-0">
                {{-- <input type="hidden" name="amendment_stage" value="@if(isset($data->user_id)){{$data->amendment_stage}}@else{{$a=''}}@endif" id="">
                <input type="hidden" name="ref_num" value="@if(isset($data->user_id)){{$data->ref_num}}@else{{getRefNum()}}@endif" id=""> --}}
                <input type="hidden" name="ref_num" value="{{ $data->ref_num ?? getRefNum() }}" id="">
                @if(($data->stage_info ?? '') == 'stage_0_1' || ($data->stage_info ?? null) == null || empty($data->stage_info ?? ''))
                    <button class="btn btn-success" name="action" value="save" id="btnSubmit21" type="submit">Save</button>
                @endif {{--  <button class="btn btn-light" data-dismiss="modal" ria-label="Close" type="button">Cancel</button> --}}
                
            </div>
        </div>
    </div>
</div>
</form>
@endsection
@push('page_js')
    <script>
        function calculateAge() {
            const dob = document.getElementById('date_of_birth').value;
            const dobDate = new Date(dob);
            const today = new Date();
            let age = today.getFullYear() - dobDate.getFullYear();
            const monthDiff = today.getMonth() - dobDate.getMonth();
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < dobDate.getDate())) {
                age--;
            }
            document.getElementById('age').value = age;
        }
    </script>
@endpush
@push('page_js')
<script src="{{ asset('theme/views_js/silent_poster.js')}}"></script>
@endpush
