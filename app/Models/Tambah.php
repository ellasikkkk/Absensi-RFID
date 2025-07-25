<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tambah extends Model
{
    protected $table = 'tambah';
    public $timestamps = false;

    protected $fillable = ['tag'];
}
