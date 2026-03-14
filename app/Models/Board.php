<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Board extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'group_id',
        'name',
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
            'group_id' => 'integer',
            'sort' => 'integer',
        ];
    }

    /**
     * Get the group that owns the board.
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    /**
     * Get the threads for the board.
     */
    public function threads(): HasMany
    {
        return $this->hasMany(Thread::class);
    }
}
