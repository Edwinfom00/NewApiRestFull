<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Intern extends Model
{
    use SoftDeletes;
    use HasFactory;

    public function getRouteKeyName()
    {
        return 'slug';
    }

    protected $guarded = [];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }


    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    //Vérifie si l'utilisateur connecté a déjà postulé à ce stage
    public function checkApplication()
    {
        return DB::table('intern_user')->where('user_id', auth()->user()->id)->where('intern_id', $this->id)->exists();
    }


    public function favorites()
    {
        return $this->belongsToMany(Intern::class, 'favorites', 'intern_id', 'user_id')->withTimestamps();
    }


    //Vérifie si l'utilisateur connecté a enregistré ce stage dans ses favoris.
    public function checkSaved()
    {
        return DB::table('favorites')->where('user_id', auth()->user()->id)->where('intern_id', $this->id)->exists();
    }
}