/**
 * Import api
 */
import postsService from '../../services/PostsService';


/**
 * Define state
 * @type {{currentUserInfo: null, isAuthenticated: boolean}}
 */
const state = {
    postsList: [],
    pagination: {}
};


/**
 * Define getters
 * @type {{}}
 */
const getters = {

};


/**
 * Define actions
 * @type {{}}
 */
const actions = {
    /**
     * Get posts list
     * @param currentPage
     * @param commit
     */
    async getPaginatedPostsList({commit}, currentPage = 1) {
        await postsService.getPaginatedPostsList(currentPage)
            .then((response) => {
                commit('GET_PAGINATED_POSTS_LIST_SUCCESSFULLY', response.data);
            })
    },

    /**
     * Create post
     * @param commit
     * @param data
     * @returns {Promise<void>}
     */
    async createPost({commit}, data) {
        await postsService.createPost(data.postItem)
            .then((response) => {
                commit('CREATE_POST_SUCCESSFULLY', response.data);
            })
    },

    /**
     * Delete post
     * @param commit
     * @param data
     * @returns {Promise<void>}
     */
    async deletePost({commit}, data) {
        await postsService.deletePost(data.postId, data.currentPage)
            .then((response) => {
                commit('DELETE_POST_SUCCESSFULLY', response.data);
            })
    },

    /**
     * Update post
     * @param commit
     * @param data
     * @returns {Promise<void>}
     */
    async updatePost({commit}, data) {
        await postsService.updatePost(data.postItem)
            .then((response) => {
                commit('UPDATE_POST_SUCCESSFULLY', response.data);
            })
    },
};


/**
 * Define mutations
 * @type {{}}
 */
const mutations = {
    /**
     * Get posts list successfully
     * @param state
     * @param data
     * @constructor
     */
    GET_PAGINATED_POSTS_LIST_SUCCESSFULLY(state, data) {
        state.postsList = data.data;
        state.pagination = data.pagination;
    },

    /**
     * Create post successfully
     * @param state
     * @param data
     * @constructor
     */
    CREATE_POST_SUCCESSFULLY(state, data) {
        state.postsList = data.data;
        state.pagination = data.pagination;
    },

    /**
     * Delete post successfully
     * @param state
     * @param data
     * @constructor
     */
    DELETE_POST_SUCCESSFULLY(state, data) {
        state.postsList = data.data;
        state.pagination = data.pagination;
    },

    /**
     * Update post successfully
     * @param state
     * @param data
     * @constructor
     */
    UPDATE_POST_SUCCESSFULLY(state, data) {
        state.postsList = data.data;
        state.pagination = data.pagination;
    },
};


/**
 * Export module
 */
export default {
    state,
    getters,
    actions,
    mutations
}
