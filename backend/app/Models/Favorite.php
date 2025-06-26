<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Favorite extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'word',
        'phonetics',
        'definitions',
        'part_of_speech',
        'example_usage',
        'synonyms',
        'notes'
    ];

    protected $casts = [
        'phonetics' => 'array',
        'definitions' => 'array',
        'part_of_speech' => 'array',
        'example_usage' => 'array',
        'synonyms' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeOlderThan($query, $days = 30)
    {
        return $query->where('created_at', '<', Carbon::now()->subDays($days));
    }
}
