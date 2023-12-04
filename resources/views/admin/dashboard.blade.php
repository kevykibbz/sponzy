@extends('admin.layout')

@section('css')
<link href="{{ asset('public/admin/jvectormap/jquery-jvectormap-1.2.2.css')}}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
	<h4 class="mb-4 fw-light">{{ __('admin.dashboard') }} <small class="fs-6">v{{$settings->version}}</small></h4>

<div class="content">
	<div class="row">

		<div class="col-lg-3 mb-3">
			<div class="card shadow-custom border-0 overflow-hidden">
				<div class="card-body">
					<h3>
						<i class="bi-arrow-repeat me-2 icon-dashboard"></i>
						<span>{{ number_format($total_subscriptions) }}</span>
					</h3>
					<small>{{ __('admin.subscriptions') }}</small>

					<span class="icon-wrap icon--admin"><i class="bi-arrow-repeat"></i></span>
				</div>
				<div class="card-footer bg-light border-0 p-3">
					<a href="{{ url('panel/admin/subscriptions') }}" class="text-muted font-weight-medium d-flex align-items-center justify-content-center arrow">
						  {{ __('general.view_all') }}
					  </a>
				  </div>
			</div><!-- card 1 -->
		</div><!-- col-lg-3 -->

		<div class="col-lg-3 mb-3">
			<div class="card shadow-custom border-0 overflow-hidden">
				<div class="card-body">
					<h3><i class="bi-cash-stack me-2 icon-dashboard"></i> {{ Helper::amountFormatDecimal($total_raised_funds) }}</h3>
					<small>{{ __('admin.earnings_net') }} ({{__('users.admin')}})</small>

					<span class="icon-wrap icon--admin"><i class="bi-cash-stack"></i></span>
				</div>
				<div class="card-footer bg-light border-0 p-3">
					<a href="{{ url('panel/admin/transactions') }}" class="text-muted font-weight-medium d-flex align-items-center justify-content-center arrow">
						  {{ __('general.see_all_transactions') }}
					  </a>
				  </div>
			</div><!-- card 1 -->
		</div><!-- col-lg-3 -->

		<div class="col-lg-3 mb-3">
			<div class="card shadow-custom border-0 overflow-hidden">
				<div class="card-body">
					<h3><i class="bi-people me-2 icon-dashboard"></i> {{ number_format($totalUsers) }}</h3>
					<small>{{ __('general.members') }}</small>
					<span class="icon-wrap icon--admin"><i class="bi-people"></i></span>
				</div>
				<div class="card-footer bg-light border-0 p-3">
					<a href="{{ url('panel/admin/members') }}" class="text-muted font-weight-medium d-flex align-items-center justify-content-center arrow">
						  {{ __('general.view_all') }}
					  </a>
				  </div>
			</div><!-- card 1 -->
		</div><!-- col-lg-3 -->

		<div class="col-lg-3 mb-3">
			<div class="card shadow-custom border-0 overflow-hidden">
				<div class="card-body">
					<h3><i class="bi-pencil-square me-2 icon-dashboard"></i> {{ number_format($total_posts) }}</h3>
					<small>{{ __('general.posts') }}</small>
					<span class="icon-wrap icon--admin"><i class="bi-pencil-square"></i></span>
				</div>
				<div class="card-footer bg-light border-0 p-3">
					<a href="{{ url('panel/admin/posts') }}" class="text-muted font-weight-medium d-flex align-items-center justify-content-center arrow">
						  {{ __('general.view_all') }}
					  </a>
				  </div>
			</div><!-- card 1 -->
		</div><!-- col-lg-3 -->

		<div class="col-lg-4 mb-3">
			<div class="card shadow-custom border-0 overflow-hidden">
				<div class="card-body">
					<h6>
						{{ Helper::amountFormatDecimal($total_funds) }}
					</h6>
					<small>{{ __('general.total_revenue') }}</small>
					<span class="icon-wrap icon--admin"><i class="bi bi-graph-up-arrow"></i></span>
				</div>
			</div><!-- card 1 -->
		</div><!-- col-lg-4 -->

		<div class="col-lg-4 mb-3">
			<div class="card shadow-custom border-0 overflow-hidden">
				<div class="card-body">
					<h6>
						{{ Helper::amountFormatDecimal($total_paid_funds) }}
					</h6>
					<small>{{ __('general.paid_to_creators') }}</small>
					<span class="icon-wrap icon--admin"><i class="bi bi-graph-up-arrow"></i></span>
				</div>
			</div><!-- card 1 -->
		</div><!-- col-lg-4 -->

		<div class="col-lg-4 mb-3">
			<div class="card shadow-custom border-0 overflow-hidden">
				<div class="card-body">
					<h6>
						{{ Helper::amountFormatDecimal($totalPaidlastMonth) }}
					</h6>
					<small>{{ __('general.paid_last_month') }}</small>
					<span class="icon-wrap icon--admin"><i class="bi bi-graph-up-arrow"></i></span>
				</div>
			</div><!-- card 1 -->
		</div><!-- col-lg-4 -->

		<div class="col-lg-4 mb-3">
			<div class="card shadow-custom border-0 overflow-hidden">
				<div class="card-body">
					<h6 class="{{$stat_revenue_today > 0 ? 'text-success' : 'text-danger' }}">
						{{ Helper::amountFormatDecimal($stat_revenue_today) }}

							{!! Helper::percentageIncreaseDecreaseAdmin($stat_revenue_today, $stat_revenue_yesterday) !!}
					</h6>
					<small>{{ __('general.revenue_today') }}</small>
					<span class="icon-wrap icon--admin"><i class="bi bi-graph-up-arrow"></i></span>
				</div>
				<div class="card-footer bg-light border-0 p-3">
					<small class="text-capitalize">{{ __('general.yesterday') }} <strong>{{ Helper::amountFormatDecimal($stat_revenue_yesterday) }}</strong></small>
				</div>
			</div><!-- card 1 -->
		</div><!-- col-lg-4 -->

		<div class="col-lg-4 mb-3">
			<div class="card shadow-custom border-0 overflow-hidden">
				<div class="card-body">
					<h6 class="{{$stat_revenue_week > 0 ? 'text-success' : 'text-danger' }}">
						{{ Helper::amountFormatDecimal($stat_revenue_week) }}

							{!! Helper::percentageIncreaseDecreaseAdmin($stat_revenue_week, $stat_revenue_last_week) !!}
					</h6>
					<small>{{ __('general.revenue_week') }}</small>
					<span class="icon-wrap icon--admin"><i class="bi bi-graph-up"></i></span>
				</div>
				<div class="card-footer bg-light border-0 p-3">
					<small class="text-capitalize">{{ __('general.last_week') }} <strong>{{ Helper::amountFormatDecimal($stat_revenue_last_week) }}</strong></small>
				</div>
			</div><!-- card 1 -->
		</div><!-- col-lg-4 -->

		<div class="col-lg-4 mb-3">
			<div class="card shadow-custom border-0 overflow-hidden">
				<div class="card-body">
					<h6 class="{{$stat_revenue_month > 0 ? 'text-success' : 'text-danger' }}">
						{{ Helper::amountFormatDecimal($stat_revenue_month) }}

							{!! Helper::percentageIncreaseDecreaseAdmin($stat_revenue_month, $stat_revenue_last_month) !!}
					</h6>
					<small>{{ __('general.revenue_month') }}</small>
					<span class="icon-wrap icon--admin"><i class="bi bi-graph-up-arrow"></i></span>
				</div>
				<div class="card-footer bg-light border-0 p-3">
					<small class="text-capitalize">{{ __('general.last_month') }} <strong>{{ Helper::amountFormatDecimal($stat_revenue_last_month) }}</strong></small>
				</div>
			</div><!-- card 1 -->
		</div><!-- col-lg-4 -->

		<div class="col-lg-12 mt-3 py-4">
			 <div class="card shadow-custom border-0">
				 <div class="card-body">
					<div class="d-lg-flex d-block justify-content-between align-items-center mb-4">
						<h6 class="mb-4 mb-lg-0"><i class="bi-cash-stack me-2"></i> {{ trans('general.earnings') }}</h6>
  
					   <select class="form-select mb-4 mb-lg-0 w-auto d-block filterEarnings">
						<option selected="" value="month">{{ __('general.this_month') }}</option>
						<option value="last-month">{{ __('general.last_month') }}</option>
						<option value="year">{{ __('general.this_year') }}</option>       
					  </select>
					  </div>
					 
					 <div class="d-block position-relative" style="height: 350px">
                        <div class="blocked display-none" id="loadChart">
                          <span class="d-flex justify-content-center align-items-center text-center w-100 h-100">
                           <i class="spinner-border spinner-border-sm me-2 text-muted"></i> {{ __('general.loading') }}
                          </span>
                      </div>
                      <canvas id="ChartSales"></canvas>
                    </div>
				 </div>
			 </div>
		</div>

		<div class="col-lg-12 mt-0 mt-lg-3 py-4">
			 <div class="card shadow-custom border-0">
				 <div class="card-body">
					 <h6 class="mb-4"><i class="bi-person-check-fill me-2"></i> {{ __('general.subscriptions_the_month') }}</h6>
					 <div style="height: 350px">
						<canvas id="ChartSubscriptions"></canvas>
					</div>
				 </div>
			 </div>
		</div>

		<div class="col-lg-6 mt-0 mt-lg-3 py-4">
			 <div class="card shadow-custom border-0">
				 <div class="card-body">
					 <h6 class="mb-4"><i class="bi-people-fill me-2"></i> {{ __('admin.latest_members') }}</h6>

					 @foreach ($users as $user)
						 <div class="d-flex mb-3">
							  <div class="flex-shrink-0">
							    <img src="{{ Helper::getFile(config('path.avatar').$user->avatar) }}" width="50" class="rounded-circle" />
							  </div>
							  <div class="flex-grow-1 ms-3">
							    <h6 class="m-0 fw-light text-break">
										<a href="{{ url($user->username) }}" target="_blank">
											{{ $user->name ?: $user->username }}
											</a>
											<small class="float-end badge rounded-pill bg-{{ $user->status == 'active' ? 'success' : ($user->status == 'pending' ? 'info' : 'warning') }}">
												{{ $user->status == 'active' ? __('general.active') : ($user->status == 'pending' ? __('general.pending') : __('admin.suspended')) }}
											</small>
									</h6>
									<div class="w-100 small">
										{{ '@'.$user->username }} / {{ Helper::formatDate($user->date) }}
									</div>
							  </div>
							</div>
					 @endforeach

					 @if ($users->count() == 0)
						 <small>{{ __('admin.no_result') }}</small>
					 @endif
				 </div>

				 @if ($users->count() != 0)
				 <div class="card-footer bg-light border-0 p-3">
					   <a href="{{ url('panel/admin/members') }}" class="text-muted font-weight-medium d-flex align-items-center justify-content-center arrow">
							 {{ __('admin.view_all_members') }}
						 </a>
					 </div>
				 @endif

			 </div>
		</div>

		<div class="col-lg-6 mt-0 mt-lg-3 py-4">
			 <div class="card shadow-custom border-0">
				 <div class="card-body">
					 <h6 class="mb-4"><i class="bi-person-check-fill me-2"></i> {{ __('admin.recent_subscriptions') }}</h6>

					 @foreach ($subscriptions as $subscription)
						 <div class="d-flex mb-3">
							 <div class="flex-shrink-0">
								 <img src="{{ Helper::getFile(config('path.avatar').$subscription->subscriber->avatar) }}" width="50" class="rounded-circle" />
							 </div>
							  <div class="flex-grow-1 ms-3">
							    <h6 class="m-0 fw-light text-break">
										@if (! isset($subscription->subscriber->username))
											<em class="text-muted">{{ __('general.no_available') }}</em>
									@else
										<a href="{{ url($subscription->subscriber->username) }}" target="_blank">
											{{$subscription->subscriber->name}}
											</a>
										@endif

										{{__('general.subscribed_to')}}

										@if (! isset($subscription->creator->username))
											<em class="text-muted">{{ __('general.no_available') }}</em>
									@else
										<a href="{{url($subscription->creator->username)}}" target="_blank">{{$subscription->creator->name}}</a>
									@endif

									</h6>

									<div class="w-100 small">
										{{ Helper::formatDate($subscription->created_at) }}
									</div>
							  </div>
							</div>
					 @endforeach

					 @if ($subscriptions->count() == 0)
						 <small>{{ __('admin.no_result') }}</small>
					 @endif
				 </div>

				 @if ($subscriptions->count() != 0)
				 <div class="card-footer bg-light border-0 p-3">
					   <a href="{{ url('panel/admin/subscriptions') }}" class="text-muted font-weight-medium d-flex align-items-center justify-content-center arrow">
							 {{ __('general.view_all') }}
						 </a>
					 </div>
					  @endif
			 </div>
		</div>

	</div><!-- end row -->
</div><!-- end content -->
@endsection

@section('javascript')
	<script src="{{ asset('public/admin/jvectormap/jquery-jvectormap-1.2.2.min.js')}}" type="text/javascript"></script>
	<script src="{{ asset('public/admin/jvectormap/jquery-jvectormap-world-mill-en.js')}}" type="text/javascript"></script>
  <script src="{{ asset('public/js/Chart.min.js') }}"></script>
	@include('admin.charts')
  @endsection
