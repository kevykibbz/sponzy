@extends('layouts.app')

@section('title'){{trans('general.messages')}} -@endsection

@section('content')
<section class="section section-sm pb-0 h-100 section-msg position-fixed">
      <div class="container container-full-w h-100">
        <div class="row justify-content-center h-100">

          <div class="col-md-3 h-100 p-0 border-left wrapper-msg-inbox" id="messagesContainer">
              @include('includes.sidebar-messages-inbox')
          </div>

        <div class="col-md-9 h-100 p-0">
          <div class="card w-100 rounded-0 h-100 border-top-0">
            <div class="content px-4 py-3 d-scrollbars container-msg">

              <div class="flex-column d-flex justify-content-center text-center h-100">

                <div class="w-100">
                  <h2 class="mb-0 font-montserrat"><i class="feather icon-send mr-2"></i> {{trans('general.messages')}}</h2>
                  <p class="lead text-muted mt-0">{{trans('general.messages_subtitle')}}</p>
                  <button class="btn btn-primary btn-sm w-small-100" data-toggle="modal" data-target="#newMessageForm">
                    <i class="bi bi-plus-lg mr-1"></i> {{trans('general.new_message')}}
                  </button>
                </div>

              </div>
            </div><!-- container-msg -->

            </div><!-- card -->
            </div><!-- end col-md-6 -->
          </div><!-- end row -->
        </div><!-- end container -->
</section>
@include('includes.modal-new-message')
@endsection

@section('javascript')
<script src="{{ asset('public/js/messages.js') }}?v={{$settings->version}}"></script>
<script src="{{ asset('public/js/fileuploader/fileuploader-msg.js') }}?v={{$settings->version}}"></script>
<script src="{{ asset('public/js/paginator-messages.js') }}"></script>
@endsection
