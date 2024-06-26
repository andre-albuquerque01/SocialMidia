<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Posts extends Model
{
    use HasFactory, HasUuids;

    protected $primaryKey = "idPost";

    protected $table = "posts";

    protected $fillable = [
        'imageUrlOne',
        'description',
        // 'user_idUser',
    ];

    protected $foreignKeys = [
        'user_idUser',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_idUser');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comments::class, 'post_idPost');
    }
    public function rates(): HasMany
    {
        return $this->hasMany(Rate::class, 'post_idPost');
    }
}
