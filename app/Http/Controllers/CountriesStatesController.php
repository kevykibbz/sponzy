<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AdminSettings;
use App\Models\States;
use App\Models\Countries;
use App\Models\User;
use App\Helper;

class CountriesStatesController extends Controller
{
  public function __construct(AdminSettings $settings)
  {
    $this->settings = $settings::first();
  }

  public function countries()
  {
    $countries = Countries::orderBy('id', 'desc')->paginate(50);

    return view('admin.countries')->withCountries($countries);
  }//<--- End Method

  public function addCountry(Request $request)
  {
    $messages = [
			'iso_code.required' => trans('validation.required', ['attribute' => trans('general.iso_code')]),
			'iso_code.unique' => trans('validation.unique', ['attribute' => trans('general.iso_code')]),
		];

		$validated = $request->validate([
        'name' => 'required|unique:countries,country_name',
        'iso_code' => 'required|unique:countries,country_code',
    ], $messages);

    $data = [
      'country_code' => strtoupper($request->iso_code),
      'country_name' => $request->name
    ];

    Countries::create($data);

    return redirect('panel/admin/countries')->withSuccessMessage(trans('admin.success_add'));
  }//<--- End Method

  public function editCountry($id)
	{
		$country = Countries::findOrFail($id);
		return view('admin.edit-country')->withCountry($country);
	}//<--- End Method

  public function updateCountry(Request $request)
  {
    $country = Countries::findOrFail($request->id);
    $messages = [
			'iso_code.required' => trans('validation.required', ['attribute' => trans('general.iso_code')]),
			'iso_code.unique' => trans('validation.unique', ['attribute' => trans('general.iso_code')]),
		];

		$validated = $request->validate([
        'name' => 'required|unique:countries,country_name,'.$request->id,
        'iso_code' => 'required|unique:countries,country_code,'.$request->id,
    ], $messages);

    $data = [
      'country_code' => strtoupper($request->iso_code),
      'country_name' => $request->name
    ];

    $country->update($data);

    return redirect('panel/admin/countries')->withSuccessMessage(trans('admin.success_update'));
  }//<--- End Method

  public function deleteCountry($id)
	{
		$country = Countries::findOrFail($id);

    // Find States
    if ($country->states()->count()) {
      foreach ($country->states()->get() as $state) {
        $state->delete();
      }
    }

    // Remove Country of users
    User::whereCountriesId($country->id)->update([
      'countries_id' => ''
    ]);

		 $country->delete();

		 return redirect('panel/admin/countries');
	}//<--- End Method

  public function states()
  {
    $states = States::orderBy('id', 'desc')->paginate(50);

    return view('admin.states')->withStates($states);
  }//<--- End Method

  public function addState(Request $request)
  {
    $messages = [
			'iso_code.required' => trans('validation.required', ['attribute' => trans('general.iso_code')]),
			'iso_code.unique' => trans('validation.unique', ['attribute' => trans('general.iso_code')]),
		];

		$validated = $request->validate([
        'name' => 'required|unique:states,name',
        'iso_code' => 'required|unique:states,code',
    ], $messages);

    $data = [
      'countries_id' => $request->country,
      'name' => $request->name,
      'code' => strtoupper($request->iso_code)
    ];

    States::create($data);

    return redirect('panel/admin/states')->withSuccessMessage(trans('admin.success_add'));
  }//<--- End Method

  public function editState($id)
	{
		$state = States::findOrFail($id);
		return view('admin.edit-state')->withState($state);
	}//<--- End Method

  public function updateState(Request $request)
  {
    $state = States::findOrFail($request->id);
    $messages = [
			'iso_code.required' => trans('validation.required', ['attribute' => trans('general.iso_code')]),
			'iso_code.unique' => trans('validation.unique', ['attribute' => trans('general.iso_code')]),
		];

		$validated = $request->validate([
        'name' => 'required|unique:states,name,'.$request->id,
        'iso_code' => 'required|unique:states,code,'.$request->id,
    ], $messages);

    $data = [
      'countries_id' => $request->country,
      'name' => $request->name,
      'code' => strtoupper($request->iso_code)
    ];

    $state->update($data);

    return redirect('panel/admin/states')->withSuccessMessage(trans('admin.success_update'));
  }//<--- End Method

  public function deleteState($id)
	{
		$state = States::findOrFail($id)->delete();

		 return redirect('panel/admin/states');
	}//<--- End Method

}
