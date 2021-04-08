/**
 * Export api
 */
export default {
    /**
     * Get payment types list
     * @returns {*}
     * @created 2020-02-18 LongTHK
     */
    getPaymentTypesList() {
        return axios.post('/admin-api/configurations/get-payment-types-list');
    },

    /**
     * Get server status list
     * @returns {*}
     * @created 2020-02-20 LongTHK
     */
    getServerStatusList() {
        return axios.post('/admin-api/configurations/get-server-status-list');
    },

    /**
     * Get transaction status list
     * @returns {*}
     * @created 2020-02-26 LongTHK
     */
    getTransactionStatusList() {
        return axios.post('/admin-api/configurations/get-transaction-status-list');
    },

    /**
     * Get gold types list
     * @returns {*}
     * @created 2020-02-24 LongTHK
     */
    getGoldTypesList() {
        return axios.post('/admin-api/configurations/get-gold-types-list');
    },

    /**
     * Get OS list
     * @returns {*}
     * @created 2020-03-02 LongTHK
     */
    getOSList() {
        return axios.post('/admin-api/configurations/get-os-list');
    },
}
