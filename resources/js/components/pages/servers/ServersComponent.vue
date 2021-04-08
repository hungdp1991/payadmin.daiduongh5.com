<template>
    <in-loading-page-component v-if="inLoading"/>
    <div class="container-fluid" v-else>
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h4 class="text-themecolor font-weight-bolder">Server</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-3">
                <div class="sticky">
                    <div class="card border-success">
                        <div class="card-header"
                             v-bind:class="{'bg-success': serverItem.id === undefined, 'bg-info': serverItem.id !== undefined}">
                            <h4 class="m-b-0 text-white">Thông tin</h4>
                        </div>
                        <div class="card-body">
                            <div class="text-center" v-if="inGetting">
                                <in-getting-component :item-id="serverItem.id"/>
                            </div>
                            <form class="form" v-else>
                                <div class="form-group"
                                     v-bind:class="{'has-danger': validationErrors.hasOwnProperty('gameId')}">
                                    <label>Game</label>
                                    <select class="select2 form-control custom-select" style="width: 100%; height:36px;"
                                            v-model="serverItem.gameId"
                                            v-select2="serverItem.gameId">
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
                                <div class="form-group"
                                     v-bind:class="{'has-danger': validationErrors.hasOwnProperty('serverId')}">
                                    <label for="serverId">Server id</label>
                                    <input :disabled="inProcessing" class="form-control" id="serverId"
                                           type="text" v-model="serverItem.serverId">
                                    <div class="form-control-feedback"
                                         v-if="validationErrors.hasOwnProperty('serverId')">{{
                                        validationErrors.serverId[0]
                                        }}
                                    </div>
                                </div>
                                <div class="form-group"
                                     v-bind:class="{'has-danger': validationErrors.hasOwnProperty('serverName')}">
                                    <label for="serverName">Tên server</label>
                                    <input :disabled="inProcessing"
                                           @change="onGameNameChanged"
                                           class="form-control"
                                           id="serverName"
                                           type="text" v-model="serverItem.serverName">
                                    <div class="form-control-feedback"
                                         v-if="validationErrors.hasOwnProperty('serverName')">{{
                                        validationErrors.serverName[0]
                                        }}
                                    </div>
                                </div>
                                <div class="form-group"
                                     v-bind:class="{'has-danger': validationErrors.hasOwnProperty('serverSlug')}">
                                    <label for="serverSlug">Slug</label>
                                    <input :disabled="inProcessing" class="form-control" id="serverSlug"
                                           type="text"
                                           v-model="serverItem.serverSlug">
                                    <div class="form-control-feedback"
                                         v-if="validationErrors.hasOwnProperty('serverSlug')">{{
                                        validationErrors.serverSlug[0]
                                        }}
                                    </div>
                                </div>
                                <div class="form-group"
                                     v-bind:class="{'has-danger': validationErrors.hasOwnProperty('serverStatus')}">
                                    <label>Tình trạng</label>
                                    <select class="form-control" v-model="serverItem.serverStatus">
                                        <option value="">---</option>
                                        <option :value="serverStatusItem.value"
                                                v-for="serverStatusItem in serversStatusList"
                                        >{{ serverStatusItem.caption }}
                                        </option>
                                    </select>
                                    <div class="form-control-feedback"
                                         v-if="validationErrors.hasOwnProperty('serverStatus')">
                                        {{ validationErrors.serverStatus[0] }}
                                    </div>
                                </div>
                                <div class="form-group"
                                     v-bind:class="{'has-danger': validationErrors.hasOwnProperty('paymentStatus')}">
                                    <label>Tich hợp thanh toán</label>
                                    <select class="form-control" v-model="serverItem.paymentStatus">
                                        <option value="">---</option>
                                        <option :value="paymentStatusItem.value"
                                                v-for="paymentStatusItem in paymentStatusList"
                                        >{{ paymentStatusItem.caption }}
                                        </option>
                                    </select>
                                    <div class="form-control-feedback"
                                         v-if="validationErrors.hasOwnProperty('paymentStatus')">
                                        {{ validationErrors.paymentStatus[0] }}
                                    </div>
                                </div>
                                <div class="form-group"
                                     v-bind:class="{'has-danger': validationErrors.hasOwnProperty('keyWebCharge')}">
                                    <label for="keyWebCharge">Web charge key</label>
                                    <input :disabled="inProcessing" class="form-control" id="keyWebCharge"
                                           type="text"
                                           v-model="serverItem.keyWebCharge">
                                    <div class="form-control-feedback"
                                         v-if="validationErrors.hasOwnProperty('keyWebCharge')">{{
                                        validationErrors.keyWebCharge[0]
                                        }}
                                    </div>
                                </div>
                                <div class="form-group"
                                     v-bind:class="{'has-danger': validationErrors.hasOwnProperty('keyIAPCharge')}">
                                    <label for="keyIAPCharge">IAP charge key</label>
                                    <input :disabled="inProcessing" class="form-control" id="keyIAPCharge"
                                           type="text"
                                           v-model="serverItem.keyIAPCharge">
                                    <div class="form-control-feedback"
                                         v-if="validationErrors.hasOwnProperty('keyIAPCharge')">{{
                                        validationErrors.keyIAPCharge[0]
                                        }}
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="card-footer">
                            <div v-if="serverItem.id === undefined">
                                <button :disabled="!permissions.createServer"
                                        @click="createServer"
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
                                    <button :disabled="!permissions.updateServer"
                                            @click="updateServer"
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
                                    <button @click="resetServerItem"
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
                                <h4 class="card-title">Danh sách server</h4>
                            </div>
                        </div>
                        <div class="text-center" v-if="inFetching">
                            <in-fetching-component/>
                        </div>
                        <div v-else>
                            <div class="row">
                                <div class="col-12">
                                    <pagination-component :data="this.pagination" @reloadList="getPaginatedServersList"
                                                          v-if="this.pagination.totalPage > 1"/>
                                </div>
                            </div>
                            <div class="table-responsive m-t-20">
                                <table class="table table-hover">
                                    <thead>
                                    <tr>
                                        <th>
                                            Thông tin server
                                            <select class="select2 form-control custom-select"
                                                    style="width: 100%; height:36px;"
                                                    v-model="searchItem.gameId"
                                                    v-select2="searchItem.gameId"
                                            >
                                                <option value="">---</option>
                                                <option :value="gameItem.gameId"
                                                        v-for="gameItem in allGamesList"
                                                >{{ gameItem.gameName }}
                                                </option>
                                            </select>
                                        </th>
                                        <th>
                                            Tình trạng server
                                            <select class="form-control" v-model="searchItem.serverStatus">
                                                <option value="">---</option>
                                                <option :value="serverStatusItem.value"
                                                        v-for="serverStatusItem in serversStatusList"
                                                >{{ serverStatusItem.caption }}
                                                </option>
                                            </select>
                                        </th>
                                        <th>
                                            Tình trạng payment
                                            <select class="form-control" v-model="searchItem.paymentStatus">
                                                <option value="">---</option>
                                                <option :value="paymentStatusItem.value"
                                                        v-for="paymentStatusItem in paymentStatusList"
                                                >{{ paymentStatusItem.caption }}
                                                </option>
                                            </select>
                                        </th>
                                        <th>
                                            &nbsp;
                                            <button @click="resetSearchItem" class="btn btn-block btn-primary">Reset
                                            </button>
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="serverItem in serversList" v-show="serversList.length > 0">
                                        <td class="td-server-info">
                                            <h4 class="d-inline">{{ serverItem.serverName }}</h4>
                                            <span class="label label-primary">{{ serverItem.serverId }}</span>
                                            <p v-if="serverItem.keyWebCharge !== null">
                                                <button class="btn btn-sm btn-outline-info">Web charge: {{
                                                    serverItem.keyWebCharge }}
                                                </button>
                                            </p>
                                            <p v-if="serverItem.keyIAPCharge !== null">
                                                <button class="btn btn-sm btn-outline-success">IAP charge: {{
                                                    serverItem.keyIAPCharge }}
                                                </button>
                                            </p>
                                        </td>
                                        <td>
                                            <select @change="updateServerStatus($event, serverItem.id)"
                                                    class="form-control">
                                                <option :selected="statusItem.value === serverItem.serverStatus"
                                                        :value="statusItem.value"
                                                        v-for="statusItem in serversStatusList">{{
                                                    statusItem.caption }}
                                                </option>
                                            </select>
                                        </td>
                                        <td>
                                            <select @change="updateServerPaymentStatus($event, serverItem.id)"
                                                    class="form-control">
                                                <option :selected="paymentStatusItem.value === serverItem.paymentStatus"
                                                        :value="paymentStatusItem.value"
                                                        v-for="paymentStatusItem in paymentStatusList">
                                                    {{ paymentStatusItem.caption }}
                                                </option>
                                            </select>
                                        </td>
                                        <td>
                                            <div class="row">
                                                <div class="col-6">
                                                    <button :disabled="!permissions.updateServer"
                                                            @click="getServerInfo(serverItem.id)"
                                                            class="btn btn-sm btn-info btn-block"
                                                            type="button">Cập nhật
                                                    </button>
                                                </div>
                                                <div class="col-6">
                                                    <button
                                                        :disabled="!permissions.deleteServer"
                                                        @click="openConfirmDeletePopup(serverItem.id, serverItem.serverName)"
                                                        class="btn btn-sm btn-danger btn-block"
                                                        type="button"
                                                    >Xóa
                                                    </button>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr v-show="serversList.length === 0">
                                        <td colspan="4">
                                            <div class="row">
                                                <div class="col-12 text-center">
                                                    <h5>Không có dữ liệu</h5>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <pagination-component :data="this.pagination" @reloadList="getPaginatedServersList"
                                                          v-if="this.pagination.totalPage > 1"/>
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
    import slugtify from "../../../helpers/slugtify";
    import {initializeSelect2Plugin, resetSelect2Value} from "../../../helpers/select2";
    import {displayConfirmDelete, displayError} from "../../../helpers/swal";
    import serversService from "../../../services/ServersService";

    export default {
        name: "ServersComponent",
        data() {
            return {
                inLoading: false,
                inFetching: false,
                inProcessing: false,
                inGetting: false,
                validationErrors: {},
                pagination: {},
                permissions: {
                    createServer: this.$store.state.authentication.userInfo.role.permissions.includes('CreateServer'),
                    updateServer: this.$store.state.authentication.userInfo.role.permissions.includes('UpdateServer'),
                    deleteServer: this.$store.state.authentication.userInfo.role.permissions.includes('DeleteServer'),
                },
                serverItem: this.defineServerItem(),
                searchItem: this.defineSearchItem(),
                allGamesList: [],
                serversList: [],
                serversStatusList: [],
                paymentStatusList: [
                    {
                        caption: 'Có',
                        value: true
                    },
                    {
                        caption: 'Không',
                        value: false
                    }
                ]
            }
        },
        mounted() {
            // set inLoading mode
            this.inLoading = true;

            Promise.all([
                new Promise((resolve, reject) => {
                    // get server status list
                    if (this.$store.state.constants.ServerStatusList.length === 0) {
                        this.getServerStatusList()
                            .then(() => {
                                resolve();
                            });
                    } else {
                        // set server status list
                        this.serversStatusList = this.$store.state.constants.ServerStatusList;
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
                    // get servers list
                    if (this.$store.state.servers.serversList.length === 0) {
                        this.getPaginatedServersList(1)
                            .then(() => {
                                resolve();
                            });
                    } else {
                        // set servers list
                        this.serversList = this.$store.state.servers.serversList;
                        // set pagination
                        this.pagination = this.$store.state.servers.pagination;

                        resolve();
                    }
                })
            ]).finally(() => {
                // release inLoading mode
                this.inLoading = false;

                // initialize select2
                setTimeout(() => {
                    initializeSelect2Plugin();
                }, 50);
            });
        },
        watch: {
            'searchItem.gameId': function () {
                this.searchServerList();
            },
            'searchItem.serverStatus': function () {
                this.searchServerList();
            },
            'searchItem.paymentStatus': function () {
                this.searchServerList();
            }
        },
        methods: {
            /**
             * Define game item
             * @created 2020-02-20 LongTHK
             */
            defineServerItem() {
                return {
                    gameId: '',
                    serverId: '',
                    serverName: '',
                    serverSlug: '',
                    serverStatus: '',
                    paymentStatus: '',
                    keyWebCharge: '',
                    keyIAPCharge: ''
                }
            },

            /**
             * Define search item
             * @created 2020-02-21 LongTHK
             */
            defineSearchItem() {
                return {
                    gameId: '',
                    serverStatus: '',
                    paymentStatus: ''
                }
            },

            /**
             * On server name changed
             * @created 2020-02-20 LongTHK
             */
            onGameNameChanged() {
                this.serverItem.serverSlug = slugtify(this.serverItem.serverName);
            },

            /**
             * Get server status list
             * @created 2020-02-20 LongTHK
             */
            async getServerStatusList() {
                // set inFetching mode
                this.inFetching = true;

                // get server status list
                await this.$store.dispatch('getServerStatusList');
                // set payment list
                this.serversStatusList = this.$store.state.constants.ServerStatusList;

                // release inFetching mode
                this.inFetching = false;
            },

            /**
             * Get all games list
             * @created 2020-02-20 LongTHK
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
             * Create server
             * @created 2020-02-20 LongTHK
             */
            createServer() {
                // set inProcessing status
                this.inProcessing = true;
                // reset validation error
                this.validationErrors = {};

                // dispatch create user action
                this.$store.dispatch('createServer', {
                    serverItem: this.serverItem,
                    currentPage: 1
                })
                    .then(() => {
                        // set servers list
                        this.serversList = this.$store.state.servers.serversList;
                        // set pagination
                        this.pagination = this.$store.state.servers.pagination;
                        // reset server item
                        this.serverItem = this.defineServerItem();

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
             * Get server info
             * @param id
             * @created 2020-02-20 LongTHK
             */
            getServerInfo(id) {
                // set inGetting status
                this.inGetting = true;
                // reset validation error
                this.validationErrors = {};

                // call api
                serversService.getServerInfo(id)
                    .then((response) => {
                        // get response data
                        let responseData = response.data.data;
                        // set server item
                        this.serverItem = responseData;
                        // set game id
                        this.serverItem.gameId = responseData.game.gameId;

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
             * Update server
             * @created 2020-02-20 LongTHK
             */
            updateServer() {
                // set inProcessing status
                this.inProcessing = true;
                // reset validation error
                this.validationErrors = {};

                // dispatch update role action
                this.$store.dispatch('updateServer', {
                    serverItem: this.serverItem,
                    currentPage: this.pagination.currentPage
                })
                    .then(() => {
                        // set servers list
                        this.serversList = this.$store.state.servers.serversList;
                        // set pagination
                        this.pagination = this.$store.state.servers.pagination;
                        // reset server item
                        this.serverItem = this.defineServerItem();

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
             * Reset server item
             * @created 2020-02-20 LongTHK
             */
            resetServerItem() {
                this.serverItem = this.defineServerItem();
                // reset validation error
                this.validationErrors = {};
                // reset select customer and product
                resetSelect2Value();
            },

            /**
             * Get paginated servers list
             * @param pageNumber
             * @created 2020-02-20 LongTHK
             */
            async getPaginatedServersList(pageNumber) {
                // set inFetching mode
                this.inFetching = true;

                // dispatch get servers list
                await this.$store.dispatch('getPaginatedServersList', pageNumber);
                // set servers list
                this.serversList = this.$store.state.servers.serversList;
                // set pagination
                this.pagination = this.$store.state.servers.pagination;

                // release inFetching mode
                this.inFetching = false;
            },

            /**
             * Open delete game confirmation box
             * @param id
             * @param serverName
             * @created 2020-02-20 LongTHK
             */
            openConfirmDeletePopup(id, serverName) {
                // define display text
                let warningText = 'Xóa các thông tin có liên quan đến server này.';
                // display popup
                displayConfirmDelete(serverName, warningText, this.deleteServer.bind(this, id));
            },

            /**
             * Delete server
             * @param id
             * @created 2020-02-20 LongTHK
             */
            deleteServer(id) {
                // dispatch delete user action
                this.$store.dispatch('deleteServer', {
                    id: id,
                    currentPage: this.pagination.currentPage
                })
                    .then(() => {
                        // set servers list
                        this.serversList = this.$store.state.servers.serversList;
                        // set pagination
                        this.pagination = this.$store.state.servers.pagination;
                    })
                    .catch((error) => {
                        // display error dialog
                        displayError(error.response.status);
                    })
            },

            /**
             * Update server status
             * @param event
             * @param id
             * @created 2020-02-21 LongTHK
             */
            updateServerStatus(event, id) {
                // dispatch update role action
                this.$store.dispatch('updateServerStatus', {
                    id: id,
                    serverStatus: event.target.value,
                    currentPage: this.pagination.currentPage
                })
                    .then(() => {
                        // set servers list
                        this.serversList = this.$store.state.servers.serversList;
                        // set pagination
                        this.pagination = this.$store.state.servers.pagination;
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
            },

            /**
             * Update payment status
             * @param event
             * @param id
             * @created 2020-02-21 LongTHK
             */
            updateServerPaymentStatus(event, id) {
                // dispatch update role action
                this.$store.dispatch('updateServerPaymentStatus', {
                    id: id,
                    paymentStatus: event.target.value,
                    currentPage: this.pagination.currentPage
                })
                    .then(() => {
                        // set servers list
                        this.serversList = this.$store.state.servers.serversList;
                        // set pagination
                        this.pagination = this.$store.state.servers.pagination;
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
            },

            /**
             * Search server
             * @returns {Promise<void>}
             * @created 2020-02-21 LongTHK
             */
            async searchServerList() {
                // set inFetching mode
                this.inFetching = true;

                // dispatch get servers list
                if (this.searchItem.gameId === ''
                    && this.searchItem.serverStatus === ''
                    && this.searchItem.paymentStatus === '') {

                    // set servers list
                    this.serversList = this.$store.state.servers.serversList;
                    // set pagination
                    this.pagination = this.$store.state.servers.pagination;
                } else {
                    serversService.searchServerList(this.searchItem)
                        .then((response) => {
                            this.serversList = response.data.data;
                        });
                }

                // release inFetching mode
                this.inFetching = false;
            },

            /**
             * Reset search item
             * @created 2020-02-21 LongTHK
             */
            resetSearchItem() {
                // reset search item
                this.searchItem = this.defineSearchItem();
                // reset select2
                resetSelect2Value();
            }
        }
    }
</script>

<style lang="less">
    .td-server-info {
        p {
            margin-bottom: 5px
        }
    }
</style>
