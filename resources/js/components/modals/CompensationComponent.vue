<template>
    <div aria-hidden="true" aria-labelledby="vcenter" class="modal" id="modalCompensation" role="dialog" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="vcenter">Xử lý đền bù</h4>
                    <button aria-hidden="true" class="close" data-dismiss="modal" type="button">×</button>
                </div>
                <div class="modal-body">
                    <form class="form">
                        <div :class="{'has-danger': playerInfo !== null && Object.keys(playerInfo).length === 0}"
                             class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="input-group">
                                        <input :disabled="inProcessing" @change="getPlayerInfo" class="form-control"
                                               placeholder="Nhập username" type="text" v-model="playerName">
                                        <div class="input-group-append">
                                            <button class="btn btn-info" type="button">
                                                <i class="fa fa-search"/>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="form-control-feedback"
                                         v-if="playerInfo !== null && Object.keys(playerInfo).length === 0">Không tìm
                                        thấy
                                        username
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-center" v-if="inGettingPlayerInfo">
                            <in-fetching-component/>
                        </div>
                        <div v-else>
                            <div v-if="playerInfo !== null && Object.keys(playerInfo).length > 0">
                                <div class="form-group">
                                    <label>Game</label>
                                    <select :disabled="inProcessing"
                                            class="select2 form-control custom-select"
                                            style="width: 100%; height:36px;"
                                            v-model="compensationItem.productAgent"
                                            v-select2="compensationItem.productAgent">
                                        <option value="">---</option>
                                        <option :value="gameItem.gameAgent"
                                                v-for="gameItem in gamesList"
                                        >{{ gameItem.gameName }}
                                        </option>
                                    </select>
                                </div>
                                <div v-if="compensationItem.productAgent !== ''">
                                    <div class="text-center" v-if="inChangingGame">
                                        <in-fetching-component/>
                                    </div>
                                    <div v-else>
                                        <div class="form-group">
                                            <label>Server</label>
                                            <select :disabled="inProcessing"
                                                    class="select2 form-control custom-select"
                                                    style="width: 100%; height:36px;"
                                                    v-model="compensationItem.serverId"
                                                    v-select2="compensationItem.serverId">
                                                <option value="">---</option>
                                                <option :value="serverItem.serverId"
                                                        v-for="serverItem in serversList"
                                                >{{ serverItem.serverName }}
                                                </option>
                                            </select>
                                        </div>
                                        <div v-if="compensationItem.serverId !== ''">
                                            <div class="text-center" v-if="inChangingServer">
                                                <in-fetching-component/>
                                            </div>
                                            <div v-else>
                                                <div class="form-group">
                                                    <label>Nhân vật</label>
                                                    <select :disabled="inProcessing"
                                                            class="select2 form-control custom-select"
                                                            style="width: 100%; height:36px;"
                                                            v-model="compensationItem.roleId"
                                                            v-select2="compensationItem.roleId">
                                                        <option value="">---</option>
                                                        <option :value="roleItem.roleId"
                                                                v-for="roleItem in rolesList"
                                                        >{{ roleItem.roleName }}
                                                        </option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>Gói nạp</label>
                                                    <select :disabled="inProcessing"
                                                            class="select2 form-control custom-select"
                                                            style="width: 100%; height:36px;"
                                                            v-model="compensationItem.goldId"
                                                            v-select2="compensationItem.goldId">
                                                        <option value="">---</option>
                                                        <option :value="goldItem.goldId"
                                                                v-for="goldItem in goldList"
                                                        >{{ goldItem.goldName }} ({{ goldItem.goldAmount | numeral(0,0)
                                                            }}
                                                            => {{
                                                            goldItem.goldValue | numeral(0,0) }})
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <span :class="{'text-success': processingStatus, 'text-danger': !processingStatus}"
                          class="text-center font-weight-bold">{{ processingMessage }}</span>
                    <button :disabled="buttonOffsetDisabledStatus || inProcessing" @click="compensate"
                            class="btn btn-success waves-effect" type="button">
                        <span v-if="!inProcessing">Đền bù</span>
                        <span v-else>
                            <in-process-component/>
                        </span>
                    </button>
                    <button class="btn btn-info waves-effect" data-dismiss="modal" type="button">Đóng</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    /**
     * Import helpers
     */
    import {initializeSelect2Plugin, resetSelect2Value} from "../../helpers/select2";
    import {convertObjectToQueryString} from "../../helpers/utils";
    /**
     * Import api
     */
    import goldService from "../../services/GoldService";

    export default {
        name: "CompensationComponent",
        data() {
            return {
                inProcessing: false,
                inGettingPlayerInfo: false,
                inChangingGame: false,
                inChangingServer: false,
                processingStatus: null,
                processingMessage: '',
                playerName: '',
                playerInfo: null,
                gamesList: [],
                serversList: [],
                rolesList: [],
                goldList: [],
                compensationItem: this.defineCompensationItem(),
                apiKey: process.env.MIX_API_KEY
            }
        },
        mounted() {
            Promise.all([
                new Promise((resolve, reject) => {
                    setTimeout(() => {
                        // get all games list
                        if (this.$store.state.games.allGamesList.needReload) {
                            this.getAllGamesList()
                                .then(() => {
                                    // set all games list
                                    this.gamesList = this.$store.state.games.allGamesList.list;
                                    resolve();
                                })
                        } else {
                            // set all games list
                            this.gamesList = this.$store.state.games.allGamesList.list;
                            resolve();
                        }
                    }, 2000);
                }),
            ]);

            // action after modal closed
            $('#modalCompensation').on('hidden.bs.modal', () => {
                // reset processing status
                this.processingStatus = null;
                // reset processing message
                this.processingMessage = '';
                // reset compensation item
                this.compensationItem = this.defineCompensationItem();
                // reset player name
                this.playerName = '';
                // reset player info
                this.playerInfo = null;
                // rest select2 plugin
                resetSelect2Value();
            })
        },
        watch: {
            'compensationItem.productAgent': function () {
                if (this.compensationItem.gameId !== '') {
                    // set inChanging game item mode
                    this.inChangingGame = true;

                    Promise.all([
                        // get all servers belong to game
                        new Promise((resolve, reject) => {
                            if (!this.$store.state.servers.allServersList.hasOwnProperty(this.compensationItem.productAgent)) {
                                this.$store.dispatch('getServersListByGameAgent', {
                                    gameAgent: this.compensationItem.productAgent
                                }).then(() => {
                                    // set all servers list
                                    this.serversList = this.$store.state.servers.allServersList[this.compensationItem.productAgent];
                                    resolve();
                                })
                            } else {
                                // set servers list
                                this.serversList = this.$store.state.servers.allServersList[this.compensationItem.productAgent];
                                resolve();
                            }
                        }),

                        // get all gold belong to game
                        new Promise((resolve, reject) => {
                            goldService.getGoldListByGameAgent(this.compensationItem.productAgent)
                                .then((response) => {
                                    this.goldList = response.data.data;
                                    resolve();
                                })
                        })
                    ]).finally(() => {
                        // release inChanging game item mode
                        this.inChangingGame = false;

                        // reset compensation item
                        this.compensationItem.serverId = '';
                        this.compensationItem.goldId = '';
                        this.compensationItem.roleId = '';

                        setTimeout(() => {
                            initializeSelect2Plugin();
                        }, 50)
                    })
                } else {
                    // clear servers list
                    this.serversList = [];
                    // clear gold list
                    this.goldList = [];
                }
            },

            'compensationItem.serverId': function () {
                // set inChanging server item mode
                this.inChangingServer = true;

                if (this.compensationItem.serverId !== '') {
                    // define query object
                    let queryObject = {
                        server_id: this.compensationItem.serverId,
                        id_user: this.compensationItem.userId,
                        productAgent: this.compensationItem.productAgent,
                        sign: md5(this.compensationItem.userId + process.env.MIX_API_JWT_TOKEN)
                    };

                    // call api
                    axios.get(process.env.MIX_API_GET_ROLE + '?' + convertObjectToQueryString(queryObject))
                        .then((response) => {
                            // get return data
                            let data = response.data;

                            if (data.status) {
                                // set roles list
                                this.rolesList = data.data.role;

                                setTimeout(function () {
                                    initializeSelect2Plugin();
                                }, 50);
                            } else {
                                this.rolesList = [];
                            }
                        })
                        .finally(() => {
                            // release inChanging server item mode
                            this.inChangingServer = false;

                            // reset compensation item
                            this.compensationItem.goldId = '';
                            this.compensationItem.roleId = '';
                        })
                }
            },

            'compensationItem.roleId': function () {
                // get current role id
                let roleId = this.compensationItem.roleId;

                // get current role item
                if (roleId === '') {
                    this.compensationItem.roleName = '';
                } else {
                    this.compensationItem.roleName = _.find(this.rolesList, function (roleItem) {
                        return roleItem.roleId === roleId;
                    }).roleName;
                }
            }
        },
        computed: {
            /**
             * Compute button offset disabled status
             */
            buttonOffsetDisabledStatus: function () {
                // define button disabled status
                let disabledStatus = false;
                _.some(this.compensationItem, (itemValue) => {
                    if (itemValue === '') {
                        disabledStatus = true;
                        return true;
                    }
                });

                return disabledStatus;
            }
        },
        methods: {
            /**
             * Define compensation item
             * @returns {{playerName: null}}
             * @created 2020-04-22 LongTHK
             */
            defineCompensationItem() {
                return {
                    userId: '',
                    username: '',
                    roleId: '',
                    roleName: '',
                    productAgent: '',
                    serverId: '',
                    goldId: ''
                }
            },

            /**
             * Get player info
             * @created 2020-04-22 LongTHK
             */
            getPlayerInfo() {
                // set inGettingPlayerInfo mode
                this.inGettingPlayerInfo = true;

                if (this.playerName !== '') {
                    // reset compensation item
                    this.compensationItem = this.defineCompensationItem();
                    // reset player info
                    this.playerInfo = null;
                    // rest select2 plugin
                    resetSelect2Value();

                    // call api
                    axios.get(process.env.MIX_API_GET_PLAYER_INFO + '?username=' + this.playerName)
                        .then((response) => {
                            // get return data
                            let data = response.data;
                            if (data.status) {
                                // get player info
                                let playerInfo = data.data.user;

                                // set player info
                                this.playerInfo = playerInfo;
                                // set player id, player name
                                this.compensationItem.userId = playerInfo.id;
                                this.compensationItem.username = playerInfo.username;

                                setTimeout(function () {
                                    initializeSelect2Plugin();
                                }, 50);
                            } else {
                                // reset player info
                                this.playerInfo = {}
                            }
                        })
                        .finally(() => {
                            // release inGettingPlayerInfo mode
                            this.inGettingPlayerInfo = false;
                        })
                } else {
                    // rest player info
                    this.playerInfo = null;
                    // reset compensation item
                    this.compensationItem = this.defineCompensationItem();
                    // release inGettingPlayerInfo mode
                    this.inGettingPlayerInfo = false;
                }
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

                // release inFetching mode
                this.inFetching = false;
            },

            /**
             * Compensate
             * @created 2020-04-28 LongTHK
             */
            compensate() {
                // set inProcessing mode
                this.inProcessing = true;
                // reset processing status
                this.processingStatus = null;
                // reset processing message
                this.processingMessage = '';

                // get compensation item
                const compensationItem = {
                    ...this.compensationItem,
                    time: Math.floor(new Date().getTime() / 1000)
                };

                // call api
                axios.post('/admin-api/refund', {
                    compensationItem: JSON.stringify(compensationItem)
                })
                    .then((response) => {
                        // get return data
                        let returnData = response.data;
                        // get message
                        this.processingMessage = returnData.messages;

                        if (returnData.status) {
                            // reset compensation item
                            this.compensationItem = this.defineCompensationItem();
                            // reset player name
                            this.playerName = '';
                            // reset player info
                            this.playerInfo = null;
                            // set processing status
                            this.processingStatus = true;
                        } else {
                            // set processing status
                            this.processingStatus = false;
                        }
                    })
                    .catch(() => {
                        // set processing message
                        this.processingMessage = 'Đền bù không thành công';
                        // set processing status
                        this.processingStatus = false;
                    })
                    .finally(() => {
                        this.inProcessing = false;
                    })
            }
        }
    }
</script>

<style scoped>

</style>
