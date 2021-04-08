/**
 * Export api
 */
export default {

    /**
     * Get statistics list
     * @returns {*}
     * @created 2020-02-25 LongTHK
     */
    getStatisticList(statisticsItem) {
        return axios.post('/admin-api/statistics/daily', {
            timeRange: statisticsItem.timeRange,
            gameId: statisticsItem.gameId
        });
    },

    /**
     * Get pay history list
     * @returns {*}
     * @created 2020-02-26 LongTHK
     */
    getPaginatedCardHistoryList(cardHistoryItem, pageNumber) {
        return axios.post('/admin-api/statistics/card-history', {
            timeRange: cardHistoryItem.timeRange,
            transactionStatus: cardHistoryItem.transactionStatus,
            gameId: cardHistoryItem.gameId,
            username: cardHistoryItem.username,
            transactionId: cardHistoryItem.transactionId,
            cardType: cardHistoryItem.cardType,
            cardSerial: cardHistoryItem.cardSerial,
            cardCode: cardHistoryItem.cardCode,
            currentPage: pageNumber
        });
    },

    /**
     * Export excel file
     * @returns {*}
     * @created 2020-02-27 LongTHK
     */
    exportCardHistoryExcel(cardHistoryItem) {
        return axios.post('/admin-api/statistics/card-history/export', {
            timeRange: cardHistoryItem.timeRange,
            transactionStatus: cardHistoryItem.transactionStatus,
            gameId: cardHistoryItem.gameId,
            username: cardHistoryItem.username,
            transactionId: cardHistoryItem.transactionId,
            cardType: cardHistoryItem.cardType,
            cardSerial: cardHistoryItem.cardSerial,
            cardCode: cardHistoryItem.cardCode,
        }, {
            responseType: 'blob',
        });
    },

    /**
     * Get pay game history list
     * @returns {*}
     * @created 2020-02-26 LongTHK
     */
    getPaginatedPayHistoryList(payHistoryItem, pageNumber) {
        return axios.post('/admin-api/statistics/pay-history', {
            timeRange: payHistoryItem.timeRange,
            transactionStatus: payHistoryItem.transactionStatus,
            gameId: payHistoryItem.gameId,
            serverId: payHistoryItem.serverId,
            username: payHistoryItem.username,
            roleName: payHistoryItem.roleName,
            transactionId: payHistoryItem.transactionId,
            cardType: payHistoryItem.cardType,
            cardSerial: payHistoryItem.cardSerial,
            cardCode: payHistoryItem.cardCode,
            currentPage: pageNumber
        });
    },

    /**
     * Export pay history list to excel file
     * @param payHistoryItem
     * @returns {*}
     * @created 2020-02-27 LongTHk
     */
    exportPayHistoryExcel(payHistoryItem) {
        return axios.post('/admin-api/statistics/pay-history/export', {
            timeRange: payHistoryItem.timeRange,
            transactionStatus: payHistoryItem.transactionStatus,
            gameId: payHistoryItem.gameId,
            serverId: payHistoryItem.serverId,
            username: payHistoryItem.username,
            roleName: payHistoryItem.roleName,
            transactionId: payHistoryItem.transactionId,
            cardType: payHistoryItem.cardType,
            cardSerial: payHistoryItem.cardSerial,
            cardCode: payHistoryItem.cardCode
        }, {
            responseType: 'blob',
        });
    },

    /**
     * Get IAP history list
     * @returns {*}
     * @created 2020-02-26 LongTHK
     */
    getPaginatedIAPHistoryList(iapHistoryItem, pageNumber) {
        return axios.post('/admin-api/statistics/iap-history', {
            timeRange: iapHistoryItem.timeRange,
            gameId: iapHistoryItem.gameId,
            serverId: iapHistoryItem.serverId,
            transactionId: iapHistoryItem.transactionId,
            transactionStatus: iapHistoryItem.transactionStatus,
            username: iapHistoryItem.username,
            os: iapHistoryItem.os,
            amountFrom: iapHistoryItem.amountFrom,
            amountTo: iapHistoryItem.amountTo,
            currentPage: pageNumber
        });
    },

    /**
     * Export IAP history list to excel file
     * @param iapHistoryItem
     * @returns {*}
     * @created 2020-03-02 LongTHk
     */
    exportIAPHistoryExcel(iapHistoryItem) {
        return axios.post('/admin-api/statistics/iap-history/export', {
            timeRange: iapHistoryItem.timeRange,
            gameId: iapHistoryItem.gameId,
            serverId: iapHistoryItem.serverId,
            transactionId: iapHistoryItem.transactionId,
            transactionStatus: iapHistoryItem.transactionStatus,
            username: iapHistoryItem.username,
            os: iapHistoryItem.os,
            amountFrom: iapHistoryItem.amountFrom,
            amountTo: iapHistoryItem.amountTo
        }, {
            responseType: 'blob',
        });
    },
}
