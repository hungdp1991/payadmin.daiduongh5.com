<template>
    <in-loading-page-component v-if="inLoading"/>
    <div class="container-fluid" v-else>
        <div class="row page-titles">
            <div class="col-md-6 align-self-center">
                <h4 class="text-themecolor">Tin tức</h4>
            </div>
            <div class="col-md-6 align-self-center text-right">
                <div class="d-flex justify-content-end align-items-center">
                    <router-link :to="{name: 'PostsCreatingComponent'}"
                                 class="btn btn-info d-none d-lg-block m-l-15 waves-effect"
                                 :class="{'disabled': !permissions.createPost}"
                    >
                        <i class="fa fa-plus-circle"/> Thêm tin mới
                    </router-link>
                </div>
            </div>
        </div>
        <div class="text-center" v-if="inFetching">
            <in-fetching-component/>
        </div>
        <div v-else>
            <div class="row">
                <div class="col-12">
                    <pagination-component :data="this.pagination" @reloadList="getPaginatedPostsList"
                                          v-if="this.pagination.totalPage > 1"/>
                </div>
            </div>
            <div class="row">
                <div class="col-3" v-for="postItem in postsList">
                    <div class="card">
                        <img :src="(postItem.postImage === null) ? '/images/default-post-icon.png' : postItem.postImage" alt="Card image cap"
                             class="card-img-top img-responsive img-thumbnail img-post-image">
                        <div class="card-body">
                            <h5 class="card-title post-title">{{ postItem.postTitle }}</h5>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-6">
                                    <router-link
                                        :to="{name: 'PostsUpdatingComponent', params: { postId: postItem.postId }}"
                                        class="btn btn-block btn-info waves-effect"
                                        :class="{'disabled': !permissions.updatePost}"
                                    >
                                        Cập nhật
                                    </router-link>
                                </div>
                                <div class="col-6">
                                    <button @click="openConfirmDeletePopup(postItem.postId, postItem.postTitle)"
                                            class="btn btn-block btn-danger waves-effect"
                                            :disabled="!permissions.deletePost"
                                            type="button">Xóa
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    /**
     * Import modules
     */
    import {displayConfirmDelete, displayError} from "../../../helpers/swal";

    export default {
        name: "PostsListComponent",
        data() {
            return {
                inLoading: false,
                inFetching: false,
                pagination: {},
                permissions: {
                    createPost: this.$store.state.authentication.userInfo.role.permissions.includes('CreatePost'),
                    updatePost: this.$store.state.authentication.userInfo.role.permissions.includes('UpdatePost'),
                    deletePost: this.$store.state.authentication.userInfo.role.permissions.includes('DeletePost'),
                },
                postsList: []
            }
        },
        async mounted() {
            // get categories list
            if (this.$store.state.posts.postsList.length === 0) {
                // set inLoading status
                this.inLoading = true;
                // get posts list
                await this.getPaginatedPostsList(1);
                // release inFetching status
                this.inLoading = false;
            } else {
                // set posts list
                this.postsList = this.$store.state.posts.postsList;
                // set pagination
                this.pagination = this.$store.state.posts.pagination;
            }
        },
        methods: {
            /**
             * Get paginated posts list
             * @created 2020-02-17 LongTHK
             */
            async getPaginatedPostsList(pageNumber) {
                // dispatch get posts list
                await this.$store.dispatch('getPaginatedPostsList', pageNumber);
                // set posts list
                this.postsList = this.$store.state.posts.postsList;
                // set pagination
                this.pagination = this.$store.state.posts.pagination;
            },

            /**
             * Open popup delete confirm
             * @param postId
             * @param postTitle
             * @created 2020-02-17 LongTHK
             */
            openConfirmDeletePopup(postId, postTitle) {
                // define display text
                let warningText = 'Xóa các thông tin có liên quan đến tin này.';
                // display popup
                displayConfirmDelete(postTitle, warningText, this.deletePost.bind(this, postId));
            },

            /**
             * Delete post
             * @param postId
             * @created 2020-02-17 LongTHK
             */
            async deletePost(postId) {
                // set fetching mode
                this.inFetching = true;

                // dispatch delete post action
                this.$store.dispatch('deletePost', {
                    postId: postId,
                    currentPage: this.pagination.currentPage
                })
                    .then(() => {
                        // set posts list
                        this.postsList = this.$store.state.posts.postsList;
                        // set pagination
                        this.pagination = this.$store.state.posts.pagination;
                    })
                    .catch((error) => {
                        // display error dialog
                        displayError(error.response.status);
                    })
                    .finally(() => {
                        // release inFetching status
                        this.inFetching = false;
                    })
            },
        }
    }
</script>

<style lang="less" scoped>
    .img-post-image {
        height: 150px;
    }

    .post-title {
        height: 60px;
        font-weight: normal;
        display: block;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>
