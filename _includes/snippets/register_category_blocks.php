<?php defined('SYSPATH') or die('No direct script access.');

// Start wildlife category block
class category_wildlife_block { // CHANGE THIS FOR OTHER BLOCKS

	public function __construct()
	{
		// Array of block params
		$block = array(
			"classname" => "category_wildlife_block", // Must match class name aboce
			"name" => "Wildlife Reports",
			"description" => "List the 10 latest reports in the wildlife category"
		);
		// register block with core, this makes it available to users 
		blocks::register($block);
	}

	public function block()
	{
		// Load the reports block view
		$content = new View('blocks/category_wildlife_block'); // CHANGE THIS IF YOU WANT A DIFFERENT VIEW
		
		// ID of the category we're looking for
		$category_id = 7; // CHANGE THIS

		// Get Reports
		$content->incidents = ORM::factory('incident')
			->with('location')
			->join('incident_category', 'incident.id', 'incident_category.incident_id')
			->where('incident_active', '1')
			->where('category_id', $category_id)
			->limit('10')
			->orderby('incident_date', 'desc')
			->find_all();

		echo $content;
	}
}

new category_wildlife_block;