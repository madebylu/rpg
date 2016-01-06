<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Place;
use App\Http\Requests\PlaceFormRequest;

class PlaceController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('place.form');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(PlaceFormRequest $request)
	{
		$place = new Place();
        
        $place->title = $request->title;
        $place->key_words = $request->key_words;
        $place->terrain = $request->terrain;
        $place->rulership = $request->rulership;
        $place->relationships = $request->relationships;
        $place->content = $request->content;
        
        $place->save();
        
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
		$place = Place::find($id);
        
        return view('place.form')->with('place', $place);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, PlaceFormRequest $request)
	{
        $place = Place::find($id);
        
        $place->title = $request->title;
        $place->key_words = $request->key_words;
        $place->terrain = $request->terrain;
        $place->rulership = $request->rulership;
        $place->relationships = $request->relationships;
        $place->content = $request->content;
        
        $place->update();
        
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
