<template>
    <in-loading-page-component v-if="inLoading"/>
    <div class="container-fluid" v-else>
        <div class="row page-titles">
            <div class="col-md-6 align-self-center">
                <h4 class="text-themecolor font-weight-bolder">Lịch sử nạp game</h4>
            </div>
            <div class="col-md-6 align-self-center text-right">
                <div class="d-flex justify-content-end align-items-center">
                    <button :disabled="payHistoryList.length === 0 || inExporting || !permissions.exportPayHistory"
                            @click="exportPayHistoryExcel"
                            class="btn btn-success waves-effect waves-light" type="button">
                        <span v-if="!inExporting">
                                <i class="fa fa-file-excel"></i>&nbsp;&nbsp;Xuất file excel
                        </span>
                        <span v-else>
                            <in-process-component/>
                        </span>
                    </button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card border-success">
                    <div class="card-body">
                        <form class="form-horizontal">
                            <div class="row">
                                <div class="col-4">
                                    <div class="form-group">
                                        <label class="control-label">Ngày</label>
                                        <div class='input-group mb-3'>
                                            <input class="form-control" id="timerange" readonly type='text'/>
                                            <div class="input-group-append">
                                        <span class="input-group-text">
                                            <span class="ti-calendar"></span>
                                        </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="form-group">
                                        <label>Game</label>
                                        <select class="select2 form-control custom-select"
                                                style="width: 100%; height:36px;"
                                                v-model="payHistoryItem.gameId"
                                                v-select2="payHistoryItem.gameId">
                                            <option value="">---</option>
                                            <option :value="gameItem.gameAgent"
                                                    v-for="gameItem in allGamesList"
                                            >{{ gameItem.gameName }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="form-group">
                                        <label>Server</label>
                                        <select class="select2 form-control custom-select"
                                                style="width: 100%; height:36px;"
                                                v-model="payHistoryItem.serverId"
                                                v-select2="payHistoryItem.serverId">
                                            <option value="">---</option>
                                            <option :value="serverItem.serverId"
                                                    v-for="serverItem in allServersList"
                                            >{{ serverItem.serverName }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="form-group">
                                        <label>Mã giao dịch</label>
                                        <input class="form-control" type="text"
                                               v-model="payHistoryItem.transactionId"/>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="form-group">
                                        <label>Tình trạng</label>
                                        <select class="form-control" v-model="payHistoryItem.transactionStatus">
                                            <option value="">---</option>
                                            <option :value="transactionStatusItem.value"
                                                    v-for="transactionStatusItem in transactionStatusList"
                                            >{{ transactionStatusItem.caption }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-4">
                                    <div class="form-group">
                                        <label>Username</label>
                                        <input class="form-control" type="text" v-model="payHistoryItem.username"/>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="form-group">
                                        <label>Role name</label>
                                        <input class="form-control" type="text" v-model="payHistoryItem.roleName"/>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="form-group">
                                        <label>Loại thẻ</label>
                                        <select class="select2 form-control custom-select"
                                                style="width: 100%; height:36px;"
                                                v-model="payHistoryItem.cardType"
                                                v-select2="payHistoryItem.cardType">
                                            <option value="">---</option>
                                            <option :value="cardItem.cardType"
                                                    v-for="cardItem in allCardsList"
                                            >{{ cardItem.cardName }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="form-group">
                                        <label>Serial</label>
                                        <input class="form-control" type="text"
                                               v-model="payHistoryItem.cardSerial"/>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="form-group">
                                        <label>Code</label>
                                        <input class="form-control" type="text" v-model="payHistoryItem.cardCode"/>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <pagination-component :data="this.pagination" @reloadList="getPaginatedPayHistoryList"
                                              v-if="this.pagination.totalPage > 1"/>
                        <div class="row">
                            <div class="col-12">
                                <div class="text-center" v-if="inFetching">
                            <span aria-hidden="true" class="spinner-grow text-info" role="status"
                                  style="width: 3rem; height: 3rem;"/>
                                </div>
                                <div v-else>
                                    <div class="table-responsive m-t-20">
                                        <table class="table table-hover table-bordered">
                                            <thead class="text-center">
                                            <tr>
                                                <th>Mã giao dịch</th>
                                                <th>Username</th>
                                                <th>Role</th>
                                                <th>Game</th>
                                                <th>Server</th>
                                                <th>Loại thẻ</th>
                                                <th>Mệnh giá</th>
                                                <th>Duy đổi</th>
                                                <th>Mã lỗi</th>
                                                <th>Thông báo lỗi</th>
                                                <th>Mã nạp game</th>
                                                <th>Thông báo nạp game</th>
                                                <th>Thời gian</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr v-for="historyItem in payHistoryList"
                                                v-show="payHistoryList.length > 0">
                                                <td>{{ historyItem.transaction_id }}</td>
                                                <td class="text-center">{{ historyItem.username }}</td>
                                                <td class="text-center">{{ historyItem.role }}</td>
                                                <td class="text-center">{{ historyItem.product_id }}</td>
                                                <td class="text-center">{{ historyItem.server_id }}</td>
                                                <td class="text-center">{{ historyItem.card_type }}</td>
                                                <td class="text-right">{{ historyItem.amount | numeral(0,0) }}</td>
                                                <td class="text-right">{{ historyItem.gold | numeral(0,0) }}</td>
                                                <td class="text-center">{{ historyItem.card_status }}</td>
                                                <td>{{ historyItem.card_message }}</td>
                                                <td class="text-center">{{ historyItem.pay }}</td>
                                                <td></td>
                                                <td class="text-center">{{ historyItem.create_date }}</td>
                                            </tr>
                                            <tr v-show="payHistoryList.length == 0">
                                                <td class="text-center" colspan="13">Không có dữ liệu</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <pagination-component :data="this.pagination" @reloadList="getPaginatedPayHistoryList"
                                              v-if="this.pagination.totalPage > 1"/>
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
    import {initializeDateRangePicker} from "../../../helpers/daterangepicker";
    import {initializeSelect2Plugin} from "../../../helpers/select2";
    import statisticsServices from "../../../services/StatisticsServices";
    import {displayError} from "../../../helpers/swal";

    export default {
        name: "PayHistoryComponent",
        data() {
            return {
                inLoading: false,
                inFetching: false,
                inExporting: false,
                pagination: {},
                permissions: {
                    exportPayHistory: this.$store.state.authentication.userInfo.role.permissions.includes('ExportPayHistory'),
                },
                allGamesList: [],
                allServersList: [],
                allCardsList: [],
                transactionStatusList: [],
                payHistoryList: [],
                payHistoryItem: {
                    timeRange: '',
                    transactionStatus: '',
                    gameId: '',
                    serverId: '',
                    username: '',
                    roleName: '',
                    transactionId: '',
                    cardType: '',
                    cardSerial: '',
                    cardCode: ''
                }
            }
        },
        watch: {
            'payHistoryItem.timeRange': function () {
                this.getPaginatedPayHistoryList(1);
            },
            'payHistoryItem.transactionStatus': function () {
                this.getPaginatedPayHistoryList(1);
            },
            'payHistoryItem.gameId': function () {
                // get all servers belong to game
                if (this.payHistoryItem.gameId !== '') {
                    if (!this.$store.state.servers.allServersList.hasOwnProperty(this.payHistoryItem.gameId)) {
                        this.getServersListByGameAgent()
                            .then(() => {
                                initializeSelect2Plugin();
                            });
                    } else {
                        // set servers list
                        this.allServersList = this.$store.state.servers.allServersList[this.payHistoryItem.gameId];
                        initializeSelect2Plugin();
                    }
                } else {
                    // clear servers list
                    this.allServersList = [];
                }

                // reset server id
                this.payHistoryItem.serverId = '';
                this.getPaginatedPayHistoryList(1);
            },
            'payHistoryItem.serverId': function () {
                this.getPaginatedPayHistoryList(1);
            },
            'payHistoryItem.username': function () {
                this.getPaginatedPayHistoryList(1);
            },
            'payHistoryItem.roleName': function () {
                this.getPaginatedPayHistoryList(1);
            },
            'payHistoryItem.transactionId': function () {
                this.getPaginatedPayHistoryList(1);
            },
            'payHistoryItem.cardType': function () {
                this.getPaginatedPayHistoryList(1);
            },
            'payHistoryItem.cardSerial': function () {
                this.getPaginatedPayHistoryList(1);
            },
            'payHistoryItem.cardCode': function () {
                this.getPaginatedPayHistoryList(1);
            },
        },
        mounted() {
            // set inLoading mode
            this.inLoading = true;

            Promise.all([
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
                    // get transaction status list
                    if (this.$store.state.constants.TransactionStatusList.length === 0) {
                        this.getTransactionStatusList()
                        .then(() => {
                            resolve();
                        });
                    } else {
                        // set server status list
                        this.transactionStatusList = this.$store.state.constants.TransactionStatusList;
                        resolve();
                    }
                }),

                new Promise((resolve, reject) => {
                    // get all cards list
                    if (this.$store.state.cards.allCardsList.needReload) {
                        this.getAllCardsList()
                            .then(() => {
                                resolve();
                            })
                    } else {
                        // set all cards list
                        this.allCardsList = this.$store.state.cards.allCardsList.list;
                        resolve();
                    }
                }),

                new Promise((resolve, reject) => {
                    // get pay game history list
                    this.getPaginatedPayHistoryList(1)
                    .then(() => {
                        resolve();
                    });
                })
            ]).finally(() => {
                // release inLoading mode
                this.inLoading = false;

                setTimeout(() => {
                    // init date range picker
                    initializeDateRangePicker($('#timerange'), this.changeHistoryItemTimeRange.bind(this.$event));
                    // initialize select2
                    initializeSelect2Plugin();
                }, 50);
            });
        },
        methods: {
            /**
             * Change current history item time range
             * @param event
             * @created 2020-02-26 LongTHK
             */
            changeHistoryItemTimeRange(event) {
                this.payHistoryItem.timeRange = event.target.value;
            },

            /**
             * Get all games list
             * @created 2020-02-26 LongTHK
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
             * Get all severs list by game id
             * @created 2020-02-27 LongTHK
             */
            async getServersListByGameAgent() {
                // set inFetching mode
                this.inFetching = true;

                // get all servers list
                await this.$store.dispatch('getServersListByGameAgent', {
                    gameAgent: this.payHistoryItem.gameId
                });
                // set all servers list
                this.allServersList = this.$store.state.servers.allServersList[this.payHistoryItem.gameId];

                // release inFetching mode
                this.inFetching = false;
            },

            /**
             * Get all cards list
             * @created 2020-02-26 LongTHK
             */
            async getAllCardsList() {
                // set inFetching mode
                this.inFetching = true;

                // get all cards list
                await this.$store.dispatch('getAllCardsList');
                // set all cards list
                this.allCardsList = this.$store.state.cards.allCardsList.list;

                // release inFetching mode
                this.inFetching = false;
            },

            /**
             * Get transaction status list
             * @created 2020-02-26 LongTHK
             */
            async getTransactionStatusList() {
                // set inFetching mode
                this.inFetching = true;

                // get transaction status list
                await this.$store.dispatch('getTransactionStatusList');
                // set transaction status list
                this.transactionStatusList = this.$store.state.constants.TransactionStatusList;

                // release inFetching mode
                this.inFetching = false;
            },

            /**
             * Get pay game history list
             * @created 2020-02-27 LongTHK
             */
            async getPaginatedPayHistoryList(pageNumber) {
                // get init statistics list
                this.inFetching = true;

                // call api
                await statisticsServices.getPaginatedPayHistoryList(this.payHistoryItem, pageNumber)
                    .then((response) => {
                        // get response data
                        let responseData = response.data;
                        // set statistics data
                        this.payHistoryList = responseData.payHistoryList;
                        // set pagination
                        this.pagination = responseData.pagination;
                    })
                    .finally(() => {
                        // release inProcessing status
                        this.inFetching = false;
                    })
            },

            /**
             * Export excel file
             * @created 2020-02-27 LongTHK
             */
            exportPayHistoryExcel() {
                // set inExporting mode
                this.inExporting = true;

                statisticsServices.exportPayHistoryExcel(this.payHistoryItem)
                    .then((response) => {
                        // get url from response file
                        const url = window.URL.createObjectURL(new Blob([response.data]));

                        // create link html element
                        const link = document.createElement('a');
                        link.href = url;
                        link.setAttribute('download', 'Lich_su_nap_game.xlsx');
                        document.body.appendChild(link);
                        // trigger click on link
                        link.click();
                    })
                    .catch((error) => {
                        // display error popup
                        displayError(error.response.status);
                    })
                    .finally(() => {
                        this.inExporting = false;
                    })
            }
        }
    }
</script>

<style scoped>

</style>
