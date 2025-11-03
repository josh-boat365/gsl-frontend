<div class="modal fade" id="report-modal" role="dialog"  aria-hidden="true">
    <div class="modal-dialog modal-dialog-right chatbox" role="document">

        <form class="reportForm" method="post" action="">
            @csrf
            <div class="modal-content chat border-0">
                <div class="card overflow-hidden mb-0 border-0">
                    <!-- action-header -->
                    <div class="action-header">
                        <div class="float-left hidden-xs d-flex ml-2">
                            <div class="img_cont mr-3">
                                <i class="fa fa-bell fs-25 rounded-circle mt-3 user_img avatar avatar-md"></i>
                            </div>
                            <div class="align-items-center mt-2 text-white">
                                <h5 class="mb-0">Run Report</h5>
                                <span class="dot-label bg-success"></span><span class="mr-3 fs-12">JENNIFER BULMA</span><br>
                            </div>
                        </div>
                        <ul class="ah-actions actions align-items-center">
                            <li>
                                <a href="#"  class="" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true"><svg class="svg-icon" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12 19 6.41z"/></svg></span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-lg-12 col-md-12">
                            <div class="card-body">
                                <div class="wd-20 mg-b-30">

                                </div>
                                <div class="form-group">
                                    <label class="form-label"> Select2 with search box</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <svg class="svg-icon" xmlns="http://www.w3.org/2000/svg" height="18" viewBox="0 0 24 24" width="18"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M20 3h-1V1h-2v2H7V1H5v2H4c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 2v3H4V5h16zM4 21V10h16v11H4z"/><path d="M4 5.01h16V8H4z" opacity=".3"/></svg>
                                            </div>
                                        </div>
                                        <input class="form-control fc-datepicker" placeholder="MM/DD/YYYY" type="text">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label"> Select2 with search box</label>
                                    <select class="form-control select2-show-search" data-placeholder="Choose one (with searchbox)">
                                        <optgroup label="Mountain Time Zone">
                                            <option value="AZ">Arizona</option>
                                            <option value="CO">Colorado</option>
                                        </optgroup>

                                    </select>
                                </div>

                                <div class="form-group">
                                    <label class="form-label"> Text box</label>
                                    <input class="form-control mb-4" placeholder="Textarea" rows="3">
                                </div>

                                <div class="form-group">
                                    <label class="form-label"> Select2 with search box</label>
                                    <textarea class="form-control mb-4" placeholder="Textarea" rows="3"></textarea>
                                </div>
                            </div>
                    </div>

                </div>
                <button type="submit" class="btn  btn-lg btn-primary">SAVE</button>
            </div>
        </form>


    </div>
</div>