<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TaxRates;
use App\Models\States;
use App\Models\Countries;
use App\Models\AdminSettings;

class TaxRatesController extends Controller
{
  use Traits\Functions;

  public function __construct(AdminSettings $settings)
	{
		$this->settings = $settings::first();
	}

  public function show()
  {
    $taxes = TaxRates::orderBy('id', 'desc')->get();

    return view('admin.tax-rates')->withTaxes($taxes);

  }//<--- End Method

  public function store(Request $request)
  {
    $messages = [
      'percentage.required' => trans('validation.required', ['attribute' => trans('general.percentage')]),
      'percentage.numeric' => trans('validation.numeric', ['attribute' => trans('general.percentage')]),
      'percentage.min' => trans('validation.min', ['attribute' => trans('general.percentage')]),
      'percentage.max' => trans('validation.max', ['attribute' => trans('general.percentage')]),
    ];

    $validated = $request->validate([
        'name' => 'required|max:250',
        'percentage' => 'required|numeric|min:1|max:100',
    ], $messages);

    $country = Countries::find($request->country);
    $state   = States::whereCode($request->state)->first();

    $tax = new TaxRates();
    $tax->name = $request->name;
    $tax->percentage = $request->percentage;
    $tax->country = $country->country_code;
    $tax->state = $state->name ?? null;
    $tax->iso_state = $state->code ?? null;
    $tax->save();

    // If Stripe is enabled create tax
    $this->createTaxStripe(
          $tax->id,
          $request->name,
          $country,
          $state->code ?? null,
          $request->percentage
        );

    return redirect('panel/admin/tax-rates')
        ->withSuccessMessage(trans('general.success_add_tax'));

  }//<--- End Method

  public function edit($id)
  {
    $tax = TaxRates::findOrFail($id);

    return view('admin.edit-tax')->withTax($tax);
  }//<--- End Method

  public function update(Request $request)
  {
    $tax = TaxRates::findOrFail($request->id);

    $validated = $request->validate([
        'name' => 'required|max:250',
    ]);

    $tax->name = $request->name;
    $tax->status = $request->status ?? false;
    $tax->save();

    if ($tax->stripe_id) {
      // If Stripe is enabled update tax
      $this->updateTaxStripe(
            $tax->stripe_id,
            $request->name,
            $request->status
          );
    }

    return redirect('panel/admin/tax-rates')
        ->withSuccessMessage(trans('general.success_update'));

  }//<--- End Method

  public function getStates(Request $request)
  {
    if (! $request->expectsJson()) {
        abort(404);
    }

    $states = States::whereCountriesId($request->id)->get();

      return response()->json($states);
    }//<--- End Method
}
