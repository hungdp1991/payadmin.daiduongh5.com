<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
    'prefix' => '',
    'namespace' => 'AdminAPI'
], function () {



    /**
     * Authentication routes
     */
    Route::group([
        'prefix' => '',
        'middleware' => 'auth:sanctum'
    ], function () {

        /**
         * Authentication route
         */
        Route::get('check-authentication-status', 'UsersController@checkAuthenticationStatus');
        Route::post('change-password', 'UsersController@changePassword');

        /**
         * Roles routes
         */
        Route::group([
            'prefix' => 'roles'
        ], function () {

            Route::post('permissions-list', 'RolesController@getPermissionsList')->middleware('can:ViewRoles');
            Route::post('all', 'RolesController@all');
            Route::post('list', 'RolesController@list')->middleware('can:ViewRoles');
            Route::post('create', 'RolesController@create')->middleware('can:CreateRole');
            Route::group([
                'middleware' => 'can:UpdateRole'
            ], function() {
                Route::post('info', 'RolesController@info');
                Route::post('update', 'RolesController@update');
            });
            Route::post('delete', 'RolesController@delete')->middleware('can:DeleteRole');
        });

        /**
         * Users routes
         */
        Route::group([
            'prefix' => 'users'
        ], function () {

            Route::post('list', 'UsersController@list')->middleware('can:ViewUsers');
            Route::post('create', 'UsersController@create')->middleware('can:CreateUser');
            Route::group([
                'middleware' => 'can:UpdateUser'
            ], function() {
                Route::post('info', 'UsersController@info');
                Route::post('update', 'UsersController@update');
                Route::post('reset-password', 'UsersController@resetPassword');
            });
            Route::post('delete', 'UsersController@delete')->middleware('can:DeleteUser');
        });

        /**
         * Categories routes
         */
        Route::group([
            'prefix' => 'categories'
        ], function () {

            Route::post('parent-categories', 'CategoriesController@getParentCategoriesList');
            Route::post('list', 'CategoriesController@list')->middleware('can:ViewCategories');
            Route::post('create', 'CategoriesController@create')->middleware('can:CreateCategory');
            Route::group([
                'middleware' => 'can:UpdateCategory'
            ], function() {
                Route::post('info', 'CategoriesController@info');
                Route::post('update', 'CategoriesController@update');
            });
            Route::post('delete', 'CategoriesController@delete')->middleware('can:DeleteCategory');
        });

        /**
         * Post routes
         */
        Route::group([
            'prefix' => 'posts'
        ], function () {

            Route::post('list', 'PostsController@list')->middleware('can:ViewPosts');
            Route::post('create', 'PostsController@create')->middleware('can:CreatePost');
            Route::group([
                'middleware' => 'can:UpdatePost'
            ], function() {
                Route::post('info', 'PostsController@info');
                Route::post('update', 'PostsController@update');
            });
            Route::post('delete', 'PostsController@delete')->middleware('can:DeletePost');
        });

        /**
         * Game routes
         */
        Route::group([
            'prefix' => 'games'
        ], function () {

            Route::post('all', 'GamesController@all');
            Route::post('list', 'GamesController@list')->middleware('can:ViewGames');
            Route::post('create', 'GamesController@create')->middleware('can:CreateGame');
            Route::group([
                'middleware' => 'can:UpdateGame'
            ], function() {
                Route::post('info', 'GamesController@info');
                Route::post('update', 'GamesController@update');
                Route::post('arrange', 'GamesController@arrange');
                Route::post('change-status', 'GamesController@changeStatus');
            });
            Route::post('delete', 'GamesController@delete')->middleware('can:DeleteGame');
        });

        /**
         * Server routes
         */
        Route::group([
            'prefix' => 'servers'
        ], function () {

            Route::post('list', 'ServersController@list')->middleware('can:ViewServers');
            Route::post('list-by-game', 'ServersController@listByGame');
            Route::post('create', 'ServersController@create')->middleware('can:CreateServer');
            Route::post('info', 'ServersController@info')->middleware('can:UpdateServer');

            Route::group([
                'prefix' => 'update',
                'middleware' => 'can:UpdateServer'
            ], function() {

                Route::post('', 'ServersController@update');
                Route::post('status', 'ServersController@updateServerStatus');
                Route::post('payment', 'ServersController@updateServerPayment');
            });

            Route::post('delete', 'ServersController@delete')->middleware('can:DeleteServer');

            Route::post('search', 'ServersController@search')->middleware('can:ViewServers');
        });

        /**
         * Cards routes
         */
        Route::group([
            'prefix' => 'cards'
        ], function () {

            Route::post('all', 'CardsController@all');
            Route::post('list', 'CardsController@list')->middleware('can:ViewCards');
            Route::post('create', 'CardsController@create')->middleware('can:CreateCard');
            Route::post('info', 'CardsController@info');

            Route::group([
                'prefix' => 'update',
                'middleware' => 'can:UpdateCard'
            ], function() {

                Route::post('', 'CardsController@update');
                Route::post('status', 'CardsController@updateCardStatus');
            });
            Route::post('delete', 'CardsController@delete')->middleware('can:DeleteCard');
        });

        /**
         * Gold routes
         */
        Route::group([
            'prefix' => 'gold'
        ], function () {

            Route::post('list', 'GoldController@list')->middleware('can:ViewGold');
            Route::post('list-by-game', 'GoldController@listByGame');
            Route::post('create', 'GoldController@create')->middleware('can:CreateGold');
            Route::group([
                'middleware' => 'can:UpdateGold'
            ], function () {
                Route::post('info', 'GoldController@info');
                Route::post('update', 'GoldController@update');
            });
            Route::post('delete', 'GoldController@delete')->middleware('can:DeleteGold');

            Route::post('search', 'GoldController@search')->middleware('can:ViewServers');
        });

        /**
         * Slider-Banner routes
         */
        Route::group([
            'prefix' => 'sliders'
        ], function () {

            Route::post('list', 'SlidersController@getList')->middleware('can:ViewSliders');
            Route::post('create', 'SlidersController@create')->middleware('can:CreateSlider');
            Route::group([
                'middleware' => 'can:UpdateSlider'
            ], function() {
                Route::post('info', 'SlidersController@info');
                Route::post('update', 'SlidersController@update');
                Route::post('change-image-redirect-link', 'SlidersController@changeImageRedirectLink');
                Route::post('change-image-title', 'SlidersController@changeImageTitle');
                Route::post('change-image-description', 'SlidersController@changeImageDescription');
                Route::post('delete-image-item', 'SlidersController@deleteImageItem');
                Route::post('set-default-slider', 'SlidersController@setDefaultSlider');
            });
            Route::post('delete', 'SlidersController@delete')->middleware('can:DeleteSlider');
        });

        /**
         * Statistics routes
         */
        Route::group([
            'prefix' => 'statistics'
        ], function () {

            Route::post('daily', 'StatisticsController@getDailyStatistics')->middleware('can:ViewDailyReport');
            Route::group([
                'prefix' => 'card-history',
                'middleware' => 'can:ViewCardHistory'
            ], function() {

                Route::post('', 'StatisticsController@getCardHistory');
                Route::post('export', 'StatisticsController@exportCardHistory')->middleware('can:ExportCardHistory');
            });

            Route::group([
                'prefix' => 'pay-history',
                'middleware' => 'can:ViewPayHistory'
            ], function() {

                Route::post('', 'StatisticsController@getPayHistory');
                Route::post('export', 'StatisticsController@exportPayHistory')->middleware('can:ExportPayHistory');
            });

            Route::group([
                'prefix' => 'iap-history',
                'middleware' => 'can:ViewIAPHistory'
            ], function() {

                Route::post('', 'StatisticsController@getIAPHistory');
                Route::post('export', 'StatisticsController@exportIAPHistory')->middleware('can:ExportIAPHistory');
            });
        });

        /**
         * Configuration routes
         */
        Route::group([
            'prefix' => 'configurations'
        ], function () {

            Route::post('get-payment-types-list', 'ConfigurationsController@getPaymentTypesList');
            Route::post('get-server-status-list', 'ConfigurationsController@getServerStatusList');
            Route::post('get-gold-types-list', 'ConfigurationsController@getGoldTypesList');
            Route::post('get-transaction-status-list', 'ConfigurationsController@getTransactionStatusList');
            Route::post('get-os-list', 'ConfigurationsController@getOSList');
        });

        /**
         * Compensation route
         */
        Route::post('/refund', 'CompensationController@refund');
    });
});
