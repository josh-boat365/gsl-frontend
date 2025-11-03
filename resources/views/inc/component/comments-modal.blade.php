<div id="commentModal" class="modal fade">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content ">
            <div class="modal-header pd-x-20">
                <h6 class="modal-title"><span class="comment_title"></span></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pd-20">
                <div class="card-body pb-2">

                    <form action="{{url('request-approval')}}/{{ $data->id }}" enctype="multipart/form-data" method="POST" class="needs-validation was-validated">
                        @csrf
                        <div class="row row-sm">

                            <div id="decline-selector" class="col-lg-12">

                            </div>

                            <div class="col-lg-12">
                                    <div class="form-group">
                                        <textarea name="comment_body" class="form-control mb-4 is-invalid state-invalid" placeholder="Textarea (invalid state)" required="" rows="3"></textarea>
                                        <input type="hidden" name="request_id" id="request_id" value="{{ $data->id }}" />
                                        <input type="hidden" name="processing_stage" value="{{ $data->stage_info }}" />
                                    </div>
                                </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- modal-body -->

        </div>
    </div><!-- modal-dialog -->
</div>
