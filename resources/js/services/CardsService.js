/**
 * Export api
 */
export default {
    /**
     * Get all cards list
     * @returns {*}
     * @created 2020-02-20 LongTHK
     */
    getAllCardsList() {
        return axios.post('/admin-api/cards/all');
    },

    /**
     * Get cards list
     * @created 2020-02-24 LongTHK
     * @returns {*}
     */
    getPaginatedCardsList() {
        return axios.post('/admin-api/cards/list');
    },

    /**
     * Create new card
     * @param cardItem
     * @returns {*}
     * @created 2020-02-24 LongTHK
     */
    createCard(cardItem) {
        return axios.post('/admin-api/cards/create', {
            cardType: cardItem.cardType,
            cardName: cardItem.cardName
        });
    },

    /**
     * Delete card
     * @param cardId
     * @returns {*}
     * @created 2020-02-24 LongTHK
     */
    deleteCard(cardId) {
        return axios.post('/admin-api/cards/delete', {
            cardId: cardId
        });
    },

    /**
     * Get card info
     * @param cardId
     * @returns {*}
     * @created 2020-02-24 LongTHK
     */
    getCardInfo(cardId) {
        return axios.post('/admin-api/cards/info', {
            cardId: cardId
        })
    },

    /**
     * Update card
     * @param cardItem
     * @returns {*}
     * @created 2020-02-24 LongTHK
     */
    updateCard(cardItem) {
        return axios.post('/admin-api/cards/update', {
            cardId: cardItem.cardId,
            cardType: cardItem.cardType,
            cardName: cardItem.cardName
        });
    },

    /**
     * Update card status
     * @param data
     * @returns {*}
     * @created 2020-05-08 LongTHK
     */
    updateCardStatus(data) {
        return axios.post('/admin-api/cards/update/status', {
            cardId: data.cardId,
            cardStatus: data.cardStatus,
            currentPage: data.currentPage
        });
    }
}
