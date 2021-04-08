/**
 * Import api
 */
import gamesService from '../../services/GamesService';


/**
 * Define state
 * @type {{currentUserInfo: null, isAuthenticated: boolean}}
 */
const state = {
    allGamesList: {
        needReload: true,
        list: []
    },
    gamesList: []
};


/**
 * Define getters
 * @type {{}}
 */
const getters = {};


/**
 * Define actions
 * @type {{}}
 */
const actions = {
    /**
     * Get all games list
     * @param commit
     * @created 2020-02-20 LongTHK
     */
    async getAllGamesList({commit}) {
        await gamesService.getAllGamesList()
            .then((response) => {
                commit('GET_ALL_GAMES_LIST_SUCCESSFULLY', response.data);
            })
    },

    /**
     * Get games list
     * @param currentPage
     * @param commit
     * @created 2020-02-19 LongTHK
     */
    async getPaginatedGamesList({commit}, currentPage = 1) {
        await gamesService.getPaginatedGamesList(currentPage)
            .then((response) => {
                commit('GET_PAGINATED_GAMES_LIST_SUCCESSFULLY', response.data);
            })
    },

    /**
     * Create game
     * @param commit
     * @param data
     * @returns {Promise<void>}
     * @created 2020-02-19 LongTHK
     */
    async createGame({commit}, data) {
        await gamesService.createGame(data.gameItem)
            .then((response) => {
                commit('CREATE_GAME_SUCCESSFULLY', response.data);
            })
    },

    /**
     * Delete post
     * @param commit
     * @param data
     * @returns {Promise<void>}
     * @created 2020-02-19 LongTHK
     */
    async deleteGame({commit}, data) {
        await gamesService.deleteGame(data.gameId, data.currentPage)
            .then((response) => {
                commit('DELETE_GAME_SUCCESSFULLY', response.data);
            })
    },

    /**
     * Update game
     * @param commit
     * @param data
     * @returns {Promise<void>}
     */
    async updateGame({commit}, data) {
        await gamesService.updateGame(data.gameItem)
            .then((response) => {
                commit('UPDATE_GAME_SUCCESSFULLY', response.data);
            })
    },

    /**
     * Change game status
     * @param commit
     * @param data
     * @returns {Promise<void>}
     */
    async changeGameStatus({commit}, data) {
        await gamesService.changeGameStatus(data)
            .then((response) => {
                commit('UPDATE_GAME_SUCCESSFULLY', response.data);
            })
    }
};


/**
 * Define mutations
 * @type {{}}
 */
const mutations = {
    /**
     * Get all games list successfully
     * @param state
     * @param data
     * @constructor
     * @created 2020-02-20 LongTHK
     */
    GET_ALL_GAMES_LIST_SUCCESSFULLY(state, data) {
        state.allGamesList.needReload = false;
        state.allGamesList.list = data.data;
    },

    /**
     * Get games list successfully
     * @param state
     * @param data
     * @constructor
     * @created 2020-02-19 LongTHK
     */
    GET_PAGINATED_GAMES_LIST_SUCCESSFULLY(state, data) {
        state.gamesList = data.data;
        state.pagination = data.pagination;
    },

    /**
     * Create game successfully
     * @param state
     * @param data
     * @constructor
     * @created 2020-02-19 LongTHK
     */
    CREATE_GAME_SUCCESSFULLY(state, data) {
        state.gamesList = data.data;
        state.pagination = data.pagination;

        state.allGamesList.needReload = true;
    },

    /**
     * @param state
     * @param data
     * @constructor
     * @created 2020-02-19 LongTHK
     */
    DELETE_GAME_SUCCESSFULLY(state, data) {
        state.gamesList = data.data;
        state.pagination = data.pagination;

        state.allGamesList.needReload = true;
    },

    /**
     * Update post successfull

        state.allGamesList.needReload = true; @constructor
     * @created 2020-02-19 LongTHK
     */
    UPDATE_GAME_SUCCESSFULLY(state, data) {
        state.gamesList = data.data;
        state.pagination = data.pagination;

        state.allGamesList.needReload = true;
    },
};


/**
 * Export module
 */
export default {
    state,
    getters,
    actions,
    mutations
}
