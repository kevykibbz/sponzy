@extends('layouts.app')

@section('title'){{ $user->hide_name == 'yes' ? $mediaTitle.$user->username : $mediaTitle.$user->name }} -@endsection
  @section('description_custom'){{$mediaTitle.$user->username}} - {{strip_tags($user->story)}}@endsection

  @section('css')

  <meta property="og:type" content="website" />
  <meta property="og:image:width" content="200"/>
  <meta property="og:image:height" content="200"/>

  <!-- Current locale and alternate locales -->
  <meta property="og:locale" content="en_US" />
  <meta property="og:locale:alternate" content="es_ES" />

  <!-- Og Meta Tags -->
  <link rel="canonical" href="{{url($user->username.$media)}}"/>
  <meta property="og:site_name" content="{{ $user->hide_name == 'yes' ? $user->username : $user->name }} - {{$settings->title}}"/>
  <meta property="og:url" content="{{url($user->username.$media)}}"/>
  <meta property="og:image" content="{{Helper::getFile(config('path.avatar').$user->avatar)}}"/>

  <meta property="og:title" content="{{ $user->hide_name == 'yes' ? $user->username : $user->name }} - {{$settings->title}}"/>
  <meta property="og:description" content="{{strip_tags($user->story)}}"/>
  <meta name="twitter:card" content="summary_large_image" />
  <meta name="twitter:image" content="{{Helper::getFile(config('path.avatar').$user->avatar)}}" />
  <meta name="twitter:title" content="{{ $user->hide_name == 'yes' ? $user->username : $user->name }}" />
  <meta name="twitter:description" content="{{strip_tags($user->story)}}"/>

  <script type="text/javascript">
      var profile_id = {{$user->id}};
      var sort_post_by_type_media = "{!! $sortPostByTypeMedia !!}";
  </script>
  @endsection

@section('content')
<div class="jumbotron jumbotron-cover-user home m-0 position-relative" style="padding: @if ($user->cover != '') @if (request()->path() == $user->username) 240px @else 125px @endif @else 125px @endif 0; background: #505050 @if ($user->cover != '') url('{{Helper::getFile(config('path.cover').$user->cover)}}') no-repeat center center; background-size: cover; @endif">
  @if (auth()->check() && auth()->user()->status == 'active' && auth()->id() == $user->id)
    <div class="progress-upload-cover"></div>

    <form action="{{url('upload/cover')}}" method="POST" id="formCover" accept-charset="UTF-8" enctype="multipart/form-data">
      @csrf
    <input type="file" name="image" id="uploadCover" accept="image/*" class="visibility-hidden">
  </form>

  <div class="flex justify-between wraper-cover-upload">
    <button class="btn btn-cover-upload p-lg-6 px-3" id="coverFile" onclick="$('#uploadCover').trigger('click');">
      <i class="fa fa-camera mr-lg-1"></i>  <span class="d-none d-lg-inline">{{__('general.change_cover')}}</span>
    </button>
  
    <button class="btn btn-cover-upload px-3 deleteCover">
      <i class="bi-trash3-fill"></i> 
    </button>
  </div>
@endif
</div>

  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <div class="w-100 text-center py-4 img-profile-user">

          <div @if (Helper::isCreatorLive($getCurrentLiveCreators, $user->id) && auth()->check() && auth()->id() != $user->id) data-url="{{ url('live', $user->username) }}" @endif class="text-center position-relative @if (Helper::isCreatorLive($getCurrentLiveCreators, $user->id) && auth()->check() && auth()->id() != $user->id) avatar-wrap-live liveLink @else avatar-wrap @endif shadow @if (auth()->check() && auth()->id() != $user->id && Cache::has('is-online-' . $user->id) && $user->active_status_online == 'yes' || auth()->guest() && Cache::has('is-online-' . $user->id) && $user->active_status_online == 'yes') user-online-profile overflow-visible @elseif (auth()->check() && auth()->id() != $user->id && !Cache::has('is-online-' . $user->id) && $user->active_status_online == 'yes' || auth()->guest() && !Cache::has('is-online-' . $user->id) && $user->active_status_online == 'yes') user-offline-profile overflow-visible @endif">

            @if (auth()->check() && auth()->id() != $user->id && Helper::isCreatorLive($getCurrentLiveCreators, $user->id))
              <span class="live-span">{{ __('general.live') }}</span>
              <div class="live-pulse"></div>
            @endif


            <div class="progress-upload">0%</div>

            @if (auth()->check() && auth()->user()->status == 'active' && auth()->id() == $user->id)

              <form action="{{url('upload/avatar')}}" method="POST" id="formAvatar" accept-charset="UTF-8" enctype="multipart/form-data">
                @csrf
              <input type="file" name="avatar" id="uploadAvatar" accept="image/*" class="visibility-hidden">
            </form>

            <a href="javascript:;" class="position-absolute button-avatar-upload" id="avatar_file">
              <i class="fa fa-camera"></i>
            </a>
          @endif
            <img src="{{Helper::getFile(config('path.avatar').$user->avatar)}}" width="150" height="150" alt="{{$user->hide_name == 'yes' ? $user->username : $user->name}}" class="rounded-circle img-user mb-2 avatarUser @if (auth()->check() && auth()->id() != $user->id && Helper::isCreatorLive($getCurrentLiveCreators, $user->id)) border-0 @endif">
          </div><!-- avatar-wrap -->

          <div class="media-body">
            <h4 class="mt-1">
              {{$user->hide_name == 'yes' ? $user->username : $user->name}}

              @if ($user->verified_id == 'yes')
              <small class="verified" title="{{__('general.verified_account')}}" data-toggle="tooltip" data-placement="top">
                <i class="bi-patch-check-fill"></i>
              </small>
            @endif

            @if ($user->featured == 'yes')
              <small class="text-featured" title="{{__('users.creator_featured')}}" data-toggle="tooltip" data-placement="top">
              <i class="fas fa fa-award"></i>
            </small>
          @endif
          </h4>

            <p>
            <span>
              @if (! Cache::has('is-online-' . $user->id) && $user->hide_last_seen == 'no')
              <span class="w-100 d-block">
                <small>{{ __('general.active') }}</small>
                <small class="timeAgo"data="{{ date('c', strtotime($user->last_seen ?? $user->date)) }}"></small>
               </span>
               @endif

              @if ($user->profession != '' && $user->verified_id == 'yes')
                {{$user->profession}}
              @endif
          </span>
            </p>

            <div class="d-flex-user justify-content-center mb-2">
            @if (auth()->check() && auth()->id() == $user->id)
              <a href="{{url('settings/page')}}" class="btn btn-primary btn-profile mr-1"><i class="fa fa-pencil-alt mr-2"></i> {{ auth()->user()->verified_id == 'yes' ? __('general.edit_my_page') : __('users.edit_profile')}}</a>
            @endif

              @if ($userPlanMonthlyActive
                  && $user->verified_id == 'yes'
                  || $user->free_subscription == 'yes'
                  && $user->verified_id == 'yes')

              @if (auth()->check() && auth()->id() != $user->id
                  && ! $checkSubscription
                  && ! $paymentIncomplete
                  && $user->free_subscription == 'no'
                  && $totalPosts != 0
                  )
                <a href="javascript:void(0);" data-toggle="modal" data-target="#subscriptionForm" class="btn btn-primary btn-profile mr-1">
                  <i class="feather icon-unlock mr-1"></i> {{__('general.subscribe_month', ['price' => Helper::formatPrice($user->getPlan('monthly', 'price'))])}}
                </a>
              @elseif (auth()->check() && auth()->id() != $user->id && ! $checkSubscription && $paymentIncomplete)
                <a href="{{ route('cashier.payment', $paymentIncomplete->last_payment) }}" class="btn btn-warning btn-profile mr-1">
                  <i class="fa fa-exclamation-triangle"></i> {{__('general.confirm_payment')}}
                </a>
              @elseif (auth()->check() && auth()->id() != $user->id && $checkSubscription)

                @if ($checkSubscription->stripe_status == 'active' && $checkSubscription->stripe_id != '')
                {!! Form::open([
                  'method' => 'POST',
                  'url' => "subscription/cancel/$checkSubscription->stripe_id",
                  'class' => 'd-inline formCancel'
                ]) !!}

                {!! Form::button('<i class="feather icon-user-check mr-1"></i> '.__('general.your_subscribed'), ['data-expiration' => __('general.subscription_expire').' '.Helper::formatDate(auth()->user()->subscription('main', $checkSubscription->stripe_price)->asStripeSubscription()->current_period_end, true), 'class' => 'btn btn-success btn-profile mr-1 cancelBtn subscriptionActive']) !!}
                {!! Form::close() !!}

              @elseif ($checkSubscription->stripe_id == '' && $checkSubscription->free == 'yes')
                {!! Form::open([
                  'method' => 'POST',
                  'url' => "subscription/free/cancel/$checkSubscription->id",
                  'class' => 'd-inline formCancel'
                ]) !!}

                {!! Form::button('<i class="feather icon-user-check mr-1"></i> '.__('general.your_subscribed'), ['data-expiration' => __('general.confirm_cancel_subscription'), 'class' => 'btn btn-success btn-profile mr-1 cancelBtn subscriptionActive']) !!}
                {!! Form::close() !!}

              @elseif ($paymentGatewaySubscription == 'Paystack' && $checkSubscription->cancelled == 'no')
                {!! Form::open([
                  'method' => 'POST',
                  'url' => "subscription/paystack/cancel/$checkSubscription->subscription_id",
                  'class' => 'd-inline formCancel'
                ]) !!}

                {!! Form::button('<i class="feather icon-user-check mr-1"></i> '.__('general.your_subscribed'), ['data-expiration' => __('general.subscription_expire').' '.Helper::formatDate($checkSubscription->ends_at), 'class' => 'btn btn-success btn-profile mr-1 cancelBtn subscriptionActive']) !!}
                {!! Form::close() !!}

              @elseif ($paymentGatewaySubscription == 'Wallet' && $checkSubscription->cancelled == 'no')
                {!! Form::open([
                  'method' => 'POST',
                  'url' => "subscription/wallet/cancel/$checkSubscription->id",
                  'class' => 'd-inline formCancel'
                ]) !!}

                {!! Form::button('<i class="feather icon-user-check mr-1"></i> '.__('general.your_subscribed'), ['data-expiration' => __('general.subscription_expire').' '.Helper::formatDate($checkSubscription->ends_at), 'class' => 'btn btn-success btn-profile mr-1 cancelBtn subscriptionActive']) !!}
                {!! Form::close() !!}

              @elseif ($paymentGatewaySubscription == 'PayPal' && $checkSubscription->cancelled == 'no')
                {!! Form::open([
                  'method' => 'POST',
                  'url' => "subscription/paypal/cancel/$checkSubscription->id",
                  'class' => 'd-inline formCancel'
                ]) !!}

                {!! Form::button('<i class="feather icon-user-check mr-1"></i> '.__('general.your_subscribed'), ['data-expiration' => __('general.subscription_expire').' '.Helper::formatDate($checkSubscription->ends_at), 'class' => 'btn btn-success btn-profile mr-1 cancelBtn subscriptionActive']) !!}
                {!! Form::close() !!}

              @elseif ($paymentGatewaySubscription == 'CCBill' && $checkSubscription->cancelled == 'no')
                {!! Form::open([
                  'method' => 'POST',
                  'url' => "subscription/ccbill/cancel/$checkSubscription->id",
                  'class' => 'd-inline formCancel'
                ]) !!}

                {!! Form::button('<i class="feather icon-user-check mr-1"></i> '.__('general.your_subscribed'), ['data-expiration' => __('general.subscription_expire').' '.Helper::formatDate($checkSubscription->ends_at), 'class' => 'btn btn-success btn-profile mr-1 cancelBtn subscriptionActive']) !!}
                {!! Form::close() !!}

              @elseif ($checkSubscription->cancelled == 'yes' || $checkSubscription->stripe_status == 'canceled')
                <a href="javascript:void(0);" class="btn btn-success btn-profile mr-1 disabled">
                  <i class="feather icon-user-check mr-1"></i> {{__('general.subscribed_until')}} {{ Helper::formatDate($checkSubscription->ends_at) }}
                </a>
              @endif

              @elseif (auth()->check() && auth()->id() != $user->id && $user->free_subscription == 'yes' && $totalPosts != 0)
                <a href="javascript:void(0);" data-toggle="modal" data-target="#subscriptionFreeForm" class="btn btn-primary btn-profile mr-1">
                  <i class="feather icon-user-plus mr-1"></i> {{__('general.subscribe_for_free')}}
                </a>
              @elseif (auth()->guest() && $totalPosts != 0)
                <a href="{{url('login')}}" data-toggle="modal" data-target="#loginFormModal" class="btn btn-primary btn-profile mr-1">
                  @if ($user->free_subscription == 'yes')
                    <i class="feather icon-user-plus mr-1"></i> {{__('general.subscribe_for_free')}}
                  @else
                  <i class="feather icon-unlock mr-1"></i> {{__('general.subscribe_month', ['price' => Helper::formatPrice($user->getPlan('monthly', 'price'))])}}
                @endif
                </a>
            @endif

            @endif

            @if (auth()->check() && auth()->id() != $user->id && $totalPosts <> 0 && $settings->disable_tips == 'off')
              <a href="javascript:void(0);" data-toggle="modal" title="{{__('general.tip')}}" data-target="#tipForm" class="btn btn-google btn-profile mr-1" data-cover="{{Helper::getFile(config('path.cover').$user->cover)}}" data-avatar="{{Helper::getFile(config('path.avatar').$user->avatar)}}" data-name="{{$user->hide_name == 'yes' ? $user->username : $user->name}}" data-userid="{{$user->id}}">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi-coin" viewBox="0 0 16 16">
                  <path d="M5.5 9.511c.076.954.83 1.697 2.182 1.785V12h.6v-.709c1.4-.098 2.218-.846 2.218-1.932 0-.987-.626-1.496-1.745-1.76l-.473-.112V5.57c.6.068.982.396 1.074.85h1.052c-.076-.919-.864-1.638-2.126-1.716V4h-.6v.719c-1.195.117-2.01.836-2.01 1.853 0 .9.606 1.472 1.613 1.707l.397.098v2.034c-.615-.093-1.022-.43-1.114-.9H5.5zm2.177-2.166c-.59-.137-.91-.416-.91-.836 0-.47.345-.822.915-.925v1.76h-.005zm.692 1.193c.717.166 1.048.435 1.048.91 0 .542-.412.914-1.135.982V8.518l.087.02z"/>
                  <path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                  <path fill-rule="evenodd" d="M8 13.5a5.5 5.5 0 1 0 0-11 5.5 5.5 0 0 0 0 11zm0 .5A6 6 0 1 0 8 2a6 6 0 0 0 0 12z"/>
                </svg> {{__('general.tip')}}
              </a>
            @elseif (auth()->guest() && $totalPosts <> 0)
              <a href="{{url('login')}}" data-toggle="modal" data-target="#loginFormModal" class="btn btn-google btn-profile mr-1" title="{{__('general.tip')}}">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi-coin" viewBox="0 0 16 16">
                  <path d="M5.5 9.511c.076.954.83 1.697 2.182 1.785V12h.6v-.709c1.4-.098 2.218-.846 2.218-1.932 0-.987-.626-1.496-1.745-1.76l-.473-.112V5.57c.6.068.982.396 1.074.85h1.052c-.076-.919-.864-1.638-2.126-1.716V4h-.6v.719c-1.195.117-2.01.836-2.01 1.853 0 .9.606 1.472 1.613 1.707l.397.098v2.034c-.615-.093-1.022-.43-1.114-.9H5.5zm2.177-2.166c-.59-.137-.91-.416-.91-.836 0-.47.345-.822.915-.925v1.76h-.005zm.692 1.193c.717.166 1.048.435 1.048.91 0 .542-.412.914-1.135.982V8.518l.087.02z"/>
                  <path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                  <path fill-rule="evenodd" d="M8 13.5a5.5 5.5 0 1 0 0-11 5.5 5.5 0 0 0 0 11zm0 .5A6 6 0 1 0 8 2a6 6 0 0 0 0 12z"/>
                </svg> {{__('general.tip')}}
              </a>
            @endif

            @if (auth()->guest() && $user->verified_id == 'yes' || auth()->check() && auth()->id() != $user->id && $user->verified_id == 'yes')
              <button @guest data-toggle="modal" data-target="#loginFormModal" @else id="sendMessageUser" @endguest data-url="{{url('messages/'.$user->id, $user->username)}}" title="{{__('general.message')}}" class="btn btn-google btn-profile mr-1">
                <i class="feather icon-send mr-1 mr-lg-0"></i> <span class="d-lg-none">{{__('general.message')}}</span>
              </button>
            @endif

            @if ($user->verified_id == 'yes')
              <button class="btn btn-profile btn-google" title="{{__('general.share')}}" id="dropdownUserShare" role="button" data-toggle="modal" data-target=".share-modal">
                <i class="feather icon-share mr-1 mr-lg-0"></i> <span class="d-lg-none">{{__('general.share')}}</span>
              </button>

            <!-- Share modal -->
          <div class="modal fade share-modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
          		<div class="modal-content">
                <div class="modal-header border-bottom-0">
                  <button type="button" class="close close-inherit" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="bi-x-lg"></i></span>
                  </button>
                </div>
                <div class="modal-body">

                  @if ($settings->generate_qr_code)
                  <div class="d-block w-100 text-center mb-5">
                    <div id="QrCode" class="d-block w-100 text-center mb-3"></div>

                    <div class="d-block w-100 text-center">
                      <a class="btn btn-primary" id="downloadQr" href="" download="QR {{ '@'.$user->username }}">
                      <i class="bi-download mr-1"></i>  {{ __('general.download') }}
                      </a>
                    </div>
                  </div>
                @endif

          				<div class="container-fluid">
          					<div class="row">
          						<div class="col-md-4 col-6 mb-3">
          							<a href="https://www.facebook.com/sharer/sharer.php?u={{url($user->username).Helper::referralLink()}}" title="Facebook" target="_blank" class="social-share text-muted d-block text-center h6">
          								<i class="fab fa-facebook-square facebook-btn"></i>
          								<span class="btn-block mt-3">Facebook</span>
          							</a>
          						</div>
          						<div class="col-md-4 col-6 mb-3">
          							<a href="https://twitter.com/intent/tweet?url={{url($user->username).Helper::referralLink()}}&text={{ e( $user->hide_name == 'yes' ? $user->username : $user->name ) }}" data-url="{{url($user->username)}}" class="social-share text-muted d-block text-center h6" target="_blank" title="Twitter">
          								<i class="bi-twitter-x text-dark"></i> <span class="btn-block mt-3">Twitter</span>
          							</a>
          						</div>
          						<div class="col-md-4 col-6 mb-3">
          							<a href="whatsapp://send?text={{url($user->username).Helper::referralLink()}}" data-action="share/whatsapp/share" class="social-share text-muted d-block text-center h6" title="WhatsApp">
          								<i class="fab fa-whatsapp btn-whatsapp"></i> <span class="btn-block mt-3">WhatsApp</span>
          							</a>
          						</div>

          						<div class="col-md-4 col-6 mb-3">
          							<a href="mailto:?subject={{ e( $user->hide_name == 'yes' ? $user->username : $user->name ) }}&amp;body={{url($user->username).Helper::referralLink()}}" class="social-share text-muted d-block text-center h6" title="{{__('auth.email')}}">
          								<i class="far fa-envelope"></i> <span class="btn-block mt-3">{{__('auth.email')}}</span>
          							</a>
          						</div>
          						<div class="col-md-4 col-6 mb-3">
          							<a href="sms:?&body={{ __('general.check_this') }} {{url($user->username).Helper::referralLink()}}" class="social-share text-muted d-block text-center h6" title="{{ __('general.sms') }}">
          								<i class="fa fa-sms"></i> <span class="btn-block mt-3">{{ __('general.sms') }}</span>
          							</a>
          						</div>
          						<div class="col-md-4 col-6 mb-3">
          							<a href="javascript:void(0);" id="btn_copy_url" class="social-share text-muted d-block text-center h6 link-share" title="{{__('general.copy_link')}}">
          							<i class="fas fa-link"></i> <span class="btn-block mt-3">{{__('general.copy_link')}}</span>
          						</a>
                      <input type="hidden" readonly="readonly" id="copy_link" class="form-control" value="{{url($user->username).Helper::referralLink()}}">
          					</div>
          					</div>

          				</div>
                </div>
              </div>
            </div>
          </div>
          @endif

          </div><!-- d-flex-user -->

            @if (auth()->check() && auth()->id() != $user->id)
            <div class="text-center">
              <button type="button" class="btn e-none btn-link text-danger p-0 mr-2" data-toggle="modal" data-target="#reportCreator">
                <small><i class="fas fa-flag mr-1"></i> {{__('general.report_user')}}</small>
              </button>

              @if (auth()->user()->isRestricted($user->id))
                <button type="button" class="btn e-none btn-link text-danger removeRestriction p-0" data-user="{{$user->id}}" id="restrictUser">
                  <small><i class="fas fa-ban mr-1"></i> {{__('general.remove_restriction')}}</small>
                </button>

              @else
                <button type="button" class="btn e-none btn-link text-danger p-0" data-user="{{$user->id}}" id="restrictUser">
                  <small><i class="fas fa-ban mr-1"></i> {{__('general.restrict')}}</small>
                </button>
              @endif

            </div>
          @endif

          </div><!-- media-body -->
        </div><!-- media -->

        @if ($user->verified_id == 'yes')
        <ul class="nav nav-profile justify-content-center nav-fill">

          <li class="nav-link @if (request()->path() == $user->username)active @endif navbar-user-mobile">
            <small class="btn-block sm-btn-size">{{ $totalPosts }}</small>
              <a href="{{request()->path() == $user->username ? 'javascript:;' : url($user->username)}}" title="{{__('general.posts')}}"><i class="feather icon-file-text"></i> <span class="d-lg-inline-block d-none">{{__('general.posts')}}</span></a>
            </li>

            <li class="nav-link @if (request()->path() == $user->username.'/photos')active @endif navbar-user-mobile">
              <small class="btn-block sm-btn-size">{{ $totalPhotos }}</small>
              <a href="{{request()->path() == $user->username.'/photos' ? 'javascript:;' : url($user->username, 'photos')}}" title="{{__('general.photos')}}"><i class="feather icon-image"></i> <span class="d-lg-inline-block d-none">{{__('general.photos')}}</span></a>
            </li>

            <li class="nav-link @if (request()->path() == $user->username.'/videos')active @endif navbar-user-mobile">
              <small class="btn-block sm-btn-size">{{ $totalVideos }}</small>
              <a href="{{request()->path() == $user->username.'/videos' ? 'javascript:;' : url($user->username, 'videos')}}" title="{{__('general.video')}}"><i class="feather icon-video"></i> <span class="d-lg-inline-block d-none">{{__('general.videos')}}</span></a>
              </li>

            <li class="nav-link @if (request()->path() == $user->username.'/audio')active @endif navbar-user-mobile">
              <small class="btn-block sm-btn-size">{{ $totalMusic }}</small>
              <a href="{{request()->path() == $user->username.'/audio' ? 'javascript:;' : url($user->username, 'audio')}}" title="{{__('general.audio')}}"><i class="feather icon-mic"></i> <span class="d-lg-inline-block d-none">{{__('general.audio')}}</span></a>
            </li>

            @if ($settings->shop || ! $settings->shop && $userProducts->count() != 0)
                <li class="nav-link @if (request()->path() == $user->username.'/shop')active @endif navbar-user-mobile">
                  <small class="btn-block sm-btn-size">{{$user->products()->whereStatus('1')->count()}}</small>
                  <a href="{{request()->path() == $user->username.'/shop' ? 'javascript:;' : url($user->username, 'shop')}}" title="{{__('general.shop')}}"><i class="feather icon-shopping-bag"></i> <span class="d-lg-inline-block d-none">{{__('general.shop')}}</span></a>
                </li>
          @endif

          @if ($totalFiles != 0)
            <li class="nav-link @if (request()->path() == $user->username.'/files')active @endif navbar-user-mobile">
              <small class="btn-block sm-btn-size">{{ $totalFiles }}</small>
              <a href="{{request()->path() == $user->username.'/files' ? 'javascript:;' : url($user->username, 'files')}}" title="{{__('general.files')}}"><i class="far fa-file-archive"></i> <span class="d-lg-inline-block d-none">{{__('general.files')}}</span></a>
            </li>
          @endif

        </ul>
      @endif

      </div><!-- col-lg-12 -->
    </div><!-- row -->
  </div><!-- container -->

  @if ($user->verified_id == 'yes' && request('media') != 'shop')
  <div class="container py-4 pb-5">
    <div class="row">
      <div class="col-lg-4 mb-3">

        <button type="button" class="btn-arrow-expand btn btn-outline-primary btn-block mb-2 d-lg-none text-word-break font-weight-bold" type="button" data-toggle="collapse" data-target="#navbarUserHome" aria-controls="navbarCollapse" aria-expanded="false">
      		{{__('users.about_me')}} <i class="fas fa-chevron-down ml-2"></i>
      	</button>

      <div class="sticky-top navbar-collapse collapse d-lg-block" id="navbarUserHome">
        <div class="card mb-3 rounded-large shadow-large">
          <div class="card-body">
            <h6 class="card-title">{{ __('users.about_me') }}</h6>
            <p class="card-text position-relative">

              @if ($likeCount != 0 || $subscriptionsActive != 0)
              <span class="btn-block">
                @if ($likeCount != 0)
                <small class="mr-2"><i class="far fa-heart mr-1"></i> {{ $likeCount }} {{ __('general.likes') }}</small>
                @endif

                @if ($subscriptionsActive != 0 && $user->hide_count_subscribers == 'no')
                    <small><i class="feather icon-users mr-1"></i> {{ Helper::formatNumber($subscriptionsActive) }} {{ trans_choice('general.subscribers', $subscriptionsActive) }}</small>
                @endif
              </span>
            @endif

              @if (isset($user->country()->country_name) && $user->hide_my_country == 'no')
              <small class="btn-block">
                <i class="feather icon-map-pin mr-1"></i> {{$user->country()->country_name}}
              </small>
              @endif

              <small class="btn-block m-0 mb-1">
                <i class="far fa-user-circle mr-1"></i> {{ __('general.member_since') }} {{ Helper::formatDate($user->date) }}
              </small>

              @if ($user->show_my_birthdate == 'yes')
                <small class="btn-block m-0 mb-1">
                  <i class="far fa-calendar-alt mr-1"></i> {{ __('general.birthdate') }} {{ Helper::formatDate($user->birthdate) }} ({{ \Carbon\Carbon::parse($user->birthdate)->age }} {{ __('general.years') }})
                </small>
              @endif


            @if ($user->verified_id == 'yes')
                  <div class="truncated">
                    {!! Helper::checkText($user->story)  !!}
                  </div>
                  <a href="javascript:void(0);" class="display-none link-border">{{ __('general.view_all') }}</a>
            @endif
            </p>

              @if ($user->website != '')
                <div class="d-block mb-1 text-truncate">
                  <a href="{{$user->website}}" title="{{$user->website}}" target="_blank" class="text-muted share-btn-user"><i class="fa fa-link mr-1"></i> {{Helper::removeHTPP($user->website)}}</a>
                </div>
              @endif

              @if ($user->facebook != '')
                <a href="{{$user->facebook}}" title="{{$user->facebook}}" target="_blank" class="text-muted share-btn-user"><i class="bi-facebook mr-2"></i></a>
              @endif

              @if ($user->twitter != '')
                <a href="{{$user->twitter}}" title="{{$user->twitter}}" target="_blank" class="text-muted share-btn-user"><i class="bi-twitter-x mr-2"></i></a>
              @endif

              @if ($user->instagram != '')
                <a href="{{$user->instagram}}" title="{{$user->instagram}}" target="_blank" class="text-muted share-btn-user"><i class="fab fa-instagram mr-2"></i></a>
              @endif

              @if ($user->youtube != '')
                <a href="{{$user->youtube}}" title="{{$user->youtube}}" target="_blank" class="text-muted share-btn-user"><i class="fab fa-youtube mr-2"></i></a>
              @endif

              @if ($user->pinterest != '')
                <a href="{{$user->pinterest}}" title="{{$user->pinterest}}" target="_blank" class="text-muted share-btn-user"><i class="fab fa-pinterest-p mr-2"></i></a>
              @endif

              @if ($user->github != '')
                <a href="{{$user->github}}" title="{{$user->github}}" target="_blank" class="text-muted share-btn-user"><i class="fab fa-github mr-2"></i></a>
              @endif

              @if ($user->snapchat != '')
                <a href="{{$user->snapchat}}" title="{{$user->snapchat}}" target="_blank" class="text-muted share-btn-user"><i class="bi-snapchat mr-2"></i></a>
              @endif

              @if ($user->tiktok != '')
                <a href="{{$user->tiktok}}" title="{{$user->tiktok}}" target="_blank" class="text-muted share-btn-user"><i class="bi-tiktok mr-2"></i></a>
              @endif

              @if ($user->telegram != '')
                <a href="{{$user->telegram}}" title="{{$user->telegram}}" target="_blank" class="text-muted share-btn-user"><i class="bi-telegram mr-2"></i></a>
              @endif

              @if ($user->twitch != '')
                <a href="{{$user->twitch}}" title="{{$user->twitch}}" target="_blank" class="text-muted share-btn-user"><i class="bi-twitch mr-2"></i></a>
              @endif

              @if ($user->discord != '')
                <a href="{{$user->discord}}" title="{{$user->discord}}" target="_blank" class="text-muted share-btn-user"><i class="bi-discord mr-2"></i></a>
              @endif

              @if ($user->vk != '')
                <a href="{{$user->vk}}" title="{{$user->vk}}" target="_blank" class="text-muted share-btn-user"><i class="fab fa-vk mr-2"></i></a>
              @endif

              @if ($user->reddit != '')
                <a href="{{$user->reddit}}" title="{{$user->reddit}}" target="_blank" class="text-muted share-btn-user"><i class="bi-reddit mr-2"></i></a>
              @endif

              @if ($user->spotify != '')
                <a href="{{$user->spotify}}" title="{{$user->spotify}}" target="_blank" class="text-muted share-btn-user"><i class="bi-spotify mr-2"></i></a>
              @endif

              @if ($user->threads != '')
                <a href="{{$user->threads}}" title="{{$user->threads}}" target="_blank" class="text-muted share-btn-user"><i class="bi-threads mr-2"></i></a>
              @endif

              @if ($user->categories_id != '0' && $user->categories_id != '' && $user->verified_id == 'yes')
              <div class="w-100 mt-2">

              @foreach (Categories::where('mode','on')->orderBy('name')->get() as $category)
                @foreach ($categories as $categoryKey)
                  @if ($categoryKey == $category->id)
                  <a href="{{url('category', $category->slug)}}" class="button-white-sm mb-2">
                    #{{ Lang::has('categories.' . $category->slug) ? __('categories.' . $category->slug) : $category->name }}
                  </a>
                @endif
                @endforeach
            @endforeach

              </div>
            @endif
          </div><!-- card-body -->
        </div><!-- card -->

        <div class="d-lg-block d-none">
        @include('includes.footer-tiny')
      </div>

        </div><!-- navbar-collapse -->
      </div><!-- col-lg-4 -->

      <div class="col-lg-8 wrap-post">

        @if (auth()->check()
            && auth()->id() == $user->id
            && ! $userPlanMonthlyActive
            && auth()->user()->free_subscription == 'no'
            )
        <div class="alert alert-danger mb-3">
                 <ul class="list-unstyled m-0">
                   <li><i class="fa fa-exclamation-triangle"></i> {{__('general.alert_not_subscription')}} <a href="{{url('settings/subscription')}}" class="text-white link-border">{{__('general.activate')}}</a></li>
                 </ul>
               </div>
               @endif

        @if (auth()->check()
            && auth()->id() == $user->id
            && request()->path() == $user->username
            && auth()->user()->verified_id != 'reject'
            )
          @include('includes.form-post')
        @endif

        @if ($updates->count() == 0 && $findPostPinned->count() == 0 || $updates->count() == 0 && $media)
            <div class="grid-updates"></div>

            <div class="my-5 text-center no-updates">
              <span class="btn-block mb-3">
                <i class="fa fa-photo-video ico-no-result"></i>
              </span>
            <h4 class="font-weight-light">{{__('general.no_posts_posted')}}</h4>
            </div>
          @else

            @if (! request()->get('sort') && $totalPosts > $settings->number_posts_show || request()->get('sort'))
            <div class="w-100 d-flex justify-content-end align-items-center mb-3 px-lg-0 px-3">

              @if (auth()->guest() && $user->posts_privacy || auth()->check())
              <div>
                <i class="bi-filter-right mr-1"></i>

                <select class="@if ($settings->button_style == 'rounded')rounded-pill @endif custom-select w-auto px-4" id="filter">
                    <option @if (! request()->get('sort')) selected @endif value="{{url()->current()}}{{ request()->get('q') ? '?q='.str_replace('#', '%23', request()->get('q')) : null }}">{{__('general.latest')}}</option>
                    <option @if (request()->get('sort') == 'oldest') selected @endif value="{{url()->current()}}{{ request()->get('q') ? '?q='.str_replace('#', '%23', request()->get('q')).'&' : '?' }}sort=oldest">{{__('general.oldest')}}</option>
                    <option @if (request()->get('sort') == 'unlockable') selected @endif value="{{url()->current()}}{{ request()->get('q') ? '?q='.str_replace('#', '%23', request()->get('q')).'&' : '?' }}sort=unlockable">{{__('general.unlockable')}}</option>
                    <option @if (request()->get('sort') == 'free') selected @endif value="{{url()->current()}}{{ request()->get('q') ? '?q='.str_replace('#', '%23', request()->get('q')).'&' : '?' }}sort=free">{{__('general.free')}}</option>
                  </select>
              </div>
              @endif

          </div>
        @endif

        @if (auth()->guest() && ! $user->posts_privacy)
        <div class="my-5 text-center no-updates">
          <span class="btn-block mb-3">
            <i class="fa fa-lock ico-no-result"></i>
          </span>
        <h4 class="font-weight-light">{{__('general.alert_posts_privacy', ['user' => '@'.$user->username])}}</h4>
        </div>

        @else

        <div class="grid-updates position-relative" id="updatesPaginator">
          @if ($findPostPinned->count() && ! request('media'))
            @include('includes.updates', ['updates' => $findPostPinned, 'isPostPinned' => true])
          @endif

          @include('includes.updates')
        </div>
        @endif   

          @endif
      </div>
      </div><!-- row -->
    </div><!-- container -->
  @endif

  @if ($user->verified_id == 'yes' && request('media') == 'shop')
    <div class="container py-5">

      @if ($userProducts->count() != 0)
      <div class="@if (auth()->check() && auth()->user()->verified_id == 'yes' && $user->id == auth()->id())d-flex justify-content-between align-items-center @else d-block @endif mb-3 text-right">

        @if (auth()->check() && auth()->user()->verified_id == 'yes' && $user->id == auth()->id())
        <div>
          @if ($settings->digital_product_sale && ! $settings->custom_content)
            <a class="btn btn-primary" href="{{ url('add/product') }}">
              <i class="bi-plus"></i> <span class="d-lg-inline-block d-none">{{ __('general.add_product') }}</span>
            </a>

          @elseif (! $settings->digital_product_sale && $settings->custom_content)
            <a class="btn btn-primary" href="{{ url('add/custom/content') }}">
              <i class="bi-plus"></i> <span class="d-lg-inline-block d-none">{{ __('general.add_custom_content') }}</span>
            </a>

          @else
            <a class="btn btn-primary" href="#" data-toggle="modal" data-target="#addItemForm">
              <i class="bi-plus"></i> <span class="d-lg-inline-block d-none">{{ __('general.add_new') }}</span>
            </a>
          @endif
        </div>
      @endif

        <div>
          <select class="ml-2 custom-select mb-2 mb-lg-0 w-auto" id="filter">
              <option @if (! request()->get('sort')) selected @endif value="{{url($user->username).'/shop'}}">{{__('general.latest')}}</option>
              <option @if (request()->get('sort') == 'oldest') selected @endif value="{{url($user->username).'/shop?sort=oldest'}}">{{__('general.oldest')}}</option>
              <option @if (request()->get('sort') == 'priceMin') selected @endif value="{{url($user->username).'/shop?sort=priceMin'}}">{{__('general.lowest_price')}}</option>
              <option @if (request()->get('sort') == 'priceMax') selected @endif value="{{url($user->username).'/shop?sort=priceMax'}}">{{__('general.highest_price')}}</option>
              @if ($settings->physical_products)
              <option @if (request()->get('sort') == 'physical') selected @endif value="{{url($user->username).'/shop?sort=physical'}}">{{__('general.physical_products')}}</option>
              @endif
              <option @if (request()->get('sort') == 'digital') selected @endif value="{{url($user->username).'/shop?sort=digital'}}">{{__('general.digital_products')}}</option>
              <option @if (request()->get('sort') == 'custom') selected @endif value="{{url($user->username).'/shop?sort=custom'}}">{{__('general.custom_content')}}</option>
            </select>

            @if ($shopCategories->count())
              <select class="ml-2 custom-select mb-2 mb-lg-0 w-auto filter">
                  <option @if (! request()->get('cat')) selected @endif value="{{url($user->username, 'shop')}}">{{__('general.all_categories')}}</option>

                    @foreach ($shopCategories as $category)
                      <option @if (request()->get('cat') == $category->slug) selected @endif value="{{url($user->username, 'shop')}}{{ '?cat='.$category->slug }}">
                        {{ Lang::has('shop-categories.' . $category->slug) ? __('shop-categories.' . $category->slug) : $category->name }}
                      </option>
                    @endforeach

                </select>
            @endif
        </div>
      </div>
    @endif

      <div class="row">

        @if ($userProducts->count() != 0)

          @foreach ($userProducts as $product)
          <div class="col-md-4 mb-4">
            @include('shop.listing-products')
          </div><!-- end col-md-4 -->
          @endforeach

          @if ($userProducts->hasPages())
            <div class="w-100 d-block">
              {{ $userProducts->onEachSide(0)->appends(['sort' => request('sort')])->links() }}
            </div>
          @endif

        @else

          <div class="my-5 text-center no-updates w-100">
            <span class="btn-block mb-3">
              <i class="feather icon-shopping-bag ico-no-result"></i>
            </span>
          <h4 class="font-weight-light">{{__('general.no_results_found')}}</h4>

        @if (auth()->check() && auth()->user()->verified_id == 'yes' && auth()->id() == $user->id)
          <div class="mt-3">
            @if ($settings->digital_product_sale && ! $settings->custom_content && ! $settings->physical_products)
              <a class="btn btn-primary" href="{{ url('add/product') }}">
                <i class="bi-plus"></i> {{ __('general.add_product') }}
              </a>

            @elseif (! $settings->digital_product_sale && $settings->custom_content && ! $settings->physical_products)
              <a class="btn btn-primary" href="{{ url('add/custom/content') }}">
                <i class="bi-plus"></i> {{ __('general.add_custom_content') }}
              </a>

            @elseif (! $settings->digital_product_sale && $settings->physical_products && ! $settings->custom_content)
              <a class="btn btn-primary" href="{{ url('add/physical/product') }}">
                <i class="bi-plus"></i> {{ __('general.add_physical_product') }}
              </a>

            @else
              <a class="btn btn-primary" href="#" data-toggle="modal" data-target="#addItemForm">
                <i class="bi-plus"></i> {{ __('general.add_new') }}
              </a>
            @endif
          </div>
        @endif

          </div>

        @endif
      </div>
    </div><!-- container -->

    @includeWhen(auth()->check() && auth()->user()->verified_id == 'yes', 'shop.modal-add-item')

  @endif


    @if (auth()->check() && auth()->id() != $user->id)
    <div class="modal fade modalReport" id="reportCreator" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-danger modal-sm">
        <div class="modal-content">
          <div class="modal-header">
            <h6 class="modal-title font-weight-light" id="modal-title-default"><i class="fas fa-flag mr-1"></i> {{__('general.report_user')}}</h6>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <i class="fa fa-times"></i>
            </button>
          </div>
     <!-- form start -->
     <form method="POST" action="{{url('report/creator', $user->id)}}" enctype="multipart/form-data">
        <div class="modal-body">
          @csrf
          <!-- Start Form Group -->
          <div class="form-group">
            <label>{{__('admin.please_reason')}}</label>
              <select name="reason" class="form-control custom-select">
               <option value="spoofing">{{__('admin.spoofing')}}</option>
                  <option value="copyright">{{__('admin.copyright')}}</option>
                  <option value="privacy_issue">{{__('admin.privacy_issue')}}</option>
                  <option value="violent_sexual">{{__('admin.violent_sexual_content')}}</option>
                  <option value="spam">{{__('general.spam')}}</option>
                  <option value="fraud">{{__('general.fraud')}}</option>
                  <option value="under_age">{{__('general.under_age')}}</option>
                </select>

                <textarea name="message" rows="" cols="40" maxlength="200" placeholder="{{__('general.message')}} ({{ __('general.optional') }})" class="form-control mt-2 textareaAutoSize"></textarea>
                
                </div><!-- /.form-group-->
            </div><!-- Modal body -->

           <div class="modal-footer">
             <button type="button" class="btn border text-white" data-dismiss="modal">{{__('admin.cancel')}}</button>
             <button type="submit" class="btn btn-xs btn-white sendReport ml-auto"><i></i> {{__('general.report_user')}}</button>
           </div>

           </form>
          </div><!-- Modal content -->
        </div><!-- Modal dialog -->
      </div><!-- Modal reportCreator -->
    @endif

    @if (auth()->check() && auth()->id() != $user->id && ! $checkSubscription  && $user->verified_id == 'yes')

    @if ($user->free_subscription == 'no')
    <div class="modal fade" id="subscriptionForm" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
      <div class="modal-dialog modal- modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
          <div class="modal-body p-0">
            <div class="card bg-white shadow border-0">
              <div class="card-header pb-2 border-0 position-relative" style="height: 100px; background: {{$settings->color_default}} @if ($user->cover != '')  url('{{Helper::getFile(config('path.cover').$user->cover)}}') no-repeat center center @endif; background-size: cover;">

              </div>
              <div class="card-body px-lg-5 py-lg-5 position-relative">

                <div class="text-muted text-center mb-3 position-relative modal-offset">
                  <img src="{{Helper::getFile(config('path.avatar').$user->avatar)}}" width="100" alt="{{$user->hide_name == 'yes' ? $user->username : $user->name}}" class="avatar-modal rounded-circle mb-1">
                  <h6 class="font-weight-light">
                    {!! __('general.subscribe_month', ['price' => '<span class="font-weight-bold">'.Helper::formatPrice($user->getPlan('monthly', 'price'), true).'</span>']) !!} {{__('general.unlocked_content')}} {{$user->hide_name == 'yes' ? $user->username : $user->name}}

                    <small class="w-100 d-block font-12">* {{ __('general.in_currency', ['currency_code' => $settings->currency_code]) }}</small>
                  </h6>
                </div>

                @if ($totalPosts == 0 && $findPostPinned->count() == 0)
                  <div class="alert alert-warning fade show small" role="alert">
                    <i class="fa fa-exclamation-triangle mr-1"></i> {{ $user->first_name }} {{ __('general.not_posted_any_content') }}
                  </div>
                @endif

                <div class="text-center text-muted mb-2">
                  <h5>{{__('general.what_will_you_get')}}</h5>
                </div>

                <ul class="list-unstyled">
                  <li><i class="fa fa-check mr-2 @if (auth()->user()->dark_mode == 'on') text-white @else text-primary @endif"></i> {{__('general.full_access_content')}}</li>
                  <li><i class="fa fa-check mr-2 @if (auth()->user()->dark_mode == 'on') text-white @else text-primary @endif"></i> {{__('general.direct_message_with_this_user')}}</li>
                  <li><i class="fa fa-check mr-2 @if (auth()->user()->dark_mode == 'on') text-white @else text-primary @endif"></i> {{__('general.cancel_subscription_any_time')}}</li>
                </ul>

                <div class="text-center text-muted mb-2 @if ($allPayment->count() == 1) d-none @endif">
                  <small><i class="far fa-credit-card mr-1"></i> {{__('general.choose_payment_gateway')}}</small>
                </div>

                <form method="post" action="{{url('buy/subscription')}}" id="formSubscription">
                  @csrf

                  <input type="hidden" name="id" value="{{$user->id}}"  />
                  <input name="interval" value="monthly" id="plan-monthly" class="d-none" type="radio">

                  @foreach ($plans as $plan)
                    <input name="interval" value="{{ $plan->interval }}" id="plan-{{ $plan->interval }}" class="d-none" type="radio">
                  @endforeach

                  @foreach ($allPayment as $payment)

                    @php

                    if ($payment->recurrent == 'no') {
                      $recurrent = '<br><small>'.__('general.non_recurring').'</small>';
                    } else if ($payment->id == 1) {
                      $recurrent = '<br><small>'.__('general.redirected_to_paypal_website').'</small>';
                    } else {
                      $recurrent = '<br><small>'.__('general.automatically_renewed').' ('.$payment->name.')</small>';
                    }

                    if ($payment->type == 'card' ) {
                      $paymentName = '<i class="far fa-credit-card mr-1"></i> '.__('general.debit_credit_card').$recurrent;
                    } else if ($payment->id == 1) {
                      $paymentName = '<img src="'.url('public/img/payments', auth()->user()->dark_mode == 'off' ? $payment->logo : 'paypal-white.png').'" width="70"/> <small class="w-100 d-block">'.__('general.redirected_to_paypal_website').'</small>';
                    } else {
                      $paymentName = '<img src="'.url('public/img/payments', $payment->logo).'" width="70"/>'.$recurrent;
                    }

                    @endphp

                    <div class="custom-control custom-radio mb-3">
                      <input name="payment_gateway" required value="{{$payment->name}}" id="radio{{$payment->name}}" @if ($allPayment->count() == 1 && Helper::userWallet('balance') == 0) checked @endif class="custom-control-input" type="radio">
                      <label class="custom-control-label" for="radio{{$payment->name}}">
                        <span><strong>{!!$paymentName!!}</strong></span>
                      </label>
                    </div>

                    @if ($payment->name == 'Stripe' && ! auth()->user()->pm_type != '')
                      <div id="stripeContainer" class="@if ($allPayment->count() == 1 && $payment->name == 'Stripe')d-block @else display-none @endif">
                      <a href="{{ url('settings/payments/card') }}" class="btn btn-secondary btn-sm mb-3 w-100">
                        <i class="far fa-credit-card mr-2"></i>
                        {{ __('general.add_payment_card') }}
                      </a>
                      </div>
                    @endif

                    @if ($payment->name == 'Paystack' && ! auth()->user()->paystack_authorization_code)
                      <div id="paystackContainer" class="@if ($allPayment->count() == 1 && $payment->name == 'Paystack')d-block @else display-none @endif">
                      <a href="{{ url('my/cards') }}" class="btn btn-secondary btn-sm mb-3 w-100">
                        <i class="far fa-credit-card mr-2"></i>
                        {{ __('general.add_payment_card') }}
                      </a>
                      </div>
                    @endif

                  @endforeach

                  @if ($settings->disable_wallet == 'on' && Helper::userWallet('balance') != 0 || $settings->disable_wallet == 'off')
                  <div class="custom-control custom-radio mb-3">
                    <input name="payment_gateway" required @if (Helper::userWallet('balance') == 0) disabled @endif value="wallet" id="radio0" class="custom-control-input" type="radio">
                    <label class="custom-control-label" for="radio0">
                      <span>
                        <strong>
                        <i class="fas fa-wallet mr-1 icon-sm-radio"></i> {{ __('general.wallet') }}
                        <span class="w-100 d-block font-weight-light">
                          {{ __('general.available_balance') }}: <span class="font-weight-bold mr-1">{{Helper::userWallet()}}</span>

                          @if (Helper::userWallet('balance') != 0 && $settings->wallet_format != 'real_money')
                            <i class="bi-info-circle text-muted" data-toggle="tooltip" data-placement="top" title="{{Helper::equivalentMoney($settings->wallet_format)}}"></i>
                          @endif

                          @if (Helper::userWallet('balance') == 0)
                          <a href="{{ url('my/wallet') }}" class="link-border">{{ __('general.recharge') }}</a>
                        @endif
                        </span>
                        <span class="w-100 d-block small">{{ __('general.automatically_renewed_wallet') }}</span>
                      </strong>
                      </span>
                    </label>
                  </div>
                @endif

                  <div class="alert alert-danger display-none" id="error">
                      <ul class="list-unstyled m-0" id="showErrors"></ul>
                    </div>

                  <div class="custom-control custom-control-alternative custom-checkbox">
                    <input class="custom-control-input" required id=" customCheckLogin" name="agree_terms" type="checkbox">
                    <label class="custom-control-label" for=" customCheckLogin">
                      <span>{{__('general.i_agree_with')}} <a href="{{$settings->link_terms}}" target="_blank">{{__('admin.terms_conditions')}}</a></span>
                    </label>
                  </div>

                  @if ($taxRatesCount != 0 && auth()->user()->isTaxable()->count())
                  <ul class="list-group list-group-flush border-dashed-radius mt-3">
                  	@foreach (auth()->user()->isTaxable() as $tax)
                  		<li class="list-group-item py-1 list-taxes">
                  	    <div class="row">
                  	      <div class="col">
                  	        <small>{{ $tax->name }} {{ $tax->percentage }}% {{ __('general.applied_price') }}</small>
                  	      </div>
                  	    </div>
                  	  </li>
                  	@endforeach
                  </ul>
                @endif

                  <div class="text-center">
                    <button type="submit" class="btn btn-primary mt-4 w-100 subscriptionBtn" onclick="$('#plan-monthly').trigger('click');">
                      <i></i> {{__('general.subscribe_month', ['price' => Helper::formatPrice($user->getPlan('monthly', 'price'), true)])}}
                    </button>

                    @if ($plans->count())
                      <a class="d-block my-3 btn-arrow-expand-bi" data-toggle="collapse" href="#collapseSubscriptionBundles" role="button" aria-expanded="false" aria-controls="collapseExample">
                        <i class="bi-box mr-1"></i> {{ __('general.subscription_bundles') }} <i class="bi-chevron-down transition-icon"></i>
                      </a>

                      <div class="collapse" id="collapseSubscriptionBundles">
                        @foreach ($plans as $plan)
                          <button type="submit" class="btn btn-primary mt-2 w-100 subscriptionBtn" onclick="$('#plan-{{$plan->interval}}').trigger('click');">
                            <i></i> {{__('general.subscribe_'.$plan->interval, ['price' => Helper::formatPrice($plan->price, true)])}}
                          </button>

                          @if (Helper::calculateSubscriptionDiscount($plan->interval, $user->getPlan('monthly', 'price'), $plan->price) > 0)
                            <small class="@if (auth()->user()->dark_mode == 'on') text-white @else text-success @endif subscriptionDiscount">
                              <em>{{ Helper::calculateSubscriptionDiscount($plan->interval, $user->getPlan('monthly', 'price'), $plan->price) }}% {{ __('general.discount') }}  </em>
                            </small>
                          @endif

                        @endforeach
                      </div>

                    @endif

                    <div class="w-100 mt-2">
                      <button type="button" class="btn e-none p-0" data-dismiss="modal">{{__('admin.cancel')}}</button>
                    </div>
                  </div>

                  @include('includes.site-billing-info')
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div><!-- End Modal Subscription -->
    @endif

    <!-- Subscription Free -->
    <div class="modal fade" id="subscriptionFreeForm" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
      <div class="modal-dialog modal- modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
          <div class="modal-body p-0">
            <div class="card bg-white shadow border-0">
              <div class="card-header pb-2 border-0 position-relative" style="height: 100px; background: {{$settings->color_default}} @if ($user->cover != '')  url('{{Helper::getFile(config('path.cover').$user->cover)}}') no-repeat center center @endif; background-size: cover;">

              </div>
              <div class="card-body px-lg-5 py-lg-5 position-relative">

                <div class="text-muted text-center mb-3 position-relative modal-offset">
                  <img src="{{Helper::getFile(config('path.avatar').$user->avatar)}}" width="100" alt="{{$user->hide_name == 'yes' ? $user->username : $user->name}}" class="avatar-modal rounded-circle mb-1">
                  <h6 class="font-weight-light">
                    {{__('general.subscribe_free_content') }} {{$user->hide_name == 'yes' ? $user->username : $user->name}}
                  </h6>
                </div>

                @if ($totalPosts == 0 && $findPostPinned->count() == 0)
                  <div class="alert alert-warning fade show small" role="alert">
                    <i class="fa fa-exclamation-triangle mr-1"></i> {{ $user->first_name }} {{ __('general.not_posted_any_content') }}
                  </div>
                @endif

                <div class="text-center text-muted mb-2">
                  <h5>{{__('general.what_will_you_get')}}</h5>
                </div>

                <ul class="list-unstyled">
                  <li><i class="fa fa-check mr-2 text-primary"></i> {{__('general.full_access_content')}}</li>
                  <li><i class="fa fa-check mr-2 text-primary"></i> {{__('general.direct_message_with_this_user')}}</li>
                  <li><i class="fa fa-check mr-2 text-primary"></i> {{__('general.cancel_subscription_any_time')}}</li>
                </ul>

                <div class="w-100 text-center">
                  <a href="javascript:void(0);" data-id="{{ $user->id }}" id="subscribeFree" class="btn btn-primary btn-profile mr-1">
                    <i class="feather icon-user-plus mr-1"></i> {{__('general.subscribe_for_free')}}
                  </a>
                  <div class="w-100 mt-2">
                    <button type="button" class="btn e-none p-0" data-dismiss="modal">{{__('admin.cancel')}}</button>
                  </div>
                </div>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div><!-- End Modal Subscription Free -->
  @endif
@endsection

@section('javascript')

@if (auth()->check() && auth()->id() == $user->id)
<script src="{{ asset('public/js/upload-avatar-cover.js') }}?v={{$settings->version}}"></script>
@endif

<script src="{{ asset('public/js/qrcode.min.js') }}?v={{$settings->version}}"></script>

<script type="text/javascript">

@if ($settings->generate_qr_code)
new QRCode(document.getElementById("QrCode"), "{{ url($user->username).Helper::referralLink() }}");

$(document).on('click','#downloadQr', function(e) {
  $(this).attr("href", $("#QrCode img").attr("src"));
});
@endif

@auth
$('.subsCCBill').on('click', function() {

  $(this).blur();
  var expiration = $(this).attr('data-expiration');
  swal({
    html: true,
    title: "{{ __('general.unsubscribe') }}",
    text: "{!! __('general.cancel_subscription_ccbill', ['ccbill' => '<a href=\'https://support.ccbill.com/\' target=\'_blank\'>https://support.ccbill.com</a>']) !!} " + expiration,
    type: "info",
    confirmButtonText: "{{ __('users.ok') }}"
    });
});
@endauth

 @if (session('noty_error'))
   		swal({
   			title: "{{ __('general.error_oops') }}",
   			text: "{{ __('general.already_sent_report') }}",
   			type: "error",
   			confirmButtonText: "{{ __('users.ok') }}"
   			});
  		 @endif

  @if (session('noty_success'))
   		swal({
   			title: "{{ __('general.thanks') }}",
   			text: "{{ __('general.reported_success') }}",
   			type: "success",
   			confirmButtonText: "{{ __('users.ok') }}"
   			});
  @endif

  $('.dropdown-menu.d-menu').on({
      "click":function(e){
        e.stopPropagation();
      }
  });

  @if (session('subscription_success'))
     swal({
       html:true,
       title: "{{ __('general.congratulations') }}",
       text: "{!! session('subscription_success') !!}",
       type: "success",
       confirmButtonText: "{{ __('users.ok') }}"
       });
    @endif

    @if (session('subscription_cancel'))
     swal({
       title: "{{ __('general.canceled') }}",
       text: "{{ session('subscription_cancel') }}",
       type: "error",
       confirmButtonText: "{{ __('users.ok') }}"
       });
    @endif

    @if (session('success_verify'))
    	swal({
    		title: "{{ __('general.welcome') }}",
    		text: "{{ __('users.account_validated') }}",
    		type: "success",
    		confirmButtonText: "{{ __('users.ok') }}"
    		});
    	 @endif

    	 @if (session('error_verify'))
    	swal({
    		title: "{{ __('general.error_oops') }}",
    		text: "{{ __('users.code_not_valid') }}",
    		type: "error",
    		confirmButtonText: "{{ __('users.ok') }}"
    		});
    	 @endif
</script>
@endsection
@php session()->forget('subscription_cancel') @endphp
@php session()->forget('subscription_success') @endphp
