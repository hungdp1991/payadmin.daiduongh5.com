<template>
    <div>
        <header-component/>
        <top-menu/>
        <div class="page-wrapper">
            <router-view/>
        </div>
        <change-password-component/>
        <compensation-component/>
        <footer-component/>
    </div>
</template>

<script>
    /**
     * Import template scripts
     */
    import SidebarMenuScript from '../../dist/js/sidebarmenu';
    import CustomScript from '../../dist/js/custom';

    /**
     * Import components
     */
    import HeaderComponent from "./block/HeaderComponent";
    import TopMenu from "./block/TopMenu";
    import ChangePasswordComponent from './modals/ChangePasswordComponent';
    import CompensationComponent from './modals/CompensationComponent';
    import FooterComponent from "./block/FooterComponent";

    export default {
        name: "MainComponent",
        components: {
            HeaderComponent,
            TopMenu,
            ChangePasswordComponent,
            CompensationComponent,
            FooterComponent
        },
        mounted() {
            // run template script
            SidebarMenuScript();
            CustomScript();
        }
    }
</script>

<style>
    /* Disable spin on input number */
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    input[type=number] {
        -moz-appearance:textfield;
    }
</style>
