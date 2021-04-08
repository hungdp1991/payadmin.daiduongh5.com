<template>
    <div id="main-wrapper">
        <router-view/>
    </div>
</template>

<script>
    export default {
        name: "AppComponent"
    }
</script>

<style scoped>
@import "../../dist/plugins/select2/dist/css/select2.min.css";
@import "../../dist/plugins/sweetalert2/dist/sweetalert2.min.css";
@import "../../dist/plugins/nestable/nestable.css";
@import "../../dist/plugins/bootstrap-tagsinput/dist/bootstrap-tagsinput.css";
@import "../../dist/plugins/bootstrap-daterangepicker/daterangepicker.css";
@import "../../dist/plugins/bootstrap-switch/bootstrap-switch.min.css";
@import "../../dist/css/style.css";
</style>
