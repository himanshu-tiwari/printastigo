<?php

namespace Printastigo\Validation\Forms;

use Respect\Validation\Validator as v;

class CustomUploadForm
{
	public static function rules(){
		return [
			'description' => v::alnum(' -,./'),
		];
	}
}

?>