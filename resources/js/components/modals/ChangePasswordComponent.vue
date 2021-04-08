<template>
    <div id="modalChangePassword" class="modal" tabindex="-1" role="dialog" aria-labelledby="vcenter" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="vcenter">Đổi mật khẩu</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <form class="form-material">
                        <div class="form-group" v-bind:class="{'has-danger': validationErrors.hasOwnProperty('oldPassword')}">
                            <label>Mật khẩu cũ</label>
                            <input class="form-control form-control-line" type="password" v-model="passwordItem.oldPassword">
                            <div class="form-control-feedback"
                                 v-if="validationErrors.hasOwnProperty('oldPassword')">{{
                                validationErrors.oldPassword[0] }}
                            </div>
                        </div>
                        <div class="form-group" v-bind:class="{'has-danger': validationErrors.hasOwnProperty('newPassword')}">
                            <label>Mật khẩu mới</label>
                            <input class="form-control form-control-line" type="password" v-model="passwordItem.newPassword">
                            <div class="form-control-feedback"
                                 v-if="validationErrors.hasOwnProperty('newPassword')">{{
                                validationErrors.newPassword[0] }}
                            </div>
                        </div>
                        <div class="form-group" v-bind:class="{'has-danger': validationErrors.hasOwnProperty('confirmedPassword')}">
                            <label>Xác nhận mật khẩu mới</label>
                            <input class="form-control form-control-line" type="password" v-model="passwordItem.confirmedPassword">
                            <div class="form-control-feedback"
                                 v-if="validationErrors.hasOwnProperty('confirmedPassword')">{{
                                validationErrors.confirmedPassword[0] }}
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <span class="text-success font-weight-normal" v-show="processingStatus !== null && processingStatus">Đổi mật khẩu thành công.</span>
                    <span class="text-danger font-weight-normal" v-show="processingStatus !== null && !processingStatus">Đổi mật khẩu không thành công.</span>
                    <button type="button" class="btn btn-success waves-effect" @click="changePassword">
                        <span v-if="!inProcessing">
                            Đổi mật khẩu
                        </span>
                        <span v-else>
                            <in-process-component/>
                        </span>
                    </button>
                    <button type="button" class="btn btn-info waves-effect" data-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    /**
     * Import api
     */
    import userService from '../../services/UserService';

    export default {
        name: "ChangePasswordComponent",
        data() {
            return {
                inProcessing: false,
                processingStatus: null,
                validationErrors: {},
                passwordItem: this.definePasswordItem()
            }
        },
        methods: {
            /**
             * Change password
             */
            changePassword() {
                // set processing mode
                this.inProcessing = true;
                // reset validation error
                this.validationErrors = {};
                // reset processing status
                this.processingStatus = null;

                // call api change password
                userService.changePassword(this.passwordItem)
                    .then(() => {
                        // set processing status
                        this.processingStatus = true;
                        // set password item
                        this.passwordItem = this.definePasswordItem()
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
                            this.processingStatus = false;
                        }
                    })
                    .finally(() => {
                        // release inProcessing status
                        this.inProcessing = false;
                    })
            },

            /**
             * Define password item
             * @returns {{confirmedPassword: string, oldPassword: string, newPassword: string}}
             */
            definePasswordItem() {
                return {
                    oldPassword: '',
                    newPassword: '',
                    confirmedPassword: ''
                }
            }
        }
    }
</script>

<style scoped>

</style>
