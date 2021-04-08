<template>
    <div id="modalArrangeGame" class="modal" tabindex="-1" role="dialog" aria-labelledby="vcenter" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="vcenter">S.xếp V.Trí game</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group" v-for="index in allGamesList.length">
                            <label>Vị trí {{ index }}</label>
                            <select class="select2 form-control custom-select" style="width: 100%; height:36px;"
                                    v-model="gamesOrderList[index - 1]" v-select2="gamesOrderList[index - 1]"
                            >
                                <option :value="gameItem.gameId" v-for="gameItem in allGamesList">{{ gameItem.gameName }}</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <span class="text-success font-weight-normal" v-show="processingStatus !== null && processingStatus">Cập nhật thành công.</span>
                    <span class="text-danger font-weight-normal" v-show="processingStatus !== null && !processingStatus">Cập nhật không thành công.</span>
                    <button type="button" class="btn btn-success waves-effect" @click="arrangeGamesList">
                        <span v-if="!inProcessing">
                            Lưu cài đặt
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
     * Import modules
     */
    import {initializeSelect2Plugin} from "../../helpers/select2";
    import gamesService from "../../services/GamesService";

    export default {
        name: "ArrangeGameComponent",
        props: {
            allGamesList: Array
        },
        data() {
            return {
                inProcessing: false,
                processingStatus: null,
                gamesOrderList: []
            }
        },
        methods: {
            arrangeGamesList() {
                // set processing mode
                this.inProcessing = true;
                // reset processing status
                this.processingStatus = null;

                // call api arrange games list
                gamesService.arrangeGamesList(this.gamesOrderList)
                    .then(() => {
                        // set processing status
                        this.processingStatus = true;
                    })
                    .catch(() => {
                        // set processing status
                        this.processingStatus = false;
                    })
                    .finally(() => {
                        // release inProcessing status
                        this.inProcessing = false;
                    })
            }
        },
        async mounted() {
            // bind action when hide modal
            $('#modalArrangeGame').on('hide.bs.modal', function () {
                this.processingStatus = null;
            }.bind(this));

            // get games order list
            this.gamesOrderList = _.map(this.allGamesList, 'gameId');
            setTimeout(() => {
                initializeSelect2Plugin();
            }, 500);
        }
    }
</script>

<style scoped>

</style>
