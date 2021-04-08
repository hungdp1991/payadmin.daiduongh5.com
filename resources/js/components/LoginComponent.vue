<template>
    <div class="login-register" style="background-image:url(./images/login-register.jpg);">
        <div class="login-box card">
            <div class="card-body">
                <form class="form-horizontal form-material">
                    <h3 class="text-center m-b-20">Đăng nhập</h3>
                    <div class="form-group ">
                        <div class="col-xs-12">
                            <input class="form-control" placeholder="Tên đăng nhập" type="text" v-model="loginInfo.username"></div>
                    </div>
                    <div class="form-group">
                        <div class="col-xs-12">
                            <input class="form-control" placeholder="Mật khẩu" type="password"  v-model="loginInfo.password"></div>
                    </div>
                    <div class="form-group" v-show="processingStatus !== null && !processingStatus">
                        <div class="col-xs-12 text-center">
                            <div class="alert alert-danger">Đăng nhập không thành công</div>
                        </div>
                    </div>
                    <div class="form-group text-center">
                        <div class="col-xs-12 p-b-20">
                            <button class="btn btn-block btn-lg btn-info btn-rounded" type="button" @click="authenticate"
                                :disabled="inProcessing"
                            >
                                <span v-if="!inProcessing">Đăng nhập</span>
                                <span v-else><in-process-component/></span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "LoginComponent",
        data() {
            return {
                loginInfo: {
                    username: '',
                    password: ''
                },
                inProcessing: false,
                processingStatus: null
            }
        },
        watch: {
            'loginInfo.username': function () {
                this.loginInfo.username = this.loginInfo.username.trim().toLowerCase();
            }
        },
        methods: {
            /**
             * Call login
             * @returns {Promise<void>}
             */
            authenticate() {
                // set in processing mode
                this.inProcessing = true;
                this.processingStatus = null;

                // call authenticate
                this.$store.dispatch('authenticate', this.loginInfo)
                    .then(() => {
                        this.$router.replace({name: 'DashboardComponent'})
                    })
                    .catch(() => {
                        this.processingStatus = false;
                        this.inProcessing = false;
                    })
            }
        }
    }
</script>

<style scoped>
    @import "../../dist/css/pages/login-register-lock.css";
</style>
