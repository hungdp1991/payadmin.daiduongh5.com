/**
 * Import Vue
 */
import Vue from 'vue';
/**
 * Import VueX
 */
import store from './vuex/store';
/**
 * Import and use VueRouter
 */
import VueRouter from 'vue-router';
/**
 * Import components
 */
import LoginComponent from "./components/LoginComponent";
import MainComponent from "./components/MainComponent";
import DashboardComponent from "./components/pages/DashboardComponent";
import RolesComponent from "./components/pages/roles/RolesComponent";
import UsersComponent from "./components/pages/users/UsersComponent";
import CategoriesComponent from "./components/pages/categories/CategoriesComponent";
import PostsMainComponent from "./components/pages/posts/PostsMainComponent";
import PostsListComponent from "./components/pages/posts/PostsListComponent";
import PostsInputComponent from "./components/pages/posts/PostsInputComponent";
import GamesComponent from "./components/pages/games/GamesComponent";
import ServersComponent from "./components/pages/servers/ServersComponent";
import CardsComponent from "./components/pages/cards/CardsComponent";
import GoldComponent from "./components/pages/gold/GoldComponent";
import SlidersComponent from "./components/pages/sliders/SlidersComponent";
import StatisticsMainComponent from "./components/pages/statistics/StatisticsMainComponent";
import DailyStatisticsComponent from "./components/pages/statistics/DailyStatisticsComponent";
import CardHistoryComponent from "./components/pages/statistics/CardHistoryComponent";
import PayHistoryComponent from "./components/pages/statistics/PayHistoryComponent";
import IAPHistoryComponent from "./components/pages/statistics/IAPHistoryComponent";

Vue.use(VueRouter);

/**
 * Import modules
 */
import {displayError} from "./helpers/swal";

/**
 * Define routes list
 */
const routes = [
    {
        path: '/login',
        name: 'LoginComponent',
        component: LoginComponent
    },

    {
        path: '/',
        component: MainComponent,
        meta: {
            checkAuthentication: true
        },
        children: [
            {
                path: '',
                name: 'DashboardComponent',
                component: DashboardComponent
            },

            {
                path: 'roles',
                name: 'RolesComponent',
                component: RolesComponent,
                meta: {
                    checkPermission: 'ViewRoles'
                }
            },

            {
                path: 'users',
                name: 'UsersComponent',
                component: UsersComponent,
                meta: {
                    checkPermission: 'ViewUsers'
                }
            },

            {
                path: 'categories',
                name: 'CategoriesComponent',
                component: CategoriesComponent,
                meta: {
                    checkPermission: 'ViewCategories'
                }
            },

            {
                path: 'posts',
                component: PostsMainComponent,
                children: [
                    {
                        path: '',
                        name: 'PostsListComponent',
                        component: PostsListComponent,
                        meta: {
                            checkPermission: 'ViewPosts'
                        }
                    },

                    {
                        path: 'create',
                        name: 'PostsCreatingComponent',
                        component: PostsInputComponent,
                        meta: {
                            checkPermission: 'ViewPosts|CreatePost'
                        }
                    },

                    {
                        path: 'update/:postId',
                        name: 'PostsUpdatingComponent',
                        component: PostsInputComponent,
                        meta: {
                            checkPermission: 'ViewPosts|UpdatePost'
                        }
                    }
                ]
            },

            {
                path: 'games',
                name: 'GamesComponent',
                component: GamesComponent,
                meta: {
                    checkPermission: 'ViewGames'
                }
            },

            {
                path: 'servers',
                name: 'ServersComponent',
                component: ServersComponent,
                meta: {
                    checkPermission: 'ViewServers'
                }
            },

            {
                path: 'cards',
                name: 'CardsComponent',
                component: CardsComponent,
                meta: {
                    checkPermission: 'ViewCards'
                }
            },

            {
                path: 'gold',
                name: 'GoldComponent',
                component: GoldComponent,
                meta: {
                    checkPermission: 'ViewGold'
                }
            },

            {
                path: 'sliders',
                name: 'SlidersComponent',
                component: SlidersComponent,
                meta: {
                    checkPermission: 'ViewSliders'
                }
            },

            {
                path: 'statistics',
                component: StatisticsMainComponent,
                children: [
                    {
                        path: 'daily',
                        name: 'DailyStatisticsComponent',
                        component: DailyStatisticsComponent,
                        meta: {
                            checkPermission: 'ViewDailyReport'
                        }
                    },

                    {
                        path: 'card-history',
                        name: 'CardHistoryComponent',
                        component: CardHistoryComponent,
                        meta: {
                            checkPermission: 'ViewCardHistory'
                        }
                    },

                    {
                        path: 'pay-history',
                        name: 'PayHistoryComponent',
                        component: PayHistoryComponent,
                        meta: {
                            checkPermission: 'ViewPayHistory'
                        }
                    },

                    {
                        path: 'iap-history',
                        name: 'IAPHistoryComponent',
                        component: IAPHistoryComponent,
                        meta: {
                            checkPermission: 'ViewIAPHistory'
                        }
                    },
                ]
            }
        ]
    },
];

/**
 * Create router instance
 * @type {VueRouter}
 */
const router = new VueRouter({
    routes,
    mode: 'history'
});

/**
 * Routers middleware
 */
router.beforeEach((to, from, next) => {
    // redirect route
    if (store.state.authentication.isAuthenticated && to.fullPath === '/login') {
        next('/');
    } else if (!store.state.authentication.isAuthenticated && to.matched.some((routeItem) => routeItem.meta.checkAuthentication)) {
        next('/login');
    } else {
        // get check permissions item
        let checkPermissions = to.meta.checkPermission;
        if (checkPermissions !== undefined) {
            // get check permission items list
            let checkPermissionItemsList = checkPermissions.split('|');
            let checkPermissionStatus = true;
            _.forEach(checkPermissionItemsList, function (item) {
                if (!store.state.authentication.userInfo.role.permissions.includes(item)) {
                    checkPermissionStatus = false;
                    return false;
                }
            });

            // determine target
            if (!checkPermissionStatus) {
                displayError(403);
                next(from.fullPath)
            } else {
                return next();
            }
        } else {
            next();
        }
    }
});

/**
 * Export router
 */
export default router;
