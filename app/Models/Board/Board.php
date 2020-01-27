<?php

namespace App\Models\Board;

use App\Models\Column\Column;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Board extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'visibility','background','owner_id'
    ];

    /**
     * Board Visibility
     */
    public const VISIBILITY = ['PUBLIC','PRIVATE','TEAM'];
    public const PUBLIC = 'PUBLIC';
    public const PRIVATE = 'PRIVATE';
    public const TEAM = 'TEAM';

    /**
     * The attributes that are dates
     *
     * @var array
     */
    protected $dates = [
        'created_at', 'updated_at', 'deleted_at'
    ];

    /********************************
     * Relationships
     ********************************/

    /**
     * A board can have one owner (user)
     *
     * @return BelongsTo
     */
    public function owner()
    {
        $user = new User;
        return $this->belongsTo(User::class, $user->getDiffKeyName());
    }

    /**
     * A Board can have many users
     *
     * This method will return all the users
     * that is related to the board
     *
     * @return BelongsToMany
     */

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('is_owner');
    }

    /**
     * A Board can have many columns
     *
     * @return HasMany
     */
    public function columns() {
        return $this->hasMany(Column::class);
    }
}
