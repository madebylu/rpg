<?php namespace App\Http\Controllers;

use Illumineate\Foundation\Bus\DispacthesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

abstract class Controller extends BaseController {

	use ValidatesRequests;

}
