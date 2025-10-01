<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\Village;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = ['id'];
    // Relasi menggunakan library Laravolt untuk Provinsi, Kota, Kecamatan, dan Kelurahan

    public function getProvinsi()
    {
        return $this->belongsTo(Province::class, 'provinsi', 'id');
    }

    public function getKota()
    {
        return $this->belongsTo(City::class, 'kota', 'id');
    }

    public function getKecamatan()
    {
        return $this->belongsTo(District::class, 'kecamatan', 'id');
    }

    public function getKelurahan()
    {
        return $this->belongsTo(Village::class, 'kelurahan', 'id');
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
