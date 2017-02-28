<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Auth;

use App\Background;
use App\Category;
use App\Character;
use App\Edge;
use App\Heritage;
use App\Game;
use App\Skill;
use App\Http\Requests\CharacterFormRequest;
use App\Http\Requests\SetBackgroundsFormRequest;
use App\Http\Requests\SetEdgeFormRequest;
use App\Http\Requests\SetSkillFormRequest;

class CharacterController extends Controller {

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
		$heritages = Heritage::lists('title', 'id');
        $games = Game::lists('title', 'id');

        return view('character.form')
            ->with('heritages', $heritages)
            ->with('games', $games);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(CharacterFormRequest $request)
	{
		$character = new Character();

        $character->name = $request->name;
        $character->heritage_id = $request->heritage_id;
        $character->description = $request->description;
        $character->level = $request->level;
        $character->armour = $request->armour;
        $character->boons_max = $request->boons_max;
        $character->user_id = Auth::User()->id;
        $character->game_id = $request->game_id;

        $character->save();

        return redirect()->route('character.view', [$character->id]);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$character = Character::find($id);
		$skills = Skill::all();
		$learnable_skills = $skills->diff($character->skill);
		$learnable_skills = $learnable_skills->lists('title', 'id');
        $user_name = Auth::user()->name;
		foreach ($character->skill as $skill):
			//calc skill totals from points and bonuses
			//can't use a virtual field because the pivot doesn't really exist
			$skill->pivot["total"] = $skill->pivot["points"] + $skill->pivot["bonus"];
		endforeach;

        return view('character.view')
            ->with('character', $character)
            ->with('learnable_skills', $learnable_skills)
            ->with('user_name', $user_name);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function ajax_show_skills(Request $request)
	{
		//return 'request successful';
		$return_html = View::make('character.ajax_skills')->render();
		return $return_html;
	}
	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$character = Character::find($id);
        $games = Game::lists('title', 'id');
        $heritages = Heritage::lists('title', 'id');

        return view('character.form')
            ->with('character', $character)
            ->with('games', $games)
            ->with('heritages', $heritages);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, CharacterFormRequest $request)
	{
		//
		$character = Character::find($id);

        $character->name = $request->name;
        $character->heritage_id = $request->heritage_id;
        $character->description = $request->description;
        $character->level = $request->level;
        $character->armour = $request->armour;
        $character->boons_max = $request->boons_max;
        $character->game_id = $request->game_id;

        $character->save();

        return redirect()->route('character.view', [$character->id]);
	}

    /**
	 * Show the form for assigning backgrounds to a character. No separate method for adding.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit_backgrounds($id)
	{
		$backgrounds = Background::all();
        $character = Character::find($id);

        return view('character.set_backgrounds')
            ->with('backgrounds', $backgrounds)
            ->with('character', $character);
	}

    /**
	 * Attach or re-attach backgrounds to the character
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function store_backgrounds($id, SetBackgroundsFormRequest $request)
	{
		$character = Character::find($id);
        $character->background()->sync($request->background_id);

        return redirect()->route('character.view', [$id]);
	}

    /**
	 * Show the form for assigning edges to a character. No separate method for adding.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit_edges($id)
	{
		$edges = Edge::orderBy('category_id')->orderBy('title')->get();
        $character = Character::find($id);
        $character_edges = $edges->diff($character->edge);

        return view('character.set_edges')
            ->with('edges', $character_edges)
            ->with('character', $character);
	}

    /**
	 * Attach or re-attach backgrounds to the character
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function sync_edges($id, SetEdgeFormRequest $request)
	{
		$character = Character::find($id);
        $character->edge()->sync($request->edge_id);

        return redirect()->route('character.view', [$id]);
	}


		/**
	 * Attach or re-attach backgrounds to the character
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function store_edges($id, SetEdgeFormRequest $request)
	{
		$character = Character::find($id);
		$character->edge()->attach($request->edge_id);

		return redirect()->route('character.view', [$id]);
	}

	public function ajax_remove_edge(Request $request){
		try{
            $character = Character::find($request->character_id);
            $character->edge()->detach($request->edge_id);
        }
        catch(\Exception $ex) {
            return $ex;
        }
		return 1;

	}
	public function ajax_add_skill(Request $request){
		$character = Character::find($request->character_id);

		$character->skill()->attach($request->skill_id, ['points' => 1, 'bonus' => 0]);
		return 1;

	}

	public function ajax_update_skill(Request $request){
		$character = Character::find($request->character_id);
		$character->skill()->updateExistingPivot($request->skill_id, ['points' => $request->skill_points, 'bonus' => $request->skill_bonus]);
		return 1;

	}

	public function ajax_remove_skill(Request $request){
		try{
            $character = Character::find($request->character_id);
            $character->skill()->detach($request->skill_id);
        }
        catch(\Exception $ex) {
            return $ex;
        }
		return 1;

	}
		 /**
	 * Show the form for assigning skills to a character. No separate method for adding.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit_skills($id)
	{
		$character = Character::find($id);
		$skills_by_category = Category::has('skill')->get();

		$character_with_skills = Character::has('skill')->with('skill')->where('id', $id)->first();
		//return dd($character);
        //$character_skills = collect([]);
        $character_skills = collect([]);
        $character_bonuses = collect([]);
        $skills_ids = Skill::lists('id');

        //populate array of 0 assigned skill id's for points and bonuses
        foreach($skills_ids as $id):
            $character_skills[$id] = 0;
            $character_bonuses[$id] = "0";
        endforeach;

        //replace 0's with skill points or bonuses where appropriate
		if($character_with_skills!=null){
	        foreach($character_with_skills->skill as $skill):
	            $character_skills[$skill->pivot['skill_id']] = $skill->pivot['points'];
	            $character_bonuses[$skill->pivot['skill_id']] = $skill->pivot['bonus'];
	        endforeach;
		}


        return view('character.set_skills')
            ->with('skills_by_category', $skills_by_category)
            ->with('character', $character)
            ->with('character_skills', $character_skills)
            ->with('character_bonuses', $character_bonuses);
	}

    /**
	 * Attach or re-attach skills to the character
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function store_skills($id, SetSkillFormRequest $request)
	{
		$character = Character::find($id);
        $points_array = [];
        foreach ($request->skill_data as $i):
            $points_array = array_merge($points_array, [$i => ['points' => $request->points[$i], 'bonus' => $request->bonus[$i]]]);
        endforeach;

        $points_array = array_combine($request->skill_data, $points_array);

        $character->skill()->sync($points_array);

        return redirect()->route('character.view', [$id]);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update_inventory(Request $request)
	{
		$character = Character::find($request->id);

		$character->inventory = $request->inventory;

		$character->save();
	}

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update_notes(Request $request)
    {
        $character = Character::find($request->id);

        $character->notes = $request->notes;

        $character->save();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update_boons_current(Request $request)
    {
        $character = Character::find($request->id);

        $character->boons_current = $request->boons_current;

        $character->save();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update_wounds_text(Request $request)
    {
        $character = Character::find($request->id);

        $character->wounds_text = $request->wounds_text;

        $character->save();
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
