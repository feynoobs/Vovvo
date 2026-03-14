<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Response extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'thread_id',
        'content',
        'sort',
    ];

    /**
     * Casts for attributes.
     *
     * @return array<string,string>
     */
    protected function casts(): array
    {
        return [
            'thread_id' => 'integer',
            'sort' => 'integer',
        ];
    }

    /**
     * Get the thread that owns the response.
     */
    public function thread(): BelongsTo
    {
        return $this->belongsTo(Thread::class);
    }
}
