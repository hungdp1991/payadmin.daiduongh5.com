<template>
    <in-loading-page-component v-if="inLoading"/>
    <div class="container-fluid" v-else>
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h4 class="text-themecolor font-weight-bolder">Gói nạp</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-3">
                <div class="sticky">
                    <div class="card border-success">
                        <div class="card-header"
                             v-bind:class="{'bg-success': goldItem.goldId === undefined, 'bg-info': goldItem.goldId !== undefined}">
                            <h4 class="m-b-0 text-white">Thông tin</h4>
                        </div>
                        <div class="card-body">
                            <div class="text-center" v-if="inGetting">
                                <in-getting-component :item-id="goldItem.goldId"/>
                            </div>
                            <form class="" v-else>
                                <div class="form-group"
                                     v-bind:class="{'has-danger': validationErrors.hasOwnProperty('gameId')}">
                                    <label>Game</label>
                                    <select class="select2 form-control custom-select" style="width: 100%; height:36px;"
                                            v-model="goldItem.gameId"
                                            v-select2="goldItem.gameId">
                                        <option value="">---</option>
                                        <option :value="gameItem.gameId"
                                                v-for="gameItem in allGamesList"
                                        >{{ gameItem.gameName }}
                                        </option>
                                    </select>
                                    <div class="form-control-feedback"
                                         v-if="validationErrors.hasOwnProperty('gameId')">
                                        {{ validationErrors.gameId[0] }}
                                    </div>
                                </div>
                                <image-choosing-component :default-file-path="goldItem.goldImage"
                                                          @updateFilePath="updateFilePath"/>
                                <div class="form-group"
                                     v-bind:class="{'has-danger': validationErrors.hasOwnProperty('goldName')}">
                                    <label for="goldName">Tên gói nạp</label>
                                    <input :disabled="inProcessing" class="form-control" id="goldName"
                                           type="text"
                                           v-model="goldItem.goldName">
                                    <div class="form-control-feedback"
                                         v-if="validationErrors.hasOwnProperty('goldName')">{{
                                        validationErrors.goldName[0]
                                        }}
                                    </div>
                                </div>
                                <div class="form-group"
                                     v-bind:class="{'has-danger': validationErrors.hasOwnProperty('goldDescription')}">
                                    <label for="goldDescription">Mô tả</label>
                                    <textarea :disabled="inProcessing" class="form-control" id="goldDescription"
                                              rows="3" v-model="goldItem.goldDescription"/>
                                    <div class="form-control-feedback"
                                         v-if="validationErrors.hasOwnProperty('goldDescription')">{{
                                        validationErrors.goldDescription[0]
                                        }}
                                    </div>
                                </div>
                                <div class="form-group"
                                     v-bind:class="{'has-danger': validationErrors.hasOwnProperty('goldType')}">
                                    <label>Loại thẻ</label>
                                    <select class="form-control"
                                            v-model="goldItem.goldType">
                                        <option value="">---</option>
                                        <option :value="goldTypeItem.value"
                                                v-for="goldTypeItem in goldTypesList"
                                        >{{ goldTypeItem.caption }}
                                        </option>
                                    </select>
                                    <div class="form-control-feedback"
                                         v-if="validationErrors.hasOwnProperty('goldType')">
                                        {{ validationErrors.goldType[0] }}
                                    </div>
                                </div>
                                <div class="form-group"
                                     v-bind:class="{'has-danger': validationErrors.hasOwnProperty('productGoldId')}">
                                    <label for="productGoldId">Product id gold</label>
                                    <input :disabled="inProcessing" class="form-control" id="productGoldId"
                                           type="text"
                                           v-model="goldItem.productGoldId">
                                    <div class="form-control-feedback"
                                         v-if="validationErrors.hasOwnProperty('productGoldId')">{{
                                        validationErrors.productGoldId[0]
                                        }}
                                    </div>
                                </div>
                                <div class="form-group"
                                     v-bind:class="{'has-danger': validationErrors.hasOwnProperty('goldAmount')}">
                                    <label for="goldAmount">Amount</label>
                                    <input :disabled="inProcessing" class="form-control" id="goldAmount"
                                           type="number"
                                           v-model="goldItem.goldAmount">
                                    <div class="form-control-feedback"
                                         v-if="validationErrors.hasOwnProperty('goldAmount')">{{
                                        validationErrors.goldAmount[0]
                                        }}
                                    </div>
                                </div>
                                <div class="form-group"
                                     v-bind:class="{'has-danger': validationErrors.hasOwnProperty('goldValue')}">
                                    <label for="goldValue">Gold</label>
                                    <input :disabled="inProcessing" class="form-control" id="goldValue"
                                           type="number"
                                           v-model="goldItem.goldValue">
                                    <div class="form-control-feedback"
                                         v-if="validationErrors.hasOwnProperty('goldValue')">{{
                                        validationErrors.goldValue[0]
                                        }}
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="card-footer">
                            <div v-if="goldItem.goldId === undefined">
                                <button :disabled="!permissions.createGold"
                                        @click="createGold"
                                        class="btn btn-block btn-success waves-effect waves-light"
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
                                    <button :disabled="!permissions.updateGold"
                                            @click="updateGold"
                                            class="btn btn-block btn-info waves-effect waves-light"
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
                                    <button @click="resetGoldItem"
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
            <div class="col-9">
                <div class="card border-success">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <h4 class="card-title">Danh sách gói nạp</h4>
                                <div class="text-center" v-if="inFetching">
                            <span aria-hidden="true" class="spinner-grow text-info" role="status"
                                  style="width: 3rem; height: 3rem;"/>
                                </div>
                                <div v-else>
                                    <div class="row">
                                        <div class="col-12">
                                            <pagination-component :data="this.pagination"
                                                                  @reloadList="getPaginatedGoldList"
                                                                  v-if="this.pagination.totalPage > 1"/>
                                        </div>
                                    </div>
                                    <div class="table-responsive m-t-20">
                                        <table class="table table-hover">
                                            <thead>
                                            <tr>
                                                <th>Ảnh đại diện<p>&nbsp;</p></th>
                                                <th>
                                                    Game
                                                    <select class="select2 form-control custom-select"
                                                            style="width: 100%; height:36px;"
                                                            v-model="searchItem.gameAgent"
                                                            v-select2="searchItem.gameAgent"
                                                    >
                                                        <option value="">---</option>
                                                        <option :value="gameItem.gameAgent"
                                                                v-for="gameItem in allGamesList"
                                                        >{{ gameItem.gameName }}
                                                        </option>
                                                    </select>
                                                </th>
                                                <th>
                                                    Loại thẻ
                                                    <select class="select2 form-control custom-select"
                                                            style="width: 100%; height:36px;"
                                                            v-model="searchItem.goldType"
                                                            v-select2="searchItem.goldType"
                                                    >
                                                        <option value="">---</option>
                                                        <option :value="goldTypeItem.value"
                                                                v-for="goldTypeItem in goldTypesList"
                                                        >{{ goldTypeItem.caption }}
                                                        </option>
                                                    </select>
                                                </th>
                                                <th>Tên<p>&nbsp;</p></th>
                                                <th>Amount<p>&nbsp;</p></th>
                                                <th>Gold<p>&nbsp;</p></th>
                                                <th></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr v-for="goldItem in goldList">
                                                <td>
                                                    <img :src="goldItem.goldImage " class="gold-image">
                                                </td>
                                                <td>{{ goldItem.game !== null ? goldItem.game.gameName : '' }}</td>
                                                <td>{{ getGoldTypeName(goldItem.goldType) }}</td>
                                                <td>{{ goldItem.goldName }}</td>
                                                <td>{{ goldItem.goldAmount }}</td>
                                                <td>{{ goldItem.goldValue }}</td>
                                                <td class="text-right">
                                                    <button :disabled="!permissions.updateGold"
                                                            @click="getGoldInfo(goldItem.goldId)"
                                                            class="btn btn-outline-info"
                                                            type="button"><i class="ti-pencil"></i>
                                                    </button>
                                                    <button
                                                        :disabled="!permissions.deleteGold"
                                                        @click="openConfirmDeletePopup(goldItem.goldId, goldItem.goldName)"
                                                        class="btn btn-outline-danger"
                                                        type="button"
                                                    ><i class="ti-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <pagination-component :data="this.pagination"
                                                                  @reloadList="getPaginatedGoldList"
                                                                  v-if="this.pagination.totalPage > 1"/>
                                        </div>
                                    </div>
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
    import {initializeSelect2Plugin, resetSelect2Value} from "../../../helpers/select2";

    /**
     * Import api
     */
    import goldService from "../../../services/GoldService";

    export default {
        name: "GoldComponent",
        data() {
            return {
                inLoading: false,
                inFetching: false,
                inProcessing: false,
                inGetting: false,
                validationErrors: {},
                permissions: {
                    createGold: this.$store.state.authentication.userInfo.role.permissions.includes('CreateGold'),
                    updateGold: this.$store.state.authentication.userInfo.role.permissions.includes('UpdateGold'),
                    deleteGold: this.$store.state.authentication.userInfo.role.permissions.includes('DeleteGold'),
                },
                pagination: {},
                goldItem: this.defineGoldItem(),
                goldList: [],
                allGamesList: [],
                goldTypesList: [],
                searchItem: this.defineSearchItem(),
            }
        },
        mounted() {
            // set inLoading mode
            this.inLoading = true;

            Promise.all([
                new Promise((resolve, reject) => {
                    // check gold list
                    if (this.$store.state.gold.goldList.length === 0) {
                        this.getPaginatedGoldList(1)
                            .then(() => {
                                resolve();

                            });
                    } else {
                        // set cards list data
                        this.goldList = this.$store.state.gold.goldList;
                        // set pagination
                        this.pagination = this.$store.state.gold.pagination;

                        resolve();
                    }
                }),

                new Promise((resolve, reject) => {
                    // get all games list
                    if (this.$store.state.games.allGamesList.needReload) {
                        this.getAllGamesList()
                            .then(() => {
                                resolve();
                            })
                    } else {
                        // set all games list
                        this.allGamesList = this.$store.state.games.allGamesList.list;
                        resolve();
                    }
                }),

                new Promise((resolve, reject) => {
                    // get gold types list
                    if (this.$store.state.constants.GoldTypesList.length === 0) {
                        this.getGoldTypesList()
                            .then(() => {
                                resolve();
                            });
                    } else {
                        // set gold types list
                        this.goldTypesList = this.$store.state.constants.GoldTypesList;
                        resolve();
                    }
                })
            ]).finally(() => {
                // release inLoading mode
                this.inLoading = false;

                setTimeout(() => {
                    // initialize select2
                    initializeSelect2Plugin();
                }, 50)
            });


            // initialize sticky
            $(".sticky").stick_in_parent({
                offset_top: 100
            });
        },
        watch: {
            'searchItem.gameAgent': function () {
                this.searchGoldList();
            },
            'searchItem.goldType': function () {
                this.searchGoldList();
            }
        },
        methods: {
            /**
             * Define gold item
             * @returns {{}}
             * @created 2020-02-24 LongTHK
             */
            defineGoldItem() {
                return {
                    gameId: '',
                    goldName: '',
                    goldDescription: '',
                    goldImage: '',
                    goldType: '',
                    productGoldId: '',
                    goldAmount: '',
                    goldValue: ''
                }
            },

            /**
             * Define search item
             * @created 2020-04-14 LongTHK
             */
            defineSearchItem() {
                return {
                    gameAgent: '',
                    goldType: ''
                }
            },

            /**
             * Reset gold item
             * @created 2020-02-24 LongTHK
             */
            resetGoldItem() {
                this.goldItem = this.defineGoldItem();
                // reset validation error
                this.validationErrors = {};
                // rest select2 plugin
                resetSelect2Value();
            },

            /**
             * Get paginated gold list
             * @param pageNumber
             * @created 2020-02-24 LongTHK
             */
            async getPaginatedGoldList(pageNumber) {
                // set inFetching mode
                this.inFetching = true;
                // dispatch get roles list
                await this.$store.dispatch('getPaginatedGoldList', pageNumber);
                // set cards list
                this.goldList = this.$store.state.gold.goldList;
                // set pagination
                this.pagination = this.$store.state.gold.pagination;
                // release inFetching mode
                this.inFetching = false;
            },

            /**
             * Get all games list
             * @created 2020-02-24 LongTHK
             */
            async getAllGamesList() {
                // set inFetching mode
                this.inFetching = true;

                // get all games list
                await this.$store.dispatch('getAllGamesList');
                // set all games list
                this.allGamesList = this.$store.state.games.allGamesList.list;

                // release inFetching mode
                this.inFetching = false;
            },

            /**
             * Get gold types list
             * @created 2020-02-18 LongTHK
             */
            async getGoldTypesList() {
                // set inFetching mode
                this.inFetching = true;

                // get categories list
                await this.$store.dispatch('getGoldTypesList');
                // set gold types list
                this.goldTypesList = this.$store.state.constants.GoldTypesList;

                // release inFetching mode
                this.inFetching = false;
            },

            /**
             * Update game image file path
             * @param filePath
             * @created 2020-02-24 LongTHK
             */
            updateFilePath(filePath) {
                this.goldItem.goldImage = filePath;
            },

            /**
             * Create new gold
             * @created LongTHK 2020-02-24
             */
            createGold() {
                // set inProcessing status
                this.inProcessing = true;
                // reset validation error
                this.validationErrors = {};

                // dispatch create card action
                this.$store.dispatch('createGold', {
                    goldItem: this.goldItem
                })
                    .then(() => {
                        // set cards list
                        this.goldList = this.$store.state.gold.goldList;
                        // set pagination
                        this.pagination = this.$store.state.gold.pagination;
                        // reset gold item
                        this.goldItem = this.defineGoldItem();

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
             * Get gold info
             * @param goldId
             * @created LongTHK 2020-02-24
             */
            getGoldInfo(goldId) {
                // set inGetting status
                this.inGetting = true;
                // reset validation error
                this.validationErrors = {};

                // call api
                goldService.getGoldInfo(goldId)
                    .then((response) => {
                        // get response data
                        let responseData = response.data.data;
                        // set gold item
                        this.goldItem = responseData;
                        // set game id
                        this.goldItem.gameId = responseData.game.gameId;

                        // initialize select2
                        setTimeout(function () {
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
             * Update gold
             * @created LongTHK 2020-02-24
             */
            updateGold() {
                // set inProcessing status
                this.inProcessing = true;
                // reset validation error
                this.validationErrors = {};

                // dispatch update role action
                this.$store.dispatch('updateGold', {
                    goldItem: this.goldItem
                })
                    .then(() => {
                        // set cards list
                        this.goldList = this.$store.state.gold.goldList;
                        // set pagination
                        this.pagination = this.$store.state.gold.pagination;
                        // reset category item
                        this.goldItem = this.defineGoldItem();
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
             * Open delete gold confirmation box
             * @param goldId
             * @param goldName
             * @created LongTHK 2020-02-24
             */
            openConfirmDeletePopup(goldId, goldName) {
                // define display text
                let warningText = 'Xóa các thông tin có liên quan đến gói nạp này.';
                // display popup
                displayConfirmDelete('', warningText, this.deleteGold.bind(this, goldId));
            },

            /**
             * Delete card
             * @param goldId
             * @created LongTHK 2020-02-24
             */
            deleteGold(goldId) {
                // dispatch delete category action
                this.$store.dispatch('deleteGold', {
                    goldId: goldId,
                })
                    .then(() => {
                        // set cards list
                        this.goldList = this.$store.state.gold.goldList;
                        // set pagination
                        this.pagination = this.$store.state.gold.pagination;
                    })
                    .catch((error) => {
                        // display error dialog
                        displayError(error.response.status);
                    })
            },

            /**
             * Get gold type name
             * @created 2020-04-14 LongTHk
             */
            getGoldTypeName(goldTypeValue) {
                // check gold type value
                if (goldTypeValue === null) {
                    return '';
                }

                return _.find(this.goldTypesList, function (goldTypeItem) {
                    return goldTypeItem.value === goldTypeValue.toString()
                }).caption;
            },

            /**
             * Search gold
             * @returns {Promise<void>}
             * @created 2020-04-14 LongTHK
             */
            async searchGoldList() {
                // set inFetching mode
                this.inFetching = true;

                // dispatch get servers list
                if (this.searchItem.gameAgent === ''
                    && this.searchItem.goldType === '') {

                    // set golds list data
                    this.goldList = this.$store.state.gold.goldList;
                    // set pagination
                    this.pagination = this.$store.state.gold.pagination;
                } else {
                    goldService.searchGoldList(this.searchItem)
                        .then((response) => {
                            this.goldList = response.data.data;
                            // set pagination
                            this.pagination = {};
                        });
                }

                // release inFetching mode
                this.inFetching = false;
            },
        }
    }
</script>

<style lang="less" scoped>
    .gold-image {
        height: 50px
    }
</style>
