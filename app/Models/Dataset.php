<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dataset extends Model
{
	protected $fillable = [
		'reviewId',
		'userName',
		'userImage',
		'content',
		'score',
		'thumbUpCount',
		'reviewCreatedVersion',
		'at',
		'replyContent',
		'repliedAt',
		'appVersion'
	];
}
