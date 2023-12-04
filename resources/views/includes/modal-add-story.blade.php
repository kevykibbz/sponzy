<!-- Start Modal payPerViewForm -->
<div class="modal fade" id="addStory" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-body p-0">
				<div class="card bg-white shadow border-0">

					<div class="card-body px-lg-5 py-lg-5 position-relative">

						<div class="mb-4 position-relative">
						<i class="bi-clock-history mr-1"></i>	<strong>{{ __('general.choose_type_story') }}</strong>

						<small data-dismiss="modal" class="btn-cancel-msg"><i class="bi bi-x-lg"></i></small>
						</div>

						@if ($settings->story_image)
						<a class="card choose-type-sale mb-3" href="{{ url('create/story') }}">
							<div class="card-body">
								<h6 class="mb-1"><i class="bi-image mr-2"></i> {{ __('general.story_image') }}</h6>
							</div>
						</a>
					@endif

					@if ($settings->story_text)
						<a class="card choose-type-sale mb-3" href="{{ url('create/story/text') }}">
							<div class="card-body">
								<h6 class="mb-1"><i class="bi-type mr-2"></i> {{ __('general.story_text') }}</h6>
							</div>
						</a>
					@endif
					</div>
				</div>
			</div>
		</div>
	</div>
</div><!-- End Modal addItemForm -->
