/**
 * Export api
 */
export default {
    /**
     * Get servers list
     * @created 2020-02-13 LongTHK
     */
    getServersListByGameAgent(gameAgent) {
        return axios.post('/admin-api/servers/list-by-game', {
            gameAgent: gameAgent
        });
    },

    /**
     * Get servers list
     * @created 2020-02-13 LongTHK
     */
    getPaginatedServersList(currentPage) {
        return axios.post('/admin-api/servers/list', {
            currentPage: currentPage
        });
    },

    /**
     * Delete server
     * @param id
     * @param currentPage
     * @returns {*}
     * @created 2020-02-13 LongTHK
     */
    deleteServer(id, currentPage) {
        return axios.post('/admin-api/servers/delete', {
            id: id,
            currentPage: currentPage
        })
    },

    /**
     * Create server
     * @param serverItem
     * @param currentPage
     * @returns {*}
     * @created 2020-02-20 LongTHK
     */
    createServer(serverItem, currentPage) {
        return axios.post('/admin-api/servers/create', {
            gameId: serverItem.gameId,
            serverId: serverItem.serverId,
            serverName: serverItem.serverName,
            serverSlug: serverItem.serverSlug,
            serverStatus: serverItem.serverStatus,
            paymentStatus: serverItem.paymentStatus,
            keyWebCharge: serverItem.keyWebCharge,
            keyIAPCharge: serverItem.keyIAPCharge,
            currentPage: currentPage
        })
    },

    /**
     * Get server info
     * @param id
     * @created 2020-02-20 LongTHK
     */
    getServerInfo(id) {
        return axios.post('/admin-api/servers/info', {
            id: id
        })
    },

    /**
     * Update server
     * @param serverItem
     * @param currentPage
     * @returns {*}
     * @created 2020-02-20 LongTHK
     */
    updateServer(serverItem, currentPage) {
        return axios.post('/admin-api/servers/update', {
            id: serverItem.id,
            gameId: serverItem.gameId,
            serverId: serverItem.serverId,
            serverName: serverItem.serverName,
            serverSlug: serverItem.serverSlug,
            serverStatus: serverItem.serverStatus,
            paymentStatus: serverItem.paymentStatus,
            keyWebCharge: serverItem.keyWebCharge,
            keyIAPCharge: serverItem.keyIAPCharge,
            currentPage: currentPage
        })
    },

    /**
     * Update server status
     * @param id
     * @param serverStatus
     * @param currentPage
     * @returns {*}
     * @created 2020-02-21 LongTHK
     */
    updateServerStatus(id, serverStatus, currentPage) {
        return axios.post('/admin-api/servers/update/status', {
            id: id,
            serverStatus: serverStatus,
            currentPage: currentPage
        })
    },

    /**
     * Update server status
     * @param id
     * @param paymentStatus
     * @param currentPage
     * @returns {*}
     * @created 2020-02-21 LongTHK
     */
    updateServerPaymentStatus(id, paymentStatus, currentPage) {
        return axios.post('/admin-api/servers/update/payment', {
            id: id,
            paymentStatus: paymentStatus,
            currentPage: currentPage
        })
    },

    /**
     * Search servers list
     * @param searchItem
     * @created 2020-02-21 LongTHK
     */
    searchServerList(searchItem) {
        return axios.post('/admin-api/servers/search', {
            gameId: searchItem.gameId,
            serverStatus: searchItem.serverStatus,
            paymentStatus: searchItem.paymentStatus
        });
    }
}
