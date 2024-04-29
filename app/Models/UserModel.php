<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class UserModel extends Authenticatable
{
    use Notifiable;

    protected $table = "m_user";
    protected $primaryKey = "user_id";
    protected $fillable = [
        'nama',
        'username',
        'password',
        'activate',
        'foto',
        'level_id',
    ];
    
    protected $hidden = [
        'password',
    ];
    
    protected $casts = [
        'password' => 'hashed',
    ];

    public function level(): BelongsTo {
        return $this->belongsTo(LevelModel::class, 'level_id', 'level_id');
    }
}
