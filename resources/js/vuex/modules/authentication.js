/**
 * Import api
 */
import userService from '../../services/UserService';


/**
 * Define state
 * @type {{currentUserInfo: null, isAuthenticated: boolean}}
 */
const state = {
    isAuthenticated: false,
    userInfo: {}
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
     * Check authentication status
     * @param commit
     * @returns {Promise<void>}
     */
    async checkAuthenticationStatus({commit}) {
        await userService.checkAuthenticationStatus()
            .then((response) => {
                commit('CHECK_AUTHENTICATION_STATUS_SUCCESSFULLY', response.data);
            })
            .catch(() => {})
    },

    /**
     * Authenticate
     * @param commit
     * @param loginInfo
     * @returns {Promise<void>}
     */
    async authenticate({commit}, loginInfo) {
        await userService.getCSRF();
        await userService.authenticate(loginInfo)
            .then((response) => {
                // get return data
                let returnData = response.data;
                // commit store
                commit('AUTHENTICATE_SUCCESSFULLY', returnData);
                // resolve
                return Promise.resolve();
            })
            .catch(() => {
                // reject
                return Promise.reject();
            })
    },

    /**
     * Logout
     * @param commit
     * @returns {Promise<void>}
     */
    async logout({commit}) {
        await userService.logout()
            .then(() => {
                // commit store
                commit('LOGOUT_SUCCESSFULLY');
                // resolve
                return Promise.resolve();
            })
            .catch(() => {
                return Promise.reject();
            });
    }
};


/**
 * Define mutations
 * @type {{}}
 */
const mutations = {
    /**
     * Check authentication status successfully
     * @param state
     * @param returnData
     * @constructor
     */
    CHECK_AUTHENTICATION_STATUS_SUCCESSFULLY(state, returnData) {
        state.isAuthenticated = true;
        state.userInfo = returnData.userInfo;
    },

    /**
     * Authenticate successfully
     * @param state
     * @param returnData
     * @constructor
     */
    AUTHENTICATE_SUCCESSFULLY(state, returnData) {
        state.isAuthenticated = true;
        state.userInfo = returnData.userInfo;
    },

    /**
     * Logout successfully
     * @param state
     * @constructor
     */
    LOGOUT_SUCCESSFULLY(state) {
        state.isAuthenticated = false;
        state.userInfo = {};
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
