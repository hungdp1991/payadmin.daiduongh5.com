/**
 * Export api
 */
export default {
    /**
     * Check authentication status
     * @returns {*}
     */
    checkAuthenticationStatus() {
        return axios.get('/admin-api/check-authentication-status');
    },

    /**
     * Get CSRF cookie
     * @returns {*}
     */
    getCSRF() {
        return axios.get('sanctum/csrf-token')
    },

    /**
     * Authenticate
     * @param loginInfo
     * @returns {*}
     */
    authenticate(loginInfo) {
        return axios.post('/authenticate', {
            username: loginInfo.username,
            password: md5(loginInfo.password)
        });
    },

    /**
     * Change password
     */
    changePassword(passwordItem) {
        return axios.post('/admin-api/change-password', {
            oldPassword: md5(passwordItem.oldPassword),
            newPassword: passwordItem.newPassword,
            confirmedPassword: passwordItem.confirmedPassword
        });
    },

    /**
     * Logout
     * @returns {*}
     */
    logout() {
        return axios.post('/logout');
    },

    /**
     * Get users list
     * @created 2020-02-13 LongTHK
     */
    getPaginatedUsersList(currentPage) {
        return axios.post('/admin-api/users/list', {
            currentPage: currentPage
        });
    },

    /**
     * Create user
     * @param userItem
     * @param currentPage
     * @returns {*}
     * @created 2020-02-13 LongTHK
     */
    createUser(userItem, currentPage) {
        return axios.post('/admin-api/users/create', {
            fullName: userItem.fullName,
            username: userItem.username,
            password: userItem.password,
            email: userItem.email,
            roleId: userItem.roleId,
            gamesList: userItem.gamesList,
            currentPage: currentPage
        })
    },

    /**
     * Delete user
     * @param userId
     * @param currentPage
     * @returns {*}
     * @created 2020-02-13 LongTHK
     */
    deleteUser(userId, currentPage) {
        return axios.post('/admin-api/users/delete', {
            userId: userId,
            currentPage: currentPage
        })
    },

    /**
     * Get user info
     * @param userId
     * @created 2020-02-13 LongTHK
     */
    getUserInfo(userId) {
        return axios.post('/admin-api/users/info', {
            userId: userId
        })
    },

    /**
     * Update user
     * @param userItem
     * @param currentPage
     * @returns {*}
     */
    updateUser(userItem, currentPage) {
        return axios.post('/admin-api/users/update', {
            userId: userItem.userId,
            fullName: userItem.fullName,
            email: userItem.email,
            roleId: userItem.roleId,
            gamesList: userItem.gamesList,
            currentPage: currentPage
        })
    },

    /**
     * Reset user password
     * @param userId
     * @returns {*}
     */
    resetPassword(userId) {
        return axios.post('/admin-api/users/reset-password', {
            userId: userId
        })
    }
}
