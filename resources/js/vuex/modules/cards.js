/**
 * Import api
 */
import cardsService from '../../services/CardsService';


/**
 * Define state
 * @type {{currentUserInfo: null, isAuthenticated: boolean}}
 */
const state = {
    allCardsList: {
        needReload: true,
        list: []
    },
    cardsList: [],
    pagination: {}
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
     * Get all cards list
     * @param commit
     * @created 2020-02-26 LongTHK
     */
    async getAllCardsList({commit}) {
        await cardsService.getAllCardsList()
            .then((response) => {
                commit('GET_ALL_CARDS_LIST_SUCCESSFULLY', response.data);
            })
    },

    /**
     * Get cards list
     * @param commit
     * @created 2020-02-24 LongTHK
     */
    async getPaginatedCardsList({commit}) {
        await cardsService.getPaginatedCardsList()
            .then((response) => {
                // commit categories list
                commit('GET_PAGINATED_CARDS_LIST_SUCCESSFULLY', response.data);
            })
    },

    /**
     * Create new card
     * @param commit
     * @param data
     * @returns {Promise<void>}
     * @created 2020-02-24
     */
    async createCard({commit}, data) {
        await cardsService.createCard(data.cardItem)
            .then((response) => {
                commit('CREATE_CARD_SUCCESSFULLY', response.data);
            })
    },

    /**
     * Delete card
     * @param commit
     * @param data
     * @returns {Promise<void>}
     * @created 2020-02-24 LongTHK
     */
    async deleteCard({commit}, data) {
        await cardsService.deleteCard(data.cardId)
            .then((response) => {
                commit('DELETE_CARD_SUCCESSFULLY', response.data);
            })
    },

    /**
     * Update card
     * @param commit
     * @param data
     * @returns {Promise<void>}
     * @created 2020-02-24
     */
    async updateCard({commit}, data) {
        await cardsService.updateCard(data.cardItem)
            .then((response) => {
                commit('UPDATE_CARD_SUCCESSFULLY', response.data);
            })
    },

    async updateCardStatus({commit}, data) {
        await cardsService.updateCardStatus(data)
            .then((response) => {
                commit('UPDATE_CARD_SUCCESSFULLY', response.data);
            })
    }
};


/**
 * Define mutations
 * @type {{}}
 */
const mutations = {
    /**
     * Get all cards list successfully
     * @param state
     * @param data
     * @constructor
     * @created 2020-02-26 LongTHK
     */
    GET_ALL_CARDS_LIST_SUCCESSFULLY(state, data) {
        state.allCardsList.needReload = false;
        state.allCardsList.list = data.data;
    },

    /**
     * Get cards successfully
     * @param state
     * @param data
     * @constructor
     */
    GET_PAGINATED_CARDS_LIST_SUCCESSFULLY(state, data) {
        state.cardsList = data.data;
        state.pagination = data.pagination;
    },

    /**
     * Create new card successfully
     * @param state
     * @param data
     * @constructor
     * @created 2020-02-24
     */
    CREATE_CARD_SUCCESSFULLY(state, data) {
        state.cardsList = data.data;
        state.pagination = data.pagination;

        state.allCardsList.needReload = true;

    },

    /**
     * Delete card successfully
     * @param state
     * @param data
     * @constructor
     * @created 2020-02-24 LongTHK
     */
    DELETE_CARD_SUCCESSFULLY(state, data) {
        state.cardsList = data.data;
        state.pagination = data.pagination;

        state.allCardsList.needReload = true;
    },

    /**
     * Update card successfully
     * @created 2020-02-24
     */
    UPDATE_CARD_SUCCESSFULLY(state, data) {
        state.cardsList = data.data;
        state.pagination = data.pagination;

        state.allCardsList.needReload = true;
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
