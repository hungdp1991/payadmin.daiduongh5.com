/**
 * Export api
 */
export default {

    /**
     * Get parent categories list
     * @created 2020-02-14 LongTHK
     * @returns {*}
     */
    getParentCategoriesList() {
        return axios.post('/admin-api/categories/parent-categories');
    },

    /**
     * Get categories list
     * @created 2020-02-14 LongTHK
     * @returns {*}
     */
    getCategoriesList() {
        return axios.post('/admin-api/categories/list');
    },

    /**
     * Create new category
     * @param categoryItem
     * @returns {*}
     * @created 2020-02-14 LongTHK
     */
    createCategory(categoryItem) {
        return axios.post('/admin-api/categories/create', {
            categoryParentId: categoryItem.categoryParentId,
            categoryName: categoryItem.categoryName,
            categorySlug: categoryItem.categorySlug
        });
    },

    /**
     * Delete category
     * @param categoryId
     * @returns {*}
     * @created 2020-02-14 LongTHK
     */
    deleteCategory(categoryId) {
        return axios.post('/admin-api/categories/delete', {
            categoryId: categoryId
        });
    },

    /**
     * Get category info
     * @param categoryId
     * @returns {*}
     * @created 2020-02-14 LongTHK
     */
    getCategoyInfo(categoryId) {
        return axios.post('/admin-api/categories/info', {
            categoryId: categoryId
        })
    },

    /**
     * Update category
     * @param categoryItem
     * @returns {*}
     * @created 2020-02-14 LongTHK
     */
    updateCategory(categoryItem) {
        return axios.post('/admin-api/categories/update', {
            categoryId: categoryItem.categoryId,
            categoryParentId: categoryItem.categoryParentId,
            categoryName: categoryItem.categoryName,
            categorySlug: categoryItem.categorySlug
        });
    },
}
