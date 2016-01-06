<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Faith;
use App\Place;
use App\SettingInformation;
use App\Http\Requests\SettingInformationFormRequest;

class SettingInformationController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$setting_information = SettingInformation::all();
        $places = Place::orderBy('title')->get();
        $faiths = Faith::orderBy('title')->get();
        return view('setting')
            ->with('setting_information', $setting_information)
            ->with('places', $places)
            ->with('faiths', $faiths);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        return view('settingInformation.form');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(SettingInformationFormRequest $request)
	{
        $settingInformation = new SettingInformation;
        
        $settingInformation->section = $request->section;
        $settingInformation->subsection = $request->subsection;
        $settingInformation->title = $request->title;
        $settingInformation->content = $request->content;
            
        
        $settingInformation->save();
        
        return redirect('/setting');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$settingInformation = SettingInformation::find($id);
        
        return view('settingInformation.form')->with('settingInformation', $settingInformation);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, SettingInformationFormRequest $request)
	{
		$settingInformation = SettingInformation::find($id);
        
        $settingInformation->section = $request->section;
        $settingInformation->subsection = $request->subsection;
        $settingInformation->title = $request->title;
        $settingInformation->content = $request->content;
            
        
        $settingInformation->update();
        
        return redirect('/setting');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}
