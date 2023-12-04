<div class="modal fade" id="storyViews" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
	<div class="modal-dialog modalStoryViews modal-dialog-scrollable modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header border-bottom-0">
				<h6 class="modal-title">{{__('general.people_seen_story')}}</h6>
				<button type="button" class="close close-inherit" data-dismiss="modal" aria-label="Close">
					<i class="bi bi-x-lg"></i>
				</button>
			  </div>

			<div class="modal-body p-0 custom-scrollbar">
				<div class="card bg-white shadow border-0">

					<div class="card-body p-lg-4">

						<div class="w-100 text-center display-nonea" id="spinner">
							<span class="spinner-border align-middle text-primary"></span>
						</div>

						<div id="containerUsers" class="text-center"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div><!-- End Modal new Message -->