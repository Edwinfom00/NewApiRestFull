<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $guarded  = [];


    public function interns(){
    	return $this->hasMany(Intern::class);
    }

    public function posts(){
    	return $this->hasMany(Post::class);
    }
    use HasFactory;
}
