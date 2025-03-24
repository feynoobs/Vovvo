<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\hasMany;
use Staudenmeir\EloquentHasManyDeep\HasManyDeep;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

class Group extends Model
{
    use HasFactory, SoftDeletes, HasRelationships;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'sequence',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'name' => 'string',
        'sequence' => 'int'
    ];

    /**
     * 指定されたグループに紐づく板の一覧の取得
     *
     * @return hasMany 板一覧
     */
    public function boards(): hasMany
    {
        return $this->hasMany(Board::class);
    }

    public function responses(): HasManyDeep
    {
        return $this->HasManyDeep(Response::class, [Board::class, Thread::class]);
    }

    /**
     * シーケンス(表示順)の最大値を返す
     *
     * @return integer シーケンスの最終値
     */
    public static function getNextSequence() : int
    {
        $last = self
            ::withTrashed()
            ->max('sequence');
        ++$last;

        return $last;
    }

}
