<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Category;
use App\Edge;
use App\Skill;
use App\Rule;
use App\Http\Requests\RuleFormRequest;

class WelcomeController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Welcome Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders the "marketing page" for the application and
	| is configured to only allow guests. Like most of the other sample
	| controllers, you are free to modify or remove it as you desire.
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('guest');
	}

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
		$rules = Rule::orderBy('section')->orderBy('subsection')->get();
        $skills = Skill::orderBy('category_id')->orderBy('title')->get();
        $edges = Edge::orderBy('category_id')->orderBy('title')->get();
        $skill_categories = Category::has('skill')->with('skill')->get();
        $edge_categories = Category::has('edge')->with('edge')->get()->sortBy('Edge.category_id')->sortBy('Edge.title');
		return view('rules')
            ->with('edges', $edges)
            ->with('rules', $rules)
            ->with('skills', $skills)
            ->with('skill_categories', $skill_categories)
            ->with('edge_categories', $edge_categories);
	}

}
