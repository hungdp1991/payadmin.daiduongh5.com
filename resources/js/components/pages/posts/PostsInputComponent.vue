<template>
    <in-loading-page-component v-if="inLoading"/>
    <div class="container-fluid" v-else>
        <div class="row page-titles">
            <div class="col-md-6 align-self-center">
                <h4 class="text-themecolor" v-if="postItem.postId === undefined">Thêm tin mới</h4>
                <h4 class="text-themecolor" v-else>Cập nhật tin</h4>
            </div>
            <div class="col-md-6 align-self-center text-right">
                <div class="d-flex justify-content-end align-items-center">
                    <button :disabled="inProcessing || !permissions.createPost" class="btn btn-success waves-effect d-none d-lg-block m-l-15" type="button" @click="createPost" v-if="postItem.postId === undefined">
                        <span v-if="!inProcessing"><i class="fas fa-arrow-alt-circle-up"></i> Đăng tin</span>
                        <span v-else>
                                <in-process-component/>
                            </span>
                    </button>
                    <button :disabled="inProcessing || !permissions.updatePost" class="btn btn-info waves-effect d-none d-lg-block m-l-15" type="button" @click="updatePost" v-else>
                        <span v-if="!inProcessing"><i class="fas fa-arrow-alt-circle-up"></i> Cập nhật</span>
                        <span v-else>
                                <in-process-component/>
                            </span>
                    </button>
                    <router-link class="btn btn-warning m-l-15 text-white waves-effect" :to="{name: 'PostsListComponent'}">
                        <i class="fas fa-step-backward"></i> Về trang tin tức
                    </router-link>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form class="form">
                            <div class="row">
                                <div class="col-3">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group"
                                                 v-bind:class="{'has-danger': validationErrors.hasOwnProperty('categoryId')}">
                                                <label>Danh mục</label>
                                                <select class="select2 form-control custom-select" style="width: 100%; height:36px;"
                                                        v-model="postItem.categoryId"
                                                        v-select2="postItem.categoryId">
                                                    <option value="">---</option>
                                                    <option :value="categoryItem.categoryId"
                                                            :disabled="categoryItem.hasOwnProperty('children') && categoryItem.children.length > 0"
                                                            v-for="categoryItem in categoriesList"
                                                    >
                                                        <span v-if="!categoryItem.hasOwnProperty('children')">&nbsp; + &nbsp;</span>
                                                        <span>{{ categoryItem.categoryName }}</span>
                                                    </option>

                                                </select>
                                                <div class="form-control-feedback"
                                                     v-if="validationErrors.hasOwnProperty('categoryId')">
                                                    {{ validationErrors.categoryId[0] }}
                                                </div>
                                            </div>
                                            <div class="form-group"
                                                 v-bind:class="{'has-danger': validationErrors.hasOwnProperty('postTitle')}">
                                                <label for="postTitle">Tiêu đề</label>
                                                <input :disabled="inProcessing" class="form-control" id="postTitle"
                                                       type="text"
                                                       v-model="postItem.postTitle" @change="onPostTitleChanged">
                                                <div class="form-control-feedback"
                                                     v-if="validationErrors.hasOwnProperty('postTitle')">{{
                                                    validationErrors.postTitle[0]
                                                    }}
                                                </div>
                                            </div>
                                            <div class="form-group"
                                                 v-bind:class="{'has-danger': validationErrors.hasOwnProperty('postSlug')}">
                                                <label for="postSlug">Slug</label>
                                                <input :disabled="inProcessing" class="form-control" id="postSlug"
                                                       type="text"
                                                       v-model="postItem.postSlug">
                                                <div class="form-control-feedback"
                                                     v-if="validationErrors.hasOwnProperty('postSlug')">{{
                                                    validationErrors.postSlug[0]
                                                    }}
                                                </div>
                                            </div>
                                            <image-choosing-component :default-file-path="postItem.postImage" @updateFilePath="updateFilePath" />
                                            <div class="form-group"
                                                 v-bind:class="{'has-danger': validationErrors.hasOwnProperty('postIntro')}">
                                                <label>Giới thiệu</label>
                                                <textarea class="form-control" rows="5"
                                                          v-model="postItem.postIntro"/>
                                                <div class="form-control-feedback"
                                                     v-if="validationErrors.hasOwnProperty('postIntro')">{{
                                                    validationErrors.postIntro[0]
                                                    }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-9">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group"
                                                 v-bind:class="{'has-danger': validationErrors.hasOwnProperty('postContent')}">
                                                <label>Nội dung</label>
                                                <ckeditor-5-component :default-content="postItem.postContent" @updateContent="updatePostContent"/>
                                                <div class="form-control-feedback"
                                                     v-if="validationErrors.hasOwnProperty('postContent')">{{
                                                    validationErrors.postContent[0]
                                                    }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
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
    import slugtify from '../../../helpers/slugtify';
    import {initializeSelect2Plugin} from "../../../helpers/select2";
    import {displayConfirmDelete, displayError} from "../../../helpers/swal";
    import postsService from '../../../services/PostsService';
    /**
     * Import CKEditor
     */
    import ClassicEditor from '@ckeditor/ckeditor5-editor-classic/src/classiceditor';
    import EssentialsPlugin from '@ckeditor/ckeditor5-essentials/src/essentials';
    import BoldPlugin from '@ckeditor/ckeditor5-basic-styles/src/bold';
    import ItalicPlugin from '@ckeditor/ckeditor5-basic-styles/src/italic';
    import LinkPlugin from '@ckeditor/ckeditor5-link/src/link';
    import ListPlugin from '@ckeditor/ckeditor5-list/src/list'
    import HeadingPlugin from '@ckeditor/ckeditor5-heading/src/heading';
    import ParagraphPlugin from '@ckeditor/ckeditor5-paragraph/src/paragraph';
    import BlockQuotePlugin from '@ckeditor/ckeditor5-block-quote/src/blockquote';
    import CkFinderAdapter from '@ckeditor/ckeditor5-ckfinder/src/ckfinder';
    import Alignment from '@ckeditor/ckeditor5-alignment/src/alignment';
    import EasyImage from '@ckeditor/ckeditor5-easy-image/src/easyimage';
    import Image from '@ckeditor/ckeditor5-image/src/image';
    import ImageCaption from '@ckeditor/ckeditor5-image/src/imagecaption';
    import ImageStyle from '@ckeditor/ckeditor5-image/src/imagestyle';
    import ImageToolbar from '@ckeditor/ckeditor5-image/src/imagetoolbar';
    import ImageResize from '@ckeditor/ckeditor5-image/src/imageresize';

    export default {
        name: "PostsInputComponent",
        data() {
            return {
                inLoading: false,
                inFetching: false,
                inProcessing: false,
                validationErrors: {},
                permissions: {
                    createPost: this.$store.state.authentication.userInfo.role.permissions.includes('CreatePost'),
                    updatePost: this.$store.state.authentication.userInfo.role.permissions.includes('UpdatePost'),
                },
                postItem: this.definePostItem(),
                categoriesList: []
            }
        },
        mounted() {
            // set inLoading mode
            this.inLoading = true;

            Promise.all([
                new Promise((resolve, reject) => {
                    // check categories list
                    if (this.$store.state.categories.categoriesList.needReload) {
                        this.getCategoriesList()
                            .then(() => {
                                resolve();
                            });
                    } else {
                        // set categories list
                        this.categoriesList = this.rebuildCategoriesList(this.$store.state.categories.categoriesList.list);
                        resolve();
                    }
                }),

                new Promise((resolve, reject) => {
                    // get post info
                    let postId = this.$route.params.postId;
                    if (postId !== undefined) {
                        // set inFetching mode
                        this.inFetching = true;
                        // call api
                        postsService.getPostInfo(postId)
                            .then((response) => {
                                // get response data
                                let responseData = response.data.data;

                                // set post item info
                                this.postItem = responseData;
                                // set post category
                                this.postItem.categoryId = responseData.postCategory.id;
                            })
                            .catch((error) => {
                                // display error popup
                                displayError(error.response.status);
                            })
                            .finally(() => {
                                this.inFetching = false;

                                resolve();
                            })
                    } else {
                        resolve();
                    }
                })
            ]).finally(() => {
                // release inLoading mode
                this.inLoading = false;

                setTimeout(function() {
                    // initialize select2
                    initializeSelect2Plugin();
                }, 50);
            });
        },
        methods: {
            /**
             * Get categories list
             * @created 2020-02-17 LongTHK
             */
            async getCategoriesList() {
                // set inFetching mode
                this.inFetching = true;
                // get categories list
                await this.$store.dispatch('getCategoriesList');
                // set categories list
                this.categoriesList = this.rebuildCategoriesList(this.$store.state.categories.categoriesList.list);

                // release inFetching mode
                this.inFetching = false;
            },

            /**
             * Rebuid categories list
             * @created 2020-02-17 LongTHK
             */
            rebuildCategoriesList(categoriesListData) {
                // define result
                let result = [];

                // rebuild categories list
                _.forEach(categoriesListData, function(firstLevelItem) {
                    // add first level item
                    result.push(firstLevelItem);
                    // add second level item
                    if (firstLevelItem.children.length > 0) {
                        _.forEach(firstLevelItem.children, function(secondLevelItem) {
                            result.push(secondLevelItem);
                        })
                    }
                });

                return result;
            },

            /**
             * Define post item
             * @returns {{image: string, title: string, slug: string}}
             * @created 2020-02-17 LongTHK
             */
            definePostItem() {
                return {
                    categoryId: '',
                    postTitle: '',
                    postSlug: '',
                    postImage: '',
                    postIntro: '',
                    postContent: ''
                }
            },

            /**
             * On post title changed
             * @created 2020-02-17 LongTHK
             */
            onPostTitleChanged() {
                this.postItem.postSlug = slugtify(this.postItem.postTitle);
            },

            /**
             * Update game image file path
             * @param filePath
             * @created 2020-02-18 LongTHK
             */
            updateFilePath(filePath) {
                this.postItem.postImage = filePath;
            },

            /**
             * Create new post
             * @created 2020-02-17 LongTHK
             */
            createPost() {
                // set inProcessing mode
                this.inProcessing = true;
                // reset validation error
                this.validationErrors = {};

                // dispatch create post action
                this.$store.dispatch('createPost', {
                    postItem: this.postItem
                })
                    .then(() => {
                        // redirect to posts list
                        this.$router.push({name: 'PostsListComponent'});
                    })
                    .catch((error) => {
                        // get error response
                        let responseError = error.response;
                        // get error status
                        let errorStatus = responseError.status;

                        // check error status
                        if (errorStatus === 422) {
                            this.validationErrors = responseError.data.errors;
                        } else {
                            // display error dialog
                            displayError(errorStatus);
                        }
                    })
                    .finally(() => {
                        // release inProcessing status
                        this.inProcessing = false;
                    })
            },

            /**
             * Update post
             * @created 2020-02-17 LongTHK
             */
            updatePost() {
                // set inProcessing mode
                this.inProcessing = true;
                // reset validation error
                this.validationErrors = {};

                // dispatch create post action
                this.$store.dispatch('updatePost', {
                    postItem: this.postItem
                })
                    .then(() => {
                        // redirect to posts list
                        this.$router.push({name: 'PostsListComponent'});
                    })
                    .catch((error) => {
                        // get error response
                        let responseError = error.response;
                        // get error status
                        let errorStatus = responseError.status;

                        // check error status
                        if (errorStatus === 422) {
                            this.validationErrors = responseError.data.errors;
                        } else {
                            // display error dialog
                            displayError(errorStatus);
                        }
                    })
                    .finally(() => {
                        // release inProcessing status
                        this.inProcessing = false;
                    })
            },

            /**
             * Update post content
             * @param content
             * @created 2020-04-16 LongTHK
             */
            updatePostContent(content) {
                this.postItem.postContent = content;
            }
        }
    }
</script>

<style lang="less" scoped>

</style>
