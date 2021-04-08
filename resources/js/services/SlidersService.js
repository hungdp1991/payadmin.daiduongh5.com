/**
 * Export api
 */
export default {

    /**
     * Get configuration items list of slider
     * @created 2020-02-21 LongTHK
     */
    getSliderConfigurationsList() {
        return axios.post('/configurations/sliders');
    },

    /**
     * Save slider configurations
     * @param sliderConfigurations
     * @created 2020-02-21 LongTHK
     */
    saveSliderConfigurations(sliderConfigurations) {
        return axios.post('/configurations/sliders/save', {
            sliderConfigurations: sliderConfigurations
        });
    },

    /**
     * Get sliders list
     * @paramt currentPage
     * @returns {*}
     * @created 2020-02-21 LongTHK
     */
    getPaginatedSlidersList(currentPage) {
        return axios.post('/admin-api/sliders/list', {
            currentPage: currentPage
        });
    },

    /**
     * Create new slider
     * @param sliderItem
     * @param currentPage
     * @returns {*}
     * @created 2020-02-21 LongTHK
     */
    createSlider(sliderItem, currentPage) {
        // generate slider items list
        let sliderItemsList = sliderItem.itemsList.map(function (item) {
            return _.omit(item, 'imageUrl');
        });

        return axios.post('/admin-api/sliders/create', {
            sliderName: sliderItem.sliderName,
            sliderDescription: sliderItem.sliderDescription,
            sliderItemsList: sliderItemsList,
            currentPage: currentPage
        });
    },

    /**
     * Update slider
     * @param sliderItem
     * @param currentPage
     * @returns {*}
     * @created 2020-02-21 LongTHK
     */
    updateSlider(sliderItem, currentPage) {
        // generate slider items list
        let sliderItemsList = sliderItem.itemsList.map(function (item) {
            return _.omit(item, 'imageUrl');
        });

        return axios.post('/admin-api/sliders/update', {
            sliderId: sliderItem.sliderId,
            sliderName: sliderItem.sliderName,
            sliderDescription: sliderItem.sliderDescription,
            sliderItemsList: sliderItemsList,
            currentPage: currentPage
        });
    },

    /**
     * Delete slider
     * @param sliderId
     * @param currentPage
     * @returns {*}
     * @created 2020-02-21 LongTHK
     */
    deleteSlider(sliderId, currentPage) {
        return axios.post('/admin-api/sliders/delete', {
            sliderId: sliderId,
            currentPage: currentPage
        })
    },

    /**
     * Delete slider image item
     * @param sliderId
     * @param imageIndex
     * @param currentPage
     * @returns {*}
     * @created 2020-02-21 LongTHK
     */
    deleteSliderImageItem(sliderId, imageIndex, currentPage) {
        return axios.post('/admin-api/sliders/delete-image-item', {
            sliderId: sliderId,
            imageIndex: imageIndex,
            currentPage: currentPage
        })
    },

    /**
     * Change image redirect link
     * @param sliderId
     * @param imageIndex
     * @param redirectLink
     * @param currentPage
     * @returns {*}
     * @created 2020-02-21 LongTHK
     */
    changeImageRedirectLink(sliderId, imageIndex, redirectLink, currentPage) {
        return axios.post('/admin-api/sliders/change-image-redirect-link', {
            sliderId: sliderId,
            imageIndex: imageIndex,
            redirectLink: redirectLink,
            currentPage: currentPage
        })
    },

    /**
     * Change image title
     * @param sliderId
     * @param imageIndex
     * @param title
     * @param currentPage
     * @returns {*}
     * @created 2020-02-21 LongTHK
     */
    changeImageTitle(sliderId, imageIndex, title, currentPage) {
        return axios.post('/admin-api/sliders/change-image-title', {
            sliderId: sliderId,
            imageIndex: imageIndex,
            title: title,
            currentPage: currentPage
        })
    },

    /**
     * Change image description
     * @param sliderId
     * @param imageIndex
     * @param description
     * @param currentPage
     * @returns {*}
     * @created 2020-02-21 LongTHK
     */
    changeImageDescription(sliderId, imageIndex, description, currentPage) {
        return axios.post('/admin-api/sliders/change-image-description', {
            sliderId: sliderId,
            imageIndex: imageIndex,
            description: description,
            currentPage: currentPage
        })
    },

    /**
     * Get slider info
     * @param sliderId
     * @created 2020-02-21 LongTHK
     */
    getSliderInfo(sliderId) {
        return axios.post('/admin-api/sliders/info', {
            sliderId: sliderId
        })
    },

    /**
     * Set default slider
     * @param sliderId
     * @param currentPage
     * @returns {*}
     */
    setDefaultBanner(sliderId, currentPage) {
        return axios.post('/admin-api/sliders/set-default-slider', {
            sliderId: sliderId,
            currentPage: currentPage
        })
    }
}
