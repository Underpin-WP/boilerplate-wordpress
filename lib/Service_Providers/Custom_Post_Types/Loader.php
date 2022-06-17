<?php

namespace Plugin_Name_Replace_Me\Service_Providers\Custom_Post_Types;


use Underpin\WordPress\Custom_Post_Types;

class Loader extends Custom_Post_Types\Loader {

	public function __construct(
		Pokemon $pokemon,
		Moves   $moves
	) {
		parent::__construct( ...func_get_args() );
	}

	public function pokemon(): Pokemon {
		return $this->get( Post_Types::pokemon->name );
	}

	public function moves(): Moves {
		return $this->get( Post_Types::moves->name );
	}

}