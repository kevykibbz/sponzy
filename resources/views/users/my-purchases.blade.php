@extends('layouts.app')

@section('content')

  <section class="section section-sm">
    <div class="container container-lg-3 pt-lg-5 pt-2">
      <div class="row">

        <div class="col-md-2">
          @include('includes.menu-sidebar-home')
        </div>

        <div class="col-md-6 p-0 second wrap-post">

          @if ($updates->count() != 0)
          <div class="grid-updates position-relative" id="updatesPaginator">
              @include('includes.updates')
          </div>

        @else
          <div class="grid-updates position-relative" id="updatesPaginator"></div>

        <div class="my-5 text-center no-updates">
          <span class="btn-block mb-3">
            <i class="bi bi-bag-x ico-no-result"></i>
          </span>
        <h4 class="font-weight-light">{{trans('general.not_purchased_any_content')}}</h4>
        </div>

        @endif
      </div><!-- end col-md-6 -->

        <div class="col-md-4 mb-4 d-lg-block d-none">

          @if ($users->count() == 0)
          <div class="panel panel-default panel-transparent mb-4 d-lg-block d-none">
        	  <div class="panel-body">
        	    <div class="media none-overflow">
        			  <div class="d-flex my-2 align-items-center">
        			      <img class="rounded-circle mr-2" src="{{Helper::getFile(config('path.avatar').auth()->user()->avatar)}}" width="60" height="60">

        						<div class="d-block">
        						<strong>{{auth()->user()->name}}</strong>


        							<div class="d-block">
        								<small class="media-heading text-muted btn-block margin-zero">
                          <a href="{{url('settings/page')}}">
                						{{ auth()->user()->verified_id == 'yes' ? trans('general.edit_my_page') : trans('users.edit_profile')}}
                            <small class="pl-1"><i class="fa fa-long-arrow-alt-right"></i></small>
                          </a>
                        </small>
        							</div>
        						</div>
        			  </div>
        			</div>
        	  </div>
        	</div>
        @endif

          <div class="d-lg-block sticky-top" id="navbarUserHome">

            @if ($users->count() != 0)
                @include('includes.explore_creators')
            @endif

            <div class="d-lg-block d-none">
              @include('includes.footer-tiny')
            </div>

         </div><!-- navbarUserHome -->

        </div><!-- col-md -->

      </div>
    </div>
  </section>
@endsection

@section('javascript')

@if (session('noty_error'))
  <script type="text/javascript">
   swal({
     title: "{{ trans('general.error_oops') }}",
     text: "{{ trans('general.already_sent_report') }}",
     type: "error",
     confirmButtonText: "{{ trans('users.ok') }}"
     });
     </script>
@endif

@if (session('noty_success'))
<script type="text/javascript">
     swal({
       title: "{{ trans('general.thanks') }}",
       text: "{{ trans('general.reported_success') }}",
       type: "success",
       confirmButtonText: "{{ trans('users.ok') }}"
       });
       </script>
 @endif

 @endsection
