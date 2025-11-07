<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable; // <- Šis ir OBLIGĀTS!
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'lietotajs'; // Tava BDUS1 tabula
}

