<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

    const SISTEMA = 1;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     *
     * Relación uno a muchos (Inversa - Pertenece a)
     *
     */
    public function role()
    {
        return $this->belongsTo(Role::class)->select('id', 'name', 'description');
    }
    public function group()
    {
        return $this->belongsTo(Group::class)->select('id', 'supervisor_id', 'province_id', 'name');
    }
    public function drone()
    {
        return $this->belongsTo(Drone::class);
    }

    /**
     *
     * Relación uno a muchos
     *
     */
    public function payment_applications()
    {
        return $this->hasMany(PaymentApplication::class, 'drone_id');
    }
    public function events()
    {
        return $this->hasMany(Event::class)->select('id', 'user_id');
    }

    /*
     *
     * Verifico si el usuario tiene uno de los roles indicados
     *
     */
    public function hasRoles(array $roles)
    {
        $rol = collect($this->role->name);
        return (bool) $rol->intersect($roles)->count();
    }

    /**
     *
     * Para definir la navegación del usuario
     *
     */
    public static function navigation()
    {
        return auth()->check() ? auth()->user()->role->name : 'guest';
    }

    /*
     *
     * Devuelve el nombre completo
     *
     */
    public function getFullName()
    {
        return $this->last_name.' '.$this->name;
    }

}
