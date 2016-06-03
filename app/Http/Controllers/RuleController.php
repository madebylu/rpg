<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Background;
use App\Category;
use App\Edge;
use App\Skill;
use App\Rule;
use App\Item;
use App\Http\Requests\RuleFormRequest;

class RuleController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$rules = Rule::orderBy('section')->orderBy('subsection')->get();
        $skills = Skill::orderBy('category_id')->orderBy('title')->get();
        $edges = Edge::orderBy('category_id')->orderBy('title')->get();
        $items = Item::orderBy('category_id')->orderBy('title')->get();
        $backgrounds = Background::orderBy('title')->get();
        $item_categories = Category::has('item')->with('item')->get();
        $skill_categories = Category::has('skill')->with('skill')->get();
        $edge_categories = Category::has('edge')->with('edge')->get()->sortBy('Edge.category_id')->sortBy('Edge.title');
		return view('rules')
            ->with('edges', $edges)
            ->with('rules', $rules)
            ->with('skills', $skills)
            ->with('items', $items)
            ->with('backgrounds', $backgrounds)
            ->with('skill_categories', $skill_categories)
            ->with('edge_categories', $edge_categories)
            ->with('item_categories', $item_categories);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('rule.form');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(RuleFormRequest $request)
	{
		$rule = new Rule;

        $rule->section = $request->section;
        $rule->subsection = $request->subsection;
        $rule->title = $request->title;
        $rule->content = $request->content;


        $rule->save();

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
		$rule = Rule::find($id);

        return view('rule.form')->with('rule', $rule);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, RuleFormRequest $request)
	{
        $rule = Rule::find($id);

        $rule->section = $request->section;
        $rule->subsection = $request->subsection;
        $rule->title = $request->title;
        $rule->content = $request->content;

        $rule->update();

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
