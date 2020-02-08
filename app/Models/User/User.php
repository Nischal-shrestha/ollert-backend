<?php

namespace App\Models\User;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Board\Board;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;


    /**
     * Key name referenced in board
     */
    protected $diffKeyName = 'owner_id';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getDiffKeyName()
    {
        return $this->diffKeyName;
    }

    /********************************
     * Accessors
     ********************************/


    /********************************
     * Mutators
     ********************************/

    /**
     * Add a mutator to ensure hashed passwords
     *
     * @param $password
     * @return String
     */
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }


    /********************************
     * Relationships
     ********************************/


    /**
     * A user can have own zero or many boards
     *
     * This method will only return the boards
     * that the user owns / created
     *
     * @return HasMany
     */
    public function ownedBoards()
    {
        return $this->hasMany(Board::class, $this->diffKeyName);
    }

    /**
     * A user can be included in zero or many boards
     *
     * This method will return owned boards as well
     * the boards that we have been invited to
     *
     * @return BelongsToMany
     */

    public function boards()
    {
        return $this->belongsToMany(Board::class)->withPivot('is_owner');
    }
}
