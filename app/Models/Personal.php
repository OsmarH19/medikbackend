<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Personal extends Model
{
    protected $table = 'Personal';
    protected $primaryKey = 'PersonalID';
    public $timestamps = false;
    protected $guarded = [];
}
