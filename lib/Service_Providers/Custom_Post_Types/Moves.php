<?php

namespace Plugin_Name_Replace_Me\Service_Providers\Custom_Post_Types;


use Underpin\WordPress\Custom_Post_Types\Item;

class Moves extends Item {

	public function __construct() {
		parent::__construct(Post_Types::moves->name);
	}

}