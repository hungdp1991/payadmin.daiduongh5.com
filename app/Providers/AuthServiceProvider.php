<?php

namespace App\Providers;

use App\Policies\CardsPolicy;
use App\Policies\CategoriesPolicy;
use App\Policies\GamesPolicy;
use App\Policies\GoldPolicy;
use App\Policies\PostsPolicy;
use App\Policies\RolesPolicy;
use App\Policies\ServersPolicy;
use App\Policies\SlidersPolicy;
use App\Policies\StatisticsPolicy;
use App\Policies\UsersPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

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

        // register own policies
        $this->registerOwnPolicies();
    }

    /**
     * Define own policies
     * @created 2020-03-03 LongTHK
     */
    private function registerOwnPolicies()
    {
        // Define gate on Roles model
        Gate::define('ViewRoles', RolesPolicy::class . '@viewRoles');
        Gate::define('CreateRole', RolesPolicy::class . '@createRole');
        Gate::define('UpdateRole', RolesPolicy::class . '@updateRole');
        Gate::define('DeleteRole', RolesPolicy::class . '@deleteRole');

        // Define gate on Users model
        Gate::define('ViewUsers', UsersPolicy::class . '@viewUsers');
        Gate::define('CreateUser', UsersPolicy::class . '@createUser');
        Gate::define('UpdateUser', UsersPolicy::class . '@updateUser');
        Gate::define('DeleteUser', UsersPolicy::class . '@deleteUser');

        // Define gate on Categories model
        Gate::define('ViewCategories', CategoriesPolicy::class . '@viewCategories');
        Gate::define('CreateCategory', CategoriesPolicy::class . '@createCategory');
        Gate::define('UpdateCategory', CategoriesPolicy::class . '@updateCategory');
        Gate::define('DeleteCategory', CategoriesPolicy::class . '@deleteCategory');

        // Define gate on Posts model
        Gate::define('ViewPosts', PostsPolicy::class . '@viewPosts');
        Gate::define('CreatePost', PostsPolicy::class . '@createPost');
        Gate::define('UpdatePost', PostsPolicy::class . '@updatePost');
        Gate::define('DeletePost', PostsPolicy::class . '@deletePost');

        // Define gate on Games model
        Gate::define('ViewGames', GamesPolicy::class . '@viewGames');
        Gate::define('CreateGame', GamesPolicy::class . '@createGame');
        Gate::define('UpdateGame', GamesPolicy::class . '@updateGame');
        Gate::define('DeleteGame', GamesPolicy::class . '@deleteGame');

        // Define gate on Servers model
        Gate::define('ViewServers', ServersPolicy::class . '@viewServers');
        Gate::define('CreateServer', ServersPolicy::class . '@createServer');
        Gate::define('UpdateServer', ServersPolicy::class . '@updateServer');
        Gate::define('DeleteServer', ServersPolicy::class . '@deleteServer');

        // Define gate on Cards model
        Gate::define('ViewCards', CardsPolicy::class . '@viewCards');
        Gate::define('CreateCard', CardsPolicy::class . '@createCard');
        Gate::define('UpdateCard', CardsPolicy::class . '@updateCard');
        Gate::define('DeleteCard', CardsPolicy::class . '@deleteCard');

        // Define gate on Gold model
        Gate::define('ViewGold', GoldPolicy::class . '@viewGold');
        Gate::define('CreateGold', GoldPolicy::class . '@createGold');
        Gate::define('UpdateGold', GoldPolicy::class . '@updateGold');
        Gate::define('DeleteGold', GoldPolicy::class . '@deleteGold');

        // Define gate on Slider model
        Gate::define('ViewSliders', SlidersPolicy::class . '@viewSliders');
        Gate::define('CreateSlider', SlidersPolicy::class . '@createSlider');
        Gate::define('UpdateSlider', SlidersPolicy::class . '@updateSlider');
        Gate::define('DeleteSlider', SlidersPolicy::class . '@deleteSlider');

        // Define gate on Statistics
        Gate::define('ViewDailyReport', StatisticsPolicy::class . '@viewDailyReport');
        Gate::define('ViewCardHistory', StatisticsPolicy::class . '@viewCardHistory');
        Gate::define('ExportCardHistory', StatisticsPolicy::class . '@exportCardHistory');
        Gate::define('ViewPayHistory', StatisticsPolicy::class . '@viewPayHistory');
        Gate::define('ExportPayHistory', StatisticsPolicy::class . '@exportPayHistory');
        Gate::define('ViewIAPHistory', StatisticsPolicy::class . '@viewIAPHistory');
        Gate::define('ExportIAPHistory', StatisticsPolicy::class . '@exportIAPHistory');
    }
}
