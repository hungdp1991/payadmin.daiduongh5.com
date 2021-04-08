<template>
    <span aria-hidden="true" class="spinner-grow"
          role="status"
          style="width: 3rem; height: 3rem;"
          v-bind:class="{'text-success': itemId === undefined, 'text-info': itemId !== undefined}"/>
</template>

<script>
    export default {
        name: "InGettingComponent",
        props: ['itemId']

    }
</script>

<style scoped>

</style>
