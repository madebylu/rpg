<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Skill;
use App\Category;
use App\Http\Requests\SkillFormRequest;

class SkillController extends Controller {

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
		$categories = Category::lists('title','id');
        
        return view('skill.form')->with('categories', $categories);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(SkillFormRequest $request)
	{
		$skill = new Skill;
        
        $skill->title = $request->title;
        $skill->category_id = $request->category_id;
        $skill->scope = $request->scope;
        $skill->content = $request->content;
        $skill->trivial_tasks = $request->trivial_tasks;
        $skill->average_tasks = $request->average_tasks;
        $skill->challenging_tasks = $request->challenging_tasks;
        $skill->impossible_tasks = $request->impossible_tasks;
        $skill->opposed_tasks = $request->opposed_tasks;
        
        $skill->save();
        
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
		$categories = Category::lists('title', 'id');
        $skill = Skill::find($id);
        
        return view('skill.form')->with('skill', $skill)->with('categories', $categories);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, SkillFormRequest $request)
	{
        $skill = Skill::find($id);
        
		$skill->title = $request->title;
        $skill->category_id = $request->category_id;
        $skill->scope = $request->scope;
        $skill->content = $request->content;
        $skill->trivial_tasks = $request->trivial_tasks;
        $skill->average_tasks = $request->average_tasks;
        $skill->challenging_tasks = $request->challenging_tasks;
        $skill->impossible_tasks = $request->impossible_tasks;
        $skill->opposed_tasks = $request->opposed_tasks;
        
        $skill->save();
        
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
