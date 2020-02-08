<?php

namespace App\Providers;

use App\Models\Board\Board;
use App\Models\User\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes(function ($router) {
            $router->forAccessTokens();
        });
        // Passport::ignoreMigrations();

        Passport::tokensExpireIn(now()->addDays(1));

        Passport::refreshTokensExpireIn(now()->addDays(5));

        /**
         * Gates
         */

        /**
         * Check if a user has access to view the board
         */
        Gate::define('view-board', function (User $user, Board $board) {
            if ($user->id == $board->owner_id) {
                return true;
            } else {
                if ($board->visibility == Board::PUBLIC) {
                    return true;
                } else if ($board->visibility == Board::PRIVATE) {
                    return $board->owner_id == $user->id || $board->users->contains($user->id);
                } else if ($board->visibility == Board::TEAM) {
                    return $user->boards->contains($board->id);
                } else {
                    return false;
                }
            }
        });
    }
}
