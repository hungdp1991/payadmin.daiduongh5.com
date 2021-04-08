/**
 * Import api
 */
import goldService from '../../services/GoldService';


/**
 * Define state
 * @type {{currentUserInfo: null, isAuthenticated: boolean}}
 */
const state = {
    goldList: [],
    pagination: {}
};


/**
 * Define getters
 * @type {{}}
 */
const getters = {

};


/**
 * Define actions
 * @type {{}}
 */
const actions = {
    /**
     * Get gold list
     * @param commit
     * @param currentPage
     * @created 2020-02-24 LongTHK
     */
    async getPaginatedGoldList({commit}, currentPage = 1) {
        await goldService.getPaginatedGoldList(currentPage)
            .then((response) => {
                // commit categories list
                commit('GET_PAGINATED_GOLD_LIST_SUCCESSFULLY', response.data);
            })
    },

    /**
     * Create new gold
     * @param commit
     * @param data
     * @returns {Promise<void>}
     * @created 2020-02-24
     */
    async createGold({commit}, data) {
        await goldService.createGold(data.goldItem)
            .then((response) => {
                commit('CREATE_GOLD_SUCCESSFULLY', response.data);
            })
    },

    /**
     * Delete gold
     * @param commit
     * @param data
     * @returns {Promise<void>}
     * @created 2020-02-24 LongTHK
     */
    async deleteGold({commit}, data) {
        await goldService.deleteGold(data.goldId)
            .then((response) => {
                commit('DELETE_GOLD_SUCCESSFULLY', response.data);
            })
    },

    /**
     * Update gold
     * @param commit
     * @param data
     * @returns {Promise<void>}
     * @created 2020-02-24
     */
    async updateGold({commit}, data) {
        await goldService.updateGold(data.goldItem)
            .then((response) => {
                commit('UPDATE_GOLD_SUCCESSFULLY', response.data);
            })
    },
};


/**
 * Define mutations
 * @type {{}}
 */
const mutations = {

    /**
     * Get gold successfully
     * @param state
     * @param data
     * @constructor
     */
    GET_PAGINATED_GOLD_LIST_SUCCESSFULLY(state, data) {
        state.goldList = data.data;
        state.pagination = data.pagination;
    },

    /**
     * Create new gold successfully
     * @param state
     * @param data
     * @constructor
     * @created 2020-02-24
     */
    CREATE_GOLD_SUCCESSFULLY(state, data) {
        state.goldList = data.data;
        state.pagination = data.pagination;
    },

    /**
     * Delete new gold successfully
     * @param state
     * @param data
     * @constructor
     * @created 2020-02-24 LongTHK
     */
    DELETE_GOLD_SUCCESSFULLY(state, data) {
        state.goldList = data.data;
        state.pagination = data.pagination;
    },

    /**
     * Update card successfully
     * @param state
     * @param data
     * @constructor
     * @created 2020-02-24
     */
    UPDATE_GOLD_SUCCESSFULLY(state, data) {
        state.goldList = data.data;
        state.pagination = data.pagination;
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
