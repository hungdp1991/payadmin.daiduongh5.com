<template>
    <nav aria-label="...">
        <ul class="pagination justify-content-center">
            <li class="page-item" :class="{'disabled': data.currentPage === 1}">
                <a class="page-link" href="#" tabindex="-1" @click="reloadCustomersList(1)">
                    <i class="fa fa-arrow-left"></i>
                </a>
            </li>

            <li class="page-item" v-for="pageItem in this.pageItemsList" :class="{'active': pageItem === data.currentPage}">
                <a class="page-link" href="#" @click="reloadCustomersList(pageItem)">{{ pageItem }}</a>
            </li>

            <li class="page-item" :class="{'disabled': data.currentPage === data.totalPage}">
                <a class="page-link" href="#"  @click="reloadCustomersList(data.totalPage)">
                    <i class="fa fa-arrow-right"></i>
                </a>
            </li>
        </ul>
    </nav>
</template>

<script>
    /**
     * Define length of pagination items list
     */
    const pageItemsListLength = 7;

    export default {
        name: "PaginationComponent",
        props: {
            data: Object
        },
        async mounted() {
            // generate page items list
            this.pageItemsList = await this.generatePagesList();
        },
        data() {
            return {
                pageItemsList: []
            }
        },
        watch: {
            'data.currentPage': async function() {
                this.pageItemsList = await this.generatePagesList();
            }
        },
        methods: {
            /**
             * Generate page items list
             * @returns {Promise<Array>}
             */
            async generatePagesList() {
                // define result
                let resultList = [];
                // get total page
                let totalPage = this.data.totalPage;
                // get current page
                let currentPage = this.data.currentPage;

                // create page item list
                if (totalPage <= pageItemsListLength) {
                    for (let i = 1; i <= totalPage; i++) {
                        resultList.push(i);
                    }
                } else {
                    if (currentPage < Math.ceil(pageItemsListLength / 2)) {
                        resultList = await Array.from(Array(pageItemsListLength)).map((e, i) => i + 1);
                    } else if(currentPage >= (totalPage - Math.ceil(pageItemsListLength / 2))) {
                        for (let i = totalPage - pageItemsListLength; i <= totalPage; i++) {
                            resultList.push(i);
                        }
                    } else {
                        for (let i = currentPage - Math.floor(pageItemsListLength / 2); i <= currentPage + Math.floor(pageItemsListLength / 2); i++) {
                            resultList.push(i);
                        }
                    }
                }

                return resultList;
            },

            /**
             * Reload customers list
             * @param pageNumber
             */
            reloadCustomersList(pageNumber) {
                event.preventDefault();
                this.$emit('reloadList', pageNumber);
            }
        },
    }
</script>

<style scoped>

</style>
