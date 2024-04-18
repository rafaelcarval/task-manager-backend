<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @OA\Schema(
 *   required={
 *     "id",
 *     "name",
 *     "email",
 *     "password"
 *   },
 * 	@OA\Property(
 *         property="id",
 *         type="integer",
 *         description="ID do usuário",
 *         example="2"
 *     ),
 * 	@OA\Property(
 *         property="name",
 *         type="string",
 *         description="Nome do usuário",
 *         example="José da Silva"
 *     ),
 * 	@OA\Property(
 *         property="email",
 *         type="string",
 *         description="Email do usuário",
 *         example="josedasilva@gmail.com"
 *     ),
 * 	@OA\Property(
 *         property="password",
 *         type="string",
 *         description="Senha do usuário",
 *         example="josedasilva@gmail.com"
 *     ),
 * 	@OA\Property(
 *         property="created_at",
 *         type="string",
 *         description="Datetime de criação do usuário",
 *         example="2024-04-16 02:24:57"
 *     ),
 * )
 */

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

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
        'password' => 'hashed',
    ];

    public function scopeFilter($search)
    {        
        return $this
            ->where('id','like', '%'. $search .'%')
            ->orWhere('created_at','like', '%'. $search .'%')
            ->orWhere('name','like', '%'. $search .'%')
            ->orWhere('email','like', '%'. $search .'%')->get();
    }
}
