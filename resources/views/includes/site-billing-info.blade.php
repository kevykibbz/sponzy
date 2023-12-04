@if ($settings->show_address_company_footer)
<div class="w-100 d-block font-12 text-center mt-3">
    <small class="d-block w-100">{{ __('general.company') }}: {{ $settings->company }}</small>
    <small class="d-block w-100">{{ __('general.address') }}: {{ $settings->address }} {{ $settings->city }} {{ $settings->country }}</small>
</div>
@endif
