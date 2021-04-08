<template>
    <in-loading-page-component v-if="inLoading"/>
    <div class="container-fluid" v-else>
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h4 class="text-themecolor font-weight-bolder">Nhóm tài khoản</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <div class="sticky">
                    <div class="card border-success">
                        <div class="card-header"
                             v-bind:class="{'bg-success': roleItem.roleId === undefined, 'bg-info': roleItem.roleId !== undefined}">
                            <h4 class="m-b-0 text-white">Thông tin</h4>
                        </div>
                        <div class="card-body">
                            <div class="text-center" v-if="inGetting">
                                <in-getting-component :item-id="roleItem.roleId"/>
                            </div>
                            <form class="form-material" v-else>
                                <div class="form-group"
                                     v-bind:class="{'has-danger': validationErrors.hasOwnProperty('roleName')}">
                                    <label>Tên nhóm nhân viên</label>
                                    <input class="form-control form-control-line" type="text"
                                           v-model="roleItem.roleName">
                                    <div class="form-control-feedback"
                                         v-if="validationErrors.hasOwnProperty('roleName')">{{
                                        validationErrors.roleName[0] }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Cấp quyền</label>
                                    <div class="row">
                                        <div class="col-12" v-for="permission in permissionsList">
                                            <div class="row p-t-5 p-b-5 p-l-20 p-r-20 row-permission-item">
                                                <div class="col-4"><strong>{{ permission.title }}</strong></div>
                                                <div class="col-2 custom-control custom-checkbox"
                                                     v-for="permissionItem in permission.items">
                                                    <input :checked="roleItem.permissionsList.has(permissionItem.value)"
                                                           :id="'checkbox' + permissionItem.value"
                                                           :value="permissionItem.value"
                                                           @change="changePermissionsList"
                                                           class="custom-control-input"
                                                           type="checkbox"
                                                    >
                                                    <label :for="'checkbox' + permissionItem.value"
                                                           class="custom-control-label">{{
                                                        permissionItem.title }}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="card-footer">
                            <div v-if="roleItem.roleId === undefined">
                                <button :disabled="!permissions.createRole"
                                        @click="createRole"
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
                                    <button :disabled="!permissions.updateRole"
                                            @click="updateRole"
                                            class="btn btn-block btn-info waves-effect waves-light"
                                            type="button"
                                    >
                                        Cập nhật
                                        <span v-show="inProcessing">
                                            <in-process-component/>
                                        </span>
                                    </button>
                                </div>
                                <div class="col-4">
                                    <button @click="resetRoleItem"
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
            <div class="col-6">
                <div class="card border-success">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <h4 class="card-title">Danh sách nhóm</h4>
                            </div>
                        </div>
                        <div class="text-center" v-if="inFetching">
                            <in-fetching-component/>
                        </div>
                        <div v-else>
                            <div class="row">
                                <div class="col-12">
                                    <pagination-component :data="this.pagination" @reloadList="getPaginatedRolesList"
                                                          v-if="this.pagination.totalPage > 1"/>
                                </div>
                            </div>
                            <div class="table-responsive m-t-20">
                                <table class="table table-hover">
                                    <thead>
                                    <tr>
                                        <th>Tên nhóm</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="roleItem in rolesList">
                                        <td>{{ roleItem.roleName }}</td>
                                        <td>
                                            <div class="row">
                                                <div class="col-6">
                                                    <button :disabled="!permissions.updateRole"
                                                            @click="getRoleInfo(roleItem.roleId)"
                                                            class="btn btn-sm btn-info btn-block"
                                                            type="button">Cập nhật
                                                    </button>
                                                </div>
                                                <div class="col-6">
                                                    <button
                                                        :disabled="!roleItem.removable || !permissions.deleteRole || roleItem.usersCounter > 0"
                                                        @click="openConfirmDeletePopup(roleItem.roleId, roleItem.roleName)"
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
                                    <pagination-component :data="this.pagination" @reloadList="getPaginatedRolesList"
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
    import {displayError, displayConfirmDelete} from '../../../helpers/swal';
    /**
     * Import api
     */
    import rolesService from '../../../services/RolesService';

    export default {
        name: "RolesComponent",
        data() {
            return {
                inLoading: false,
                inFetching: false,
                inProcessing: false,
                inGetting: false,
                validationErrors: {},
                permissionsList: [],
                permissions: {
                    createRole: this.$store.state.authentication.userInfo.role.permissions.includes('CreateRole'),
                    updateRole: this.$store.state.authentication.userInfo.role.permissions.includes('UpdateRole'),
                    deleteRole: this.$store.state.authentication.userInfo.role.permissions.includes('DeleteRole'),
                },
                rolesList: [],
                pagination: {},
                roleItem: this.defineRoleItem(),
            }
        },
        async mounted() {
            // set inLoading mode
            this.inLoading = true;

            Promise.all([
                new Promise((resolve, reject) => {
                    // get permissions list
                    if (this.$store.state.roles.permissionsList.length === 0) {
                        // get permissions list
                        this.getPermissionsList()
                            .then(() => {
                                resolve();
                            });
                    } else {
                        // set permissions list
                        this.permissionsList = this.$store.state.roles.permissionsList;
                        resolve();
                    }
                }),

                new Promise((resolve, reject) => {
                    // get roles list
                    if (this.$store.state.roles.rolesList.length === 0) {
                        // get roles list
                        this.getPaginatedRolesList(1)
                            .then(() => {
                                resolve();
                            });
                    } else {
                        // set roles list
                        this.rolesList = this.$store.state.roles.rolesList;
                        // set pagination
                        this.pagination = this.$store.state.roles.pagination;

                        resolve();
                    }
                })
            ]).finally(() => {
                // release inLoading mode
                this.inLoading = false;
            });
        },
        methods: {
            /**
             * Define role item
             * @returns {{phoneNumber: string, fullName: string, email: string}}
             * created LongTHK 2020-02-12
             */
            defineRoleItem() {
                return {
                    roleName: '',
                    permissionsList: new Set()
                }
            },

            /**
             * Get permissions list
             * @returns {Promise<void>}
             * @created LongTHK 2020-02-12
             */
            async getPermissionsList() {
                // set inGetting mode
                this.inGetting = true;
                // dispatch get permissions list
                await this.$store.dispatch('getPermissionsList');
                // set permissions list
                this.permissionsList = this.$store.state.roles.permissionsList;
                // release inGetting mode
                this.inGetting = false;
            },

            /**
             * Get paginated roles list
             * @param pageNumber
             * @created LongTHK 2020-02-12
             */
            async getPaginatedRolesList(pageNumber) {
                // set inFetching mode
                this.inFetching = true;
                // dispatch get roles list
                await this.$store.dispatch('getPaginatedRolesList', pageNumber);
                // set roles list
                this.rolesList = this.$store.state.roles.rolesList;
                // set pagination
                this.pagination = this.$store.state.roles.pagination;
                // release inFetching mode
                this.inFetching = false;
            },

            /**
             * Create new role
             * @created LongTHK 2020-02-12
             */
            createRole() {
                // set inProcessing status
                this.inProcessing = true;
                // reset validation error
                this.validationErrors = {};

                // dispatch create role action
                this.$store.dispatch('createRole', {
                    roleItem: this.roleItem,
                    currentPage: 1
                })
                    .then(() => {
                        // reload roles list
                        this.rolesList = this.$store.state.roles.rolesList;
                        // reload pagination
                        this.pagination = this.$store.state.roles.pagination;
                        // rest customer item
                        this.roleItem = this.defineRoleItem();
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
             * Action on changing permission item
             * @param event
             * @created LongTHK 2020-02-12
             */
            changePermissionsList(event) {
                // get current checkbox
                let currentCheckbox = event.target;
                // get current checkbox value
                let currentCheckboxValue = currentCheckbox.value;

                // add or remove permission item
                if (currentCheckbox.checked) {
                    this.roleItem.permissionsList.add(currentCheckboxValue);
                } else {
                    this.roleItem.permissionsList.delete(currentCheckboxValue);
                }
            },

            /**
             * Open delete role confirmation box
             * @param roleId
             * @param roleName
             * @created LongTHK 2020-02-12
             */
            openConfirmDeletePopup(roleId, roleName) {
                // define display text
                let warningText = 'Xóa các thông tin có liên quan đến nhóm nhân viên này.';
                // display popup
                displayConfirmDelete(roleName, warningText, this.deleteRole.bind(this, roleId));
            },

            /**
             * Delete role
             * @created LongTHK 2020-02-12
             */
            deleteRole(roleId) {
                // dispatch delete role action
                this.$store.dispatch('deleteRole', {
                    roleId: roleId,
                    currentPage: this.pagination.currentPage
                })
                    .then(() => {
                        // reload roles list
                        this.rolesList = this.$store.state.roles.rolesList;
                        // reload pagination
                        this.pagination = this.$store.state.roles.rolesList;
                    })
                    .catch((error) => {
                        // display error dialog
                        displayError(error.response.status);
                    })
            },

            /**
             * Get role info
             * @param roleId
             * @created LongTHK 2020-02-12
             */
            getRoleInfo(roleId) {
                // set inGetting status
                this.inGetting = true;
                // reset validation error
                this.validationErrors = {};

                // call api
                rolesService.getRoleInfo(roleId)
                    .then((response) => {
                        // get response data
                        let responseData = response.data.data;
                        // set role item
                        this.roleItem = responseData;
                        // convert permissions list to set
                        this.roleItem.permissionsList = new Set(responseData.permissionsList);
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
             * Reset role item
             * @created LongTHK 2020-02-12
             */
            resetRoleItem() {
                this.roleItem = this.defineRoleItem();
                // reset validation error
                this.validationErrors = {};
            },

            /**
             * Update role
             * @created LongTHK 2020-02-12
             */
            updateRole() {
                // set inProcessing status
                this.inProcessing = true;
                // reset validation error
                this.validationErrors = {};

                // dispatch update role action
                this.$store.dispatch('updateRole', {
                    roleItem: this.roleItem,
                    currentPage: this.pagination.currentPage
                })
                    .then(() => {
                        // reload roles list
                        this.rolesList = this.$store.state.roles.rolesList;
                        // reload pagination
                        this.pagination = this.$store.state.roles.pagination;
                        // rest role item
                        this.roleItem = this.defineRoleItem();
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
        }
    }
</script>

<style scoped>

</style>
