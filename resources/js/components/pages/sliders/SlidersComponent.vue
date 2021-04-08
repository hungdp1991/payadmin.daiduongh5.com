<template>
    <in-loading-page-component v-if="inLoading"/>
    <div class="container-fluid" v-else>
        <div class="row page-titles">
            <div class="col-md-6 align-self-center">
                <h4 class="text-themecolor">Sliders / Banners</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-4">
                <div class="sticky">
                    <div class="card mb-5">
                        <div class="card-body">
                            <div class="text-center" v-if="inGetting">
                            <span aria-hidden="true" class="spinner-grow"
                                  role="status"
                                  style="width: 3rem; height: 3rem;"
                                  v-bind:class="{'text-success': sliderItem.sliderId === undefined, 'text-info': sliderItem.sliderId !== undefined}"/>
                            </div>
                            <form class="form" v-else>
                                <div class="form-group"
                                     v-bind:class="{'has-danger': validationErrors.hasOwnProperty('sliderName')}">
                                    <label for="sliderName">Tên slider</label>
                                    <input :disabled="inProcessing" class="form-control" id="sliderName" type="text"
                                           v-model="sliderItem.sliderName">
                                    <div class="form-control-feedback"
                                         v-if="validationErrors.hasOwnProperty('sliderName')">{{
                                        validationErrors.sliderName[0]
                                        }}
                                    </div>
                                </div>
                                <div class="form-group"
                                     v-bind:class="{'has-danger': validationErrors.hasOwnProperty('sliderDescription')}">
                                    <label for="sliderDescription">Mô tả</label>
                                    <textarea :disabled="inProcessing" class="form-control" id="sliderDescription"
                                              rows="5"
                                              v-model="sliderItem.sliderDescription"/>
                                    <div class="form-control-feedback"
                                         v-if="validationErrors.hasOwnProperty('sliderDescription')">{{
                                        validationErrors.sliderDescription[0]
                                        }}
                                    </div>
                                </div>
                                <div class="form-group"
                                     v-bind:class="{'has-danger': validationErrors.hasOwnProperty('sliderItemsList')}">
                                    <label>Hình</label>
                                    <div class="preview-uploaded-file">
                                        <div class="row">
                                            <div class="col-6 mt-3"
                                                 v-for="(imageItem, imageIndex) in sliderItem.itemsList">
                                                <div class="preview-image">
                                                    <img :src="imageItem.imagePath" class="img-thumbnail">
                                                    <button @click="removeFromSliderItemsList(imageIndex)"
                                                            class="btn btn-sm btn-danger waves-effect button-remove"
                                                            type="button">
                                                        <i class="fas fa-times"/>
                                                    </button>
                                                </div>
                                                <div class="input-group input-group-sm">
                                                    <input class="form-control" placeholder="Chuyển hướng" type="text"
                                                           v-model="sliderItem.itemsList[imageIndex].imageRedirect">
                                                </div>
                                                <div class="input-group input-group-sm">
                                                    <input class="form-control" placeholder="Tiêu đề" type="text"
                                                           v-model="sliderItem.itemsList[imageIndex].imageTitle">
                                                </div>
                                                <div class="input-group input-group-sm">
                                                <textarea class="form-control" placeholder="Mô tả" rows="2"
                                                          v-model="sliderItem.itemsList[imageIndex].imageDescription"/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-control-feedback"
                                         v-if="validationErrors.hasOwnProperty('sliderItemsList')">{{
                                        validationErrors.sliderItemsList[0]
                                        }}
                                    </div>
                                    <button :disabled="!permissions.createSlider || !permissions.updateSlider"
                                            @click="openChoosingImageModal"
                                            class="btn btn-outline-dark btn-block waves-effect mt-2"
                                            type="button">Chọn hình
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div class="card-footer">
                            <div class="row" v-if="sliderItem.sliderId === undefined">
                                <div class="col-12">
                                    <button :disabled="inProcessing || !permissions.createSlider"
                                            @click="createSlider"
                                            class="btn btn-block btn-success waves-effect"
                                            type="button">
                                        <span v-if="!inProcessing">Thêm mới</span>
                                        <span v-else>
                                        <div aria-hidden="true" class="spinner-grow spinner-grow-sm"
                                             role="status"></div>
                                        <div aria-hidden="true" class="spinner-grow spinner-grow-sm"
                                             role="status"></div>
                                        <div aria-hidden="true" class="spinner-grow spinner-grow-sm"
                                             role="status"></div>
                                    </span>
                                    </button>
                                </div>
                            </div>
                            <div class="row" v-else>
                                <div class="col-8">
                                    <button :disabled="inProcessing || !permissions.createSlider" @click="updateSlider"
                                            class="btn btn-block btn-info waves-effect"
                                            type="button">
                                        <span v-if="!inProcessing">Cập nhật</span>
                                        <span v-else>
                                        <div aria-hidden="true" class="spinner-grow spinner-grow-sm"
                                             role="status"></div>
                                        <div aria-hidden="true" class="spinner-grow spinner-grow-sm"
                                             role="status"></div>
                                        <div aria-hidden="true" class="spinner-grow spinner-grow-sm"
                                             role="status"></div>
                                    </span>
                                    </button>
                                </div>
                                <div class="col-4">
                                    <button @click="resetSliderItem" class="btn btn-block btn-warning waves-effect"
                                            type="button">Hủy
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-8">
                <div class="card">
                    <div class="card-body">
                        <div class="text-center" v-if="inFetching">
                            <span aria-hidden="true" class="spinner-grow text-info" role="status"
                                  style="width: 3rem; height: 3rem;"/>
                        </div>
                        <div class="row" v-else>
                            <div class="col-12">
                                <pagination-component :data="this.pagination" @reloadList="getPaginatedSlidersList"
                                                      v-if="this.pagination.totalPage > 1"/>
                                <div class="table-responsive form-material">
                                    <table class="table table-hover">
                                        <thead>
                                        <tr>
                                            <th class="w-25">Tên</th>
                                            <th>Hình</th>
                                            <th class="w-25"></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="sliderItem in slidersList">
                                            <td>
                                                <h4>{{ sliderItem.sliderName }}</h4>
                                                <p class="text-small">{{ sliderItem.sliderDescription }}</p>
                                            </td>
                                            <td>
                                                <div class="row">
                                                    <div class="col-6 mb-2"
                                                         v-for="(imageItemValue, imageItemIndex) in sliderItem.sliderItemsList">
                                                        <div class="preview-image">
                                                            <img :src="imageItemValue.imagePath"
                                                                 class="img-thumbnail">
                                                            <button
                                                                :disabled="!permissions.updateSlider"
                                                                @click="deleteImageItem(sliderItem.sliderId, imageItemIndex)"
                                                                class="btn btn-sm btn-danger waves-effect button-remove">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        </div>
                                                        <div class="input-group input-group-sm">
                                                            <input :disabled="!permissions.updateSlider"
                                                                   :value="imageItemValue.imageRedirect"
                                                                   @change="changeImageRedirectLink($event, sliderItem.sliderId, imageItemIndex)"
                                                                   class="form-control"
                                                                   placeholder="redirect link..."
                                                                   type="text">
                                                        </div>
                                                        <div class="input-group input-group-sm">
                                                            <input :disabled="!permissions.updateSlider"
                                                                   :value="imageItemValue.imageTitle"
                                                                   @change="changeImageTitle($event, sliderItem.sliderId, imageItemIndex)"
                                                                   class="form-control"
                                                                   placeholder="tiêu đề"
                                                                   type="text">
                                                        </div>
                                                        <div class="input-group input-group-sm">
                                                            <textarea
                                                                :disabled="!permissions.updateSlider"
                                                                @change="changeImageDescription($event, sliderItem.sliderId, imageItemIndex)"
                                                                class="form-control" placeholder="Mô tả"
                                                                rows="2"
                                                            >{{ imageItemValue.imageDescription }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="row">
                                                    <div class="col-6">
                                                        <button :disabled="!permissions.updateSlider"
                                                                @click="getSliderInfo(sliderItem.sliderId)"
                                                                class="btn btn-sm btn-info btn-block waves-effect"
                                                                type="button">Cập nhật
                                                        </button>
                                                    </div>
                                                    <div class="col-6">
                                                        <button
                                                            :disabled="sliderItem.isDefault || !permissions.updateSlider"
                                                            @click="openConfirmDeletePopup(sliderItem.sliderId, sliderItem.sliderName)"
                                                            class="btn btn-sm btn-danger btn-block waves-effect"
                                                            type="button">Xóa
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="row m-t-10">
                                                    <div class="col-12">
                                                        <button
                                                            :disabled="sliderItem.isDefault || !permissions.updateSlider"
                                                            @click="setDefaultBanner(sliderItem.sliderId)"
                                                            class="btn btn-sm btn-primary btn-block waves-effect text-white"
                                                            type="button">Đặt làm banner
                                                        </button>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <pagination-component :data="this.pagination" @reloadList="getPaginatedSlidersList"
                                                      v-if="this.pagination.totalPage > 1"/>
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
    import slidersService from '../../../services/SlidersService'
    import {displayConfirmDelete, displayError} from "../../../helpers/swal";

    export default {
        name: "SlidersComponent",
        mounted() {
            // set inLoading mode
            this.inLoading = true;

            Promise.all([
                new Promise(async (resolve, reject) => {
                    if (this.$store.state.sliders.slidersList.length === 0) {
                        // set inFetching status
                        this.inFetching = true;
                        // get customers list
                        await this.getPaginatedSlidersList(1);
                        // release inFetching status
                        this.inFetching = false;

                        resolve();
                    } else {
                        // set customers list
                        this.slidersList = this.$store.state.sliders.slidersList;
                        // set pagination
                        this.pagination = this.$store.state.sliders.pagination;

                        resolve();
                    }
                })
            ]).finally(() => {
                // release inLoading mode
                this.inLoading = false;
            });

            // initialize sticky
            $('.sticky').stick_in_parent({
                offset_top: 100
            });
        },
        data() {
            return {
                inLoading: false,
                inFetching: false,
                inProcessing: false,
                inGetting: false,
                validationErrors: {},
                permissions: {
                    createSlider: this.$store.state.authentication.userInfo.role.permissions.includes('CreateSlider'),
                    updateSlider: this.$store.state.authentication.userInfo.role.permissions.includes('UpdateSlider'),
                    deleteSlider: this.$store.state.authentication.userInfo.role.permissions.includes('DeleteSlider'),
                },
                slidersList: [],
                pagination: {},
                sliderItem: this.defineSliderItem(),
            }
        },
        methods: {
            /**
             * Define slider item
             * @created 2020-02-21 LongTHK
             */
            defineSliderItem() {
                return {
                    sliderName: '',
                    sliderDescription: '',
                    itemsList: []
                }
            },

            /**
             * Get sliders list
             * @created 2020-02-21 LongTHK
             */
            async getPaginatedSlidersList(pageNumber) {
                // dispatch get customers list
                await this.$store.dispatch('getPaginatedSlidersList', pageNumber);
                // set customers list
                this.slidersList = this.$store.state.sliders.slidersList;
                // set pagination
                this.pagination = this.$store.state.sliders.pagination;
            },

            /**
             * Create new slider
             * @created 2020-02-21 LongTHK
             */
            createSlider() {
                // set inProcessing status
                this.inProcessing = true;
                // reset validation error
                this.validationErrors = {};
                // set fetching mode
                this.inFetching = true;

                // dispatch create slider action
                this.$store.dispatch('createSlider', {
                    sliderItem: this.sliderItem,
                    currentPage: 1
                })
                    .then(() => {
                        // reload slider list
                        this.slidersList = this.$store.state.sliders.slidersList;
                        // reload pagination
                        this.pagination = this.$store.state.sliders.pagination;
                        // reset slider item
                        this.sliderItem = this.defineSliderItem();
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
                        // release inFetching status
                        this.inFetching = false;
                    })
            },

            /**
             * Open popup delete confirm
             * @param sliderId
             * @param sliderName
             * @created 2020-02-21 LongTHK
             */
            openConfirmDeletePopup(sliderId, sliderName) {
                // define display text
                let warningText = 'Xóa các thông tin có liên quan đến slider này.';
                // display popup
                displayConfirmDelete(sliderName, warningText, this.deleteSlider.bind(this, sliderId));
            },

            /**
             * Delete slider
             * @param sliderId
             * @created 2020-02-21 LongTHK
             */
            deleteSlider(sliderId) {
                // set fetching mode
                this.inFetching = true;

                // dispatch delete slider action
                this.$store.dispatch('deleteSlider', {
                    sliderId: sliderId,
                    currentPage: this.pagination.currentPage
                })
                    .then(() => {
                        // reload sliders list
                        this.slidersList = this.$store.state.sliders.slidersList;
                        // reload pagination
                        this.pagination = this.$store.state.sliders.pagination;
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

            /**
             * Delete slider image item
             * @param sliderId
             * @param imageIndex
             * @created 2020-02-21 LongTHK
             */
            deleteImageItem(sliderId, imageIndex) {
                // dispatch delete slider action
                this.$store.dispatch('deleteSliderImageItem', {
                    sliderId: sliderId,
                    imageIndex: imageIndex,
                    currentPage: this.pagination.currentPage
                })
                    .then(() => {
                        // reload sliders list
                        this.slidersList = this.$store.state.sliders.slidersList;
                    })
                    .catch((error) => {
                        // display error dialog
                        displayError(error.response.status);
                    });
            },

            /**
             * Change image item redirect link
             * @param event
             * @param sliderId
             * @param imageIndex
             * @created 2020-02-21 LongTHK
             */
            changeImageRedirectLink(event, sliderId, imageIndex) {
                // dispatch change redirect link
                this.$store.dispatch('changeImageRedirectLink', {
                    sliderId: sliderId,
                    imageIndex: imageIndex,
                    redirectLink: event.target.value,
                    currentPage: this.pagination.currentPage
                })
                    .then(() => {
                        // reload sliders list
                        this.slidersList = this.$store.state.sliders.slidersList;
                    })
                    .catch((error) => {
                        // display error dialog
                        displayError(error.response.status);
                    });
            },

            /**
             * Change image item title
             * @param event
             * @param sliderId
             * @param imageIndex
             * @created 2020-02-21 LongTHK
             */
            changeImageTitle(event, sliderId, imageIndex) {
                // dispatch change redirect link
                this.$store.dispatch('changeImageTitle', {
                    sliderId: sliderId,
                    imageIndex: imageIndex,
                    title: event.target.value,
                    currentPage: this.pagination.currentPage
                })
                    .then(() => {
                        // reload sliders list
                        this.slidersList = this.$store.state.sliders.slidersList;
                    })
                    .catch((error) => {
                        // display error dialog
                        displayError(error.response.status);
                    });
            },

            /**
             * Change image item description
             * @param event
             * @param sliderId
             * @param imageIndex
             * @created 2020-02-21 LongTHK
             */
            changeImageDescription(event, sliderId, imageIndex) {
                // dispatch change redirect link
                this.$store.dispatch('changeImageDescription', {
                    sliderId: sliderId,
                    imageIndex: imageIndex,
                    description: event.target.value,
                    currentPage: this.pagination.currentPage
                })
                    .then(() => {
                        // reload sliders list
                        this.slidersList = this.$store.state.sliders.slidersList;
                    })
                    .catch((error) => {
                        // display error dialog
                        displayError(error.response.status);
                    });
            },

            /**
             * Reset slider item
             * @created 2020-02-21 LongTHK
             */
            resetSliderItem() {
                this.sliderItem = this.defineSliderItem();
                // reset validation error
                this.validationErrors = {};
            },

            /**
             * Get slider info
             * @param sliderId
             * @created 2020-02-21 LongTHK
             */
            getSliderInfo(sliderId) {
                // set inGetting status
                this.inGetting = true;
                // reset validation error
                this.validationErrors = {};

                // call api
                slidersService.getSliderInfo(sliderId)
                    .then((response) => {
                        // get slider info
                        let sliderInfo = response.data.data;
                        // set slider item info
                        this.sliderItem.sliderId = sliderInfo.sliderId;
                        this.sliderItem.sliderDescription = sliderInfo.sliderDescription;
                        this.sliderItem.sliderName = sliderInfo.sliderName;
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
             * Update slider
             * @created 2020-02-21 LongTHK
             */
            updateSlider() {
                // set inProcessing status
                this.inProcessing = true;
                // reset validation error
                this.validationErrors = {};

                // dispatch update customer action
                this.$store.dispatch('updateSlider', {
                    sliderItem: this.sliderItem,
                    currentPage: 1
                })
                    .then(() => {
                        // reload slider list
                        this.slidersList = this.$store.state.sliders.slidersList;
                        // reload pagination
                        this.pagination = this.$store.state.sliders.pagination;
                        // reset slider item
                        this.sliderItem = this.defineSliderItem();
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
                        // release inFetching status
                        this.inFetching = false;
                    })
            },

            /**
             * Remove item from slider items list
             * @param itemIndex
             * @created 2020-02-21 LongTHK
             */
            removeFromSliderItemsList(itemIndex) {
                this.sliderItem.itemsList = _.remove(this.sliderItem.itemsList, function (value, index) {
                    return index !== itemIndex;
                });
            },

            /**
             * Open choosing file modal
             * @created 2020-02-21 LongTHK
             */
            openChoosingImageModal() {
                // define current component
                let currentComponent = this;

                CKFinder.modal({
                    language: 'en',
                    chooseFiles: true,
                    width: 800,
                    height: 600,
                    onInit: function (finder) {
                        finder.on('files:choose', function (event) {
                            // get images list
                            let imagesList = event.data.files;

                            // process images list
                            for (let i = 0; i < imagesList.length; i++) {
                                // get current image
                                let imageUrl = imagesList.models[i].getUrl();

                                // push slider item to items list
                                currentComponent.sliderItem.itemsList.push({
                                    imagePath: imageUrl.split(window.location.hostname)[1],
                                    imageTitle: '',
                                    imageDescription: '',
                                    imageRedirect: ''
                                });
                            }
                        }.bind(currentComponent));
                    }
                });
            },

            /**
             * Set default banner
             * @param sliderId
             * @created 2020-03-02 LongTHK
             */
            setDefaultBanner(sliderId) {
                // dispatch update customer action
                this.$store.dispatch('setDefaultBanner', {
                    sliderId: sliderId,
                    currentPage: this.pagination.currentPage
                })
                    .then(() => {
                        // reload slider list
                        this.slidersList = this.$store.state.sliders.slidersList;
                        // reload pagination
                        this.pagination = this.$store.state.sliders.pagination;
                    })
                    .catch((error) => {
                        // display error dialog
                        displayError(error.response.status);
                    })
            }
        }
    }
</script>

<style lang="less" scoped>
    .input-group-text {
        cursor: pointer;

        i {
            color: #ffffff;
        }
    }

    .preview-image {
        position: relative;

        .button-remove {
            position: absolute;
            bottom: 0;
            right: 0;
        }
    }
</style>
