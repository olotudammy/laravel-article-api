<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Article extends Model
{
    use HasFactory;

    protected $table = 'articles';

    protected $fillable = [
        "title",
        "body",
        "status",
        "published_at",
        "user_id",
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
