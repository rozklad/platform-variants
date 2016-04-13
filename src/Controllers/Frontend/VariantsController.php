<?php namespace Sanatorium\Variants\Controllers\Frontend;

use Platform\Foundation\Controllers\Controller;

class VariantsController extends Controller {

	/**
	 * Return the main view.
	 *
	 * @return \Illuminate\View\View
	 */
	public function index()
	{
		return view('sanatorium/variants::index');
	}

}
