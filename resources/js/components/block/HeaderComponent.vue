<template>
    <header class="topbar">
        <nav class="navbar top-navbar navbar-expand-md navbar-dark">
            <div class="navbar-header">
                <a class="navbar-brand" href="">
                    <b>
                        <img alt="homepage" class="dark-logo" src="/images/logo-icon.png"/>
                        <img alt="homepage" class="light-logo" src="/images/logo-light-icon.png"/>
                    </b>
                    <span class="hidden-sm-down">
                        <img alt="homepage" class="dark-logo" src="/images/logo-text.png"/>
                        <img alt="homepage" class="light-logo" src="/images/logo-light-text.png"/>
                    </span>
                </a>
            </div>
            <div class="navbar-collapse">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link nav-toggler d-block d-md-none waves-effect waves-dark"
                           href="javascript:void(0)"><i class="ti-menu"></i></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link sidebartoggler d-none waves-effect waves-dark" href="javascript:void(0)">
                            <i class="icon-menu"></i>
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav my-lg-0">
                    <li class="nav-item dropdown u-pro">
                        <a aria-expanded="false" aria-haspopup="true"
                           class="nav-link dropdown-toggle waves-effect waves-dark profile-pic" data-toggle="dropdown"
                           href="">
                            <i class="ti-user"></i>
                            <span class="hidden-md-down">{{ this.$store.state.authentication.userInfo.username }} &nbsp;<i
                                class="fa fa-angle-down"></i></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right animated flipInY">
                            <a class="dropdown-item" data-target="#modalChangePassword" data-toggle="modal"
                               href="javascript:void(0)"><i class="ti-key"></i> Đổi mật khẩu</a>
                            <div class="dropdown-divider"></div>
                            <a @click="logout" class="dropdown-item" href="#"><i class="fa fa-power-off"></i> Logout</a>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
</template>

<script>
    export default {
        name: "HeaderComponent",
        methods: {
            logout(event) {
                // prevent default
                event.preventDefault();
                // call logout
                this.$store.dispatch('logout')
                    .then(() => {
                        window.location.reload();
                    })
                    .catch(() => {
                    });
            }
        }
    }
</script>

<style scoped>

</style>
