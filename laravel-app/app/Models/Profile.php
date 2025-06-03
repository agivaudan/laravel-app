<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Enums\ProfileStatus;
use App\Models\User;

class Profile extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'last_name',
        'first_name',
        'user_id',
        'image',
        'status',
    ];

    /**
     * Relationship to User::class
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    } 
}
