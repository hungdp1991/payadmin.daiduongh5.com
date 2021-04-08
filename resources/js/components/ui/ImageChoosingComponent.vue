<template>
    <div class="form-group">
        <label>Hình</label>
        <div class="input-group mb-3">
            <input :value="filePath" class="form-control" id="postImage" readonly type="text">
            <div class="input-group-append">
                <button @click="openChoosingImageModal" class="btn btn-info waves-effect"
                        type="button">Chọn hình
                </button>
            </div>
        </div>
        <div class="text-center">
            <img :src="filePath"
                 class="col-4 img-thumbnail post-image">
        </div>
    </div>
</template>

<script>
    export default {
        name: "ImageChoosingComponent",
        props: {
            defaultFilePath: String
        },
        data() {
            return {
                filePath: (this.defaultFilePath === '') ? '' : this.defaultFilePath
            }
        },
        watch: {
            'defaultFilePath': function () {
                this.filePath = (this.defaultFilePath === '') ? '' : this.defaultFilePath
            }
        },
        methods: {
            /**
             * Open choosing file modal
             * @created 2020-02-17 LongTHK
             */
            openChoosingImageModal(event) {
                // define current component
                let currentComponent = this;

                // get current button
                let currentButton = $(event.target);

                CKFinder.modal({
                    language: 'en',
                    chooseFiles: true,
                    width: 800,
                    height: 600,
                    onInit: function (finder) {
                        finder.on('files:choose', function (event) {
                            currentComponent.setFileUrl(event, '', currentButton, currentComponent)
                        });
                        finder.on('file:choose:resizedImage', function (event) {
                            currentComponent.setFileUrl(event, 'resize', currentButton, currentComponent)
                        });
                    }
                });
            },

            /**
             * Set image url
             * @param event
             * @param mode
             * @param currentButton
             * @param currentComponent
             */
            setFileUrl(event, mode, currentButton, currentComponent) {
                // get current input filepath
                let currentInputFilePath = currentButton.parent().prev();
                // get current preview image
                let currentPreviewImage = currentButton.parent().parent().next().children('img');

                // define image file path
                let imageFilePath = '';

                // get file url
                let fileUrl = '';
                switch (mode) {
                    case 'resize':
                        fileUrl = event.data.resizedUrl;
                        break;
                    default:
                        fileUrl = event.data.files.first().getUrl();
                }

                // get image file path
                imageFilePath = fileUrl.split(window.location.hostname)[1];
                // set text of file path
                currentInputFilePath.val(imageFilePath);

                // set preview image
                currentPreviewImage.attr('src', imageFilePath);
                // update current component value
                currentComponent.filePath = imageFilePath;

                currentComponent.$emit('updateFilePath', this.filePath);
            }
        }
    }
</script>

<style scoped>

</style>
