<template>
    <aside class="left-sidebar">
        <div class="scroll-sidebar">
            <nav class="sidebar-nav">
                <ul id="sidebarnav">
                    <li v-if="permissions.viewDailyReport">
                        <router-link class="has-arrow waves-effect waves-dark" aria-expanded="false" :to="{name: 'DashboardComponent'}">
                            <i class="icon-speedometer"></i>
                            <span class="hide-menu"> Dashboard</span>
                        </router-link>
                    </li>
                    <li v-if="permissions.viewRoles || permissions.viewUsers">
                        <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                            <i class="icon-user"></i>
                            <span class="hide-menu"> Tài khoản</span>
                        </a>
                        <ul aria-expanded="false" class="collapse">
                            <li v-if="permissions.viewRoles">
                                <router-link :to="{name: 'RolesComponent'}">Nhóm tài khoản</router-link>
                            </li>
                            <li v-if="permissions.viewUsers">
                                <router-link :to="{name: 'UsersComponent'}">Tài khoản</router-link>
                            </li>
                        </ul>
                    </li>
                    <li v-if="permissions.viewCategories || permissions.viewPosts">
                        <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                            <i class="icon-pencil"></i>
                            <span class="hide-menu"> Bài viết</span>
                        </a>
                        <ul aria-expanded="false" class="collapse">
                            <li v-if="permissions.viewCategories">
                                <router-link :to="{name: 'CategoriesComponent'}">Chuyên mục</router-link>
                            </li>
                            <li v-if="permissions.viewPosts">
                                <router-link :to="{name: 'PostsListComponent'}">Bài viết</router-link>
                            </li>
                        </ul>
                    </li>
                    <li v-if="permissions.viewGames || permissions.viewServers">
                        <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                            <i class="icon-game-controller"></i>
                            <span class="hide-menu"> Game</span>
                        </a>
                        <ul aria-expanded="false" class="collapse">
                            <li v-if="permissions.viewGames">
                                <router-link :to="{name: 'GamesComponent'}">Game</router-link>
                            </li>
                            <li v-if="permissions.viewServers">
                                <router-link :to="{name: 'ServersComponent'}">Server</router-link>
                            </li>
                        </ul>
                    </li>
                    <li v-if="permissions.viewCards || permissions.viewGold">
                        <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                            <i class="icon-credit-card"></i>
                            <span class="hide-menu"> Thẻ</span>
                        </a>
                        <ul aria-expanded="false" class="collapse">
                            <li v-if="permissions.viewCards">
                                <router-link :to="{name: 'CardsComponent'}">Loại thẻ</router-link>
                            </li>
                            <li v-if="permissions.viewGold">
                                <router-link :to="{name: 'GoldComponent'}">Gói nạp</router-link>
                            </li>
                        </ul>
                    </li>
                    <li v-if="permissions.viewSliders">
                        <router-link class="has-arrow waves-effect waves-dark" aria-expanded="false" :to="{name: 'SlidersComponent'}">
                            <i class="icon-control-play"></i>
                            <span class="hide-menu"> Sliders / Banners</span>
                        </router-link>
                    </li>
                    <li v-if="permissions.viewDailyReport || permissions.viewCardHistory || permissions.viewPayHistory || permissions.viewIAPHistory">
                        <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                            <i class="icon-chart"></i>
                            <span class="hide-menu"> Thống kê</span>
                        </a>
                        <ul aria-expanded="false" class="collapse">
                            <li v-if="permissions.viewDailyReport">
                                <router-link :to="{name: 'DailyStatisticsComponent'}">Thống kê theo ngày</router-link>
                            </li>
                            <li v-if="permissions.viewCardHistory">
                                <router-link :to="{name: 'CardHistoryComponent'}">Lịch sử nạp thẻ</router-link>
                            </li>
                            <li v-if="permissions.viewPayHistory">
                                <router-link :to="{name: 'PayHistoryComponent'}">Lịch sử nạp game</router-link>
                            </li>
                            <li v-if="permissions.viewIAPHistory">
                                <router-link :to="{name: 'IAPHistoryComponent'}">Lịch sử nạp IAP</router-link>
                            </li>
                            <li>
                                <a data-target="#modalCompensation" data-toggle="modal" href="javascript:void(0)">Xử lý đền bù</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>
</template>

<script>
    export default {
        name: "TopMenu",
        data() {
            return {
                permissions: {
                    viewRoles: this.$store.state.authentication.userInfo.role.permissions.includes('ViewRoles'),
                    viewUsers: this.$store.state.authentication.userInfo.role.permissions.includes('ViewUsers'),
                    viewCategories: this.$store.state.authentication.userInfo.role.permissions.includes('ViewCategories'),
                    viewPosts: this.$store.state.authentication.userInfo.role.permissions.includes('ViewPosts'),
                    viewGames: this.$store.state.authentication.userInfo.role.permissions.includes('ViewGames'),
                    viewServers: this.$store.state.authentication.userInfo.role.permissions.includes('ViewServers'),
                    viewCards: this.$store.state.authentication.userInfo.role.permissions.includes('ViewCards'),
                    viewGold: this.$store.state.authentication.userInfo.role.permissions.includes('ViewGold'),
                    viewSliders: this.$store.state.authentication.userInfo.role.permissions.includes('ViewSliders'),
                    viewDailyReport: this.$store.state.authentication.userInfo.role.permissions.includes('ViewDailyReport'),
                    viewCardHistory: this.$store.state.authentication.userInfo.role.permissions.includes('ViewCardHistory'),
                    viewPayHistory: this.$store.state.authentication.userInfo.role.permissions.includes('ViewPayHistory'),
                    viewIAPHistory: this.$store.state.authentication.userInfo.role.permissions.includes('ViewIAPHistory')
                },
            }
        }
    }
</script>

<style scoped>

</style>
