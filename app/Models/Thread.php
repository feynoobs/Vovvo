<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Thread extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'board_id',
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
            'board_id' => 'integer',
            'sort' => 'integer',
        ];
    }

    /**
     * Get the board that owns the thread.
     */
    public function board()
    {
        return $this->belongsTo(Board::class);
    }

    /**
     * Get the responses for the thread.
     */
    public function responses()
    {
        return $this->hasMany(Response::class);
    }
}
