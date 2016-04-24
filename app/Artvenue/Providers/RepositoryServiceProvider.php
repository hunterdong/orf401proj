<?php
/**
 * @author Abhimanyu Sharma <abhimanyusharma003@gmail.com>
 */
namespace App\Artvenue\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'App\Artvenue\Repository\ImageRepositoryInterface',
            'App\Artvenue\Repository\Eloquent\ImageRepository'
        );

        $this->app->bind(
            'App\Artvenue\Repository\UsersRepositoryInterface',
            'App\Artvenue\Repository\Eloquent\UsersRepository'
        );

        $this->app->bind(
            'App\Artvenue\Repository\BlogRepositoryInterface',
            'App\Artvenue\Repository\Eloquent\BlogRepository'
        );

        $this->app->bind(
            'App\Artvenue\Repository\CategoryRepositoryInterface',
            'App\Artvenue\Repository\Eloquent\CategoryRepository'
        );

        $this->app->bind(
            'App\Artvenue\Repository\FlagsRepositoryInterface',
            'App\Artvenue\Repository\Eloquent\FlagsRepository'
        );

        $this->app->bind(
            'App\Artvenue\Repository\CommentsRepositoryInterface',
            'App\Artvenue\Repository\Eloquent\CommentsRepository'
        );

        $this->app->bind(
            'App\Artvenue\Repository\ReplyRepositoryInterface',
            'App\Artvenue\Repository\Eloquent\ReplyRepository'
        );

        $this->app->bind(
            'App\Artvenue\Repository\VotesRepositoryInterface',
            'App\Artvenue\Repository\Eloquent\VotesRepository'
        );

        $this->app->bind(
            'App\Artvenue\Repository\FollowRepositoryInterface',
            'App\Artvenue\Repository\Eloquent\FollowRepository'
        );

        $this->app->bind(
            'App\Artvenue\Repository\FavoriteRepositoryInterface',
            'App\Artvenue\Repository\Eloquent\FavoriteRepository'
        );
    }
}