<div id="creditRiskModal" class="modal fade">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content ">
            <div class="modal-header pd-x-20">
                <h6 class="modal-title"><span>Credit Risk Input</span></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pd-20">
                <div class="card-body pb-2">

                    <form action="{{url('credit-risk-update')}}/{{ $data->id }}" enctype="multipart/form-data" method="POST" class="needs-validation was-validated">
                            @csrf
                        <div class="row row-sm">

                                        <div class="form-group col-md-4 mb-0">
                                            <div class="form-group">
                                                <label class="form-label">Interest Rate</label>
                                                <input
                                                        type="number"
                                                        min="1"
                                                        name="interest_rate"
                                                        class="form-control is-invalid state-invalid"
                                                        id="interest_rate"
                                                        value="{{$data->interest_rate ?? ''}}"
                                                        placeholder="0"
                                                        required=""
                                                        step="any"
                                                />
                                            </div>
                                        </div>

                                        <div class="form-group col-md-4 mb-0">
                                            <div class="form-group">
                                                <label class="form-label">Loan Tenor <i>(in months)</i></label>
                                                <input
                                                        type="number"
                                                        name="loan_tenor"
                                                        class="form-control fc-datepicker"
                                                        id="loan_tenor"
                                                        value="{{$data->loan_tenor ?? ''}}"
                                                        placeholder="0"
                                                        required=""
                                                        step="any"
                                                    />
                                            </div>
                                        </div>

                                        <div class="form-group col-md-4 mb-0">
                                            <div class="form-group">
                                                <label class="form-label">Disbursement Amount</label>
                                                <input
                                                        type="number"
                                                        min="1"
                                                        name="recommend_amount"
                                                        class="form-control is-invalid state-invalid"
                                                        id="recommend_amount"
                                                        value="{{$data->recommend_amount ?? ''}}"
                                                        placeholder="0.00"
                                                        required=""
                                                        step="any"
                                                />
                                            </div>
                                        </div>

                                        <div class="form-group col-md-4 mb-0">
                                            <div class="form-group">
                                                <label class="form-label">Monthly Instalment</label>
                                                <input
                                                        type="number"
                                                        min="1"
                                                        name="monthly_installment"
                                                        class="form-control is-invalid state-invalid"
                                                        id="monthly_installment"
                                                        value="{{$data->monthly_installment ?? ''}}"
                                                        placeholder="0.00"
                                                        required=""
                                                        step="any"
                                                />
                                            </div>
                                        </div>

                                        <div class="form-group col-md-4 mb-0">
                                            <div class="form-group">
                                                <label class="form-label">Sum of Existing Loan Repayment</label>
                                                <input
                                                        type="number"
                                                        name="existing_loan_repayment"
                                                        class="form-control is-invalid state-invalid"
                                                        id="existing_loan_repayment"
                                                        value="{{$data->existing_loan_repayment ?? 0}}"
                                                        placeholder="0.00"
                                                        step="any"
                                                />
                                            </div>
                                        </div>

                                        <div class="form-group col-md-4 mb-0">
                                            <div class="form-group">
                                                <label class="form-label">Debt Service Ratio-After</label>
                                                <input
                                                        type="number"
                                                        max="40"
                                                        name="debt_ratio"
                                                        class="form-control is-invalid state-invalid"
                                                        id="debt_ratio"
                                                        value="{{$data->debt_ratio ?? ''}}"
                                                        placeholder="0.00"
                                                        required=""
                                                        step="any"
                                                />
                                            </div>
                                        </div>

                                        <div class="form-group col-md-4 mb-0">
                                            <div class="form-group">
                                                <label class="form-label">Guarantor Provided</label>
                                                <select name="guarantor_provided" class="branch form-control select2-show-search" data-placeholder="Select" required="">
                                                    <option selected value="" disabled="disabled">Select If Guarantor Provided</option>
                                                    <option {{$data->guarantor_provided == 'YES' ? 'selected' : ''}} value="YES">YES</option>
                                                    <option {{$data->guarantor_provided == 'NO' ? 'selected' : ''}} value="NO">NO</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group col-md-4 mb-0">
                                            <div class="form-group">
                                                <label class="form-label">Pension Form Sined</label>
                                                <select name="Pension_form_sined" class="branch form-control select2-show-search" data-placeholder="Select" required="">
                                                    <option selected value="" disabled="disabled">Select If Pension Form Sined </option>
                                                    <option {{$data->Pension_form_sined == 'YES' ? 'selected' : ''}} value="YES">YES</option>
                                                    <option {{$data->Pension_form_sined == 'NO' ? 'selected' : ''}} value="NO">NO</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group col-md-4 mb-0">
                                            <div class="form-group">
                                                <label class="form-label">Existing Loan</label>
                                                <select name="existing_loan" id="existing_loan" class="branch form-control select2-show-search" data-placeholder="Select" required="">
                                                    <option selected value="" disabled="disabled">Select If Existing Loan Running </option>
                                                    <option {{$data->existing_loan == 'YES' ? 'selected' : ''}} value="YES">YES</option>
                                                    <option {{$data->existing_loan == 'NO' ? 'selected' : ''}} value="NO">NO</option>
                                                </select>
                                            </div>
                                        </div>

                                        {{-- <div class="form-group col-md-4 mb-0">
                                            <div class="form-group">
                                                <label class="form-label">Existing Loan Type & Balance</label>
                                                <select name="existing_loan_type" class="branch form-control select2-show-search" data-placeholder="Select">
                                                    <option selected value="" disabled="disabled">Select Existing Loan Type<</option>
                                                    <option {{$data->existing_loan_type == 'PERSONAL LOAN' ? 'selected' : ''}} value="PERSONAL LOAN">PERSONAL LOAN</option>
                                                    <option {{$data->existing_loan_type == 'EDUCATION LOAN' ? 'selected' : ''}} value="EDUCATION LOAN">EDUCATION LOAN</option>
                                                    <option {{$data->existing_loan_type == 'RENT LOAN' ? 'selected' : ''}} value="RENT LOAN">RENT LOAN</option>
                                                    <option {{$data->existing_loan_type == 'CAR LOAN' ? 'selected' : ''}} value="CAR LOAN">CAR LOAN</option>
                                                </select>
                                            </div>
                                        </div> --}}

                                        <div class="form-group col-md-12 mb-0 existingInfo">
                                            <div class="form-group">
                                                <label class="form-label">Existing Loan Type & Balance</label>
                                                <input
                                                        type="text"
                                                        name="existing_loan_info"
                                                        class="form-control is-invalid state-invalid"
                                                        id="existing_loan_info"
                                                        value="{{$data->existing_loan_info ?? ''}}"
                                                        placeholder="eg.: Loan Type (Balance)"
                                                        step="any"
                                                />
                                            </div>
                                        </div>

                                        {{-- <div class="form-group col-md-4 mb-0">
                                            <div class="form-group">
                                                <label class="form-label">P.F Balance</label>
                                                <input
                                                        type="number"
                                                        min="1"
                                                        name="pf_balance"
                                                        class="form-control is-invalid state-invalid"
                                                        id="pf_balance"
                                                        value="{{$data->pf_balance ?? ''}}"
                                                        placeholder="0.00"
                                                        step="any"
                                                />
                                            </div>
                                        </div> --}}

                            <div class="col-lg-12">
                                <div class="form-group">
                                    <textarea
                                                name="risk_review_remark"
                                                class="form-control mb-4 is-invalid state-invalid"
                                                placeholder="Other Remarks"
                                                id="risk_review_remark"
                                                rows="2"
                                    >{{$data->risk_review_remark ?? ''}}</textarea>
                                    <input type="hidden" name="risk_request_id" id="risk_request_id" value="{{$data->id}}" />
                                    <input type="hidden" name="action_type" id="action_type" value="" />
                                </div>
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
