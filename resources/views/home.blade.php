@extends('layouts.dashboard')

@section('content')
<!--Page header-->
<div class="page-header">
    <div class="page-leftheader">
        <h4 class="page-title">Dashboard</h4>
    </div>

</div>
<!--End Page header-->
@if (Auth::user()->role_id == 3)
<!--Agents-->
    <!--Row-->
    <div class="row">
        <div class="col-xl-12 col-lg-6">
            <div class="row">
                <div class="col-xl-6 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <p class="mb-1 fs-18 text-muted">Request In Process</p>
                                    <h2 class="mb-0 font-weight-bold">{{ $in_process }}</h2>
                                </div>
                                <div class="col col-auto">
                                    <i class="mdi mdi-account-convert fs-60 text-warning icon-dropshadow-warning mr-3"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <p class="mb-1 fs-18 text-muted">Total Approved</p>
                                    <h2 class="mb-0 font-weight-bold">{{ $total_approved }}</h2>
                                </div>
                                <div class="col col-auto">
                                    <i class="mdi mdi-account-edit fs-60 text-primary icon-dropshadow-primary mr-3"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>


            <div class="row">
                <div class="col-xl-6 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <p class="mb-1 fs-18 text-muted">Total Disbursed</p>
                                    <h2 class="mb-0 font-weight-bold">{{ $total_disbursed }}</h2>
                                </div>
                                <div class="col col-auto">
                                    <i class="mdi mdi-account-check fs-60 text-success icon-dropshadow-success mr-3"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <p class="mb-1 fs-18 text-muted">Total Declined</p>
                                    <h2 class="mb-0 font-weight-bold">{{$total_declined}}</h2>
                                </div>
                                <div class="col col-auto">
                                    <i class="mdi mdi-account-remove fs-60 text-danger icon-dropshadow-danger mr-3"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Row-->

    <!-- Row-->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">LATEST LOAN REQUEST</h3>
                </div>
                <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered text-nowrap" id="example1">
                    <thead>
                        <tr>
                            <th class="wd-15p border-bottom-0">id</th>
                            <th class="wd-15p border-bottom-0">Name</th>
                            <th class="wd-15p border-bottom-0">Amount</th>
                            <th class="wd-15p border-bottom-0">Branch</th>
                            <th class="wd-25p border-bottom-0">Status</th>
                            <th class="wd-20p border-bottom-0">agent</th>
                            <th class="wd-25p border-bottom-0">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($user_request as $data)
                        <tr>
                            <td>{{ $data->id }}</td>
                            <td class="d-flex"><img class="avatar-xl rounded shadow mr-3" src="{{ url('storage/app/')}}/{{$data->pp_attachment}}" alt="Image description">
                                <div class="ml-3 mt-2">
                                    <h5 class="mb-0 text-dark">{{ $data->LoanCustomer->first_name ?? " " }} {{ $data->LoanCustomer->surname ?? " " }} {{ $data->LoanCustomer->middle_name ?? " " }}</h5>
                                    <p class="mb-0  fs-13 text-muted">{{ $data->LoanCustomer->occupation ?? " " }}</p>
                                </div>
                            </td>
                            <td>{{ $data->requested_amount }}</td>
                            <td>{{ getBranchDescription($data->branch_code) }}</td>
                            <td>{!! statusBadge($data->stage_info, $data->updated_at, $data->created_at) !!}</td>
                            {{-- <td>{{ statusBadge($data->stage_info) }}</td> --}}
                            <td>{{ user($data->request_by)->first_name ?? '' }} {{ user($data->request_by)->last_name ?? '' }}</td>
                            <td>
                                <a class="btn btn-primary btn-sm" href="{{ url('details') }}/{{ $data->id }}"><i class="fa fa-eye"></i> View</a>
                                @if($data->stage_info == 'stage_0' && $btnAmend == 1)<a class="btn btn-warning btn-sm" href="{{ url('create') }}/{{ $data->id }}"><i class="fa fa-pencil"></i> Amend</a>@endif
                                    {{-- <a class="dropdown-item" href="#"><i class="angle fa fa-close"></i> Decline</a>
                                    <a class="dropdown-item  open-reminder-modal" href="#"><i class="angle fa fa-bell"></i>Set Reminder</a> --}}
                            </td>
                        </tr>






                        
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
            </div>
        </div>
    </div>
    <!-- End Row -->
<!-- End Agents -->
@else
<!--Others-->
<!--Row-->
    <div class="row">
        <div class="col-xl-12 col-lg-6">
            <div class="row">
                <div class="col-xl-6 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <p class="mb-1 fs-18 text-muted">Request In Process</p>
                                    <h2 class="mb-0 font-weight-bold">{{ $get_in_process }}</h2>
                                </div>
                                <div class="col col-auto">
                                    <i class="mdi mdi-account-convert fs-60 text-warning icon-dropshadow-warning mr-3"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <p class="mb-1 fs-18 text-muted">Total Approved</p>
                                    <h2 class="mb-0 font-weight-bold">{{ $get_total_approved }}</h2>
                                </div>
                                <div class="col col-auto">
                                    <i class="mdi mdi-account-edit fs-60 text-primary icon-dropshadow-primary mr-3"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>


            <div class="row">
                <div class="col-xl-6 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <p class="mb-1 fs-18 text-muted">Total Disbursed</p>
                                    <h2 class="mb-0 font-weight-bold">{{ $get_total_disbursed }}</h2>
                                </div>
                                <div class="col col-auto">
                                    <i class="mdi mdi-account-check fs-60 text-success icon-dropshadow-success mr-3"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <p class="mb-1 fs-18 text-muted">Total Declined</p>
                                    <h2 class="mb-0 font-weight-bold">{{$get_total_declined}}</h2>
                                </div>
                                <div class="col col-auto">
                                    <i class="mdi mdi-account-remove fs-60 text-danger icon-dropshadow-danger mr-3"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Row-->
<!-- End Others -->
@endif
{{-- <div class="row row-deck">
</div> --}}
<!--End row-->

@endsection
