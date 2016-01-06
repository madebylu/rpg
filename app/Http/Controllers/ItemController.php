<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Category;
use App\Item;
use Illuminate\Http\Request;

use App\Http\Requests\ItemFormRequest;

class ItemController extends Controller {

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

        return view('item.form')->with('categories', $categories);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(ItemFormRequest $request)
	{

		$item = new Item();

		$item->title = $request->title;
		$item->category_id = $request->category_id;
		$item->cost = $request->cost;
		$item->content = $request->content;
		$item->save();

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
		$item = Item::find($id);
		$categories = Category::lists('title', 'id');

		return view('item.form')->with('item', $item)->with('categories', $categories);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, ItemFormRequest $request)
	{
		$item = Item::find($id);

		$item->title = $request->title;
		$item->category_id = $request->category_id;
		$item->cost = $request->cost;
		$item->content = $request->content;

		$item->update();

		return redirect('/rules#item'.$id);
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
