<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Model User untuk mengelola data pengguna sistem
 * Class ini mengatur:
 * - Data profil pengguna
 * - Autentikasi pengguna
 * - Relasi dengan role/peran
 * - Keamanan data sensitif
 */
class User extends Authenticatable
{
    /** 
     * Menggunakan trait HasFactory untuk membuat data dummy
     * Menggunakan trait Notifiable untuk fitur notifikasi
     */
    use HasFactory, Notifiable;

    /**
     * Daftar kolom yang bisa diisi melalui mass assignment
     * Mass assignment adalah pengisian data sekaligus melalui array/collection
     */
    protected $fillable = [
        'nama',      // Menyimpan nama lengkap pengguna
        'username',  // Username unik untuk login
        'email',     // Alamat email pengguna (harus unik)
        'password',  // Password yang sudah dienkripsi
        'id_role',   // ID peran/role pengguna dalam sistem
    ];

    /**
     * Daftar atribut yang tidak boleh tampil saat model diubah ke array/JSON
     * Melindungi data sensitif agar tidak terekspos ke response API
     */
    protected $hidden = [
        'password',       // Menyembunyikan hash password
        'remember_token', // Menyembunyikan token "remember me"
    ];

    /**
     * Daftar atribut yang perlu dikonversi ke tipe data khusus
     * Mengatur cara Laravel mengubah data dari/ke database
     */

    /**
     * Mendefinisikan relasi ke model Role
     * Setiap user memiliki satu role yang ditentukan oleh id_role
     * belongsTo artinya user adalah pemilik foreign key
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'id_role');
    }
}