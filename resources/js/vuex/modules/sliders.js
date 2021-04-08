/**
 * Import api
 */
import slidersService from '../../services/SlidersService';

/**
 * Define state
 * @type {{currentUserInfo: null, isAuthenticated: boolean}}
 */
const state = {
    slidersList: [],
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
     * Get all sliders list
     * @param commit
     * @returns {Promise<void>}
     * @created 2020-02-21 LongTHK
     */
    async getAllSlidersList({commit}) {
        await slidersService.getAllSlidersList()
            .then((response) => {
                commit('GET_ALL_SLIDERS_LIST_SUCCESSFULLY', response.data);
            })
    },

    /**
     * Get sliders list
     * @param currentPage
     * @param commit
     * @created 2020-02-21 LongTHK
     */
    async getPaginatedSlidersList({commit}, currentPage = 1) {
        await slidersService.getPaginatedSlidersList(currentPage)
            .then((response) => {
                commit('GET_PAGINATED_SLIDERS_LIST_SUCCESSFULLY', response.data);
            })

    },

    /**
     * Create slider
     * @param commit
     * @param data
     * @returns {Promise<void>}
     * @created 2020-02-21 LongTHK
     */
    async createSlider({commit}, data) {
        await slidersService.createSlider(data.sliderItem, data.currentPage)
            .then((response) => {
                commit('CREATE_SLIDER_SUCCESSFULLY', response.data);
            })
    },

    /**
     * Delete slider
     * @param commit
     * @param data
     * @returns {Promise<void>}
     * @created 2020-02-21 LongTHK
     */
    async deleteSlider({commit}, data) {
        await slidersService.deleteSlider(data.sliderId, data.currentPage)
            .then((response) => {
                commit('DELETE_SLIDER_SUCCESSFULLY', response.data);
            })
    },

    /**
     * Delete image item
     * @param commit
     * @param data
     * @returns {Promise<void>}
     * @created 2020-02-21 LongTHK
     */
    async deleteSliderImageItem({commit}, data) {
        await slidersService.deleteSliderImageItem(data.sliderId, data.imageIndex, data.currentPage)
            .then((response) => {
                commit('DELETE_IMAGE_SUCCESSFULLY', response.data);
            })
    },

    /**
     * Change image redirect link
     * @param commit
     * @param data
     * @returns {Promise<void>}
     * @created 2020-02-21 LongTHK
     */
    async changeImageRedirectLink({commit}, data) {
        await slidersService.changeImageRedirectLink(data.sliderId, data.imageIndex, data.redirectLink, data.currentPage)
            .then((response) => {
                commit('CHANGE_IMAGE_REDIRECT_LINK_SUCCESSFULLY', response.data);
            })
    },

    /**
     * Change image title
     * @param commit
     * @param data
     * @returns {Promise<void>}
     * @created 2020-02-21 LongTHK
     */
    async changeImageTitle({commit}, data) {
        await slidersService.changeImageTitle(data.sliderId, data.imageIndex, data.title, data.currentPage)
            .then((response) => {
                commit('CHANGE_IMAGE_TITLE_SUCCESSFULLY', response.data);
            })
    },

    /**
     * Change image description
     * @param commit
     * @param data
     * @returns {Promise<void>}
     * @created 2020-02-21 LongTHK
     */
    async changeImageDescription({commit}, data) {
        await slidersService.changeImageDescription(data.sliderId, data.imageIndex, data.description, data.currentPage)
            .then((response) => {
                commit('CHANGE_IMAGE_DESCRIPTION_SUCCESSFULLY', response.data);
            })
    },

    /**
     * Update slider
     * @param commit
     * @param data
     * @returns {Promise<void>}
     * @created 2020-02-21 LongTHK
     */
    async updateSlider({commit}, data) {
        await slidersService.updateSlider(data.sliderItem, data.currentPage)
            .then((response) => {
                commit('UPDATE_SLIDER_SUCCESSFULLY', response.data);
            })
    },

    /**
     * Set default banner
     * @param commit
     * @param data
     * @returns {Promise<void>}
     * @created 2020-03-02 LongTHK
     */
    async setDefaultBanner({commit}, data) {
        await slidersService.setDefaultBanner(data.sliderId, data.currentPage)
            .then((response) => {
                commit('SET_DEFAULT_BANNER_SUCCESSFULLY', response.data);
            })
    }
};


/**
 * Define mutations
 * @type {{}}
 */
const mutations = {
    /**
     * Get sliders list successfully
     * @param state
     * @param data
     * @constructor
     * @created 2020-02-21 LongTHK
     */
    GET_PAGINATED_SLIDERS_LIST_SUCCESSFULLY(state, data) {
        state.slidersList = data.data;
        state.pagination = data.pagination;
    },

    /**
     * Create slider successfully
     * @param state
     * @param data
     * @constructor
     * @created 2020-02-21 LongTHK
     */
    CREATE_SLIDER_SUCCESSFULLY(state, data) {
        state.slidersList = data.data;
        state.pagination = data.pagination;
    },

    /**
     * Delete customer successfully
     * @param state
     * @param data
     * @constructor
     * @created 2020-02-21 LongTHK
     */
    DELETE_SLIDER_SUCCESSFULLY(state, data) {
        state.slidersList = data.data;
        state.pagination = data.pagination;
    },

    /**
     * Delete image item successfully
     * @param state
     * @param data
     * @constructor
     * @created 2020-02-21 LongTHK
     */
    DELETE_IMAGE_SUCCESSFULLY(state, data) {
        state.slidersList = data.data;
    },

    /**
     * Change image item redirect link successfully
     * @param state
     * @param data
     * @constructor
     * @created 2020-02-21 LongTHK
     */
    CHANGE_IMAGE_REDIRECT_LINK_SUCCESSFULLY(state, data) {
        state.slidersList = data.data;
    },

    /**
     * Change title successfully
     * @param state
     * @param data
     * @constructor
     * @created 2020-02-21 LongTHK
     */
    CHANGE_IMAGE_TITLE_SUCCESSFULLY(state, data) {
        state.slidersList = data.data;
    },

    /**
     * Change description successfully
     * @param state
     * @param data
     * @constructor
     * @created 2020-02-21 LongTHK
     */
    CHANGE_IMAGE_DESCRIPTION_SUCCESSFULLY(state, data) {
        state.slidersList = data.data;
    },

    /**
     * Update slider successfully
     * @param state
     * @param data
     * @constructor
     * @created 2020-02-21 LongTHK
     */
    UPDATE_SLIDER_SUCCESSFULLY(state, data) {
        state.slidersList = data.data;
        state.pagination = data.pagination;
    },

    /**
     * Set default slider successfully
     * @param state
     * @param data
     * @constructor
     * @created 2020-03-02 LongTHK
     */
    SET_DEFAULT_BANNER_SUCCESSFULLY(state, data) {
        state.slidersList = data.data;
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
