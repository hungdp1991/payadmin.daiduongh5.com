/**
 * Export api
 */
export default {
    /**
     * Get gold list
     * @created 2020-02-24 LongTHK
     * @returns {*}
     */
    getPaginatedGoldList(currentPage) {
        return axios.post('/admin-api/gold/list', {
            currentPage: currentPage
        });
    },

    /**
     * Create new gold
     * @param goldItem
     * @returns {*}
     * @created 2020-02-24 LongTHK
     */
    createGold(goldItem) {
        return axios.post('/admin-api/gold/create', {
            gameId: goldItem.gameId,
            goldName: goldItem.goldName,
            goldDescription: goldItem.goldDescription,
            goldImage: goldItem.goldImage,
            goldAmount: goldItem.goldAmount,
            goldValue: goldItem.goldValue,
            productGoldId: goldItem.productGoldId,
            goldType: goldItem.goldType
        });
    },

    /**
     * Delete gold
     * @param goldId
     * @returns {*}
     * @created 2020-02-24 LongTHK
     */
    deleteGold(goldId, currentPage) {
        return axios.post('/admin-api/gold/delete', {
            goldId: goldId,
            currentPage: currentPage
        });
    },

    /**
     * Get gold info
     * @param goldId
     * @returns {*}
     * @created 2020-02-24 LongTHK
     */
    getGoldInfo(goldId) {
        return axios.post('/admin-api/gold/info', {
            goldId: goldId
        })
    },

    /**
     * Update gold
     * @param goldItem
     * @returns {*}
     * @created 2020-02-24 LongTHK
     */
    updateGold(goldItem) {
        return axios.post('/admin-api/gold/update', {
            goldId: goldItem.goldId,
            gameId: goldItem.gameId,
            goldName: goldItem.goldName,
            goldDescription: goldItem.goldDescription,
            goldImage: goldItem.goldImage,
            goldAmount: goldItem.goldAmount,
            goldValue: goldItem.goldValue,
            productGoldId: goldItem.productGoldId,
            goldType: goldItem.goldType
        });
    },

    /**
     * Search gold list
     * @param searchItem
     * @created 2020-04-14 LongTHK
     */
    searchGoldList(searchItem) {
        return axios.post('/admin-api/gold/search', {
            gameAgent: searchItem.gameAgent,
            goldType: searchItem.goldType
        });
    },

    /**
     * Get gold list by game agent
     * @created 2020-02-13 LongTHK
     */
    getGoldListByGameAgent(gameAgent) {
        return axios.post('/admin-api/gold/list-by-game', {
            gameAgent: gameAgent
        });
    },
}
