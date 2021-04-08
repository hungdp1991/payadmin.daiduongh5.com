<template>
    <ckeditor :config="editorConfig" :editor="editor" v-model="content"/>
</template>

<script>
    /**
     * Import CKEditor
     */
    import ClassicEditor from '@ckeditor/ckeditor5-editor-classic/src/classiceditor';
    import EssentialsPlugin from '@ckeditor/ckeditor5-essentials/src/essentials';
    import Font from '@ckeditor/ckeditor5-font/src/font';
    import BoldPlugin from '@ckeditor/ckeditor5-basic-styles/src/bold';
    import ItalicPlugin from '@ckeditor/ckeditor5-basic-styles/src/italic';
    import LinkPlugin from '@ckeditor/ckeditor5-link/src/link';
    import ListPlugin from '@ckeditor/ckeditor5-list/src/list'
    import HeadingPlugin from '@ckeditor/ckeditor5-heading/src/heading';
    import ParagraphPlugin from '@ckeditor/ckeditor5-paragraph/src/paragraph';
    import BlockQuotePlugin from '@ckeditor/ckeditor5-block-quote/src/blockquote';
    import CkFinderAdapter from '@ckeditor/ckeditor5-ckfinder/src/ckfinder';
    import Alignment from '@ckeditor/ckeditor5-alignment/src/alignment';
    import EasyImage from '@ckeditor/ckeditor5-easy-image/src/easyimage';
    import Image from '@ckeditor/ckeditor5-image/src/image';
    import ImageCaption from '@ckeditor/ckeditor5-image/src/imagecaption';
    import ImageStyle from '@ckeditor/ckeditor5-image/src/imagestyle';
    import ImageToolbar from '@ckeditor/ckeditor5-image/src/imagetoolbar';
    import ImageResize from '@ckeditor/ckeditor5-image/src/imageresize';

    export default {
        name: "CKEditor5Component",
        props: {
            defaultContent: String
        },
        watch: {
            content: function() {
                this.$emit('updateContent', this.content);
            }
        },
        data() {
            return {
                content: this.defaultContent !== '' ? this.defaultContent : '',
                editor: ClassicEditor,
                editorConfig: {
                    plugins: [
                        EssentialsPlugin,
                        Font,
                        BoldPlugin,
                        ItalicPlugin,
                        LinkPlugin,
                        HeadingPlugin,
                        ParagraphPlugin,
                        ListPlugin,
                        BlockQuotePlugin,
                        CkFinderAdapter,
                        Alignment,
                        EasyImage,
                        Image,
                        ImageCaption,
                        ImageStyle,
                        ImageToolbar,
                        ImageResize
                    ],

                    toolbar: {
                        items: [
                            'undo',
                            'redo',
                            '|',
                            'heading',
                            'fontFamily',
                            'fontSize',
                            'fontColor',
                            'fontBackgroundColor',
                            'bold',
                            'italic',
                            'blockQuote',
                            '|',
                            'alignment',
                            '|',
                            'bulletedList',
                            'numberedList',
                            '|',
                            'link',
                            'ckfinder'
                        ]
                    },
                    fontSize: {
                        options: [
                            9,
                            11,
                            13,
                            15,
                            17,
                            19,
                            21
                        ]
                    },
                    image: {
                        toolbar: [
                            'imageStyle:full',
                            'imageStyle:alignLeft',
                            'imageStyle:alignCenter',
                            'imageStyle:alignRight',
                            '|',
                            'imageTextAlternative'
                        ],
                        styles: [
                            'full',
                            'alignLeft',
                            'alignCenter',
                            'alignRight'
                        ]
                    },
                }
            }
        }
    }
</script>

<style scoped>

</style>
