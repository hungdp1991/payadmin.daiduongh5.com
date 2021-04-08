<template>
    <in-loading-page-component v-if="inLoading"/>
    <div class="container-fluid" v-else>
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h4 class="text-themecolor font-weight-bolder">Chuyên mục</h4>

            </div>
        </div>
        <div class="row">
            <div class="col-8">
                <div class="sticky">
                    <div class="card border-success">
                        <div class="card-header"
                             v-bind:class="{'bg-success': categoryItem.categoryId === undefined, 'bg-info': categoryItem.categoryId !== undefined}">
                            <h4 class="m-b-0 text-white">Thông tin</h4>
                        </div>
                        <div class="card-body">
                            <div class="text-center" v-if="inGetting">
                                <in-getting-component :item-id="categoryItem.categoryId"/>
                            </div>
                            <form class="form-material" v-else>
                                <div class="form-group"
                                     v-bind:class="{'has-danger': validationErrors.hasOwnProperty('categoryParentId')}">
                                    <label>Danh mục cha</label>
                                    <select class="select2 form-control custom-select" style="width: 100%; height:36px;"
                                            v-model="categoryItem.categoryParentId"
                                            v-select2="categoryItem.categoryParentId">
                                        <option value="0">---</option>
                                        <option :value="categoryItem.categoryId"
                                                v-for="categoryItem in parentCategoriesList">
                                            {{ categoryItem.categoryName }}
                                        </option>
                                    </select>
                                    <div class="form-control-feedback"
                                         v-if="validationErrors.hasOwnProperty('categoryParentId')">
                                        {{ validationErrors.categoryParentId[0] }}
                                    </div>
                                </div>
                                <div class="form-group"
                                     v-bind:class="{'has-danger': validationErrors.hasOwnProperty('categoryName')}">
                                    <label>Tên chuyên mục</label>
                                    <input @change="onCategoryNameChanged" class="form-control form-control-line"
                                           type="text" v-model="categoryItem.categoryName">
                                    <div class="form-control-feedback"
                                         v-if="validationErrors.hasOwnProperty('categoryName')">
                                        {{ validationErrors.categoryName[0] }}
                                    </div>
                                </div>
                                <div class="form-group"
                                     v-bind:class="{'has-danger': validationErrors.hasOwnProperty('categorySlug')}">
                                    <label>Slug</label>
                                    <input :disabled="inProcessing" class="form-control"
                                           type="text"
                                           v-model="categoryItem.categorySlug">
                                    <div class="form-control-feedback"
                                         v-if="validationErrors.hasOwnProperty('categorySlug')">
                                        {{ validationErrors.categorySlug[0] }}
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="card-footer">
                            <div v-if="categoryItem.categoryId === undefined">
                                <button @click="createCategory"
                                        class="btn btn-block btn-success waves-effect waves-light"
                                        :disabled="!permissions.createCategory"
                                        type="button">
                                    <span v-if="!inProcessing">
                                        Thêm mới
                                    </span>
                                    <span v-else>
                                        <in-process-component/>
                                    </span>
                                </button>
                            </div>
                            <div class="row" v-else>
                                <div class="col-8">
                                    <button @click="updateCategory"
                                            class="btn btn-block btn-info waves-effect waves-light"
                                            :disabled="!permissions.updateCategory"
                                            type="button">
                                        <span v-if="!inProcessing">
                                            Cập nhật
                                        </span>
                                        <span v-else>
                                            <in-process-component/>
                                        </span>
                                    </button>
                                </div>
                                <div class="col-4">
                                    <button @click="resetCategoryItem"
                                            class="btn btn-block btn-warning waves-effect waves-light"
                                            type="button">
                                        Hủy
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="card border-success">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <h4 class="card-title">Danh sách chuyên mục</h4>
                                <div class="text-center" v-if="inFetching">
                            <span aria-hidden="true" class="spinner-grow text-info" role="status"
                                  style="width: 3rem; height: 3rem;"/>
                                </div>
                                <div class="dd myadmin-dd-empty" id="categoriesList">
                                    <ol class="dd-list">
                                        <li :data-id="firstLevelCategoryItem.categoryId"
                                            class="dd-item dd3-item"
                                            v-for="firstLevelCategoryItem in categoriesList"
                                        >
                                            <div class="dd-handle dd3-handle"></div>
                                            <div class="dd3-content"> {{ firstLevelCategoryItem.categoryName }}</div>
                                            <div class="dd3-action">
                                                <button @click="getCategoryInfo(firstLevelCategoryItem.categoryId)"
                                                        :disabled="!permissions.updateCategory"
                                                        class="btn btn-sm btn-outline-info"
                                                >
                                                    <i class="fa fa-pen"></i>
                                                </button>
                                                <button :disabled="firstLevelCategoryItem.children.length > 0 || !permissions.deleteCategory"
                                                        @click="openConfirmDeletePopup(firstLevelCategoryItem.categoryId, firstLevelCategoryItem.categoryName)"
                                                        class="btn btn-sm btn-outline-danger"
                                                >
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </div>
                                            <ol class="dd-list" v-if="firstLevelCategoryItem.children.length > 0">
                                                <li :data-id="secondLevelCategoryItem.categoryId"
                                                    class="dd-item dd3-item"
                                                    v-for="secondLevelCategoryItem in firstLevelCategoryItem.children"
                                                >
                                                    <div class="dd-handle dd3-handle"></div>
                                                    <div class="dd3-content">
                                                        {{ secondLevelCategoryItem.categoryName}}
                                                    </div>
                                                    <div class="dd3-action">
                                                        <button
                                                            @click="getCategoryInfo(secondLevelCategoryItem.categoryId)"
                                                            class="btn btn-sm btn-outline-info"
                                                        >
                                                            <i class="fa fa-pen"></i>
                                                        </button>
                                                        <button
                                                            @click="openConfirmDeletePopup(secondLevelCategoryItem.categoryId, secondLevelCategoryItem.categoryName)"
                                                            class="btn btn-sm btn-outline-danger"
                                                        >
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </li>
                                            </ol>
                                        </li>
                                    </ol>
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
    import {initializeSelect2Plugin, resetSelect2Value} from "../../../helpers/select2";
    import {initializeNestable} from '../../../helpers/nestable';
    import {displayConfirmDelete, displayError} from "../../../helpers/swal";
    import slugtify from "../../../helpers/slugtify";
    import categoriesService from "../../../services/CategoriesService";


    export default {
        name: "CategoriesComponent",
        data() {
            return {
                inLoading: false,
                inFetching: false,
                inProcessing: false,
                inGetting: false,
                validationErrors: {},
                permissions: {
                    createCategory: this.$store.state.authentication.userInfo.role.permissions.includes('CreateCategory'),
                    updateCategory: this.$store.state.authentication.userInfo.role.permissions.includes('UpdateCategory'),
                    deleteCategory: this.$store.state.authentication.userInfo.role.permissions.includes('DeleteCategory'),
                },
                categoryItem: this.defineCategoryItem(),
                parentCategoriesList: [],
                categoriesList: []
            }
        },
        mounted() {
            // set inLoading mode
            this.inLoading = true;


            Promise.all([
                new Promise((resolve, reject) => {
                    // check parent categories list
                    if (this.$store.state.categories.parentCategoriesList.needReload) {
                        this.getParentCategoriesList()
                            .then(() => {
                                resolve();
                            });
                    } else {
                        this.parentCategoriesList = this.$store.state.categories.parentCategoriesList.list;
                        resolve();
                    }
                }),

                new Promise((resolve, reject) => {
                    // check categories list
                    if (this.$store.state.categories.categoriesList.needReload) {
                        this.getCategoriesList()
                            .then(() => {
                                resolve();
                            });
                    } else {
                        // set categories list data
                        this.categoriesList = this.$store.state.categories.categoriesList.list;
                        resolve();
                    }
                })
            ]).finally(() => {
                // release inLoading mode
                this.inLoading = false;

                setTimeout(function() {
                    // init select2 plugin
                    initializeSelect2Plugin();
                    // init nestable plugin
                    initializeNestable($('#categoriesList'), 2, this.changeCategoriesListData);
                }, 50)
            });



            // initialize sticky
            $(".sticky").stick_in_parent({
                offset_top: 100
            });
        },
        methods: {
            /**
             * Define category item
             * @returns {{}}
             * @created 2020-02-14 LongTHK
             */
            defineCategoryItem() {
                return {
                    categoryParentId: 0,
                    categoryName: '',
                    categorySlug: ''
                }
            },

            /**
             * Reset category item
             */
            resetCategoryItem() {
                this.categoryItem = this.defineCategoryItem();
                // reset validation error
                this.validationErrors = {};
                // reset select customer and product
                resetSelect2Value();
            },

            /**
             * Get parent categories list
             * @created 2020-02-14 LongTHK
             */
            async getParentCategoriesList() {
                // set inGetting mode
                this.inGetting = true;
                // get products list
                await this.$store.dispatch('getParentCategoriesList');
                // set parent categories list
                this.parentCategoriesList = this.$store.state.categories.parentCategoriesList.list;
                // release inGetting mode
                this.inGetting = false;
            },

            /**
             * Get categories list
             * @created 2020-02-14 LongTHK
             */
            async getCategoriesList() {
                // set inFetching mode
                this.inFetching = true;
                // get categories list
                await this.$store.dispatch('getCategoriesList');
                // set categories list
                this.categoriesList = this.$store.state.categories.categoriesList.list;
                // release inFetching mode
                this.inFetching = false;
            },

            /**
             * On category name changed
             */
            onCategoryNameChanged() {
                this.categoryItem.categorySlug = slugtify(this.categoryItem.categoryName);
            },

            /**
             * Change categories list data
             * @created 2020-02-14 LongTHK
             */
            changeCategoriesListData() {
                console.log('changed');
            },

            /**
             * Create new category
             * @created LongTHK 2020-02-14
             */
            createCategory() {
                // set inProcessing status
                this.inProcessing = true;
                // reset validation error
                this.validationErrors = {};

                // dispatch create user action
                this.$store.dispatch('createCategory', {
                    categoryItem: this.categoryItem
                })
                    .then(() => {
                        // set parent categories list
                        this.parentCategoriesList = this.$store.state.categories.parentCategoriesList;
                        // set categories list
                        this.categoriesList = this.$store.state.categories.categoriesList;

                        // reset category item
                        this.categoryItem = this.defineCategoryItem();

                        // reset select customer and product
                        resetSelect2Value();
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
             * Get category info
             * @param categoryId
             * @created LongTHK 2020-02-14
             */
            getCategoryInfo(categoryId) {
                // set inGetting status
                this.inGetting = true;
                // reset validation error
                this.validationErrors = {};

                // call api
                categoriesService.getCategoyInfo(categoryId)
                    .then((response) => {
                        // get response data
                        let responseData = response.data.data;

                        // set role item
                        this.categoryItem = responseData;

                        setTimeout(function () {
                            // initialize select2
                            initializeSelect2Plugin();
                        }, 200)
                    })
                    .catch((error) => {
                        // display error popup
                        displayError(error.response.status);
                    })
                    .finally(() => {
                        this.inGetting = false;
                    })
            },

            /**
             * Update category
             * @created LongTHK 2020-02-14
             */
            updateCategory() {
                // set inProcessing status
                this.inProcessing = true;
                // reset validation error
                this.validationErrors = {};

                // dispatch update role action
                this.$store.dispatch('updateCategory', {
                    categoryItem: this.categoryItem
                })
                    .then(() => {
                        // set parent categories list
                        this.parentCategoriesList = this.$store.state.categories.parentCategoriesList;
                        // set categories list
                        this.categoriesList = this.$store.state.categories.categoriesList;

                        // reset category item
                        this.categoryItem = this.defineCategoryItem();

                        // reset select customer and product
                        resetSelect2Value();
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
             * Open delete category confirmation box
             * @param categoryId
             * @param categoryName
             * @created LongTHK 2020-02-13
             */
            openConfirmDeletePopup(categoryId, categoryName) {
                // define display text
                let warningText = 'Xóa các thông tin có liên quan đến chuyên mục này.';
                // display popup
                displayConfirmDelete(categoryName, warningText, this.deleteCategory.bind(this, categoryId));
            },

            /**
             * Delete category
             * @param categoryId
             * @created LongTHK 2020-02-13
             */
            deleteCategory(categoryId) {
                // dispatch delete category action
                this.$store.dispatch('deleteCategory', {
                    categoryId: categoryId,
                })
                    .then(() => {
                        // set parent categories list
                        this.parentCategoriesList = this.$store.state.categories.parentCategoriesList;
                        // set categories list
                        this.categoriesList = this.$store.state.categories.categoriesList;
                    })
                    .catch((error) => {
                        // display error dialog
                        displayError(error.response.status);
                    })
            },
        }
    }
</script>

<style lang="less" scoped>
    .myadmin-dd-empty {
        .dd-list {
            .dd3-content {
                padding-left: 54px;
            }

            .dd3-action {
                position: absolute;
                right: 5px;
                top: 5px;
                z-index: 1;
            }
        }
    }
</style>
