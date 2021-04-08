/**
 * Export api
 */
export default {
    /**
     * Get posts list
     * @paramt currentPage
     * @returns {*}
     * @created 2020-02-17 LongTHK
     */
    getPaginatedPostsList(currentPage) {
        return axios.post('/admin-api/posts/list', {
            currentPage: currentPage
        });
    },

    /**
     * Delete post
     * @param postId
     * @param currentPage
     * @returns {*}
     * @created 2020-02-17 LongTHK
     */
    deletePost(postId, currentPage) {
        return axios.post('/admin-api/posts/delete', {
            postId: postId,
            currentPage: currentPage
        })
    },

    /**
     * Create new post
     * @param postItem
     * @returns {*}
     * @created 2020-02-17 LongTHK
     */
    createPost(postItem) {
        return axios.post('/admin-api/posts/create', {
            categoryId: postItem.categoryId,
            postTitle: postItem.postTitle,
            postSlug: postItem.postSlug,
            postImage: postItem.postImage,
            postIntro: postItem.postIntro,
            postContent: postItem.postContent
        })
    },

    /**
     * Get post info
     * @param postId
     * @created 2020-02-17 LongTHK
     */
    getPostInfo(postId) {
        return axios.post('/admin-api/posts/info', {
            postId: postId
        })
    },

    /**
     * Update post
     * @param postItem
     * @returns {*}
     * @created 2020-02-17 LongTHK
     */
    updatePost(postItem) {
        return axios.post('/admin-api/posts/update', {
            postId: postItem.postId,
            categoryId: postItem.categoryId,
            postTitle: postItem.postTitle,
            postSlug: postItem.postSlug,
            postImage: postItem.postImage,
            postIntro: postItem.postIntro,
            postContent: postItem.postContent
        })
    },
}
