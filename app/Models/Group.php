<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Group extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
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
            'sort' => 'integer',
        ];
    }

    /**
     * Get the boards for the group.
     */
    public function boards(): HasMany
    {
        return $this->hasMany(Board::class);
    }
}
