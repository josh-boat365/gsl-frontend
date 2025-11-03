<div class="card mt-5">
    <form method="POST" action="{{ url('import')}}" autocomplete="off" enctype="multipart/form-data">
        {{ csrf_field() }}
    <div class="card-header">
            <div class="col-md-5">
                    <div class="card-title">Online Ghana Card Update Requests</div>
            </div>

        </div>
    </form>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered text-nowrap" id="example1">
                <thead>
                    <tr>
                        <th class="wd-15p border-bottom-0">Name</th>
                        <th class="wd-15p border-bottom-0">CustomerN0.</th>
                        <th class="wd-20p border-bottom-0">Phone</th>
                        <th class="wd-15p border-bottom-0">Branch</th>
                        <th class="wd-10p border-bottom-0">Request Date</th>
                        <th class="wd-25p border-bottom-0">Status</th>
                        <th class="wd-25p border-bottom-0">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @empty($requestData)
                    @else
                    @foreach($requestData as $data)
                    <tr>
                        <td>{{ $data->account_name }}</td>
                        <td>{{ $data->customer_number }}</td>
                        <td>{{ $data->acct_phone_num }}</td>
                        <td>{{ $data->account_branch }}</td>
                        <td>{{ $data->created_at }}</td>
                        <td>{!! statusBadge($data->update_status) !!}</td>
                        <td>
                            <a class="btn btn-primary btn-sm" href="{{ url('view-details') }}/{{$data->id}}"><i class="angle fa fa-send"></i> View</a>
                                {{-- <a class="dropdown-item" href="#"><i class="angle fa fa-edit"></i> Reassign</a>
                                <a class="dropdown-item" href="#"><i class="angle fa fa-close"></i> Decline</a>
                                <a class="dropdown-item  open-reminder-modal" href="#"><i class="angle fa fa-bell"></i>Set Reminder</a> --}}
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    @endempty
                </tbody>
            </table>
        </div>
    </div>
</div>