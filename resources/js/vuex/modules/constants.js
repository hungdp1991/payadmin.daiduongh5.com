/**
 * Import api
 */
import constantsService from "../../services/ConstantsService";

/**
 * Define state
 * @type {{currentUserInfo: null, isAuthenticated: boolean}}
 */
const state = {
    AccessTokenKeyName: 'AccessToken',
    PaymentTypesList: [],
    ServerStatusList: [],
    TransactionStatusList: [],
    GoldTypesList: [],
    OSList: []
};

/**
 * Define actions
 * @type {{}}
 */
const actions = {
    /**
     * Get payment types list
     * @param commit
     * @created 2020-02-18 LongTHK
     */
    async getPaymentTypesList({commit}) {
        await constantsService.getPaymentTypesList()
            .then((response) => {
                commit('GET_PAYMENT_TYPES_LIST_SUCCESSFULLY', response.data);
            })
    },

    /**
     * Get server status list
     * @param commit
     * @created 2020-02-20 LongTHK
     */
    async getServerStatusList({commit}) {
        await constantsService.getServerStatusList()
            .then((response) => {
                commit('GET_SERVER_STATUS_LIST_SUCCESSFULLY', response.data);
            })
    },

    /**
     * Get transaction status list
     * @param commit
     * @created 2020-02-26 LongTHK
     */
    async getTransactionStatusList({commit}) {
        await constantsService.getTransactionStatusList()
            .then((response) => {
                commit('GET_TRANSACTION_STATUS_LIST_SUCCESSFULLY', response.data);
            })
    },

    /**
     * Get gold types list
     * @param commit
     * @created 2020-02-20 LongTHK
     */
    async getGoldTypesList({commit}) {
        await constantsService.getGoldTypesList()
            .then((response) => {
                commit('GET_GOLD_TYPES_LIST_SUCCESSFULLY', response.data);
            })
    },

    /**
     * Get OS list
     * @param commit
     * @created 2020-03-02 LongTHK
     */
    async getOSList({commit}) {
        await constantsService.getOSList()
            .then((response) => {
                commit('GET_OS_LIST_SUCCESSFULLY', response.data);
            })
    },
};

/**
 * Define mutations
 * @type {{}}
 */
const mutations = {
    /**
     * Get payment types list successfully
     * @param state
     * @param data
     * @constructor
     * @created 2020-02-18 LongTHK
     */
    GET_PAYMENT_TYPES_LIST_SUCCESSFULLY(state, data) {
        state.PaymentTypesList = data;
    },

    /**
     * Get server status list successfully
     * @param state
     * @param data
     * @constructor
     * @created 2020-02-20 LongTHK
     */
    GET_SERVER_STATUS_LIST_SUCCESSFULLY(state, data) {
        state.ServerStatusList = data;
    },

    /**
     * Get gold types list successfully
     * @param state
     * @param data
     * @constructor
     * @created 2020-02-24 LongTHK
     */
    GET_GOLD_TYPES_LIST_SUCCESSFULLY(state, data) {
        state.GoldTypesList = data;
    },

    /**
     * Get transaction status list successfully
     * @param state
     * @param data
     * @constructor
     * @created 2020-02-26 LongTHK
     */
    GET_TRANSACTION_STATUS_LIST_SUCCESSFULLY(state, data) {
        state.TransactionStatusList = data;
    },

    /**
     * Get OS list successfully
     * @param state
     * @param data
     * @constructor
     * @created 2020-03-02 LongTHK
     */
    GET_OS_LIST_SUCCESSFULLY(state, data) {
        state.OSList = data;
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
