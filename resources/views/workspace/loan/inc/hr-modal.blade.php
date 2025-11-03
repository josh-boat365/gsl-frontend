<div id="hrModal" class="modal fade">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content ">
            <div class="modal-header pd-x-20">
                <h6 class="modal-title"><span>Supervisor Input</span></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pd-20">
                <div class="card-body pb-2">

                    <form action="{{url('hr-update')}}/{{ $data->id }}" enctype="multipart/form-data" method="POST" class="needs-validation was-validated">
                        @csrf
                        <div class="row row-sm">

                                        <div class="form-group col-md-6 mb-0">
                                            <div class="form-group">
                                                <label class="form-label">Disbursed Amount</label>
                                                <input
                                                        type="number"
                                                        name="disbursed_amount"
                                                        class="form-control is-invalid state-invalid"
                                                        id="disbursed_amount"
                                                        value="{{$data->disbursed_amount ?? ''}}"
                                                        placeholder="0.00"
                                                        step="0.01"
                                                        required=""
                                                />
                                            </div>
                                        </div>

                                        <div class="form-group col-md-6 mb-0">
                                            <div class="form-group">
                                                <label class="form-label">Monthly Installment</label>
                                                <input
                                                        type="number"
                                                        name="monthly_installment"
                                                        class="form-control is-invalid state-invalid"
                                                        id="monthly_installment"
                                                        value="{{$data->monthly_installment ?? ''}}"
                                                        placeholder="0.00"
                                                        step="0.01"
                                                        required=""
                                                />
                                            </div>
                                        </div>

                                        <div class="form-group col-md-6 mb-0">
                                            <div class="form-group">
                                                <label class="form-label">Account Number</label>
                                                <input
                                                        type="number"
                                                        name="account_number"
                                                        class="form-control is-invalid state-invalid"
                                                        id="account_number"
                                                        value="{{$data->account_number ?? ''}}"
                                                        placeholder="0"
                                                />
                                            </div>
                                        </div>

                                        <div class="form-group col-md-3 mb-0">
                                            <div class="form-group">
                                                <label class="form-label">Customer Number</label>
                                                <input
                                                        type="number"
                                                        name="customer_number"
                                                        class="form-control is-invalid state-invalid"
                                                        id="customer_number"
                                                        value="{{$data->customer_number ?? ''}}"
                                                        placeholder="0"
                                                />
                                            </div>
                                        </div>

                                        <div class="form-group col-md-3 mb-0">
                                            <div class="form-group">
                                                <label class="form-label">Rate</label>
                                                <input
                                                        type="text"
                                                        name="rate"
                                                        class="form-control is-invalid state-invalid"
                                                        id="rate"
                                                        value="{{$data->rate ?? 0}}"
                                                        placeholder="0"
                                                />
                                            </div>
                                        </div>

                                        <div class="form-group col-md-3 mb-0">
                                            <div class="form-group">
                                                <label class="form-label">Tenure</label>
                                                <input
                                                        type="text"
                                                        name="tenure"
                                                        class="form-control is-invalid state-invalid"
                                                        id="tenure"
                                                        value="{{$data->tenure ?? 0}}"
                                                        placeholder="0"
                                                />
                                            </div>
                                        </div>

                                        <div class="form-group col-md-3 mb-0">
                                            <div class="form-group">
                                                <label class="form-label">Mandate Number</label>
                                                <input
                                                        type="text"
                                                        name="mandate_number"
                                                        class="form-control is-invalid state-invalid"
                                                        id="mandate_number"
                                                        value="{{$data->mandate_number ?? 0}}"
                                                        placeholder="0"
                                                />
                                            </div>
                                        </div>

                                        <div class="form-group col-md-3 mb-0">
                                            <div class="form-group">
                                                <label class="form-label">Mandate PIN</label>
                                                <input
                                                        type="text"
                                                        name="mandate_pin"
                                                        class="form-control is-invalid state-invalid"
                                                        id="mandate_pin"
                                                        value="{{$data->mandate_pin ?? 0}}"
                                                        placeholder="0"
                                                />
                                            </div>
                                        </div>

                                        <div class="form-group col-md-3 mb-0">
                                            <div class="form-group">
                                                <label class="form-label">OTP Code</label>
                                                <input
                                                        type="text"
                                                        name="otp_code"
                                                        class="form-control is-invalid state-invalid"
                                                        id="otp_code"
                                                        value="{{$data->otp_code ?? ""}}"
                                                        placeholder="CODE"
                                                />
                                            </div>
                                        </div>

                            <div class="col-lg-12">
                                <div class="form-group">
                                    <textarea
                                                name="processor_remark"
                                                class="form-control mb-4 is-invalid state-invalid"
                                                placeholder="Other Remarks"
                                                id="processor_remark"
                                                rows="2"
                                    >{{$data->processor_remark ?? ''}}</textarea>
                                    <input type="hidden" name="request_id" id="request_id" value="{{$data->id}}" />
                                </div>
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
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Update</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- modal-body -->

        </div>
    </div><!-- modal-dialog -->
</div>
