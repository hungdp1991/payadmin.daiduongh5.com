/**
 * Export api
 */
export default {
    /**
     * Get all games list
     * @returns {*}
     * @created 2020-02-20 LongTHK
     */
    getAllGamesList() {
        return axios.post('/admin-api/games/all');
    },

    /**
     * Get games list
     * @paramt currentPage
     * @returns {*}
     * @created 2020-02-19 LongTHK
     */
    getPaginatedGamesList(currentPage) {
        return axios.post('/admin-api/games/list', {
            currentPage: currentPage
        });
    },

    /**
     * Create new game
     * @param gameItem
     * @returns {*}
     * @created 2020-02-19 LongTHK
     */
    createGame(gameItem) {
        return axios.post('/admin-api/games/create', {
            gameAgent: gameItem.gameAgent,
            gameName: gameItem.gameName,
            gameSlug: gameItem.gameSlug,
            gameImage: gameItem.gameImage,
            paymentType: gameItem.paymentType,
            acceptedChargeType: gameItem.acceptedChargeType,
            gameRedirect: gameItem.gameRedirect,
            limitIPList: gameItem.limitIPList
        })
    },

    /**
     * Delete game
     * @param gameId
     * @param currentPage
     * @returns {*}
     * @created 2020-02-19 LongTHK
     */
    deleteGame(gameId, currentPage) {
        return axios.post('/admin-api/games/delete', {
            gameId: gameId,
            currentPage: currentPage
        })
    },

    /**
     * Get game info
     * @param gameId
     * @created 2020-02-19 LongTHK
     */
    getGameInfo(gameId) {
        return axios.post('/admin-api/games/info', {
            gameId: gameId
        })
    },

    /**
     * Update game
     * @param gameItem
     * @returns {*}
     * @created 2020-02-19 LongTHK
     */
    updateGame(gameItem) {
        return axios.post('/admin-api/games/update', {
            gameId: gameItem.gameId,
            gameAgent: gameItem.gameAgent,
            gameName: gameItem.gameName,
            gameSlug: gameItem.gameSlug,
            gameImage: gameItem.gameImage,
            paymentType: gameItem.paymentType,
            acceptedChargeType: gameItem.acceptedChargeType,
            gameRedirect: gameItem.gameRedirect,
            limitIPList: gameItem.limitIPList
        })
    },

    /**
     * Arrange games list
     * @param gamesOrderList
     * @returns {*}
     * @created 2020-04-13 LongTHK
     */
    arrangeGamesList(gamesOrderList) {
        return axios.post('/admin-api/games/arrange', {
            gamesOrderList: gamesOrderList
        })
    },

    /**
     * Change game status
     * @param data
     * @returns {*}
     */
    changeGameStatus(data) {
        return axios.post('/admin-api/games/change-status', {
            gameId: data.gameId,
            gameStatus: data.gameStatus
        })
    }
}
