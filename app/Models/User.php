<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Enums\UserType;
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'telefono', // Añadir esta línea
        'direccion', // Añadir esta línea
        'tipo', // Añadir esta línea
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function modulos()
    {
        return $this->hasMany(Modulo::class, 'docente_id');
    }

        // Métodos de ayuda para verificar el tipo de usuario
    public function isDocente(): bool
    {
        return $this->tipo === UserType::Docente;
    }

    public function isAdministrativo(): bool
    {
        return $this->tipo === UserType::Administrativo;
    }
    


}
