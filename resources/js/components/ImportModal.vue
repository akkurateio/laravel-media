<template>
    <div class="akk-modal import-modal">
        <div @click="close(false)" class="icon-box close-button">
            <Icon name="Close" />
        </div>
        <Import @done="close" />
    </div>
</template>
<script>
import Import from './Import';

export default {
    components: {
        Import,
    },
    methods: {
        close() {
            if (this.uploading) {
                return;
            }
            this.$emit('close');
        },
    },
    computed: {
        uploading: {
            get() {
                return this.$store.state.media.uploading;
            },
            set(value) {
                this.$store.commit('media/setUploading', value);
            },
        },
    },
};
</script>
<style lang="scss" scoped>
.import-modal {
    position: relative;
    max-width: 680px;
    .close-button {
        position: absolute;
        top: 0;
        right: 0;
        cursor: pointer;
    }
}
</style>
