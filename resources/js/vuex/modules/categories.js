/**
 * Import api
 */
import categoriesService from '../../services/CategoriesService';
/**
 * Import module
 */
import {buildCategoriesList} from '../../helpers/nestable';
import userService from "../../services/UserService";


/**
 * Define state
 * @type {{currentUserInfo: null, isAuthenticated: boolean}}
 */
const state = {
    parentCategoriesList: {
        needReload: true,
        list: []
    },
    categoriesList: {
        needReload: true,
        list: []
    }
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
     * Get parent categories list
     * @param commit
     * @created 2020-02-14 LongTHK
     */
    async getParentCategoriesList({commit}) {
        await categoriesService.getParentCategoriesList()
            .then((response) => {
                commit('GET_PARENT_CATEGORIES_LIST_SUCCESSFULLY', response.data);
            })
    },

    /**
     * Get categories list
     * @param commit
     * @created 2020-02-14 LongTHK
     */
    async getCategoriesList({commit}) {
        await categoriesService.getCategoriesList()
            .then((response) => {
                // commit categories list
                commit('GET_CATEGORIES_LIST_SUCCESSFULLY', buildCategoriesList(response.data.data));
            })
    },

    /**
     * Create new category
     * @param commit
     * @param data
     * @returns {Promise<void>}
     * @created 2020-02-14
     */
    async createCategory({commit}, data) {
        await categoriesService.createCategory(data.categoryItem)
            .then((response) => {
                commit('CREATE_CATEGORY_SUCCESSFULLY', response.data);
            })
    },

    /**
     * Delete category
     * @param commit
     * @param data
     * @returns {Promise<void>}
     * @created 2020-02-14 LongTHK
     */
    async deleteCategory({commit}, data) {
        await categoriesService.deleteCategory(data.categoryId)
            .then((response) => {
                commit('DELETE_CATEGORY_SUCCESSFULLY', response.data);
            })
    },

    /**
     * Update category
     * @param commit
     * @param data
     * @returns {Promise<void>}
     * @created 2020-02-14
     */
    async updateCategory({commit}, data) {
        await categoriesService.updateCategory(data.categoryItem)
            .then((response) => {
                commit('UPDATE_CATEGORY_SUCCESSFULLY', response.data);
            })
    },
};


/**
 * Define mutations
 * @type {{}}
 */
const mutations = {

    /**
     * Get parent categories list successfully
     * @param state
     * @param data
     * @constructor
     */
    GET_PARENT_CATEGORIES_LIST_SUCCESSFULLY(state, data) {
        state.parentCategoriesList.needReload = false;
        state.parentCategoriesList.list = data.data;
    },

    /**
     * Get categories list successfully
     * @param state
     * @param data
     * @constructor
     */
    GET_CATEGORIES_LIST_SUCCESSFULLY(state, data) {
        state.categoriesList.needReload = false;
        state.categoriesList.list = data;
    },

    /**
     * Create new category successfully
     * @param state
     * @param data
     * @constructor
     * @created 2020-02-14
     */
    CREATE_CATEGORY_SUCCESSFULLY(state, data) {
        state.parentCategoriesList.needReload = false;
        state.parentCategoriesList.list = data.parentCategoriesList;

        state.categoriesList.needReload = false;
        state.categoriesList.list = buildCategoriesList(data.categoriesList);
    },

    /**
     * Delete new category successfully
     * @param state
     * @param data
     * @constructor
     * @created 2020-02-14 LongTHK
     */
    DELETE_CATEGORY_SUCCESSFULLY(state, data) {
        state.parentCategoriesList.needReload = false;
        state.parentCategoriesList.list = data.parentCategoriesList;

        state.categoriesList.needReload = false;
        state.categoriesList.list = buildCategoriesList(data.categoriesList);
    },

    /**
     * Update category successfully
     * @param state
     * @param data
     * @constructor
     * @created 2020-02-14
     */
    UPDATE_CATEGORY_SUCCESSFULLY(state, data) {
        state.parentCategoriesList.needReload = false;
        state.parentCategoriesList.list = data.parentCategoriesList;

        state.categoriesList.needReload = false;
        state.categoriesList.list = buildCategoriesList(data.categoriesList);
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
