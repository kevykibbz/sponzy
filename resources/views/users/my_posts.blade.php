@extends('layouts.app')

@section('title') {{__('general.my_posts')}} -@endsection

@section('content')
<section class="section section-sm">
    <div class="container">
      <div class="row justify-content-center text-center mb-sm">
        <div class="col-lg-8 py-5">
          <h2 class="mb-0 font-montserrat"><i class="feather icon-feather mr-2"></i> {{__('general.my_posts')}}</h2>
          <p class="lead text-muted mt-0">{{__('general.all_post_created')}}</p>
        </div>
      </div>
      <div class="row">

        <div class="col-md-12 mb-5 mb-lg-0">

          @if ($posts->count() != 0)
          <div class="btn-block mb-3 text-right">
            <span>
              <select class="ml-2 custom-select mb-2 mb-lg-0 w-auto filter">
                <option @selected(!request('sort')) value="{{ url('my/posts') }}">{{ __('general.all') }}</option>

                @if ($settings->allow_scheduled_posts)
                <option @selected(request('sort') == 'scheduled') value="{{ url('my/posts?sort=scheduled') }}">
                  {{ __('general.scheduled') }}
                </option> 
                @endif
                
                <option @selected(request('sort') == 'pending') value="{{ url('my/posts?sort=pending') }}">
                  {{ __('admin.pending') }}
                </option> 


                <option @selected(request('sort') == 'ppv') value="{{ url('my/posts?sort=ppv') }}">
                  {{ __('general.ppv') }}
                </option>
              </select>
            </span>
          </div>

          <div class="card shadow-sm mb-2">
          <div class="table-responsive">
            <table class="table table-striped m-0">
              <thead>
                <tr>
                  <th scope="col">ID</th>
                  <th scope="col">{{__('admin.content')}}</th>
                  <th scope="col">{{__('admin.description')}}</th>
                  <th scope="col">{{__('admin.type')}}</th>
                  <th scope="col">{{__('general.price')}}</th>
                  <th scope="col">{{__('general.interactions')}}</th>
                  <th scope="col">{{__('admin.date')}}</th>
                  <th scope="col">{{__('admin.status')}}</th>
                </tr>
              </thead>

              <tbody>

                @foreach ($posts as $post)
                  <tr>
                    <td>{{ $post->id }}</td>

                    <td>
                      @if ($post->media_count)
                      {{ $post->media_count }} {{trans_choice('general.files', $post->media_count )}}
                      @else
                      {{ __('general.text') }}
                      @endif
                    </td>

                    <td>
                    <a href="{{ url($post->creator->username, 'post').'/'.$post->id }}" target="_blank">
                      {{ str_limit($post->description, 20, '...') }} <i class="bi bi-box-arrow-up-right ml-1"></i>
                    </a>
                    </td>
                    <td>
                      @if ($post->locked == 'yes')
                        <i class="feather icon-lock mr-1" title="{{__('users.content_locked')}}"></i>
                      @else
                        <i class="iconmoon icon-WorldWide mr-1" title="{{__('general.public')}}"></i>
                      @endif
                    </td>
                    <td>{{ Helper::amountFormatDecimal($post->price) }}</td>
                    <td>
                      <i class="far fa-heart"></i> {{ $post->likes_count }} 
                      <i class="far fa-comment ml-1"></i> {{ ($post->comments_count + $post->replies_count) }}
                      <i class="feather icon-bookmark ml-1"></i> {{ $post->bookmarks_count }}
                    </td>
                    <td>{{Helper::formatDate($post->date)}}</td>
                    <td>
                      @if ($post->status == 'active')
                        <span class="badge badge-pill badge-success text-uppercase">{{__('general.active')}}</span>
                      @elseif($post->status == 'schedule')
                      <span class="badge badge-pill badge-info text-uppercase">{{__('general.scheduled')}}</span>
                        <a tabindex="0" role="button" data-container="body" data-toggle="popover" data-trigger="focus" data-placement="top" data-content="{{ __('general.date_schedule') }} {{ Helper::formatDateSchedule($post->scheduled_date) }}">
                          <i class="far fa-question-circle"></i>
                        </a>
                        @else
                        <span class="badge badge-pill badge-warning text-uppercase">{{__('general.pending')}}</span>
                      @endif
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          </div><!-- card -->

          @if ($posts->hasPages())
  			    	{{ $posts->onEachSide(0)->links() }}
  			    	@endif

        @else
          <div class="my-5 text-center">
            <span class="btn-block mb-3">
              <i class="feather icon-feather ico-no-result"></i>
            </span>
            <h4 class="font-weight-light">{{__('general.not_post_created')}}</h4>
          </div>
        @endif
        </div><!-- end col-md-6 -->

      </div>
    </div>
  </section>
@endsection
