<template>
    <span>
        <input :checked="status"
               data-off-color="warning" data-off-text="<i class='fas fa-lock'></i>"
               data-on-color="success" data-on-text="<i class='fas fa-lock-open'></i>"
               type="checkbox">
    </span>
</template>

<script>
    export default {
        name: "BootstrapSwitchComponent",
        props: {
            id: Number,
            status: Boolean
        },
        data() {
            return {
                inputBootstrapSwitch: null
            }
        },
        mounted() {
            // get current component
            let _this = this;
            // get bootstrap switch
            this.inputBootstrapSwitch = $(this.$el.childNodes[0]);

            // initialize bootstrap switch plugin
            this.inputBootstrapSwitch.bootstrapSwitch()
                .on('switchChange.bootstrapSwitch', function (event, state) {
                    console.log(state);
                    _this.$emit('updateStatus', _this.id, state)
                });
        }
    }
</script>

<style scoped>

</style>
