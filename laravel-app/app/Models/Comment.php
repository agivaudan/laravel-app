<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'content',
        'user_id',
        'profile_id',
    ];

    /**
     * Relationship to User::class
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship to Profile::class
     */
    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }
}
