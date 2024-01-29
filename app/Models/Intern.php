<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Intern extends Model
{

  protected  $fillable =[
    'title',
   'position',
   'description',
    'roles',
   'address',
   'type',
   'last_date'
  ];





    use HasFactory;
}
