<?php

namespace App;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Board extends Model
{
    use SoftDeletes;



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
     * @return hasOneRelationship
     */
    public function owner()
    {
        $user = new User;
        return $this->hasOne(User::class, $user->getKeyName(), $user->getDiffKeyName());
    }

    /**
     * A Board can have many users
     * 
     * This method will return all the users
     * that is related to the board 
     * 
     * @return belongsToMany
     */

    public function users() 
    {
        return $this->belongsToMany(User::class)->withPivot('is_owner');
    }
}
