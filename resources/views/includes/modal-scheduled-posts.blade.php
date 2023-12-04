<!-- Start Modal liveStreamingForm -->
<div class="modal fade" id="modalSchedulePost" tabindex="-1" role="dialog" aria-labelledby="modal-form"
    aria-hidden="true">
    <div class="modal-dialog modal- modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body p-0">
                <div class="card bg-white shadow border-0">

                    <div class="card-body px-lg-5 py-lg-5 position-relative">

                        <div class="mb-3">
                            <i class="bi-calendar mr-1"></i> <strong>{{__('general.schedule')}}</strong>
                        </div>

                        <form action="javascript:void(0);">

                        <div class="form-group">
                            <input type="datetime-local" id="datetime" autocomplete="off" required value="" min="{{ now()->tomorrow()->addHours(8) }}" max="{{ now()->addYear() }}" class="form-control" name="date" placeholder="{{ __('admin.date') }} *">

                            <small class="form-text display-none text-danger" id="scheduleAlert">
                                <i class="bi-exclamation-triangle-fill mr-1"></i> <strong>{{ __('general.error_post_schedule') }}</strong>
                            </small>
                        </div><!-- End form-group -->

                        <div class="text-center">
                            <button type="button" class="btn e-none mt-4"
                                data-dismiss="modal">{{__('admin.cancel')}}</button>
                            <button type="submit" class="btn btn-primary mt-4" id="btnSubmitSchedule"><i></i>
                                {{__('general.confirm')}}
                            </button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div><!-- End Modal liveStreamingForm -->