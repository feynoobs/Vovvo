<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \Illuminate\Database\Eloquent\Relations\HasMany;

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
        'sequence',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'board_id' => 'int',
        'name' => 'string',
        'sequence' => 'int'
    ];

    /**
     * 指定されたグループに紐づく板の一覧の取得
     *
     * @return hasMany 板一覧
     */
    public function responses(): hasMany
    {
        return $this->hasMany(Response::class);
    }
}
