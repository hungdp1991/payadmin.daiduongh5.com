/**
 * Import api
 */
import rolesService from '../../services/RolesService';


/**
 * Define state
 * @type {{currentUserInfo: null, isAuthenticated: boolean}}
 */
const state = {
    permissionsList: [],
    allRolesList: {
        needReload: true,
        list: []
    },
    rolesList: [],
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
     * Get permissions list
     * @param commit
     * @returns {Promise<void>}
     * @created LongTHK 2020-02-12
     */
    async getPermissionsList({commit}) {
        await rolesService.getPermissionsList()
            .then((response) => {
                commit('GET_PERMISSIONS_LIST_SUCCESSFULLY', response.data);
            })
    },

    /**
     * Get roles list
     * @param commit
     * @created 2020-02-13 LongTHK
     */
    async getAllRolesList({commit}) {
        await rolesService.getAllRolesList()
            .then((response) => {
                commit('GET_ALL_ROLES_LIST_SUCCESSFULLY', response.data);
            })
    },

    /**
     * Get roles list
     * @param currentPage
     * @param commit
     */
    async getPaginatedRolesList({commit}, currentPage = 1) {
        await rolesService.getPaginatedRolesList(currentPage)
            .then((response) => {
                commit('GET_PAGINATED_ROLES_LIST_SUCCESSFULLY', response.data);
            })

    },

    /**
     * Create role
     * @param commit
     * @param data
     * @returns {Promise<void>}
     */
    async createRole({commit, dispatch}, data) {
        await rolesService.createRole(data.roleItem, data.currentPage)
            .then((response) => {
                commit('CREATE_ROLE_SUCCESSFULLY', response.data);
            })
    },

    /**
     * Delete role
     * @param commit
     * @param data
     * @returns {Promise<void>}
     */
    async deleteRole({commit, dispatch}, data) {
        await rolesService.deleteRole(data.roleId, data.currentPage)
            .then((response) => {
                commit('DELETE_ROLE_SUCCESSFULLY', response.data);
            })
    },

    /**
     * Update role
     * @param commit
     * @param data
     * @returns {Promise<void>}
     */
    async updateRole({commit, dispatch}, data) {
        await rolesService.updateRole(data.roleItem, data.currentPage)
            .then((response) => {
                commit('UPDATE_ROLE_SUCCESSFULLY', response.data);
            })
    },
};


/**
 * Define mutations
 * @type {{}}
 */
const mutations = {
    /**
     * Get permissions list successfully
     * @param state
     * @param data
     * @constructor
     * @created LongTHK 2020-02-12
     */
    GET_PERMISSIONS_LIST_SUCCESSFULLY(state, data) {
        state.permissionsList = data;
    },

    /**
     * Get roles list successfully
     * @param state
     * @param data
     * @constructor
     */
    GET_ALL_ROLES_LIST_SUCCESSFULLY(state, data) {
        state.allRolesList.needReload = false;
        state.allRolesList.list = data.data;
    },

    /**
     * Get roles list successfully
     * @param state
     * @param data
     * @constructor
     * @created LongTHK 2020-02-12
     */
    GET_PAGINATED_ROLES_LIST_SUCCESSFULLY(state, data) {
        state.rolesList = data.data;
        state.pagination = data.pagination;
    },

    /**
     * Create role successfully
     * @param state
     * @param data
     * @constructor
     */
    CREATE_ROLE_SUCCESSFULLY(state, data) {
        state.rolesList = data.data;
        state.pagination = data.pagination;

        state.allRolesList.needReload = true;
    },

    /**
     * Delete role successfully
     * @param state
     * @param data
     * @constructor
     */
    DELETE_ROLE_SUCCESSFULLY(state, data) {
        state.rolesList = data.data;
        state.pagination = data.pagination;

        state.allRolesList.needReload = true;
    },

    /**
     * Update role successfully
     * @param state
     * @param data
     * @constructor
     */
    UPDATE_ROLE_SUCCESSFULLY(state, data) {
        state.rolesList = data.data;
        state.pagination = data.pagination;

        state.allRolesList.needReload = true;
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
