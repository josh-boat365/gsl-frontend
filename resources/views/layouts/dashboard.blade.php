<!DOCTYPE html>
<html class="no-js css-menubar" lang="en">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
      <meta name="description" content="bootstrap material admin template">
      <meta name="author" content="">
      <title>Best Point Staff Portal</title>
      <meta name="csrf-token" class="csrf_token" content="{{ csrf_token() }}">
      @include('assets.ACSS')
      @stack('page_css')
      {!! Toastr::message() !!}
      @livewireStyles
   </head>

   <body class="app sidebar-mini light-mode default-sidebar">
      {{--<div id="global-loader" >
         <img src="https://codeigniter.spruko.com/Dashtic/DASHTIC-LTR/public/assets/images/svgs/loader.svg" alt="loader">
      </div>--}}

         <div class="page-main">
            <!--aside open-->
            @include('inc.side-bar')
            <div class="app-content main-content">















               <div class="side-app">
                  <!--app header-->
                  @include('inc.top-header')
                  <!--/app header-->
                  @yield('content')
               </div>
            </div>
            <!-- end app-content-->
         </div>
         <!-- Notifications Modal Form-->
         <!-- HR Modal -->
        @include('workspace.loan.inc.affordability-calculator')
        {{-- @include('inc.component.reminder-modal-form')
        @include('inc.component.report-modal-form') --}}
         <!--Footer-->
         <footer class="footer">
            <div class="container">
               <div class="row align-items-center flex-row-reverse">
                  <div class="col-md-12 col-sm-12 mt-3 mt-lg-0 text-center">
                     © {{ date('Y') }} <a href="#">Smartflow Inc.</a>
                  </div>
               </div>
            </div>
         </footer>
         <!-- End Footer-->
      </div>
      @include('assets.AJS')
      @stack('page_js')
      {!! Toastr::message() !!}
       @livewireScripts
   </body>
</html>
