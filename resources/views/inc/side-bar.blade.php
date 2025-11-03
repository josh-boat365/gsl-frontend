<div class="app-sidebar app-sidebar2">
    <div class="app-sidebar__logo">
      <a class="header-brand" href="{{ route('home') }}">
        <h4 class="header-brand-img desktop-lgo">GSL LOAN PORTAL</h4>
        <img src="{{asset('theme/assets/images/brand/logo1.png')}}" class="header-brand-img dark-logo" alt="Dashtic logo">
        <img src="{{asset('theme/assets/images/brand/favicon.png')}}" class="header-brand-img mobile-logo" alt="Dashtic logo">
        <img src="{{asset('theme/assets/images/brand/favicon1.png')}}" class="header-brand-img darkmobile-logo" alt="Dashtic logo">
      </a>
    </div>
  </div>
  <aside class="app-sidebar app-sidebar3">
    <div class="app-sidebar__user">
      <div class="dropdown user-pro-body text-center">
        <div class="user-pic">
          <img src="{{asset('theme/assets/images/users/16.jpg')}}" alt="user-img" class="avatar-xl rounded-circle mb-1">
        </div>
        <div class="user-info">
          <h5 class=" mb-1 font-weight-bold">{{Auth::user()->first_name}} {{Auth::user()->last_name}}</h5>
          <span class="text-muted app-sidebar__user-name text-sm">{{Auth::user()->email}}</span>
          <span class="badge badge-primary badge-pill mt-2">{{getBranchName(Auth::user()->branch_code)->branch_name}}</span>
        </div>
      </div>
    </div>
    <ul class="side-menu">
{{--
'stage_0' => 'Requested By',
'stage_1' => 'BM / HOD / Supervisor',
'stage_2' => 'HR Confirmation',
'stage_3' => 'Credit Risk Recommend',
'stage_4' => 'MCC Approval',
'stage_5' => 'Business Unit Update',
'stage_6' => 'Credit Risk Review',
'stage_7' => 'Disbursement Origination',
'stage_8' => 'Risk Origination Review',
'stage_9' => 'CPU Manager\'s Approval',
'stage_10' => 'CPU Final Disbursement',
'stage_11' => 'Completed and Disbursed',
--}}
@if(Auth::user()->user_group == "external")
      <li class="slide">
        <a class="side-menu__item" href="{{ route('home') }}">
          <i class="fa fa-home fs-25 mr-2"></i>
        <span class="side-menu__label">Dashboard</span><i class="angle fa fa-angle-right"></i></a>
      </li>

      <li class="slide">
        <a class="side-menu__item" href="{{ url('list') }}">
          <i class="zmdi zmdi-chart-donut fs-25 mr-2"></i>
          <span class="side-menu__label">Loans</span><i class="angle fa fa-angle-right"></i></a>
      </li>

      <li class="slide">
        <a class="side-menu__item" href="{{ url('list') }}/stage_0_1">
          <i class="zmdi zmdi-chart-donut fs-25 mr-2"></i>
          <span class="side-menu__label">Saved Requests</span><i class="angle fa fa-angle-right"></i></a>
      </li>
{{--========== Agents==========--}}
{{--@if (Auth::user()->role_id == 3 || Auth::user()->role_id == 1)
      

      
@endif--}}
{{--========== Supervisor==========--}}
@if (Auth::user()->role_id == 7 || Auth::user()->role_id == 1)
    <li class="slide">
        <a class="side-menu__item" href="{{ url('list') }}/stage_1">
        <i class="mdi mdi-account-convert fs-25 mr-2"></i>
        <span class="side-menu__label">Processing</span><i class="angle fa fa-angle-right"></i></a>
    </li>
@endif
    {{--========== HQ BDO (15)==========--}}
@if (Auth::user()->role_id == 4 || Auth::user()->role_id == 1)
    <li class="slide">
        <a class="side-menu__item" href="{{ url('list') }}/stage_3">
        <i class="mdi mdi-account-check fs-25 mr-2"></i>
        <span class="side-menu__label">Approval</span><i class="angle fa fa-angle-right"></i></a>
    </li>
        <li class="slide">
        <a class="side-menu__item" href="{{ url('list') }}/stage_4">
        <i class="zmdi zmdi-balance-wallet fs-25 mr-2"></i>
        <span class="side-menu__label">Pre-Disbursement</span><i class="angle fa fa-angle-right"></i></a>
    </li>
    <li class="slide">
        <a class="side-menu__item" href="{{ url('list') }}/stage_5">
        <i class="zmdi zmdi-collection-text fs-25 mr-2"></i>
        <span class="side-menu__label">Export</span><i class="angle fa fa-angle-right"></i></a>
    </li>
@endif

    {{--========== CSO ==========--}}
@if (Auth::user()->role_id == 2 || Auth::user()->role_id == 1)
    <li class="slide">
        <a class="side-menu__item" href="{{ url('list') }}/stage_2">
        <i class="mdi mdi-account-card-details fs-25 mr-2"></i>
        <span class="side-menu__label">Account Opening</span><i class="angle fa fa-angle-right"></i></a>
    </li>
@endif

    {{--========== Request Status (all)==========--}}
{{-- @if (Auth::user()->role_id == 2 || Auth::user()->role_id == 1) --}}
    <li class="slide">
        <a class="side-menu__item" href="{{ url('list') }}/stage_5">
        <i class="zmdi zmdi-collection-text fs-25 mr-2"></i>
        <span class="side-menu__label">Export</span><i class="angle fa fa-angle-right"></i></a>
    </li>
    <li class="slide">
        <a class="side-menu__item" href="{{ url('list') }}/stage_0">
        <i class="mdi mdi-account-location fs-25 mr-2"></i>
        <span class="side-menu__label">Request Status</span><i class="angle fa fa-angle-right"></i></a>
    </li>
{{-- @endif --}}
    





    {{-- <li class="slide">
        <a class="side-menu__item" href="{{ url('list') }}/stage_11">
        <i class="mdi mdi-account-card-details fs-25 mr-2"></i>
        <span class="side-menu__label">Disbursed Loans</span><i class="angle fa fa-angle-right"></i></a>
    </li>

    <li class="slide">
        <a class="side-menu__item" href="{{ url('list') }}/stage_0">
        <i class="mdi mdi-account-card-details fs-25 mr-2"></i>
        <span class="side-menu__label">Request Amendment</span><i class="angle fa fa-angle-right"></i></a>
    </li>

    <li class="slide">
        <a class="side-menu__item" href="{{ url('list') }}/stage_0_0">
        <i class="mdi mdi-account-card-details fs-25 mr-2"></i>
        <span class="side-menu__label">Declined Loans</span><i class="angle fa fa-angle-right"></i></a>
    </li> --}}




      <li class="slide">
        <a class="side-menu__item" href="{{ url('profile') }}">
          <i class="mdi mdi-account-network fs-25 mr-2"></i>
          <span class="side-menu__label">Profile</span><i class="angle fa fa-angle-right"></i></a>
      </li>
    {{-- <li class="slide">
        <a class="side-menu__item" href="{{ url('view-approved') }}">
          <i class="mdi mdi-account-check fs-25 mr-2"></i>
          <span class="side-menu__label">Approved</span><i class="angle fa fa-angle-right"></i></a>
      </li>
      <li class="slide">
        <a class="side-menu__item" href="{{ url('view-declined') }}">
          <i class="mdi mdi-account-off fs-25 mr-2"></i>
          <span class="side-menu__label">Declined</span><i class="angle"><span class="badge badge-pill badge-danger mt-2">0</span></i></a>
      </li> --}}



@else
    <li class="slide">
        <a class="side-menu__item" href="{{ route('home') }}">
          <i class="fa fa-home fs-25 mr-2"></i>
        <span class="side-menu__label">Dashboard</span><i class="angle fa fa-angle-right"></i></a>
      </li>

      <li class="slide">
        <a class="side-menu__item" href="{{ url('list') }}">
          <i class="zmdi zmdi-chart-donut fs-25 mr-2"></i>
          <span class="side-menu__label">Loans</span><i class="angle fa fa-angle-right"></i></a>
      </li>
      <li class="slide">
        <a class="side-menu__item" href="{{ url('list') }}/stage_0_1">
          <i class="zmdi zmdi-chart-donut fs-25 mr-2"></i>
          <span class="side-menu__label">Saved Requests</span><i class="angle fa fa-angle-right"></i></a>
      </li>
    {{--========== HR Confirmation (15)==========--}}
@if (Auth::user()->role_id == 4 || Auth::user()->role_id == 1)
    <li class="slide">
        <a class="side-menu__item" href="{{ url('list') }}/stage_1">
        <i class="mdi mdi-account-convert fs-25 mr-2"></i>
        <span class="side-menu__label">BDO Processing</span><i class="angle fa fa-angle-right"></i></a>
    </li>
    <li class="slide">
        <a class="side-menu__item" href="{{ url('list') }}/stage_4">
        <i class="zmdi zmdi-balance-wallet fs-25 mr-2"></i>
        <span class="side-menu__label">Pre-Disbursement</span><i class="angle fa fa-angle-right"></i></a>
    </li>
    <li class="slide">
        <a class="side-menu__item" href="{{ url('list') }}/stage_5">
        <i class="zmdi zmdi-collection-text fs-25 mr-2"></i>
        <span class="side-menu__label">Export</span><i class="angle fa fa-angle-right"></i></a>
    </li>
@endif
    {{--========== HR Confirmation (15)==========--}}
@if (Auth::user()->role_id == 5 || Auth::user()->role_id == 1)
    <li class="slide">
        <a class="side-menu__item" href="{{ url('list') }}/stage_3">
        <i class="mdi mdi-account-check fs-25 mr-2"></i>
        <span class="side-menu__label">BM Approval</span><i class="angle fa fa-angle-right"></i></a>
    </li>
@endif

    {{--========== Credit Risk (9,14)==========--}}
@if (Auth::user()->role_id == 2 || Auth::user()->role_id == 1)
    <li class="slide">
        <a class="side-menu__item" href="{{ url('list') }}/stage_2">
        <i class="mdi mdi-account-card-details fs-25 mr-2"></i>
        <span class="side-menu__label">Account Opening</span><i class="angle fa fa-angle-right"></i></a>
    </li>
@endif
    <li class="slide">
        <a class="side-menu__item" href="{{ url('list') }}/stage_0">
        <i class="mdi mdi-account-location fs-25 mr-2"></i>
        <span class="side-menu__label">Request Status</span><i class="angle fa fa-angle-right"></i></a>
    </li>
    <li class="slide">
        <a class="side-menu__item" href="{{ url('profile') }}">
        <i class="mdi mdi-account-network fs-25 mr-2"></i>
        <span class="side-menu__label">Profile</span><i class="angle fa fa-angle-right"></i></a>
    </li>
@endif
@if (Auth::user()->id == 1 || Auth::user()->id == 5 || Auth::user()->id == 108)
    <li class="slide">
        <a class="side-menu__item" href="{{ url('user/onboard') }}">
        <i class="mdi mdi-account-plus fs-25 mr-2"></i>
        <span class="side-menu__label">Add User</span><i class="angle fa fa-angle-right"></i></a>
    </li>
@endif
    </ul>
  </aside>
