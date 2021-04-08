/**
 * Import Vue
 */
import Vue from 'vue';
/**
 * Import and use VueX
 */
import VueX from 'vuex';
Vue.use(VueX);

/**
 * Import modules
 */
import constants from './modules/constants';
import authentication from './modules/authentication';
import roles from './modules/roles';
import users from './modules/users';
import categories from './modules/categories';
import posts from './modules/posts';
import games from './modules/games';
import servers from './modules/servers';
import cards from './modules/cards';
import gold from './modules/gold';
import sliders from './modules/sliders';

/**
 * Export default Store
 */
export default new VueX.Store({
    modules: {
        constants: constants,
        authentication: authentication,
        roles: roles,
        users: users,
        categories: categories,
        posts: posts,
        games: games,
        servers: servers,
        cards: cards,
        gold: gold,
        sliders: sliders
    }
});
