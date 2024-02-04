<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{

    protected $fillable =[
        'cname','user_id', 'slug', 'address', 'phone', 'website', 'logo','banner', 'slogan', 'description'
    ];

    public function interns(){
        return $this->hasMany(Intern::class);
    }
    public function getRouteKeyName()
    {
        return 'slug';
    }
    use HasFactory;
}