@extends('layouts.dashboard')

@section('content')

<div class="card mt-5">
    <div class="card-header">
            <div class="col-md-6">
                    <div class="card-title">LIST OF LOAN APPLICATIONS</div>
            </div>

            <div class="col-md-6">
                @if ($btnNewLoan == 1)
                    <a href="{{url('create')}}">
                        <span class="float-md-right btn btn-group text-center bg-primary me-2" style="color: white;">
                            <i class="angle fa fa-plus mt-1"></i>&nbsp;&nbsp;New Loan</span>
                    </a>
                @endif

                @if($searchDisbursement == 1)
                    <form class="getForm" method="POST" action="{{ url('pre-disbursement/multiple') }}">
                        @csrf

                        <div class="form-group col-md-12 mb-0">
                            <div class="input-group input-daterange col-md-12">
                                {{-- <label class="form-label">Date of Birth <span class="text-red">*</span></label> --}}
                                <input
                                        type="text"
                                        name="payFrom"
                                        class="form-control fc-datepicker"
                                        id="payFrom"
                                        placeholder="YYYY-MM-DD"
                                        value="{{ $payFrom ?? '' }}"
                                        required
                                        readonly
                                    />
                                <span class="input-group-addon">to</span>
                                <input
                                        type="text"
                                        name="payTo"
                                        class="form-control fc-datepicker mr-2"
                                        id="payTo"
                                        placeholder="YYYY-MM-DD"
                                        value="{{ $payTo ?? '' }}"
                                        required
                                        readonly
                                    />
                                    
                                    @if($btnExport != 1)  
                                    <span class="input-group-append mr-2">
                                                    <button name="btnType" value="btnSearch" class="btn btn-info" type="submit" title="Search"><i class="si si-magnifier"></i></button>
                                                </span>         
                                    <span class="input-group-append mr-2">
                                                    <button name="btnType" value="btnDisbursement" class="btn btn-primary" type="submit"><i class="si si-wallet" onclick="return confirmSubmit()" title="Pre-Disbursed"></i> Pre-Disbursed</button>
                                                </span>
                                    @else
                                    <span class="input-group-append mr-2">
                                        <button name="btnType" value="btnExport" class="btn btn-info" type="submit" title="Search"><i class="si si-magnifier"></i></button>
                                    </span>
                                    <span class="input-group-append" title="Export" onclick="return callExport()"><a href="#" class="btn btn-primary"><i class="si si-docs"></i> Export</a></span>
                                    @endif
                            </div>
                        </div>
                    </form>
                @endif






        {{-- <a href="{{route('workspace.funds-transfer.home')}}">
                <span class="float-md-right mr-3 btn btn-group text-center bg-success me-2" style="color: white;"><i class="angle fa fa-backward mt-1"></i> Back</span>
            </a> --}}
        </div>

        </div>
    <div class="card-body">
        <div class="table-responsive">
            <input type="text" id="tableSearch" class="form-control" placeholder="Search...">
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
                @foreach($requestData as $data)
                    <tr>
                        <td>{{ $data->id }}</td>
                        <td class="d-flex">
                            @if($data->pp_attachment != null || !empty($data->pp_attachment))
                            <img class="avatar-xl rounded-circle mr-3" src="{{ url('storage/app/')}}/{{$data->pp_attachment}}" alt="Image description">
                            @else
                            <img class="avatar-xl rounded-circle mr-3" src="{{asset('theme/assets/images/users/16.jpg')}}" alt="Image description">
                            @endif
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
                            @if($data->stage_info != 'stage_0_1')
                                <a class="btn btn-primary btn-sm mb-2" href="{{ url('details') }}/{{ $data->id }}"><i class="fa fa-eye"></i> View</a>
                            @endif
                            @if($data->stage_info == 'stage_0_1')
                                <a class="btn btn-warning btn-sm mb-2" href="{{ url('create') }}/{{ $data->id }}"><i class="fa fa-eye"></i> Resume</a>
                            @endif

                            @if($data->stage_info == 'stage_4' && $searchDisbursement == 1)
                                <form id="form{{ $data->id }}" class="getForm" method="POST" action="{{ url('pre-disbursement/single') }}">@csrf
                                    <button class="btn btn-primary" id="btn{{ $data->id }}" type="submit" onclick="return confirmSubmit()"><i class="si si-wallet" title="Pre-Disbursed"></i></button>
                                    <input type="hidden" name="request_id" id="{{ $data->id }}" value="{{ $data->id }}">
                                </form>
                            {{-- <a class="btn btn-warning btn-sm" href="{{ url('create') }}/{{ $data->id }}"><i class="fa fa-pencil"></i> Amend</a> --}}
                            @endif
                            {{-- @if($data->stage_info == 'stage_0' && $btnAmend == 1)<a class="btn btn-warning btn-sm" href="{{ url('create') }}/{{ $data->id }}"><i class="fa fa-pencil"></i> Amend</a>@endif --}}
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

@endsection
@push('page_js')
    <script src="{{ asset('theme/views_js/silent_poster.js')}}"></script>
    <script>
        $(document).ready(function() {
            // Handle direct search from the page
            $("#tableSearch").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                filterTable(value);
            });

            // Handle search parameter from URL when redirected
            var urlParams = new URLSearchParams(window.location.search);
            var searchParam = urlParams.get('search');
            if (searchParam) {
                $("#tableSearch").val(searchParam);
                filterTable(searchParam.toLowerCase());
            }

            function filterTable(value) {
                $("#example1 tbody tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            }
        });
    </script>
@endpush
@push('page_js')
    <script>
        function confirmSubmit() {
            // Display an alert before form submission
            if (confirm('Are you sure you want to pre-disburse the selected request?')) {
                document.querySelector('.getForm').submit(); // Submit the form
                return true;
            } else {
                return false; // Cancel form submission
            }
        }

        function callExport() {
            
            if (!$('#payFrom').val() || !$('#payTo').val()) {
                return false;
            }
            window.location = {!! json_encode(url('export-pre-disbursement')) !!} + '/' + $('#payFrom').val() +'/' + $('#payTo').val();
            
        }
    </script>
@endpush