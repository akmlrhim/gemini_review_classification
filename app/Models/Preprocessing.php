<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Preprocessing extends Model
{
	protected $table = 'preprocessing';

	protected $fillable = [
		'created_by',
		'case_folding',
		'tokenize',
		'stopword',
		'lemmatized',
		'label'
	];

	public function user()
	{
		return $this->belongsTo('App\Models\User', 'created_by');
	}
}
