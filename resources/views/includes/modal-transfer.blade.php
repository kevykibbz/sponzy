<div class="modal fade" id="modalTransfer" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
	<div class="modal-dialog modal- modal-dialog-centered modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-body p-0">
				<div class="card bg-white shadow border-0">
					<div class="card-body px-lg-5 py-lg-5 position-relative">
						<h6>{{__('general.balance')}}: {{Helper::amountFormatDecimal(auth()->user()->balance)}}</h6>
						<form method="post" action="{{url('transfer/balance')}}" id="formSendTip">
							<input type="number" min="1" max="{{auth()->user()->balance}}" required autocomplete="off" id="onlyNumber" class="form-control mb-3" name="amount" placeholder="{{__('admin.amount')}}">
							@csrf

							<div class="alert alert-danger display-none" id="errorTip">
									<ul class="list-unstyled m-0" id="showErrorsTransfer"></ul>
								</div>

							<div class="text-center">
								<button type="button" class="btn e-none mt-4" data-dismiss="modal">{{__('admin.cancel')}}</button>
								<button type="submit" class="btn btn-primary mt-4 submitForm">
									{{__('general.transfer')}}
								</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div><!-- End Modal Tip  -->
