@extends('layouts.dashboard')

@section('content')


<div class="row mt-6">
        <div class="col-md-12 col-lg-12">
        <div class="card-header row">
            <div class="col-md-6">
                {{-- <div class="card-title">LOAN APPLICATION DETAILS</div> --}}
                <form method="POST" action="" id="changeBranchForm2">
                  @csrf
                <div class="form-group">
                                        
                                            <div class="input-group"><span class="font-weight-semibold fs-17 mt-2">Change Request Branch</span>       
                <select name="branch2" class="branch form-control select2" data-placeholder="Choose one (with optgroup)">

                    @if($data->branch_code >= 0)
                        <option selected
                                value="{{$data->branch_code}}">
                            {{getBranchName($data->branch_code)->branch_name}}
                        </option>
                        @else
                        <option selected value="">Select Branch</option>

                        @endif
                    @foreach(getBranches() as $branch)
            <option value="{{$branch->branch_code}}">{{$branch->branch_name}}</option>
                        @endforeach


                </select>
                <span class="input-group-append">
                    <input type="hidden" name="request_id" value="{{ $data->id }}">
                                                    <button class="btn btn-primary" type="submit">Switch</button>
                                                </span>
                                            </div>
                                        </div>

            </form> 
            </div>
            <div class="col-md-6 mb-2">
                      
<form action="{{url('request-approval')}}/{{ $data->id }}" enctype="multipart/form-data" method="POST">
    @csrf
            @if ($data->status_flag != 'DISBURSED')
                <a href="#">
                    <span class="comment_btn float-md-right mr-3 btn btn-group text-center bg-danger me-2 ml-2"
                        action_type="DECLINE"
                        style="color: #ffffff;">
                        <i class="angle fa fa-close mt-1"></i>
                        DECLINE
                    </span>
                </a>

                @if(Auth::user()->role_id == 7 || Auth::user()->role_id == 1)
                    <a href="#">
                        <span class="comment_btn float-md-right btn btn-group text-center bg-primary"
                            action_type="APPROVE"
                            style="color: white;">
                            <i class="angle fa fa-check mt-1"></i>
                            RECOMMEND
                        </span>
                    </a>
                @endif
                @if(Auth::user()->role_id == 4 || Auth::user()->role_id == 5)
                    <a href="#">
                        <span class="comment_btn float-md-right btn btn-group text-center bg-primary"
                            action_type="APPROVE"
                            style="color: white;">
                            <i class="angle fa fa-check mt-1"></i>
                            APPROVED
                        </span>
                    </a>
                @endif
                @if(Auth::user()->role_id == 2)
                    <a href="#">
                        <span class="comment_btn float-md-right btn btn-group text-center bg-primary"
                            action_type="APPROVE"
                            style="color: white;">
                            <i class="angle fa fa-check mt-1"></i>
                            ACCOUNT OPEND
                        </span>
                    </a>
                @endif

            @endif
                    {{-- <button type="submit" class="float-md-right btn text-center btn-primary">Approve</button> --}}



                <a href="{{ url()->previous() }}">
                    <span class="float-md-right mr-3 btn btn-group text-center bg-info" style="color: white;">
                        <i class="angle fa fa-backward mt-1"></i>
                        BACK
                    </span>
                </a>

            </div>
        </div>

        </div>
        <div class="col-md-12 col-lg-8">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                        <h5 class="card-title">Request Information</h5>
                        </div>
                        <div class="col-md-6">
                            <h5 class="card-title float-md-right mr-3 text-muted"><i>Loan Amount</i> (GHS{{ $data->disbursed_amount ?? $data->requested_amount }})</h5>
                        </div>
                    </div>
                    {{-- <h3 class="card-title">APPLICANT INFORMATION</h3> --}}
                    <b><hr class="mt-1" style="width: auto; border-top: 1px solid;"></b>
                    <div class="mt-1 card-text">
                        <div class="row">
<p class="col-md-12 col-lg-6"><img class="img-responsive" src="{{ url('storage/app/')}}/{{$data->pp_attachment}}" alt="card image front"></p>
<p class="col-md-12 col-lg-6"><img class="img-responsive" src="{{ url('storage/app/')}}/{{$data->id_attachment}}" alt="card image front"></p>
                        </div>
                    </div>

<b><hr class="mt-1" style="width: auto; border-top: 1px solid;"></b>
<div class="mt-1 card-text">
    <div class="row mb-4">
{{-- APPLICANT INFORMATION --}}
<p class="col-md-12 col-lg-6"><b>First Name :</b> {{ $data->LoanCustomer->first_name ?? " " }}</p>
<p class="col-md-12 col-lg-6"><b>Surname :</b> {{ $data->LoanCustomer->surname ?? " " }}</p>
<p class="col-md-12 col-lg-6"><b>Middle Name :</b> {{ $data->LoanCustomer->middle_name ?? " " }}</p>
<p class="col-md-12 col-lg-6"><b>Date of Birth :</b> {{ $data->LoanCustomer->date_of_birth ?? " " }}</p>
<p class="col-md-12 col-lg-6"><b>Age :</b> {{ $data->LoanCustomer->age ?? " " }}</p>
<p class="col-md-12 col-lg-6"><b>Gender :</b> {{ $data->LoanCustomer->gender ?? " " }}</p>
    </div>
</div>

<h4 class="heading-inverse bg-info">IDENTIFICATION DETAIL</h4>
<div class="card-text">
    <div class="row mb-4">
{{-- IDENTIFICATION --}}
<p class="col-md-12 col-lg-6"><b>ID Type :</b> {{ $data->LoanCustomer->id_type ?? " " }}</p>
<p class="col-md-12 col-lg-6"><b>ID Number :</b> {{ $data->LoanCustomer->id_number ?? " " }}</p>
<p class="col-md-12 col-lg-6"><b>Date of Issue :</b> {{ $data->LoanCustomer->date_of_issue ?? " " }}</p>
<p class="col-md-12 col-lg-6"><b>Expiry Date :</b> {{ $data->LoanCustomer->expiry_date ?? " " }}</p>
<p class="col-md-12 col-lg-6"><b>Place of Issue :</b> {{ $data->LoanCustomer->place_of_issue ?? " " }}</p>
<p class="col-md-12 col-lg-6"><b>Residential Address :</b> {{ $data->LoanCustomer->residential_address ?? " " }}</p>
<p class="col-md-12 col-lg-6"><b>Residential Landmark :</b> {{ $data->LoanCustomer->residential_landmark ?? " " }}</p>
<p class="col-md-12 col-lg-6"><b>Work Address :</b> {{ $data->LoanCustomer->work_address ?? " " }}</p>
<p class="col-md-12 col-lg-6"><b>Work Landmark :</b> {{ $data->LoanCustomer->work_landmark ?? " " }}</p>
    </div>
</div>

<h4 class="heading-inverse bg-info">CONTACT DETAIL</h4>
<div class="mt-1 card-text">
    <div class="row mb-4">
{{-- CONTACT DETAILS --}}
<p class="col-md-12 col-lg-6"><b>Mobile :</b> {{ $data->LoanCustomer->mobile ?? " " }}</p>
<p class="col-md-12 col-lg-6"><b>Home Phone :</b> {{ $data->LoanCustomer->home_phone ?? " " }}</p>
<p class="col-md-12 col-lg-6"><b>Work Phone :</b> {{ $data->LoanCustomer->work_phone ?? " " }}</p>
<p class="col-md-12 col-lg-6"><b>Email :</b> {{ $data->LoanCustomer->email ?? " " }}</p>
    </div>
</div>

<h4 class="heading-inverse bg-info">EMPLOYMENT DETAIL</h4>
<div class="mt-1 card-text">
    <div class="row mb-4">
{{-- EMPLOYMENT DETAILS --}}
<p class="col-md-12 col-lg-6"><b>Occupation :</b> {{ $data->LoanCustomer->occupation ?? " " }}</p>
<p class="col-md-12 col-lg-6"><b>Employer :</b> {{ $data->LoanCustomer->employer ?? " " }}</p>
<p class="col-md-12 col-lg-6"><b>Department :</b> {{ $data->LoanCustomer->department ?? " " }}</p>
<p class="col-md-12 col-lg-6"><b>Employment Date :</b> {{ $data->LoanCustomer->employment_date ?? " " }}</p>
<p class="col-md-12 col-lg-6"><b>Employee Number :</b> {{ $data->LoanCustomer->employee_number ?? " " }}</p>
<p class="col-md-12 col-lg-6"><b>Years Employed :</b> {{ $data->LoanCustomer->years_employed ?? " " }}</p>
    </div>
</div>

<h4 class="heading-inverse bg-info">REFERENCE DETAIL</h4>
<div class="mt-1 card-text">
    <div class="row mb-4">
{{-- GUARANTOR DETAILS --}}
<p class="col-md-12 col-lg-6"><b>Reference's Full Name :</b> {{ $data->LoanCustomer->g_full_name ?? " " }}</p>
<p class="col-md-12 col-lg-6"><b>Reference's Relationship :</b> {{ $data->LoanCustomer->g_relationship ?? " " }}</p>
<p class="col-md-12 col-lg-6"><b>Mobile :</b> {{ $data->LoanCustomer->g_mobile ?? " " }}</p>
<p class="col-md-12 col-lg-6"><b>Home Phone :</b> {{ $data->LoanCustomer->g_home_phone ?? " " }}</p>
<p class="col-md-12 col-lg-6"><b>Work Phone :</b> {{ $data->LoanCustomer->g_work_phone ?? " " }}</p>
<p class="col-md-12 col-lg-6"><b>Email :</b> {{ $data->LoanCustomer->g_email ?? " " }}</p>
<p class="col-md-12 col-lg-6"><b>Residential Address :</b> {{ $data->LoanCustomer->g_residential_address ?? " " }}</p>
<p class="col-md-12 col-lg-6"><b>Residential Landmark :</b> {{ $data->LoanCustomer->g_residential_landmark ?? " " }}</p>
<p class="col-md-12 col-lg-6"><b>Work Address :</b> {{ $data->LoanCustomer->g_work_address ?? " " }}</p>
<p class="col-md-12 col-lg-6"><b>Work Landmark :</b> {{ $data->LoanCustomer->g_work_landmark ?? " " }}</p>
    </div>
</div>

<h4 class="heading-inverse bg-info">LOAN DETAIL</h4>
<div class="mt-1 card-text">
    <div class="row mb-4">
{{-- LOAN DETAIL --}}
<p class="col-md-12 col-lg-6"><b>Requested Amount :</b> {{ $data->requested_amount }}</p>
<p class="col-md-12 col-lg-6"><b>Monthly Installment :</b> {{ $data->monthly_installment }}</p>
<p class="col-md-12 col-lg-6"><b>Net Salary :</b> {{ $data->net_salary }}</p>
<p class="col-md-12 col-lg-6"><b>Tenure :</b> {{ $data->tenure }} (<b>Type of Request</b> : {{ $data->frequency }})</p>
    </div>
</div>

<h4 class="heading-inverse bg-info">PAYMENT INSTRUCTION</h4>
<div class="mt-1 card-text">
    <div class="row mb-4">
{{-- PAYMENT INSTRUCTION --}}
<p class="col-md-12 col-lg-6"><b>Payment Type :</b> {{ $data->payment_type }}</p>
<p class="col-md-12 col-lg-6"><b>Bank Name :</b> {{ $data->bank_name }}</p>
<p class="col-md-12 col-lg-6"><b>Bank Branch :</b> {{ $data->bank_branch }}</p>
<p class="col-md-12 col-lg-6"><b>Sort Code :</b> {{ $data->sort_code }}</p>
<p class="col-md-12 col-lg-6"><b>Pay Account Number :</b> {{ $data->pay_account_number }}</p>
<p class="col-md-12 col-lg-6"><b>Pay Account Name :</b> {{ $data->pay_account_name }}</p>
    </div>
</div>

                </div>
            </div>



            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                        <h5 class="card-title">Supervisor Input</h5>
                        </div>
                        <div class="col-md-6">
                        @if ($data->status_flag != 'DISBURSED')
                            @if(Auth::user()->role_id == 5 || Auth::user()->role_id == 7 || Auth::user()->role_id == 2 || Auth::user()->role_id == 1 || Auth::user()->role_id == 4)
                                <a id="hr_btn" href="##" class="float-md-right btn btn-group btn-outline-primary text-center  me-2 btn-sm" ><i class="mdi mdi-account-edit" data-toggle="tooltip" title="" data-original-title="Update Record (BDO Input)"></i></a>
                            @endif
                        @endif
                        </div>
                    </div>
                    <b><hr class="mt-1" style="width: auto; border-top: 1px solid;"></b>
                    <div class="mt-1 card-text">
                        <div class="row">
                            <p class="col-md-12 col-lg-6"><b>Disbursed Amount :</b> GH₵{{$data->disbursed_amount ?? $data->requested_amount}}</p>
                            <p class="col-md-12 col-lg-6"><b>Monthly Installment :</b> GH₵{{$data->monthly_installment ?? '0.00'}}</p>
                            <p class="col-md-12 col-lg-6"><b>Account Number :</b> {{$data->account_number ?? 'N/A'}}</p>
                            <p class="col-md-12 col-lg-6"><b>Mandate No. :</b> {{$data->mandate_number ?? 'N/A'}}</p>
                            <p class="col-md-12 col-lg-6"><b>Mandate PIN :</b> {{$data->mandate_pin ?? 'N/A'}}</p>
                            <p class="col-md-12 col-lg-6"><b>Tenure :</b> {{$data->tenure ?? '0'}}</p>
                            <p class="col-md-12 col-lg-6"><b>OTP Code :</b> {{$data->otp_code ?? 'N/A'}}</p>
                            <p class="col-md-12 col-lg-6"><b>Remark :</b> {{$data->processor_remark ?? 'N/A'}}</p>
                        </div>
                        <b><hr class="mt-1 mb-1" style="width: auto; border-top: 1px solid;"></b>

                        <div class="row">
                            <div class="form-group col-md-12 mt-3">
                                <div class="table-responsive">
                                    <table class="table table-bordered card-table table-vcenter text-nowrap">
                                        <thead class="bg-primary text-white">
                                            <tr>
                                                <th class="text-center" style="width: 10%;color: #fff;">Document Type</th>
                                                <th class="text-center" style="color: #fff;">Upload File</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="align-middle">
                                                    <label class="form-label mb-0"><b>Direct Debit Form</b></label>
                                                </td>
                                                <td>
                            @if($data->dd_attachment)
                                <div class="form-preview">
                                    @php
                                        $fileExtension = pathinfo($data->dd_attachment, PATHINFO_EXTENSION);
                                    @endphp
                                    
                                    @if(in_array(strtolower($fileExtension), ['pdf']))
                                        <embed src="{{ url('storage/app/')}}/{{$data->dd_attachment}}" type="application/pdf" width="100%" height="500px">
                                    @elseif(in_array(strtolower($fileExtension), ['jpg', 'jpeg', 'png']))
                                    <a  href="{{ url('storage/app/')}}/{{$data->dd_attachment}}" download="{{ url('storage/app/')}}/{{$data->dd_attachment}}" class="btn btn-pill btn-primary mb-3 btn-block"><i class="fe fe-download-cloud"></i> Click to Download </a>
                                        <img src="{{ url('storage/app/')}}/{{$data->dd_attachment}}" alt="Direct Debit Form" class="img-fluid">
                                    @endif
                                </div>
                            @else
                                <a href="#" class="btn btn-pill btn-light mb-3 btn-block"><i class="fe fe-x-circle"></i> Attachment Not Available  </a>
                            @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="align-middle">
                                                    <label class="form-label mb-0"><b>Affordability Document</b></label>
                                                </td>
                                                <td>
                            @if($data->affordability_doc)
                                <div class="form-preview">
                                    @php
                                        $fileExtension = pathinfo($data->affordability_doc, PATHINFO_EXTENSION);
                                    @endphp
                                    
                                    @if(in_array(strtolower($fileExtension), ['pdf']))
                                        <embed src="{{ url('storage/app/')}}/{{$data->affordability_doc}}" type="application/pdf" width="100%" height="500px">
                                    @elseif(in_array(strtolower($fileExtension), ['jpg', 'jpeg', 'png']))
                                    <a  href="{{ url('storage/app/')}}/{{$data->affordability_doc}}" download="{{ url('storage/app/')}}/{{$data->affordability_doc}}" class="btn btn-pill btn-primary mb-3 btn-block"><i class="fe fe-download-cloud"></i> Click to Download </a>
                                        <img src="{{ url('storage/app/')}}/{{$data->affordability_doc}}" alt="Direct Debit Form" class="img-fluid">
                                    @endif
                                </div>
                            @else
                                <a href="#" class="btn btn-pill btn-light mb-3 btn-block"><i class="fe fe-x-circle"></i> Attachment Not Available  </a>
                            @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="align-middle">
                                                    <label class="form-label mb-0"><b>Payslip</b> <small class="text-muted">(Last 3 months)</small></label>
                                                </td>
                                                <td>
                                <table> 
                                    <tr> 
                                        <td>
                            @if($data->payslip_1)
                                <div class="form-preview">
                                    @php
                                        $fileExtension = pathinfo($data->payslip_1, PATHINFO_EXTENSION);
                                    @endphp
                                    
                                    @if(in_array(strtolower($fileExtension), ['pdf']))
                                        <embed src="{{ url('storage/app/')}}/{{$data->payslip_1}}" type="application/pdf" width="100%" height="300px">
                                    @elseif(in_array(strtolower($fileExtension), ['jpg', 'jpeg', 'png']))
                                    <a  href="{{ url('storage/app/')}}/{{$data->payslip_1}}" download="{{ url('storage/app/')}}/{{$data->payslip_1}}" class="btn btn-pill btn-primary mb-3 btn-block"><i class="fe fe-download-cloud"></i> Click to Download</a>
                                        <img src="{{ url('storage/app/')}}/{{$data->payslip_1}}" alt="Direct Debit Form" class="img-fluid">
                                    @endif
                                </div>
                            @else
                                <a href="#" class="btn btn-pill btn-light mb-3 btn-block"><i class="fe fe-x-circle"></i> Attachment Not Available  </a>
                            @endif
                                        </td> 
                                        <td>
                            @if($data->payslip_2)
                                <div class="form-preview">
                                    @php
                                        $fileExtension = pathinfo($data->payslip_2, PATHINFO_EXTENSION);
                                    @endphp
                                    
                                    @if(in_array(strtolower($fileExtension), ['pdf']))
                                        <embed src="{{ url('storage/app/')}}/{{$data->payslip_2}}" type="application/pdf" width="100%" height="300px">
                                    @elseif(in_array(strtolower($fileExtension), ['jpg', 'jpeg', 'png']))
                                    <a  href="{{ url('storage/app/')}}/{{$data->payslip_2}}" download="{{ url('storage/app/')}}/{{$data->payslip_2}}" class="btn btn-pill btn-primary mb-3 btn-block"><i class="fe fe-download-cloud"></i> Click to Download</a>
                                        <img src="{{ url('storage/app/')}}/{{$data->payslip_2}}" alt="Direct Debit Form" class="img-fluid">
                                    @endif
                                </div>
                            @else
                                <a href="#" class="btn btn-pill btn-light mb-3 btn-block"><i class="fe fe-x-circle"></i> Attachment Not Available  </a>
                            @endif
                                        </td> 
                                        <td>
                            @if($data->payslip_3)
                                <div class="form-preview">
                                    @php
                                        $fileExtension = pathinfo($data->payslip_3, PATHINFO_EXTENSION);
                                    @endphp
                                    
                                    @if(in_array(strtolower($fileExtension), ['pdf']))
                                        <embed src="{{ url('storage/app/')}}/{{$data->payslip_3}}" type="application/pdf" width="100%" height="300px">
                                    @elseif(in_array(strtolower($fileExtension), ['jpg', 'jpeg', 'png']))
                                    <a  href="{{ url('storage/app/')}}/{{$data->payslip_3}}" download="{{ url('storage/app/')}}/{{$data->payslip_3}}" class="btn btn-pill btn-primary mb-3 btn-block"><i class="fe fe-download-cloud"></i> Click to Download</a>
                                        <img src="{{ url('storage/app/')}}/{{$data->payslip_3}}" alt="Direct Debit Form" class="img-fluid">
                                    @endif
                                </div>
                            @else
                                <a href="#" class="btn btn-pill btn-light mb-3 btn-block"><i class="fe fe-x-circle"></i> Attachment Not Available  </a>
                            @endif
                                        </td> 
                                    </tr>
                                </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="align-middle">
                                                    <label class="form-label mb-0"><b>Mandate Form</b></label>
                                                </td>
                                                <td>
                            @if($data->mandate_form)
                                <div class="form-preview">
                                    @php
                                        $fileExtension = pathinfo($data->mandate_form, PATHINFO_EXTENSION);
                                    @endphp
                                    
                                    @if(in_array(strtolower($fileExtension), ['pdf']))
                                        <embed src="{{ url('storage/app/')}}/{{$data->mandate_form}}" type="application/pdf" width="100%" height="500px">
                                    @elseif(in_array(strtolower($fileExtension), ['jpg', 'jpeg', 'png']))
                                    <a  href="{{ url('storage/app/')}}/{{$data->mandate_form}}" download="{{ url('storage/app/')}}/{{$data->mandate_form}}" class="btn btn-pill btn-primary mb-3 btn-block"><i class="fe fe-download-cloud"></i> Click to Download </a>
                                        <img src="{{ url('storage/app/')}}/{{$data->mandate_form}}" alt="Direct Debit Form" class="img-fluid">
                                    @endif
                                </div>
                            @else
                                <a href="#" class="btn btn-pill btn-light mb-3 btn-block"><i class="fe fe-x-circle"></i> Attachment Not Available  </a>
                            @endif
                                                </td>
                                            </tr>
                                            
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <p class="col-md-12 col-lg-6"><b>Review By :</b> {{ user($data->processed_by)->first_name ?? '' }} {{ user($data->processed_by)->last_name ?? '' }}</p>
                            <p class="col-md-12 col-lg-6"><b>Date :</b> {{ $data->processed_date ?? '' }}</p>
                        </div>
                    </div>
                </div>
            </div>



            {{-- <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                        <h5 class="card-title">Credit Risk Management</h5>
                        </div>
                        <div class="col-md-6">
                        @if(Auth::user()->role_id == 9 || Auth::user()->role_id == 14)
                            <a id="credit_risk_btn" href="#" class="float-md-right btn btn-group btn-outline-primary text-center  me-2 btn-sm" ><i class="mdi mdi-account-edit" data-toggle="tooltip" title="" data-original-title="Update Record (Credit Risk Input)"></i></a>
                        @endif
                        </div>
                    </div>
                    <b><hr class="mt-1" style="width: auto; border-top: 1px solid;"></b>
                    <div class="mt-1 card-text">
                        <div class="row">
                            <p class="col-md-12 col-lg-6"><b>Interest Rate :</b> {{$data->interest_rate ?? '0'}}</p>
                            <p class="col-md-12 col-lg-6"><b>Disbursement Amount :</b> GH₵{{$data->recommend_amount ?? '0.00'}}</p>
                            <p class="col-md-12 col-lg-6"><b>Monthly Instalment :</b> GH₵{{$data->monthly_installment ?? '0.00'}}</p>
                            <p class="col-md-12 col-lg-6"><b>Maturity Granted :</b> {{$data->loan_tenor ?? '0'}} <i>months</i></p>
                            <p class="col-md-12 col-lg-6"><b>Debt Service Ratio-After :</b> {{$data->loan_tenor ?? '0'}}</p>
                            <p class="col-md-12 col-lg-6"><b>P.F Balance :</b> GH₵{{$data->pf_balance ?? '0.00'}}</p>
                            <p class="col-md-12 col-lg-6"><b>Guarantor Provided :</b> {{$data->guarantor_provided ?? 'N/A'}}</p>
                            <p class="col-md-12 col-lg-6"><b>Pension Form Sined :</b> {{$data->Pension_form_sined ?? 'N/A'}}</p>
                            <p class="col-md-12 col-lg-6"><b>Existing Loan :</b> GH₵{{$data->existing_loan ?? 'N/A'}}</p>
                            <p class="col-md-12 col-lg-6"><b>Existing Loan Repayment :</b> GH₵{{$data->existing_loan_repayment ?? '0.00'}}</p>
                            <p class="col-md-12 col-lg-12"><b>Existing Loan Type & Balance :</b> {{$data->existing_loan_info ?? 'N/A'}}</p>
                            <p class="col-md-12 col-lg-12"><b>Comment :</b> {{$data->risk_review_remark ?? 'N/A'}}</p>
                        </div>
                        <b><hr class="mt-1 mb-1" style="width: auto; border-top: 1px solid;"></b>
                        <div class="row">
                            <p class="col-md-12 col-lg-6"><b>Credit Risk :</b> {{ user($data->risk_review_by)->first_name ?? '' }} {{ user($data->risk_review_by)->last_name ?? '' }}</p>
                            <p class="col-md-12 col-lg-6"><b>Date :</b> {{ $data->risk_review_date ?? '' }}</p>
                        </div>
                    </div>
                </div>
            </div> --}}



            {{-- <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Management Credit Committee Approvals</h5>
                </div>
                <div class="table-responsive">
                    <table class="table card-table table-vcenter table-primary mb-0" style="table-layout: fixed;">
                        <thead  class="bg-primary text-white">
                            <tr >
                                <th class="text-white">Name</th>
                                <th class="text-white">Date</th>
                                <th class="text-white">Remark</th>
                                <th class="text-white">Status</th>
                            </tr>
                        </thead>
                        <tbody>

                        @foreach ( $data->LoanApproval as $loan_approval )
                            @if ($loan_approval->a_stage_info == 'stage_4')
                                <tr>
                                    <th scope="row">{{$loan_approval->approver_name}}</th>
                                    <td>{{$loan_approval->approval_date}}</td>
                                    <td style="word-wrap: break-word">{{$loan_approval->a_remark}}</td>
                                    <td>{!! approvalStatus($loan_approval->approval_status) !!}</td>
                                </tr>
                            @endif
                        @endforeach
                            <tr>
                                <th scope="row">{{ '' ?? '' }}</th>
                                <td>{{ '' ?? '' }}</td>
                                <td>{{ '' ?? '' }}</td>
                                <td></td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div> --}}

        </div>
        <div class="col-md-12 col-lg-4">

            <div class="card">
                <div class="card-body">
                <h5 class="card-title">REQUEST STATUS</h5>
                    <div class="row">
                        {{-- <div class="col-lg-12 col-xl-12 col-md-12 col-sm-12">
                            {!! statusBadge($data->stage_info, $data->updated_at, $data->created_at) !!}
                        </div> --}}
                        <div class="latest-timeline latest-timeline1">
                                            <ul class="timeline mb-0">
                                                <li class="mt-0 media media-lg">
                                                    @if($data->pp_attachment != NULL) 
                                                        <span class="latest-timeline1-icon bg-success shadow3">01</span>
                                                        <div class="media mt-0">
                                                            <div class="media-body">
                                                                <h6 class="mb-1"><a href="#" class="font-weight-semibold fs-17">Passport Picture </a><span class="badge badge-success ml-2"><i class="fe fe-check mr-2"></i>Completed</span></h6>
                                                                <br/>
                                                            </div>
                                                        </div>
                                                    @else 
                                                        <span class="latest-timeline1-icon bg-gray-400 shadow3">01</span>
                                                        <div class="media mt-0">
                                                            <div class="media-body">
                                                                <h6 class="mb-1"><a href="#" class="font-weight-semibold fs-17">Passport Picture </a><span class="badge bg-gray-400 ml-2"  style="color: white;"><button type="button" class="btn bg-gray-400 btn-loading btn-sm"></button>Pending</span></h6>
                                                                <br/>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    
                                                </li>
                                                <li class="mt-0 media media-lg">
                                                    <div class="media mt-0">
                                                    @if($data->id_attachment != NULL) 
                                                        <span class="latest-timeline1-icon bg-success shadow3">02</span>
                                                        <div class="media mt-0">
                                                            <div class="media-body">
                                                                <h6 class="mb-1"><a href="#" class="font-weight-semibold fs-17">ID Card Picture </a><span class="badge badge-success ml-2"><i class="fe fe-check mr-2"></i>Completed</span></h6>
                                                                <br/>
                                                            </div>
                                                        </div>
                                                    @else 
                                                        <span class="latest-timeline1-icon bg-gray-400 shadow3">02</span>
                                                        <div class="media-body">
                                                            <h6 class="mb-1"><a href="#" class="font-weight-semibold fs-17">ID Card Picture </a><span class="badge bg-gray-400 ml-2 shadow3" style="color: white;"><button type="button" class="btn bg-gray-400 btn-loading btn-sm"></button>Pending </span></h6>
                                                            <br/>
                                                        </div>
                                                    @endif
                                                    </div>
                                                </li>
                                                <li class="mt-0 media media-lg">
                                                    <div class="media mt-0">
                                                    @if($data->dd_attachment != NULL) 
                                                        <span class="latest-timeline1-icon bg-success shadow3">03</span>
                                                        <div class="media mt-0">
                                                            <div class="media-body">
                                                                <h6 class="mb-1"><a href="#" class="font-weight-semibold fs-17">Direct Debit Form </a><span class="badge badge-success ml-2"><i class="fe fe-check mr-2"></i>Completed</span></h6>
                                                                <br/>
                                                            </div>
                                                        </div>
                                                    @else 
                                                        <span class="latest-timeline1-icon bg-gray-400 shadow3">03</span>
                                                        <div class="media-body">
                                                            <h6 class="mb-1"><a href="#" class="font-weight-semibold fs-17">Direct Debit Form </a><span class="badge bg-gray-400 ml-2 shadow3" style="color: white;"><button type="button" class="btn bg-gray-400 btn-loading btn-sm"></button>Pending </span></h6>
                                                            <br/>
                                                        </div>
                                                    @endif
                                                    </div>
                                                </li>

                                                <li class="mt-0 media media-lg">
                                                    <div class="media mt-0">
                                                    @if($data->affordability_doc != NULL) 
                                                        <span class="latest-timeline1-icon bg-success shadow3">03</span>
                                                        <div class="media mt-0">
                                                            <div class="media-body">
                                                                <h6 class="mb-1"><a href="#" class="font-weight-semibold fs-17">Affordability Document </a><span class="badge badge-success ml-2"><i class="fe fe-check mr-2"></i>Completed</span></h6>
                                                                <br/>
                                                            </div>
                                                        </div>
                                                    @else 
                                                        <span class="latest-timeline1-icon bg-gray-400 shadow3">03</span>
                                                        <div class="media-body">
                                                            <h6 class="mb-1"><a href="#" class="font-weight-semibold fs-17">Affordability Document </a><span class="badge bg-gray-400 ml-2 shadow3" style="color: white;"><button type="button" class="btn bg-gray-400 btn-loading btn-sm"></button>Pending </span></h6>
                                                            <br/>
                                                        </div>
                                                    @endif
                                                    </div>
                                                </li>

                                                <li class="mt-0 media media-lg">
                                                    <div class="media mt-0">
                                                    @if($data->payslip_1 != NULL && $data->payslip_2 != NULL && $data->payslip_3 != NULL) 
                                                        <span class="latest-timeline1-icon bg-success shadow3">04</span>
                                                        <div class="media mt-0">
                                                            <div class="media-body">
                                                                <h6 class="mb-1"><a href="#" class="font-weight-semibold fs-17">Payslip </a><span class="badge badge-success ml-2"><i class="fe fe-check mr-2"></i>Completed</span></h6>
                                                                <br/>
                                                            </div>
                                                        </div>
                                                    @else 
                                                        <span class="latest-timeline1-icon bg-gray-400 shadow3">04</span>
                                                        <div class="media-body">
                                                            <h6 class="mb-1"><a href="#" class="font-weight-semibold fs-17">Payslip </a><span class="badge bg-gray-400 ml-2 shadow3" style="color: white;"><button type="button" class="btn bg-gray-400 btn-loading btn-sm"></button>Pending </span></h6>
                                                            <br/>
                                                        </div>
                                                    @endif
                                                    </div>
                                                </li>

                                                <li class="mt-0 media media-lg">
                                                    <div class="media mt-0">
                                                    @if($data->mandate_form != NULL) 
                                                        <span class="latest-timeline1-icon bg-success shadow3">05</span>
                                                        <div class="media mt-0">
                                                            <div class="media-body">
                                                                <h6 class="mb-1"><a href="#" class="font-weight-semibold fs-17">Mandate Form </a><span class="badge badge-success ml-2"><i class="fe fe-check mr-2"></i>Completed</span></h6>
                                                                <br/>
                                                            </div>
                                                        </div>
                                                    @else 
                                                        <span class="latest-timeline1-icon bg-gray-400 shadow3">05</span>
                                                        <div class="media-body">
                                                            <h6 class="mb-1"><a href="#" class="font-weight-semibold fs-17">Mandate Form </a><span class="badge bg-gray-400 ml-2 shadow3" style="color: white;"><button type="button" class="btn bg-gray-400 btn-loading btn-sm"></button>Pending </span></h6>
                                                            <br/>
                                                        </div>
                                                    @endif
                                                    </div>
                                                </li>

                                                <li class="mt-0 media media-lg">
                                                    <div class="media mt-0">
                                                    @if($data->otp_status == 'CONFIRMED') 
                                                        <span class="latest-timeline1-icon bg-success shadow3">06</span>
                                                        <div class="media mt-0">
                                                            <div class="media-body">
                                                                <h6 class="mb-1"><a href="#" class="font-weight-semibold fs-17">OTP Confirmed </a><span class="badge badge-success ml-2"><i class="fe fe-check mr-2"></i>Completed</span></h6>
                                                                <br/>
                                                            </div>
                                                        </div>
                                                    @else 
                                                        <span class="latest-timeline1-icon bg-gray-400 shadow3">06</span>
                                                        <div class="media-body">
                                                            <h6 class="mb-1"><a href="#" class="font-weight-semibold fs-17">OTP Confirmed </a><span class="badge bg-gray-400 ml-2 shadow3" style="color: white;"><button type="button" class="btn bg-gray-400 btn-loading btn-sm"></button>Pending </span></h6>
                                                            <br/>
                                                        </div> 
                                                    @endif
                                                    </div>
                                                </li>
                                                <li class="mt-0 media media-lg">
                                                    <div class="media mt-0">
                                                    @if($data->approved_by != NULL) 
                                                        <span class="latest-timeline1-icon bg-success shadow3">07</span>
                                                        <div class="media mt-0">
                                                            <div class="media-body">
                                                                <h6 class="mb-1"><a href="#" class="font-weight-semibold fs-17">TPRS Updated </a><span class="badge badge-success ml-2"><i class="fe fe-check mr-2"></i>Completed</span></h6>
                                                                <br/>
                                                            </div>
                                                        </div>
                                                    @else 
                                                        <span class="latest-timeline1-icon bg-gray-400 shadow3">07</span>
                                                        <div class="media-body">
                                                            <h6 class="mb-1"><a href="#" class="font-weight-semibold fs-17">TPRS Updated </a><span class="badge bg-gray-400 ml-2 shadow3" style="color: white;"><button type="button" class="btn bg-gray-400 btn-loading btn-sm"></button>Pending </span></h6>
                                                            <br/>
                                                        </div> 
                                                    @endif
                                                    </div>
                                                </li>
                                                <li class="mt-0 media media-lg">
                                                    <div class="media mt-0">
                                                    @if($data->account_number != NULL) 
                                                        <span class="latest-timeline1-icon bg-success shadow3">08</span>
                                                        <div class="media mt-0">
                                                            <div class="media-body">
                                                                <h6 class="mb-1"><a href="#" class="font-weight-semibold fs-17">Account Opened </a><span class="badge badge-success ml-2"><i class="fe fe-check mr-2"></i>Completed</span></h6>
                                                                <br/>
                                                            </div>
                                                        </div>
                                                    @else 
                                                        <span class="latest-timeline1-icon bg-gray-400 shadow3">08</span>
                                                        <div class="media-body">
                                                            <h6 class="mb-1"><a href="#" class="font-weight-semibold fs-17">Account Opened </a><span class="badge bg-gray-400 ml-2 shadow3" style="color: white;"><button type="button" class="btn bg-gray-400 btn-loading btn-sm"></button>Pending </span></h6>
                                                            <br/>
                                                        </div> 
                                                    @endif
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                    </div>
                </div>
            </div>
@if(Auth::user()->role_id == 7 || Auth::user()->role_id == 4 || Auth::user()->role_id == 1 && $data->otp_status != 'CONFIRMED')
            <div class="card">
                <div class="card-body">
                <h5 class="card-title">OTP Confirmation <a href="{{ url('resend_otp') }}/{{ $data->id }}" class="btn btn-outline-light btn-pill btn-sm ml-3">Send code to customer</a></h5>
                    <div class="row">
                        <div class="col-lg-12 col-xl-12 col-md-12 col-sm-12 mb-3">

                                <div class="form-group col-md-12 mb-0">
                                    <div class="input-group input-daterange col-md-12">
                                        {{-- <label class="form-label">Date of Birth <span class="text-red">*</span></label> --}}
                                        <input
                                                type="text"
                                                style="font-size: 20px; font-weight:bolder; letter-spacing: 0.4em;"
                                                name="otp_code"
                                                class="form-control"
                                                id="otp_code"
                                                placeholder="CODE"
                                                oninput="this.value = this.value.toUpperCase();"
                                                maxlength="6"
                                                autocomplete="off"
                                                required
                                            />
                                            <span class="input-group-append mr-2">
                                                <button class="btn btn-info" type="submit" name="btnOTP" value="otpConf" title="Click to confirm OTP code"><i class="si si-key mr-1"></i>Verify Code</button>
                                            </span>
                                            
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
@endif
            {{-- <div class="card">
                <div class="card-body">
                <h5 class="card-title">Download Direct Debit Form</h5>
                    <div class="row">
                        <div class="col-lg-12 col-xl-12 col-md-12 col-sm-12 mb-3">
                            @if($data->dd_attachment)
                                <a  href="{{ url('storage/app/')}}/{{$data->dd_attachment}}" download="{{ url('storage/app/')}}/{{$data->dd_attachment}}" class="btn btn-pill btn-primary mb-3 btn-block"><i class="fe fe-download-cloud"></i> Click to Download Direct Debit Form</a>

                                <div class="form-preview mt-3">
                                    @php
                                        $fileExtension = pathinfo($data->dd_attachment, PATHINFO_EXTENSION);
                                    @endphp
                                    
                                    @if(in_array(strtolower($fileExtension), ['pdf']))
                                        <embed src="{{ url('storage/app/')}}/{{$data->dd_attachment}}" type="application/pdf" width="100%" height="600px">
                                    @elseif(in_array(strtolower($fileExtension), ['jpg', 'jpeg', 'png']))
                                        <img src="{{ url('storage/app/')}}/{{$data->dd_attachment}}" alt="Direct Debit Form" class="img-fluid">
                                    @endif
                                </div>


                            @else
                                <a href="#" class="btn btn-pill btn-light mb-3 btn-block"><i class="fe fe-x-circle"></i> Attachment Not Available  </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div> --}}

            <div class="card">
                <div class="card-body">
                <h5 class="card-title">REQUEST LOCATION</h5>
                    <div class="row">
                        <div class="col-md-12 col-lg-6">
                            <p class="col-md-12 col-lg-12"><b>Requested By :</b> @if(isset($data->request_by)){{ user($data->request_by)->first_name ?? '' }} {{ user($data->request_by)->last_name ?? '' }} @else N/A @endif</p>
                            {{-- <p class="col-md-12 col-lg-12"><b>Date Requested :</b> {{ $data->request_date ?? 'N/A' }}</p> --}}
                        </div>
                        <div class="col-md-12 col-lg-6">
                            <p class="col-md-12 col-lg-12"><b>Date Requested :</b> {{ $data->request_date ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <b><hr class="mt-1 mb-4" style="width: auto; border-top: 1px solid;"></b>
                    <div class="row">
                        <div class="col-md-12 col-lg-6">
                            <p class="col-md-12 col-lg-12"><b>Processed By :</b> @if(isset($data->processed_by)){{ user($data->processed_by)->first_name ?? '' }} {{ user($data->processed_by)->last_name ?? '' }} @else N/A @endif</p>
                            {{-- <p class="col-md-12 col-lg-12"><b>Date Requested :</b> {{ $data->request_date ?? 'N/A' }}</p> --}}
                        </div>
                        <div class="col-md-12 col-lg-6">
                            <p class="col-md-12 col-lg-12"><b>Date Processed :</b> {{ $data->processed_date ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <b><hr class="mt-1 mb-4" style="width: auto; border-top: 1px solid;"></b>
                    <div class="row">
                        <div class="col-md-12 col-lg-6">
                            <p class="col-md-12 col-lg-12"><b>Approved By :</b> @if(isset($data->approved_by)){{ user($data->approved_by)->first_name ?? '' }} {{ user($data->approved_by)->last_name ?? '' }} @else N/A @endif</p>
                            {{-- <p class="col-md-12 col-lg-12"><b>Date Requested :</b> {{ $data->request_date ?? 'N/A' }}</p> --}}
                        </div>
                        <div class="col-md-12 col-lg-6">
                            <p class="col-md-12 col-lg-12"><b>Date Approved :</b> {{ $data->approval_date ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <b><hr class="mt-1 mb-4" style="width: auto; border-top: 1px solid;"></b>
                    <div class="row">
                        <div class="col-md-12 col-lg-6">
                            <p class="col-md-12 col-lg-12"><b>A/C Created By :</b> @if(isset($data->acct_created_by)){{ user($data->acct_created_by)->first_name ?? '' }} {{ user($data->acct_created_by)->last_name ?? '' }} @else N/A @endif</p>
                            {{-- <p class="col-md-12 col-lg-12"><b>Date Requested :</b> {{ $data->request_date ?? 'N/A' }}</p> --}}
                        </div>
                        <div class="col-md-12 col-lg-6">
                            <p class="col-md-12 col-lg-12"><b>Date Created :</b> {{ $data->acct_creation_date ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <b><hr class="mt-1 mb-4" style="width: auto; border-top: 1px solid;"></b>


                    {{-- @foreach ( $data->LoanApproval as $approvals )
                        @if ($approvals->a_stage_info != 'stage_4')
                            <div class="row">
                                <div class="col-md-12 col-lg-6">
                                    <p class="col-md-12 col-lg-12"><b>{{ statusDescription($approvals->a_stage_info) }} :</b> {{ $approvals->approver_name ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-12 col-lg-6">
                                    <p class="col-md-12 col-lg-12"><b>Date Processed :</b> {{ $approvals->approval_date ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <b><hr class="mt-1 mb-4" style="width: auto; border-top: 1px solid;"></b>
                        @endif
                    @endforeach --}}


                    {{-- <div class="row">
                        <div class="col-md-12 col-lg-6">
                            <p class="col-md-12 col-lg-12"><b>HR Confirmation :</b> @if(isset($data->user_id)){{ $data->User->first_name .' '. $data->User->last_name }} @else N/A @endif</p>
                        </div>
                        <div class="col-md-12 col-lg-6">
                            <p class="col-md-12 col-lg-12"><b>Date Processed :</b> {{ $data->request_date ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <b><hr class="mt-1 mb-4" style="width: auto; border-top: 5px solid;"></b>
                    <div class="row">
                        <div class="col-md-12 col-lg-6">
                            <p class="col-md-12 col-lg-12"><b>Credit Risk Recommend :</b> @if(isset($data->user_id)){{ $data->User->first_name .' '. $data->User->last_name }} @else N/A @endif</p>

                        </div>
                        <div class="col-md-12 col-lg-6">
                            <p class="col-md-12 col-lg-12"><b>Date Processed :</b> {{ $data->request_date ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <b><hr class="mt-1 mb-4" style="width: auto; border-top: 5px solid;"></b>
                    <div class="row">
                        <div class="col-md-12 col-lg-6">
                            <p class="col-md-12 col-lg-12"><b>MCC Approval :</b> @if(isset($data->user_id)){{ $data->User->first_name .' '. $data->User->last_name }} @else N/A @endif</p>
                        </div>
                        <div class="col-md-12 col-lg-6">
                            <p class="col-md-12 col-lg-12"><b>Date Processed :</b> {{ $data->request_date ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <b><hr class="mt-1 mb-4" style="width: auto; border-top: 5px solid;"></b>
                    <div class="row">
                        <div class="col-md-12 col-lg-6">
                            <p class="col-md-12 col-lg-12"><b>Business Unit Update :</b> @if(isset($data->user_id)){{ $data->User->first_name .' '. $data->User->last_name }} @else N/A @endif</p>
                        </div>
                        <div class="col-md-12 col-lg-6">
                            <p class="col-md-12 col-lg-12"><b>Date Processed :</b> {{ $data->request_date ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <b><hr class="mt-1 mb-4" style="width: auto; border-top: 5px solid;"></b>
                    <div class="row">
                        <div class="col-md-12 col-lg-6">
                            <p class="col-md-12 col-lg-12"><b>Credit Risk Review :</b> @if(isset($data->user_id)){{ $data->User->first_name .' '. $data->User->last_name }} @else N/A @endif</p>
                        </div>
                        <div class="col-md-12 col-lg-6">
                            <p class="col-md-12 col-lg-12"><b>Date Processed :</b> {{ $data->request_date ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <b><hr class="mt-1 mb-4" style="width: auto; border-top: 5px solid;"></b>
                    <div class="row">
                        <div class="col-md-12 col-lg-6">
                            <p class="col-md-12 col-lg-12"><b>Disbursement Origination :</b> @if(isset($data->user_id)){{ $data->User->first_name .' '. $data->User->last_name }} @else N/A @endif</p>
                        </div>
                        <div class="col-md-12 col-lg-6">
                            <p class="col-md-12 col-lg-12"><b>Date Processed :</b> {{ $data->request_date ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <b><hr class="mt-1 mb-4" style="width: auto; border-top: 5px solid;"></b>
                    <div class="row">
                        <div class="col-md-12 col-lg-6">
                            <p class="col-md-12 col-lg-12"><b>Risk Origination Review :</b> @if(isset($data->user_id)){{ $data->User->first_name .' '. $data->User->last_name }} @else N/A @endif</p>
                        </div>
                        <div class="col-md-12 col-lg-6">
                            <p class="col-md-12 col-lg-12"><b>Date Processed :</b> {{ $data->request_date ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <b><hr class="mt-1 mb-4" style="width: auto; border-top: 5px solid;"></b>
                    <div class="row">
                        <div class="col-md-12 col-lg-6">
                            <p class="col-md-12 col-lg-12"><b>CPU Manager's Approval :</b> @if(isset($data->user_id)){{ $data->User->first_name .' '. $data->User->last_name }} @else N/A @endif</p>
                        </div>
                        <div class="col-md-12 col-lg-6">
                            <p class="col-md-12 col-lg-12"><b>Date Processed :</b> {{ $data->request_date ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <b><hr class="mt-1 mb-4" style="width: auto; border-top: 5px solid;"></b>
                    <div class="row">
                        <div class="col-md-12 col-lg-6">
                            <p class="col-md-12 col-lg-12"><b>CPU Final Disbursement :</b> @if(isset($data->user_id)){{ $data->User->first_name .' '. $data->User->last_name }} @else N/A @endif</p>
                        </div>
                        <div class="col-md-12 col-lg-6">
                            <p class="col-md-12 col-lg-12"><b>Date Processed :</b> {{ $data->request_date ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <b><hr class="mt-1 mb-4" style="width: auto; border-top: 5px solid;"></b> --}}
                    </div>
                </div>
            </div>


        {{-- <div class="card">
            <div class="card-body text-center">
                <div class="h2 m-0 font-weight-bold"><i class="mdi mdi-account-alert text-warning icon-dropshadow-warning mr-3"></i>{{ userLeaveDays($data->user_id)->outstanding_days }}</div>
                <div class="text-muted mb-0"> Outstanding Days</div>
            </div>
        </div>

        <div class="card">
            <div class="card-body text-center">
                <div class="h2 m-0 font-weight-bold"><i class="mdi mdi-account-check text-success icon-dropshadow-success mr-3"></i>{{ userLeaveDays($data->user_id)->days_taken }}</div>
                <div class="text-muted mb-0"> Days Taken</div>
            </div>
        </div>

        <div class="card">
            <div class="card-body text-center">
                <div class="h2 m-0 font-weight-bold"><i class="mdi mdi-account-plus text-primary icon-dropshadow-primary mr-3"></i>{{ userLeaveDays($data->user_id)->total_entitled_to }}</div>
                <div class="text-muted mb-0"> Total Leave Days</div>
            </div>
        </div> --}}

    </div>
</form>

    <!-- HR Modal -->
    @include('workspace.loan.inc.hr-modal')
    @include('workspace.loan.inc.credit-risk-modal')
    <!-- Comments Modal -->
    @include('inc.component.comments-modal')
    <!-- modal -->
@endsection
@push('page_js')
    <script src="{{ asset('theme/views_js/live_note.js')}}"></script>
    <script src="{{ asset('theme/views_js/silent_poster.js')}}"></script>
@endpush
@push('page_js')
<script>

    $(document).on('click', '#hr_btn', function(e) {
        $('#hrModal').modal('show');
    });

    $(document).on('click', '#credit_risk_btn', function(e) {
        $('#creditRiskModal').modal('show');
    });

    $('#existing_loan_repayment').change(function(){
        getDSR();
    });

    $('#monthly_installment').change(function(){
        getDSR();
    });

    $('#existing_loan').change(function(){
        existingLoanInput()
    });

    getDSR();

    existingLoanInput()

    function getDSR() {
    //$('#debt_ratio').val(0);
    var DSR = 0.00;
    var TLP = 0.00;
    var NET = 0.00;
    var MI = 0.00;
    var ELR = 0.00;

    MI = parseFloat($("#monthly_installment").val());
    if(MI === undefined || MI === undefined || MI === null || MI === '')
    {
        MI = 0.00;
    }

    ELR = parseFloat($("#existing_loan_repayment").val());
    if(ELR === undefined || ELR === null || ELR === '')
    {
        ELR = 0.00;
    }

    NET = parseFloat($("#net_salary").val());
    if(NET === undefined || NET === null || NET === '')
    {
        NET = 0.00;
    }

    TLP = MI + ELR;
    DSR = (TLP / NET) * 100;
//alert('mi ' + MI + ': ELR '+ ELR + ': NET ' + NET + ': TLP ' + TLP + ': DSR ' + DSR);
    $('#debt_ratio').val(DSR.toFixed(2));

}

function existingLoanInput(){
    if($('#existing_loan').children("option:selected").val() == "YES"){
        $(".existingInfo").show();
    }
    else{
        $(".existingInfo").hide();
    }
}

$('select[name="branch2"]').on('change', function(){
    $('#changeBranchForm2').attr('action', 'change-request-branch/'+ $(this).val());
});
</script>
@endpush
