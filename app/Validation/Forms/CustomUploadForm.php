<?php

namespace Printastigo\Validation\Forms;

use Respect\Validation\Validator as v;

class CustomUploadForm
{
	public static function rules(){
		return [
			'design' => v::url(),
			'description' => v::optional(v::alnum(' -,./')),
			'amount' => v::numeric(),
		];
	}
}

?>