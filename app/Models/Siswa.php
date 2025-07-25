<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $table = 'siswa'; // nama tabel di database
    protected $primaryKey = 'id';
    public $timestamps = false; // karena tidak ada kolom created_at dan updated_at

    protected $fillable = ['tag', 'Nama', 'Kelas'];
}
