/**
 * Export api
 */
export default {
    /**
     * Get permissions list
     * @created LongTHK 2020-02-12
     */
    getPermissionsList() {
        return axios.post('/admin-api/roles/permissions-list')
    },

    /**
     * Get all roles list
     * @created 2020-02-13 LongTHK
     * @returns {*}
     */
    getAllRolesList() {
        return axios.post('/admin-api/roles/all');
    },

    /**
     * Get roles list
     * @created LongTHK 2020-02-12
     */
    getPaginatedRolesList(currentPage) {
        return axios.post('/admin-api/roles/list', {
            currentPage: currentPage
        });
    },

    /**
     * Create role
     * @param roleItem
     * @param currentPage
     * @returns {*}
     * @created LongTHK 2020-02-12
     */
    createRole(roleItem, currentPage) {
        return axios.post('/admin-api/roles/create', {
            roleName: roleItem.roleName,
            permissionsList: Array.from(roleItem.permissionsList),
            currentPage: currentPage
        })
    },

    /**
     * Delete role
     * @param roleId
     * @param currentPage
     * @returns {*}
     * @created LongTHK 2020-02-12
     */
    deleteRole(roleId, currentPage) {
        return axios.post('/admin-api/roles/delete', {
            roleId: roleId,
            currentPage: currentPage
        })
    },

    /**
     * Get role info
     * @param roleId
     * @created LongTHK 2020-02-12
     */
    getRoleInfo(roleId) {
        return axios.post('/admin-api/roles/info', {
            roleId: roleId
        })
    },

    /**
     * Update role
     * @param roleItem
     * @param currentPage
     * @created LongTHK 2020-02-12
     * @returns {*}
     */
    updateRole(roleItem, currentPage) {
        return axios.post('/admin-api/roles/update', {
            roleId: roleItem.roleId,
            roleName: roleItem.roleName,
            permissionsList: Array.from(roleItem.permissionsList),
            currentPage: currentPage
        })
    },
}
