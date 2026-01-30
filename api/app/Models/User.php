<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Tavs legacy tabulas nosaukums
     */
    protected $table = 'lietotaji';
    public $timestamps = false;


    /**
     * Aizpildāmie lauki (mass assignment)
     */
    protected $fillable = [
        'vards',
        'uzvards',
        'epasts',
        'telefons',
        'personas_kods',
        'adrese',
        'parole',
        'loma',
        'blokets',
        'punkti',
        'limenis_id',
    ];

    /**
     * Ko nerādīt JSON atbildēs
     */
    protected $hidden = [
        'parole',
        'remember_token',
    ];

    /**
     * Cast tipi
     */
    protected function casts(): array
    {
        return [
            'blokets' => 'boolean',
            'punkti' => 'integer',
            'limenis_id' => 'integer',
            // Ja tev vēlāk DB būs email_verified_at kolonna, atkomentē:
            // 'email_verified_at' => 'datetime',
        ];
    }

    /**
     * Laravel pēc noklusējuma meklē "password", bet tev ir "parole".
     * Šis pasaka autentifikācijai, kuru kolonnu izmantot.
     */
    public function getAuthPassword()
    {
        return $this->parole;
    }

    /**
     * Lai visi mail notifikāciju sūtījumi (verifikācija, reset) iet uz "epasts"
     */
    public function routeNotificationForMail($notification = null): string
    {
        return (string) $this->epasts;
    }

    public function getEmailForPasswordReset(): string
    {
        return (string) $this->epasts;
    }

    public function getEmailForVerification(): string
    {
        return (string) $this->epasts;
    }
}
