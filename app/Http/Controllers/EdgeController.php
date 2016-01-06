<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Category;
use App\Edge;
use App\Http\Requests\EdgeFormRequest;

class EdgeController extends Controller {

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
		$categories = Category::lists('title', 'id');
        
        return view('edge.form')->with('categories', $categories);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(EdgeFormRequest $request)
	{
		$edge = new Edge();
        
        $edge->title = $request->title;
        $edge->category_id = $request->category_id;
        $edge->slug = $request->slug;
        $edge->requirements = $request->requirements;
        $edge->content = $request->content;
        
        $edge->save();
        
        return redirect('/rules');
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
        $edge = Edge::find($id);
        $categories = Category::lists('title', 'id');
        
        return view('edge.form')->with('edge', $edge)->with('categories', $categories);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, EdgeFormRequest $request)
	{
        $edge = Edge::find($id);
        
        $edge->title = $request->title;
        $edge->category_id = $request->category_id;
        $edge->slug = $request->slug;
        $edge->requirements = $request->requirements;
        $edge->content = $request->content;
        
        $edge->update();
        
        return redirect('/rules');
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
