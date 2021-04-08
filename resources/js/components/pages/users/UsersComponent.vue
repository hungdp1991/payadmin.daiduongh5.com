<template>
    <in-loading-page-component v-if="inLoading"/>
    <div class="container-fluid" v-else>
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h4 class="text-themecolor font-weight-bolder">Tài khoản</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-3">
                <div class="sticky">
                    <div class="card border-success">
                        <div class="card-header"
                             v-bind:class="{'bg-success': userItem.userId === undefined, 'bg-info': userItem.userId !== undefined}">
                            <h4 class="m-b-0 text-white">Thông tin</h4>
                        </div>
                        <div class="card-body">
                            <div class="text-center" v-if="inGetting">
                                <in-getting-component :item-id="userItem.userId"/>
                            </div>
                            <form class="form-material" v-else>
                                <div class="form-group"
                                     v-bind:class="{'has-danger': validationErrors.hasOwnProperty('fullName')}">
                                    <label>Tên</label>
                                    <input class="form-control form-control-line" type="text" v-model="userItem.fullName">
                                    <div class="form-control-feedback"
                                         v-if="validationErrors.hasOwnProperty('fullName')">{{
                                        validationErrors.fullName[0] }}
                                    </div>
                                </div>
                                <div class="form-group"
                                     v-bind:class="{'has-danger': validationErrors.hasOwnProperty('username')}">
                                    <label>Tên đăng nhập</label>
                                    <input class="form-control form-control-line" type="text"
                                           v-model="userItem.username" :disabled="userItem.userId !== undefined">
                                    <div class="form-control-feedback"
                                         v-if="validationErrors.hasOwnProperty('username')">{{
                                        validationErrors.username[0] }}
                                    </div>
                                </div>
                                <div class="form-group"
                                     v-bind:class="{'has-danger': validationErrors.hasOwnProperty('password')}" v-if="userItem.userId === undefined">
                                    <label>Mật khẩu</label>
                                    <input class="form-control form-control-line" type="password"
                                           v-model="userItem.password" :disabled="userItem.userId !== undefined">
                                    <div class="form-control-feedback"
                                         v-if="validationErrors.hasOwnProperty('password')">{{
                                        validationErrors.password[0] }}
                                    </div>
                                </div>
                                <div class="form-group"
                                     v-bind:class="{'has-danger': validationErrors.hasOwnProperty('email')}">
                                    <label>Email</label>
                                    <input class="form-control form-control-line" type="text" v-model="userItem.email">
                                    <div class="form-control-feedback"
                                         v-if="validationErrors.hasOwnProperty('email')">{{
                                        validationErrors.email[0] }}
                                    </div>
                                </div>
                                <div class="form-group"
                                     v-bind:class="{'has-danger': validationErrors.hasOwnProperty('roleId')}">
                                    <label>Nhóm</label>
                                    <select class="select2 form-control custom-select" style="width: 100%; height:36px;"
                                            v-model="userItem.roleId" v-select2="userItem.roleId">
                                        <option value="">Chọn</option>
                                        <option :value="roleItem.roleId" v-for="roleItem in rolesList">{{
                                            roleItem.roleName }}
                                        </option>
                                    </select>
                                    <div class="form-control-feedback"
                                         v-if="validationErrors.hasOwnProperty('roleId')">{{
                                        validationErrors.roleId[0] }}
                                    </div>
                                </div>
                                <div class="form-group"
                                     v-bind:class="{'has-danger': validationErrors.hasOwnProperty('gamesList')}">
                                    <label>Game</label>
                                    <select class="select2 form-control custom-select" style="width: 100%; height:36px;"
                                            v-model="userItem.gamesList" v-select2="userItem.gamesList" multiple>
                                        <option :value="gameItem.gameId" v-for="gameItem in gamesList">{{
                                            gameItem.gameName }}
                                        </option>
                                    </select>
                                    <div class="form-control-feedback"
                                         v-if="validationErrors.hasOwnProperty('gamesList')">{{
                                        validationErrors.roleId[0] }}
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="card-footer">
                            <div v-if="userItem.userId === undefined">
                                <button @click="createUser"
                                        class="btn btn-block btn-success waves-effect waves-light"
                                        :disabled="!permissions.createUser"
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
                                    <button @click="updateUser"
                                            class="btn btn-block btn-info waves-effect waves-light"
                                            :disabled="!permissions.updateUser"
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
                                    <button @click="resetUserItem"
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
                                <h4 class="card-title">Danh sách tài khoản</h4>
                            </div>
                        </div>
                        <div class="text-center" v-if="inFetching">
                            <in-fetching-component/>
                        </div>
                        <div v-else>
                            <div class="row">
                                <div class="col-12">
                                    <pagination-component :data="this.pagination" @reloadList="getPaginatedUsersList"
                                                v-if="this.pagination.totalPage > 1"/>
                                </div>
                            </div>
                            <div class="table-responsive m-t-20">
                                <table class="table table-hover">
                                    <thead>
                                    <tr>
                                        <th>Tên đăng nhập</th>
                                        <th>Games</th>
                                        <th>Nhóm</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="userItem in usersList">
                                        <td>
                                            <span class="label-success label-status" v-if="userItem.status"></span>
                                            <span class="label-danger label-status" v-else></span>
                                            {{ userItem.username }}
                                        </td>
                                        <td>{{ stringifyGamesList(userItem.gamesList) }}</td>
                                        <td>{{ userItem.role.roleName }}</td>
                                        <td>
                                            <div class="row">
                                                <div class="col-4">
                                                    <button @click="getUserInfo(userItem.userId)"
                                                            class="btn btn-sm btn-info btn-block"
                                                            :disabled="!permissions.updateUser"
                                                            type="button">Cập nhật
                                                    </button>
                                                </div>
                                                <div class="col-4">
                                                    <button
                                                        :disabled="!userItem.removable || !permissions.deleteUser"
                                                        @click="openConfirmDeletePopup(userItem.userId, userItem.username)"
                                                        class="btn btn-sm btn-danger btn-block"
                                                        type="button"
                                                    >Xóa
                                                    </button>
                                                </div>
                                                <div class="col-4">
                                                    <button
                                                        class="btn btn-sm btn-warning btn-block"
                                                        :disabled="!permissions.updateUser"
                                                        @click="openConfirmResetPasswordPopup(userItem.userId, userItem.username)"
                                                        type="button">Reset mật khẩu
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
                                    <pagination-component :data="this.pagination" @reloadList="getPaginatedUsersList"
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
    import {displayConfirmDelete, displayError, displayConfirmPasswordReset, displayNewPassword} from "../../../helpers/swal";
    import {initializeSelect2Plugin, resetSelect2Value} from "../../../helpers/select2";
    import userService from '../../../services/UserService';

    export default {
        name: "UsersComponent",
        data() {
            return {
                inLoading: false,
                inFetching: false,
                inProcessing: false,
                inGetting: false,
                validationErrors: {},
                permissions: {
                    createUser: this.$store.state.authentication.userInfo.role.permissions.includes('CreateUser'),
                    updateUser: this.$store.state.authentication.userInfo.role.permissions.includes('UpdateUser'),
                    deleteUser: this.$store.state.authentication.userInfo.role.permissions.includes('DeleteUser'),
                },
                rolesList: [],
                gamesList: [],
                usersList: [],
                pagination: {},
                userItem: this.defineUserItem()
            }
        },
        mounted() {
            // set inLoading mode
            this.inLoading = true;

            Promise.all([
                new Promise((resolve, reject) => {
                    // check initial games list
                    if (this.$store.state.games.allGamesList.needReload) {
                        this.getAllGamesList()
                            .then(() => {
                                resolve();
                            });
                    } else {
                        this.gamesList = this.$store.state.games.allGamesList.list;
                        resolve();
                    }
                }),

                new Promise((resolve, reject) => {
                    // check initial roles list
                    if (this.$store.state.roles.allRolesList.needReload) {
                        this.getRolesList()
                            .then(() => {
                                resolve();
                            });
                    } else {
                        this.rolesList = this.$store.state.roles.allRolesList.list;
                        resolve();
                    }
                }),

                new Promise((resolve, reject) => {
                    // get users list
                    if (this.$store.state.users.usersList.length === 0) {
                        this.getPaginatedUsersList(1)
                        .then(() => {
                            resolve();
                        });
                    } else {
                        // set users list
                        this.usersList = this.$store.state.users.usersList;
                        // set pagination
                        this.pagination = this.$store.state.users.pagination;

                        resolve();
                    }
                }),
            ]).finally(() => {
                // release inLoading mode
                this.inLoading = false;
                setTimeout(function () {
                    initializeSelect2Plugin();
                }, 50);
            });

            // initialize sticky
            $(".sticky").stick_in_parent({
                offset_top: 100
            });
        },
        methods: {
            /**
             * Define user item
             * @returns {{password: string, roleId: string, username: string}}
             * @created LongTHK 2020-02-13
             */
            defineUserItem() {
                return {
                    fullName: '',
                    username: '',
                    password: '',
                    email: '',
                    roleId: '',
                    gamesList: []
                }
            },

            /**
             * Reset user item
             * @created LongTHK 2020-02-13
             */
            resetUserItem() {
                this.userItem = this.defineUserItem();
                // reset validation error
                this.validationErrors = {};
                // reset select customer and product
                resetSelect2Value();
            },

            /**
             * Get all games list
             * @created 2020-04-14 LongTHK
             */
            async getAllGamesList() {
                // set inGetting mode
                this.inGetting = true;
                // get games list
                await this.$store.dispatch('getAllGamesList');
                // set games list
                this.gamesList = this.$store.state.games.allGamesList.list;
                // release inGetting mode
                this.inGetting = false;
            },

            /**
             * Get roles list
             * @created 2020-02-13 LongTHK
             */
            async getRolesList() {
                // set inGetting mode
                this.inGetting = true;
                // get products list
                await this.$store.dispatch('getAllRolesList');
                // set roles list
                this.rolesList = this.$store.state.roles.allRolesList.list;
                // release inGetting mode
                this.inGetting = false;
            },

            /**
             * Get paginated users list
             * @param pageNumber
             * @created 2020-02-13 LongTHK
             */
            async getPaginatedUsersList(pageNumber) {
                // set inFetching mode
                this.inFetching = true;
                // dispatch get roles list
                await this.$store.dispatch('getPaginatedUsersList', pageNumber);
                // set users list
                this.usersList = this.$store.state.users.usersList;
                // set pagination
                this.pagination = this.$store.state.users.pagination;
                // release inFetching mode
                this.inFetching = false;
            },

            /**
             * Create new user
             * @created LongTHK 2020-02-13
             */
            createUser() {
                // set inProcessing status
                this.inProcessing = true;
                // reset validation error
                this.validationErrors = {};

                // dispatch create user action
                this.$store.dispatch('createUser', {
                    userItem: this.userItem,
                    currentPage: 1
                })
                    .then(() => {
                        // reload users list
                        this.usersList = this.$store.state.users.usersList;
                        // reload pagination
                        this.pagination = this.$store.state.users.pagination;
                        // reset user item
                        this.userItem = this.defineUserItem();

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
             * Open delete user confirmation box
             * @param userId
             * @param username
             * @created LongTHK 2020-02-13
             */
            openConfirmDeletePopup(userId, username) {
                // define display text
                let warningText = 'Xóa các thông tin có liên quan đến nhân viên này.';
                // display popup
                displayConfirmDelete(username, warningText, this.deleteUser.bind(this, userId));
            },

            /**
             * Delete user
             * @param userId
             * @created LongTHK 2020-02-13
             */
            deleteUser(userId) {
                // dispatch delete user action
                this.$store.dispatch('deleteUser', {
                    userId: userId,
                    currentPage: this.pagination.currentPage
                })
                    .then(() => {
                        // reload users list
                        this.usersList = this.$store.state.users.usersList;
                        // set pagination
                        this.pagination = this.$store.state.users.pagination;
                    })
                    .catch((error) => {
                        // display error dialog
                        displayError(error.response.status);
                    })
            },

            /**
             * Get user info
             * @param userId
             * @created LongTHK 2020-02-13
             */
            getUserInfo(userId) {
                // set inGetting status
                this.inGetting = true;
                // reset validation error
                this.validationErrors = {};

                // call api
                userService.getUserInfo(userId)
                    .then((response) => {
                        // get response data
                        let responseData = response.data.data;
                        // set role item
                        this.userItem = responseData;
                        // set user role id
                        this.userItem.roleId = responseData.role.roleId;

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
             * Update user
             * @created LongTHK 2020-02-13
             */
            updateUser() {
                // set inProcessing status
                this.inProcessing = true;
                // reset validation error
                this.validationErrors = {};

                // dispatch update role action
                this.$store.dispatch('updateUser', {
                    userItem: this.userItem,
                    currentPage: this.pagination.currentPage
                })
                    .then(() => {
                        // reload users list
                        this.usersList = this.$store.state.users.usersList;
                        // reload pagination
                        this.pagination = this.$store.state.users.pagination;
                        // reset user item
                        this.userItem = this.defineUserItem();

                        // reset select2
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
             * Open reset user password confirmation box
             * @param userId
             * @param username
             * @created LongTHK 2020-02-13
             */
            openConfirmResetPasswordPopup(userId, username) {
                // display popup
                displayConfirmPasswordReset(username, this.resetPassword.bind(this, userId, username));
            },

            /**
             * Reset user password
             * @param userId
             * @param username
             * @created LongTHK 2020-02-13
             */
            resetPassword(userId, username) {
                // call api
                userService.resetPassword(userId)
                    .then((response) => {
                        // display success popup
                        displayNewPassword(username, response.data);
                    })
                    .catch((error) => {
                        // display error dialog
                        displayError(error.response.status);
                    })
            },

            /**
             * Stringify games list
             * @param gamesList
             * @returns {string}
             * @created 2020-04-14 LongTHK
             */
            stringifyGamesList(gamesList) {
                // get current component
                let _this = this;

                if (gamesList.length > 0) {
                    let strGamesList = '';
                    _.map(gamesList, function(gameId) {
                        let gameInfo = _.find(_this.gamesList, function (item) {
                            return item.gameId === gameId;
                        });

                        if (gameInfo !== undefined) {
                            strGamesList += gameInfo.gameName + ' / '
                        }
                    });

                    return strGamesList;
                } else {
                    return '';
                }
            }
        }
    }
</script>

<style scoped>
    .label-status {
        width: 10px;
        height: 10px;
        display: inline-block;
        border-radius: 50%;
    }
</style>
               
