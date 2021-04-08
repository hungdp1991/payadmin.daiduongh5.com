/**
 * Import api
 */
import userService from '../../services/UserService';


/**
 * Define state
 * @type {{currentUserInfo: null, isAuthenticated: boolean}}
 */
const state = {
    usersList: [],
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
     * Get users list
     * @param currentPage
     * @param commit
     * @created 2020-02-13 LongTHK
     */
    async getPaginatedUsersList({commit}, currentPage = 1) {
        await userService.getPaginatedUsersList(currentPage)
            .then((response) => {
                commit('GET_PAGINATED_USERS_LIST_SUCCESSFULLY', response.data);
            })
    },

    /**
     * Create user
     * @param commit
     * @param data
     * @returns {Promise<void>}
     * @created 2020-02-13 LongTHK
     */
    async createUser({commit}, data) {
        await userService.createUser(data.userItem, data.currentPage)
            .then((response) => {
                commit('CREATE_USER_SUCCESSFULLY', response.data);
            })
    },

    /**
     * Delete user
     * @param commit
     * @param data
     * @returns {Promise<void>}
     * @created 2020-02-13 LongTHK
     */
    async deleteUser({commit}, data) {
        await userService.deleteUser(data.userId, data.currentPage)
            .then((response) => {
                commit('DELETE_USER_SUCCESSFULLY', response.data);
            })
    },

    /**
     * Update user
     * @param commit
     * @param data
     * @returns {Promise<void>}
     * @created 2020-02-13 LongTHK
     */
    async updateUser({commit}, data) {
        await userService.updateUser(data.userItem, data.currentPage)
            .then((response) => {
                commit('UPDATE_USER_SUCCESSFULLY', response.data);
            })
    },
};


/**
 * Define mutations
 * @type {{}}
 */
const mutations = {
    /**
     * Get users list successfully
     * @param state
     * @param data
     * @constructor
     * @created 2020-02-13 LongTHK
     */
    GET_PAGINATED_USERS_LIST_SUCCESSFULLY(state, data) {
        state.usersList = data.data;
        state.pagination = data.pagination;
    },

    /**
     * Create user successfully
     * @param state
     * @param data
     * @constructor
     * @created 2020-02-13 LongTHK
     */
    CREATE_USER_SUCCESSFULLY(state, data) {
        state.usersList = data.data;
        state.pagination = data.pagination;
    },

    /**
     * Delete user successfully
     * @param state
     * @param data
     * @constructor
     * @created 2020-02-13 LongTHK
     */
    DELETE_USER_SUCCESSFULLY(state, data) {
        state.usersList = data.data;
        state.pagination = data.pagination;
    },

    /**
     * Update user successfully
     * @param state
     * @param data
     * @constructor
     * @created 2020-02-13 LongTHK
     */
    UPDATE_USER_SUCCESSFULLY(state, data) {
        state.usersList = data.data;
        state.pagination = data.pagination;
    }
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
