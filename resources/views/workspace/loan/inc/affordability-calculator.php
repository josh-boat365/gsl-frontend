<div id="calculatorModal" class="modal fade">
    <div class="modal-dialog" role="document">
        <div class="modal-content ">
            <div class="modal-header pd-x-20">
                <h6 class="modal-title"><span>Affordability Calculator</span></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pd-20">
                <div class="card-body pb-2">

                    <form action="" enctype="multipart/form-data" method="POST" class="needs-validation was-validated">

                        <div class="row row-sm">

                                        <div class="form-group col-md-6 mb-0">
                                            <div class="form-group">
                                                <label class="form-label">Loan Amount</label>
                                                <input
                                                        type="number"
                                                        min="1"
                                                        name="cl_loan_amount"
                                                        id="cl_loan_amount"
                                                        class="form-control is-invalid state-invalid"
                                                        value=""
                                                        placeholder="0"
                                                        required
                                                />
                                            </div>
                                        </div>

                                        <div class="form-group col-md-3 mb-0">
                                            <div class="form-group">
                                                <label class="form-label">Tenor</label>
                                                <input
                                                        type="number"
                                                        min="1"
                                                        name="cl_tenor"
                                                        class="form-control is-invalid state-invalid"
                                                        id="cl_tenor"
                                                        value=""
                                                        placeholder="0"
                                                        required
                                                />
                                            </div>
                                        </div>

                                        <div class="form-group col-md-3 mb-0">
                                            <div class="form-group">
                                                <label class="form-label">Rate</label>
                                                <input
                                                        type="number"
                                                        min="1"
                                                        name="cl_rate"
                                                        class="form-control is-invalid state-invalid"
                                                        id="cl_rate"
                                                        value=""
                                                        placeholder="0"
                                                        required
                                                />
                                            </div>
                                        </div>

                                        <div class="form-group col-md-6 mb-0">
                                            <div class="form-group">
                                                <label class="form-label">Net Salary</label>
                                                <input
                                                        type="number"
                                                        name="cl_net_salary"
                                                        class="form-control is-invalid state-invalid"
                                                        id="cl_net_salary"
                                                        value=""
                                                        placeholder="0.00"
                                                        step="any"
                                                />
                                            </div>
                                        </div>

                                        <div class="form-group col-md-6 mb-0">
                                            <div class="form-group">
                                                <label class="form-label">Existing Loan Repayment</label>
                                                <input
                                                        type="number"
                                                        name="cl_ex_repayment"
                                                        class="form-control is-invalid state-invalid"
                                                        id="cl_ex_repayment"
                                                        value=""
                                                        placeholder="0.00"
                                                        step="any"
                                                />
                                            </div>
                                        </div>

                            <div class="col-lg-12">

                            </div>
                        </div>
                        <div class="table-responsive">
                    <table class="table card-table table-vcenter table-bordered table-primary mb-0 text-center" style="table-layout: fixed;">
                        <thead  class="bg-primary text-white">
                            <tr >
                                <th class="text-white">Monthly Repayment</th>
                                <th class="text-white">DSR <span class="text-muted app-sidebar__user-name text-sm">must be less or = 40%</span></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row"><span id="monthly_installment">₵0.00</span></th>
                                <td><span id="get_DSR">0%</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                        <div class="modal-footer">
                            <a href="#" id="btn_calculate" class="btn btn-primary">Calculate</a>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- modal-body -->

        </div>
    </div><!-- modal-dialog -->
</div>
