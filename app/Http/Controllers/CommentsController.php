<?php

namespace App\Http\Controllers;

use App\Models\Comment;

class CommentsController extends BaseController
{
	/**
	 * Corresponding model name.
	 *
	 * @var App\Models\Comment
	 */
	protected $modelName = Comment::class;
}
