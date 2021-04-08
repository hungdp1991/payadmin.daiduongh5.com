<template>
    <div class="container-fluid">

    </div>
</template>

<script>
    export default {
        name: "DashboardComponent",
        mounted() {
            // redirect to Daily Statistics in case of have permission
            if (this.$store.state.authentication.userInfo.role.permissions.includes('ViewDailyReport')) {
                this.$router.push({name: 'DailyStatisticsComponent'})
            }
        }
    }
</script>

<style scoped>

</style>
