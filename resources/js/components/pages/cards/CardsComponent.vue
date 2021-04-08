<template>
    <in-loading-page-component v-if="inLoading"/>
    <div class="container-fluid" v-else>
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h4 class="text-themecolor font-weight-bolder">Loại thẻ</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-5">
                <div class="sticky">
                    <div class="card border-success">
                        <div class="card-header"
                             v-bind:class="{'bg-success': cardItem.cardId === undefined, 'bg-info': cardItem.cardId !== undefined}">
                            <h4 class="m-b-0 text-white">Thông tin</h4>
                        </div>
                        <div class="card-body">
                            <div class="text-center" v-if="inGetting">
                                <in-getting-component :item-id="cardItem.cardId"/>
                            </div>
                            <form class="form-material" v-else>
                                <div class="form-group"
                                     v-bind:class="{'has-danger': validationErrors.hasOwnProperty('cardType')}">
                                    <label>Loại thẻ</label>
                                    <input class="form-control form-control-line"
                                           type="text" v-model="cardItem.cardType">
                                    <div class="form-control-feedback"
                                         v-if="validationErrors.hasOwnProperty('cardType')">
                                        {{ validationErrors.cardType[0] }}
                                    </div>
                                </div>
                                <div class="form-group"
                                     v-bind:class="{'has-danger': validationErrors.hasOwnProperty('cardName')}">
                                    <label>Tên loại thẻ</label>
                                    <input :disabled="inProcessing" class="form-control"
                                           type="text"
                                           v-model="cardItem.cardName">
                                    <div class="form-control-feedback"
                                         v-if="validationErrors.hasOwnProperty('cardName')">
                                        {{ validationErrors.cardName[0] }}
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="card-footer">
                            <div v-if="cardItem.cardId === undefined">
                                <button :disabled="!permissions.createCard"
                                        @click="createCard"
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
                                    <button :disabled="!permissions.updateCard"
                                            @click="updateCard"
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
                                    <button @click="resetCardItem"
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
            <div class="col-7">
                <div class="card border-success">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <h4 class="card-title">Danh sách loại thẻ</h4>
                                <div class="text-center" v-if="inFetching">
                            <span aria-hidden="true" class="spinner-grow text-info" role="status"
                                  style="width: 3rem; height: 3rem;"/>
                                </div>
                                <div v-else>
                                    <div class="row">
                                        <div class="col-12">
                                            <pagination-component :data="this.pagination"
                                                                  @reloadList="getPaginatedCardsList"
                                                                  v-if="this.pagination.totalPage > 1"/>
                                        </div>
                                    </div>
                                    <div class="table-responsive m-t-20">
                                        <table class="table table-hover">
                                            <thead>
                                            <tr>
                                                <th>Loại thẻ</th>
                                                <th>Tên loại thẻ</th>
                                                <th>Tình trạng</th>
                                                <th></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr v-for="cardItem in cardsList">
                                                <td>{{ cardItem.cardType }}</td>
                                                <td>{{ cardItem.cardName }}</td>
                                                <td>
                                                    <bootstrap-switch-component :id="cardItem.cardId"
                                                                                :status="cardItem.cardStatus"
                                                                                @updateStatus="updateCardStatus"/>
                                                </td>
                                                <td>
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <button :disabled="!permissions.updateCard"
                                                                    @click="getCardInfo(cardItem.cardId)"
                                                                    class="btn btn-sm btn-info btn-block"
                                                                    type="button">Cập nhật
                                                            </button>
                                                        </div>
                                                        <div class="col-6">
                                                            <button
                                                                :disabled="!permissions.deleteCard"
                                                                @click="openConfirmDeletePopup(cardItem.cardId, cardItem.cardName)"
                                                                class="btn btn-sm btn-danger btn-block"
                                                                type="button"
                                                            >Xóa
                                                            </button>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <pagination-component :data="this.pagination"
                                                                  @reloadList="getPaginatedUsersList"
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
    import cardsService from "../../../services/CardsService";


    export default {
        name: "CardsComponent",
        data() {
            return {
                inLoading: false,
                inFetching: false,
                inProcessing: false,
                inGetting: false,
                validationErrors: {},
                permissions: {
                    createCard: this.$store.state.authentication.userInfo.role.permissions.includes('CreateCard'),
                    updateCard: this.$store.state.authentication.userInfo.role.permissions.includes('UpdateCard'),
                    deleteCard: this.$store.state.authentication.userInfo.role.permissions.includes('DeleteCard'),
                },
                pagination: {},
                cardItem: this.defineCardItem(),
                cardsList: []
            }
        },
        mounted() {
            // set inLoading mode
            this.inLoading = true;

            Promise.all([
                new Promise((resolve, reject) => {
                    // check cards list
                    if (this.$store.state.cards.cardsList.length === 0) {
                        this.getPaginatedCardsList(1)
                            .then(() => {
                                resolve();
                            });
                    } else {
                        // set cards list data
                        this.cardsList = this.$store.state.cards.cardsList;
                        resolve();
                    }
                })
            ]).finally(() => {
                // release inLoading mode
                this.inLoading = false;
            });

            // initialize sticky
            $(".sticky").stick_in_parent({
                offset_top: 100
            });
        },
        methods: {
            /**
             * Define card item
             * @returns {{}}
             * @created 2020-02-24 LongTHK
             */
            defineCardItem() {
                return {
                    cardType: '',
                    cardName: '',
                    cardStatus: ''
                }
            },

            /**
             * Reset card item
             * @created 2020-02-24 LongTHK
             */
            resetCardItem() {
                this.cardItem = this.defineCardItem();
                // reset validation error
                this.validationErrors = {};
            },

            /**
             * Get paginated cards list
             * @param pageNumber
             * @created 2020-02-24 LongTHK
             */
            async getPaginatedCardsList(pageNumber) {
                // set inFetching mode
                this.inFetching = true;
                // dispatch get roles list
                await this.$store.dispatch('getPaginatedCardsList', pageNumber);
                // set cards list
                this.cardsList = this.$store.state.cards.cardsList;
                // set pagination
                this.pagination = this.$store.state.cards.pagination;
                // release inFetching mode
                this.inFetching = false;
            },

            /**
             * Create new card
             * @created LongTHK 2020-02-24
             */
            createCard() {
                // set inProcessing status
                this.inProcessing = true;
                // reset validation error
                this.validationErrors = {};

                // dispatch create card action
                this.$store.dispatch('createCard', {
                    cardItem: this.cardItem
                })
                    .then(() => {
                        // set cards list
                        this.cardsList = this.$store.state.cards.cardsList;
                        // set pagination
                        this.pagination = this.$store.state.cards.pagination;
                        // reset card item
                        this.cardItem = this.defineCardItem();
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
             * Get card info
             * @param cardId
             * @created LongTHK 2020-02-24
             */
            getCardInfo(cardId) {
                // set inGetting status
                this.inGetting = true;
                // reset validation error
                this.validationErrors = {};

                // call api
                cardsService.getCardInfo(cardId)
                    .then((response) => {
                        // set card item
                        this.cardItem = response.data.data;
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
             * Update card
             * @created LongTHK 2020-02-24
             */
            updateCard() {
                // set inProcessing status
                this.inProcessing = true;
                // reset validation error
                this.validationErrors = {};

                // dispatch update role action
                this.$store.dispatch('updateCard', {
                    cardItem: this.cardItem
                })
                    .then(() => {
                        // set cards list
                        this.cardsList = this.$store.state.cards.cardsList;
                        // set pagination
                        this.pagination = this.$store.state.cards.pagination;
                        // reset category item
                        this.cardItem = this.defineCardItem();
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
             * Update card status
             * @param cardId
             * @param cardStatus
             */
            updateCardStatus(cardId, cardStatus) {
                // dispatch action
                this.$store.dispatch('updateCardStatus', {
                    cardId: cardId,
                    cardStatus: cardStatus,
                    currentPage: this.pagination.currentPage
                })
                    .then(() => {
                        // set cards list
                        this.cardsList = this.$store.state.cards.cardsList;
                        // set pagination
                        this.pagination = this.$store.state.cards.pagination;
                    })
                    .catch(() => {
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
            },

            /**
             * Open delete card confirmation box
             * @param cardId
             * @param cardName
             * @created LongTHK 2020-02-24
             */
            openConfirmDeletePopup(cardId, cardName) {
                // define display text
                let warningText = 'Xóa các thông tin có liên quan đến loại thẻ này.';
                // display popup
                displayConfirmDelete(cardName, warningText, this.deleteCard.bind(this, cardId));
            },

            /**
             * Delete card
             * @param cardId
             * @created LongTHK 2020-02-24
             */
            deleteCard(cardId) {
                // dispatch delete category action
                this.$store.dispatch('deleteCard', {
                    cardId: cardId,
                })
                    .then(() => {
                        // set cards list
                        this.cardsList = this.$store.state.cards.cardsList;
                        // set pagination
                        this.pagination = this.$store.state.cards.pagination;
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

</style>
