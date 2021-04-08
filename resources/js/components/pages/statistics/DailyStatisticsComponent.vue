<template>
    <in-loading-page-component v-if="inLoading"/>
    <div class="container-fluid" v-else>
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h4 class="text-themecolor font-weight-bolder">Thống kê theo ngày</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card border-success">
                    <div class="card-body">
                        <form class="form-horizontal">
                            <div class="row">
                                <div class="col-3">
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
                                <div class="col-3">
                                    <div class="form-group">
                                        <label>Game</label>
                                        <select class="select2 form-control custom-select"
                                                style="width: 100%; height:36px;"
                                                v-model="statisticsItem.gameId"
                                                v-select2="statisticsItem.gameId">
                                            <option value="">---</option>
                                            <option :value="gameItem.gameAgent"
                                                    v-for="gameItem in allGamesList"
                                            >{{ gameItem.gameName }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label class="text-primary">Tổng tiền đã nạp vào game</label>
                                        <h1 class="text-center text-primary">{{ totalStatistics.totalAmount |
                                            numeral(0,0) }}</h1>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label class="text-info">Tổng gold đã nạp vào game</label>
                                        <h1 class="text-center text-info">{{ totalStatistics.totalGold | numeral(0,0)
                                            }}</h1>
                                    </div>
                                </div>
                            </div>
                        </form>
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
                                                <th>Ngày</th>
                                                <th>Tổng tiền nạp Web</th>
                                                <th>Tổng tiền nạp IAP</th>
                                                <th>Tổng tiền đã nạp</th>
                                                <th>Đã nạp vào game trên Web</th>
                                                <th>Đã nạp vào game IAP</th>
                                                <th>Tổng tiền đã nạp vào game</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr v-for="statisticsItem in statisticsList"
                                                v-show="statisticsList.length > 0">
                                                <td>{{ statisticsItem.date }}</td>
                                                <td class="text-right">{{ statisticsItem.amount | numeral(0,0) }}</td>
                                                <td class="text-right">{{ statisticsItem.amount_iap | numeral(0,0) }}
                                                </td>
                                                <td class="text-right">{{ statisticsItem.total_amount | numeral(0,0)
                                                    }}
                                                </td>
                                                <td class="text-right">{{ statisticsItem.gold | numeral(0,0) }}</td>
                                                <td class="text-right">{{ statisticsItem.gold_iap | numeral(0,0) }}</td>
                                                <td class="text-right">{{ statisticsItem.total_gold | numeral(0,0) }}
                                                </td>
                                            </tr>
                                            <tr v-show="statisticsList.length == 0">
                                                <td class="text-center" colspan="7">Không có dữ liệu</td>
                                            </tr>
                                            </tbody>
                                        </table>
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
    import {initializeSelect2Plugin} from "../../../helpers/select2";
    import {initializeDateRangePicker} from '../../../helpers/daterangepicker';
    import statisticsServices from '../../../services/StatisticsServices';

    export default {
        name: "DailyStatisticsComponent",
        data() {
            return {
                inLoading: false,
                inFetching: false,
                allGamesList: [],
                statisticsItem: {
                    timeRange: '',
                    gameId: '',
                },
                statisticsList: [],
                totalStatistics: {
                    totalAmount: 0,
                    totalGold: 0
                }
            }
        },
        mounted() {
            // set inLoading mode
            this.inLoading = true;

            // redirect to Dashboard in case of not have permission
            if (!this.$store.state.authentication.userInfo.role.permissions.includes('ViewDailyReport')) {
                this.$router.replace('/')
            }

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
                    // get statistics list
                    this.getStatisticList()
                        .then(() => {
                            resolve();
                        });
                })
            ]).finally(() => {
                // release inLoading mode
                this.inLoading = false;

                setTimeout(() => {
                    // initialize select2
                    initializeSelect2Plugin();
                    // init date range picker
                    initializeDateRangePicker($('#timerange'), this.changeStatisticsItemTimeRange.bind(this.$event));
                }, 50);
            });
        },
        watch: {
            'statisticsItem.timeRange': function () {
                this.getStatisticList();
            },
            'statisticsItem.gameId': function () {
                this.getStatisticList();
            },
            'statisticsList': function () {
                this.calculateTotal();
            }
        },
        methods: {
            /**
             * Get all games list
             * @created 2020-02-25 LongTHK
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
             * Get statistics list
             * @returns {Promise<void>}
             * @created 2020-02-25 LongTHk
             */
            async getStatisticList() {
                // get init statistics list
                this.inFetching = true;

                // call api
                await statisticsServices.getStatisticList(this.statisticsItem)
                    .then((response) => {
                        // set statistics data
                        this.statisticsList = response.data.statisticsList;
                    })
                    .finally(() => {
                        // release inProcessing status
                        this.inFetching = false;
                    })
            },

            /**
             * Change current statistics item time range
             * @created 2020-02-26 LongTHK
             */
            changeStatisticsItemTimeRange(event) {
                this.statisticsItem.timeRange = event.target.value;
            },

            /**
             * Calculate total
             * @created 2020-02-25 LongTHK
             */
            calculateTotal() {
                // define result
                let totalAmount = 0;
                let totalGold = 0;

                // process statistics list
                _.forEach(this.statisticsList, function (item) {
                    totalAmount += parseInt(item.total_amount);
                    totalGold += parseInt(item.total_gold);
                });

                // set result
                this.totalStatistics.totalAmount = totalAmount;
                this.totalStatistics.totalGold = totalGold;
            },
        }
    }
</script>

<style scoped>

</style>
