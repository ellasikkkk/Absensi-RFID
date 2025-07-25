<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rfid extends Model
{
    protected $table = 'rfid'; // nama tabel

    public $timestamps = false; // karena tidak menggunakan created_at dan updated_at

    // tambahkan 'status' agar bisa diisi saat create()
    protected $fillable = ['tag', 'tanggal', 'waktu', 'status'];
}
