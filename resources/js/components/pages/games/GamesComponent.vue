<template>
    <in-loading-page-component v-if="inLoading"/>
    <div class="container-fluid" v-else>
        <div class="row page-titles">
            <div class="col-md-6 align-self-center">
                <h4 class="text-themecolor font-weight-bolder">Game</h4>
            </div>
            <div class="col-md-6 align-self-center text-right">
                <div class="d-flex justify-content-end align-items-center">
                    <button class="btn btn-outline-primary d-none d-lg-block m-l-15 waves-effect"
                            data-target="#modalArrangeGame"
                            data-toggle="modal" type="button">
                        <i class="fa fa-fire"></i> S.xếp V.Trí game
                    </button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-3">
                <div class="sticky">
                    <div class="card border-success">
                        <div class="card-header"
                             v-bind:class="{'bg-success': gameItem.gameId === undefined, 'bg-info': gameItem.gameId !== undefined}">
                            <h4 class="m-b-0 text-white">Thông tin</h4>
                        </div>
                        <div class="card-body">
                            <div class="text-center" v-if="inGetting">
                                <in-getting-component :item-id="gameItem.gameId"/>
                            </div>
                            <form class="form" v-else>
                                <div class="form-group"
                                     v-bind:class="{'has-danger': validationErrors.hasOwnProperty('gameAgent')}">
                                    <label for="gameAgent">Mã game</label>
                                    <input :disabled="inProcessing" class="form-control" id="gameAgent"
                                           type="text" v-model="gameItem.gameAgent">
                                    <div class="form-control-feedback"
                                         v-if="validationErrors.hasOwnProperty('gameAgent')">{{
                                        validationErrors.gameAgent[0]
                                        }}
                                    </div>
                                </div>
                                <div class="form-group"
                                     v-bind:class="{'has-danger': validationErrors.hasOwnProperty('gameName')}">
                                    <label for="gameName">Tên game</label>
                                    <input :disabled="inProcessing" @change="onGameNameChanged" class="form-control"
                                           id="gameName"
                                           type="text" v-model="gameItem.gameName">
                                    <div class="form-control-feedback"
                                         v-if="validationErrors.hasOwnProperty('gameName')">{{
                                        validationErrors.gameName[0]
                                        }}
                                    </div>
                                </div>
                                <div class="form-group"
                                     v-bind:class="{'has-danger': validationErrors.hasOwnProperty('gameSlug')}">
                                    <label for="gameSlug">Slug</label>
                                    <input :disabled="inProcessing" class="form-control" id="gameSlug"
                                           type="text"
                                           v-model="gameItem.gameSlug">
                                    <div class="form-control-feedback"
                                         v-if="validationErrors.hasOwnProperty('gameSlug')">{{
                                        validationErrors.gameSlug[0]
                                        }}
                                    </div>
                                </div>
                                <image-choosing-component :default-file-path="gameItem.gameImage"
                                                          @updateFilePath="updateFilePath"/>
                                <div class="form-group"
                                     v-bind:class="{'has-danger': validationErrors.hasOwnProperty('paymentType')}">
                                    <label>Thanh toán</label>
                                    <select class="form-control"
                                            v-model="gameItem.paymentType">
                                        <option value="">---</option>
                                        <option :value="paymentTypeItem.value"
                                                v-for="paymentTypeItem in paymentTypesList"
                                        >{{ paymentTypeItem.caption }}
                                        </option>
                                    </select>
                                    <div class="form-control-feedback"
                                         v-if="validationErrors.hasOwnProperty('paymentType')">
                                        {{ validationErrors.paymentType[0] }}
                                    </div>
                                </div>
                                <div class="form-group"
                                     v-bind:class="{'has-danger': validationErrors.hasOwnProperty('gameRedirect')}">
                                    <label for="gameRedirect">Điều hướng</label>
                                    <input :disabled="inProcessing || gameItem.paymentType !== 'outer_link'"
                                           class="form-control" id="gameRedirect"
                                           type="text"
                                           v-model="gameItem.gameRedirect">
                                    <div class="form-control-feedback"
                                         v-if="validationErrors.hasOwnProperty('gameRedirect')">{{
                                        validationErrors.gameRedirect[0]
                                        }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Loại thẻ thanh toán</label>
                                    <select :disabled="inProcessing" class="form-control select-accepted-charge-type"
                                            multiple="multiple" v-model="gameItem.acceptedChargeType">
                                        <option :value="cardItem.cardId" v-for="cardItem in allCardsList">{{
                                            cardItem.cardName }}
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group"
                                     v-bind:class="{'has-danger': validationErrors.hasOwnProperty('limitIPList')}">
                                    <label>Giới hạn IP truy cập</label>
                                    <input :value="gameItem.limitIPList !== null ? gameItem.limitIPList.join(',') : ''"
                                           class="form-control"
                                           data-role="tagsinput" id="inputLimitIPList" placeholder="add IP"
                                           type="text"/>
                                    <div class="form-control-feedback"
                                         v-if="validationErrors.hasOwnProperty('limitIPList')">
                                        {{ validationErrors.limitIPList[0] }}
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="card-footer">
                            <div v-if="gameItem.gameId === undefined">
                                <button :disabled="!permissions.createGame"
                                        @click="createGame"
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
                                    <button :disabled="!permissions.updateGame"
                                            @click="updateGame"
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
                                    <button @click="resetGameItem"
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
                                <h4 class="card-title">Danh sách game</h4>
                            </div>
                        </div>
                        <div class="text-center" v-if="inFetching">
                            <in-fetching-component/>
                        </div>
                        <div v-else>
                            <div class="row">
                                <div class="col-12">
                                    <pagination-component :data="this.pagination" @reloadList="getPaginatedGamesList"
                                                          v-if="this.pagination.totalPage > 1"/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-4" v-for="gameItem in gamesList">
                                    <div class="card card-game-item">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-4">
                                                    <img
                                                        :src="(gameItem.gameImage === null) ? '/images/default-game-icon.png' : gameItem.gameImage"
                                                        class="card-img-top img-responsive">
                                                </div>
                                                <div class="col-8">
                                                    <h5 class="card-title post-title">
                                                        <button
                                                            :class="{'btn-success': gameItem.gameStatus, 'btn-danger': !gameItem.gameStatus}"
                                                            class="btn btn-sm">
                                                            {{ gameItem.gameAgent }}
                                                        </button>
                                                        <span>{{ gameItem.gameName }}</span>
                                                    </h5>
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <button
                                                                :class="{'btn-warning': gameItem.gameStatus, 'btn-success': !gameItem.gameStatus}"
                                                                @click="changeGameStatus(gameItem.gameId, gameItem.gameStatus)"
                                                                class="btn btn-sm btn-block">
                                                                <span v-if="gameItem.gameStatus">Đóng game</span>
                                                                <span v-else>Mở game</span>
                                                            </button>
                                                        </div>
                                                        <div class="col-12 m-t-5">
                                                            <button class="btn btn-sm btn-block btn-outline-primary">{{
                                                                getPaymentTypeName(gameItem.paymentType) }}
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="row p-t-10"
                                                         v-if="gameItem.paymentType === 'outer_link' ">
                                                        <div class="col-12">
                                                            <span class="label label-info">
                                                                <a :href="gameItem.gameRedirect" class="text-white"
                                                                   target="_blank">{{ gameItem.gameRedirect }}</a>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row p-t-10">
                                                <div class="col-12">
                                                    <span class="badge badge-warning m-r-5"
                                                          v-for="ipItem in gameItem.limitIPList">{{ ipItem }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <div class="row">
                                                <div class="col-6">
                                                    <button :disabled="!permissions.updateGame"
                                                            @click="getGameInfo(gameItem.gameId)"
                                                            class="btn btn-block btn-info waves-effect"
                                                            type="button">Cập nhật
                                                    </button>
                                                </div>
                                                <div class="col-6">
                                                    <button :disabled="!permissions.deleteGame"
                                                            @click="openConfirmDeletePopup(gameItem.gameId, gameItem.gameName)"
                                                            class="btn btn-block btn-danger waves-effect"
                                                            type="button">Xóa
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <pagination-component :data="this.pagination" @reloadList="getPaginatedGamesList"
                                                          v-if="this.pagination.totalPage > 1"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <arrange-game-component :all-games-list="gamesList" v-if="gamesList.length > 0"/>
    </div>
</template>

<script>
    /**
     * Import modules
     */
    import slugtify from "../../../helpers/slugtify";
    import {displayConfirmDelete, displayError} from "../../../helpers/swal";
    import gamesService from "../../../services/GamesService";
    /**
     * Immport components
     */
    import ArrangeGameComponent from "../../modals/ArrangeGameComponent";

    export default {
        name: "GamesComponent",
        components: {
            ArrangeGameComponent
        },
        data() {
            return {
                inLoading: false,
                inFetching: false,
                inProcessing: false,
                inGetting: false,
                validationErrors: {},
                pagination: {},
                permissions: {
                    createGame: this.$store.state.authentication.userInfo.role.permissions.includes('CreateGame'),
                    updateGame: this.$store.state.authentication.userInfo.role.permissions.includes('UpdateGame'),
                    deleteGame: this.$store.state.authentication.userInfo.role.permissions.includes('DeleteGame'),
                },
                allCardsList: [],
                gameItem: this.defineGameItem(),
                gamesList: [],
                paymentTypesList: [],
                domList: {
                    inputLimitIPList: null,
                    selectAcceptedChargeType: null
                }
            }
        },
        mounted() {
            // set inLoading mode
            this.inLoading = true;

            Promise.all([
                // get payment types list
                new Promise((resolve, reject) => {
                    if (this.$store.state.constants.PaymentTypesList.length === 0) {
                        this.getPaymentTypesList()
                            .then(() => {
                                resolve();
                            });
                    } else {
                        // set payment list
                        this.paymentTypesList = this.$store.state.constants.PaymentTypesList;
                        resolve();
                    }
                }),

                // get games list
                new Promise((resolve, reject) => {
                    if (this.$store.state.games.gamesList.length === 0) {
                        this.getPaginatedGamesList(1)
                            .then(() => {
                                resolve();
                            });
                    } else {
                        // set games list
                        this.gamesList = this.$store.state.games.gamesList;
                        // set pagination
                        this.pagination = this.$store.state.games.pagination;

                        resolve();
                    }
                }),

                // get all cards list
                new Promise((resolve, reject) => {
                    if (this.$store.state.cards.allCardsList.needReload) {
                        this.getAllCardsList();
                        resolve();
                    } else {
                        this.allCardsList = this.$store.state.cards.allCardsList.list;
                        resolve();
                    }
                })
            ]).finally(() => {
                // release inLoading mode
                this.inLoading = false;

                setTimeout(() => {
                    // init taginput plugin
                    this.initTagsInputPlugin();
                    this.initializeAcceptedChargeType();
                }, 50)
            });
        },
        methods: {
            /**
             * Define game item
             * @created 2020-02-18 LongTHk
             */
            defineGameItem() {
                return {
                    gameAgent: '',
                    gameName: '',
                    gameSlug: '',
                    gameImage: '',
                    paymentType: '',
                    gameRedirect: '',
                    acceptedChargeType: [],
                    limitIPList: []
                }
            },

            /**
             * Initialize TagsInput plugin
             */
            initTagsInputPlugin() {
                // get current component
                let _this = this;

                // get input limit IP
                this.domList.inputLimitIPList = $('#inputLimitIPList');

                // init input tag plugin
                this.domList.inputLimitIPList.tagsinput({
                    trimValue: true,
                });

                // bind action when add / remove Ip list
                this.domList.inputLimitIPList.on('itemAdded itemRemoved', function () {
                    _this.gameItem.limitIPList = _this.domList.inputLimitIPList.tagsinput('items');
                });
            },

            /**
             * Initialize accepted charge types
             */
            initializeAcceptedChargeType() {
                // get current component
                let _this = this;

                // get select accepted charge type
                this.domList.selectAcceptedChargeType = $('.select-accepted-charge-type');

                // init select plugin
                this.domList.selectAcceptedChargeType.select2()
                    .on('select2:select', () => {
                        _this.gameItem.acceptedChargeType = this.domList.selectAcceptedChargeType.val();
                    })
                    .on('select2:unselect', () => {
                        _this.gameItem.acceptedChargeType = this.domList.selectAcceptedChargeType.val();
                    });
            },

            /**
             * Update game image file path
             * @param filePath
             * @created 2020-02-18 LongTHK
             */
            updateFilePath(filePath) {
                this.gameItem.gameImage = filePath;
            },

            /**
             * On game name changed
             * @created 2020-02-18 LongTHK
             */
            onGameNameChanged() {
                this.gameItem.gameSlug = slugtify(this.gameItem.gameName);
            },

            /**
             * Get payment types list
             * @created 2020-02-18 LongTHK
             */
            async getPaymentTypesList() {
                // set inFetching mode
                this.inFetching = true;

                // get categories list
                await this.$store.dispatch('getPaymentTypesList');
                // set payment list
                this.paymentTypesList = this.$store.state.constants.PaymentTypesList;

                // release inFetching mode
                this.inFetching = false;
            },

            /**
             * Get payment type name by payment type value
             * @param paymentTypeValue
             * @created 2020-02-19 LongTHK
             */
            getPaymentTypeName(paymentTypeValue) {
                return _.find(this.$store.state.constants.PaymentTypesList, ['value', paymentTypeValue]).caption;
            },

            /**
             * Get paginated games list
             * @param pageNumber
             * @created 2020-02-19 LongTHK
             */
            async getPaginatedGamesList(pageNumber) {
                // set inFetching mode
                this.inFetching = true;
                // dispatch get games list
                await this.$store.dispatch('getPaginatedGamesList', pageNumber);
                // set games list
                this.gamesList = this.$store.state.games.gamesList;
                // set pagination
                this.pagination = this.$store.state.games.pagination;
                // release inFetching mode
                this.inFetching = false;
            },

            /**
             * Get all cards list
             */
            getAllCardsList() {
                this.$store.dispatch('getAllCardsList')
                    .then(() => {
                        this.allCardsList = this.$store.state.cards.allCardsList.list;
                    })
            },

            /**
             * Create new game
             * @created 2020-02-19 LongTHK
             */
            createGame() {
                // set inProcessing status
                this.inProcessing = true;
                // reset validation error
                this.validationErrors = {};

                // dispatch create game action
                this.$store.dispatch('createGame', {
                    gameItem: this.gameItem,
                    currentPage: 1
                })
                    .then(() => {
                        // reload games list
                        this.gamesList = this.$store.state.games.gamesList;
                        // reload pagination
                        this.pagination = this.$store.state.games.pagination;
                        // reset user item
                        this.gameItem = this.defineGameItem();

                        // reset tagsinput
                        this.domList.inputLimitIPList.tagsinput('removeAll');
                        // reset accepted charge type
                        this.domList.selectAcceptedChargeType.val('').trigger('change');
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
             * Open delete user confirmation box
             * @param gameId
             * @param gameName
             * @created 2020-02-19 LongTHK
             */
            openConfirmDeletePopup(gameId, gameName) {
                // define display text
                let warningText = 'Xóa các thông tin có liên quan đến game này.';
                // display popup
                displayConfirmDelete(gameName, warningText, this.deleteGame.bind(this, gameId));
            },

            /**
             * Delete game
             * @param gameId
             * @created 2020-02-19 LongTHK
             */
            deleteGame(gameId) {
                // dispatch delete game action
                this.$store.dispatch('deleteGame', {
                    gameId: gameId,
                    currentPage: this.pagination.currentPage
                })
                    .then(() => {
                        // reload games list
                        this.gamesList = this.$store.state.games.gamesList;
                        // set pagination
                        this.pagination = this.$store.state.games.pagination;
                    })
                    .catch((error) => {
                        // display error dialog
                        displayError(error.response.status);
                    })
            },

            /**
             * Get game info
             * @param gameId
             * @created 2020-02-19 LongTHK
             */
            getGameInfo(gameId) {
                // get current component
                let _this = this;
                // set inGetting status
                this.inGetting = true;
                // reset validation error
                this.validationErrors = {};

                // call api
                gamesService.getGameInfo(gameId)
                    .then((response) => {
                        // set game item
                        this.gameItem = response.data.data;

                        // init select 2 plugin
                        setTimeout(function () {
                            // reinitialize input tags plugin
                            _this.initTagsInputPlugin();
                            // reinitialize select 2 plugin
                            _this.initializeAcceptedChargeType();
                        }, 100);
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
             * Update game
             * @created 2020-02-19 LongTHK
             */
            updateGame() {
                // set inProcessing status
                this.inProcessing = true;
                // reset validation error
                this.validationErrors = {};

                // dispatch update role action
                this.$store.dispatch('updateGame', {
                    gameItem: this.gameItem,
                    currentPage: this.pagination.currentPage
                })
                    .then(() => {
                        // reload games list
                        this.gamesList = this.$store.state.games.gamesList;
                        // reload pagination
                        this.pagination = this.$store.state.games.pagination;
                        // reset game item
                        this.gameItem = this.defineGameItem();

                        // reset tagsinput
                        this.domList.inputLimitIPList.tagsinput('removeAll');
                        // reset accepted charge type
                        this.domList.selectAcceptedChargeType.val('').trigger('change');
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
             * Reset game item
             * @created 2020-02-19 LongTHK
             */
            resetGameItem() {
                this.gameItem = this.defineGameItem();
                // reset validation error
                this.validationErrors = {};
                // reset tagsinput
                this.domList.inputLimitIPList.tagsinput('removeAll');
            },

            /**
             * Change game status
             * @param gameId
             * @param currentGameStatus
             */
            changeGameStatus(gameId, currentGameStatus) {
                // dispatch update role action
                this.$store.dispatch('changeGameStatus', {
                    gameId: gameId,
                    gameStatus: !currentGameStatus
                })
                    .then(() => {
                        // reload games list
                        this.gamesList = this.$store.state.games.gamesList;
                    })
                    .catch((error) => {
                        // display error
                        displayError(error.response.status);
                    })
            }
        }
    }
</script>

<style lang="less">
    .bootstrap-tagsinput {
        width: 100%
    }

    .card-game-item {
        border: solid 1px #edf1f5 !important;
    }
</style>
