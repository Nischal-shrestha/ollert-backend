<?php

namespace App\Models\Column;

use App\Models\Board\Board;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Column extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'board_id','created_by'
    ];

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
     * A Column belongs to one board
     *
     * @return BelongsTo
     */
    public function board()
    {
        return $this->belongsTo(Board::class);
    }

    /**
     * A Column is created by one user
     *
     * @return BelongsTo
     */
    public function creator()
    {
        return $this->belongsTo(User::class,'created_by');
    }
}
