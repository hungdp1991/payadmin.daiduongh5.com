/**
 * Import api
 */
import serversService from "../../services/ServersService";

/**
 * Define state
 * @type {{currentUserInfo: null, isAuthenticated: boolean}}
 */
const state = {
    allServersList: {},
    serversList: []
};

/**
 * Define actions
 * @type {{}}
 */
const actions = {
    /**
     * Get all servers list belong to game Id
     * @param commit
     * @created 2020-02-20 LongTHK
     */
    async getServersListByGameAgent({commit}, data) {
        await serversService.getServersListByGameAgent(data.gameAgent)
            .then((response) => {
                commit('GET_ALL_SERVERS_LIST_BY_GAME_AGENT_SUCCESSFULLY', response.data);
            })
    },

    /**
     * Get servers list
     * @param currentPage
     * @param commit
     * @created 2020-02-20 LongTHK
     */
    async getPaginatedServersList({commit}, currentPage = 1) {
        await serversService.getPaginatedServersList(currentPage)
            .then((response) => {
                commit('GET_PAGINATED_SERVERS_LIST_SUCCESSFULLY', response.data);
            })
    },

    /**
     * Delete server
     * @param commit
     * @param data
     * @returns {Promise<void>}
     * @created 2020-02-20 LongTHK
     */
    async deleteServer({commit}, data) {
        await serversService.deleteServer(data.id, data.currentPage)
            .then((response) => {
                commit('DELETE_SERVER_SUCCESSFULLY', response.data);
            })
    },

    /**
     * Create server
     * @param commit
     * @param data
     * @returns {Promise<void>}
     * @created 2020-02-20 LongTHK
     */
    async createServer({commit}, data) {
        await serversService.createServer(data.serverItem, data.currentPage)
            .then((response) => {
                commit('CREATE_SERVER_SUCCESSFULLY', response.data);
            })
    },

    /**
     * Update server
     * @param commit
     * @param data
     * @returns {Promise<void>}
     * @created 2020-02-20 LongTHK
     */
    async updateServer({commit}, data) {
        await serversService.updateServer(data.serverItem, data.currentPage)
            .then((response) => {
                commit('UPDATE_SERVER_SUCCESSFULLY', response.data);
            })
    },

    /**
     * Update server payment status
     * @param commit
     * @param data
     * @returns {Promise<void>}
     * @created 2020-02-21 LongTHK
     */
    async updateServerPaymentStatus({commit}, data) {
        await serversService.updateServerPaymentStatus(data.id, data.paymentStatus, data.currentPage)
            .then((response) => {
                commit('UPDATE_SERVER_PAYMENT_STATUS_SUCCESSFULLY', response.data);
            })
    },

    /**
     * Update payment status
     * @param commit
     * @param data
     * @returns {Promise<void>}
     * @created 2020-02-21 LongTHK
     */
    async updateServerStatus({commit}, data) {
        await serversService.updateServerStatus(data.id, data.serverStatus, data.currentPage)
            .then((response) => {
                commit('UPDATE_SERVER_STATUS_SUCCESSFULLY', response.data);
            })
    },
};

/**
 * Define mutations
 * @type {{}}
 */
const mutations = {
    /**
     * Get servers list successfully
     * @param state
     * @param data
     * @constructor
     * @created 2020-02-20 LongTHK
     */
    GET_PAGINATED_SERVERS_LIST_SUCCESSFULLY(state, data) {
        state.serversList = data.data;
        state.pagination = data.pagination;
    },

    /**
     * Delete server successfully
     * @param state
     * @param data
     * @constructor
     * @created 2020-02-20 LongTHK
     */
    DELETE_SERVER_SUCCESSFULLY(state, data) {
        state.serversList = data.data;
        state.pagination = data.pagination;

        state.allServersList = {};
    },

    /**
     * Create server successfully
     * @param state
     * @param data
     * @constructor
     * @created 2020-02-20 LongTHK
     */
    CREATE_SERVER_SUCCESSFULLY(state, data) {
        state.serversList = data.data;
        state.pagination = data.pagination;

        state.allServersList = {};
    },

    /**
     * Update server successfully
     * @param state
     * @param data
     * @constructor
     * @created 2020-02-20 LongTHK
     */
    UPDATE_SERVER_SUCCESSFULLY(state, data) {
        state.serversList = data.data;
        state.pagination = data.pagination;

        state.allServersList = {};
    },

    /**
     * Update server status successfully
     * @param state
     * @param data
     * @constructor
     * @created 2020-02-21 LongTHK
     */
    UPDATE_SERVER_STATUS_SUCCESSFULLY(state, data) {
        state.serversList = data.data;
        state.pagination = data.pagination;
    },

    /**
     * Update server payment status successfully
     * @param state
     * @param data
     * @constructor
     * @created 2020-02-21 LongTHK
     */
    UPDATE_SERVER_PAYMENT_STATUS_SUCCESSFULLY(state, data) {
        state.serversList = data.data;
        state.pagination = data.pagination;
    },

    /**
     * Get all servers belong to games successfully
     * @param state
     * @param data
     * @constructor
     * @created 2020-02-21 LongTHK
     */
    GET_ALL_SERVERS_LIST_BY_GAME_AGENT_SUCCESSFULLY(state, data) {
        // get game agent
        let gameAgent = data.gameAgent;
        if (gameAgent !== null) {
            state.allServersList[gameAgent] = data.data;
        }
    }
};

/**
 * Export module
 */
export default {
    state,
    actions,
    mutations
}
