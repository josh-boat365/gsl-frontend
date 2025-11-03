@extends('layouts.dashboard')

@section('content')
    <div class="col-lg-6 col-md-6">
    <form  method="post" class="card  mt-3">
        <div class="card-header">
            <h3 class="card-title">Select2 elements</h3>
        </div>
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
    </form>
    </div>
@endsection
@push('page_js')
    <script src="{{ asset('theme/views_js/live_note.js')}}"></script>
    <script src="{{ asset('theme/views_js/silent_poster.js')}}"></script>
@endpush