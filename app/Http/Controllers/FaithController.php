<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Faith;
use App\Http\Requests\FaithFormRequest;

class FaithController extends Controller {

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
		return view('faith.form');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(FaithFormRequest $request)
	{
		$faith = new Faith();
        
        $faith->title = $request->title;
        $faith->honourific = $request->honourific;
        $faith->beliefs = $request->beliefs;
        $faith->structure = $request->structure;
        
        $faith->save();
        
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
		$faith = Faith::find($id);
        
        return view('faith.form')->with('faith', $faith);

	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, FaithFormRequest $request)
	{
		$faith = Faith::find($id);
        
        $faith->title = $request->title;
        $faith->honourific = $request->honourific;
        $faith->beliefs = $request->beliefs;
        $faith->structure = $request->structure;
        
        $faith->update();
        
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
