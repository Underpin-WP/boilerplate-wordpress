<?php

namespace Plugin_Name_Replace_Me\Core\Providers;


use Underpin\Interfaces\Data_Provider;

class Unmet_Requirements_Provider implements Data_Provider {

	public function __construct( public readonly array $unmet_expected ) {

	}

}