<template>
    <transition name="side">
        <Side
            v-bind="imageSelected"
            @deleteImage="deleteImage(imageSelected)"
            v-if="showInfo && imageSelected"
        />
    </transition>
</template>
<script>
import Side from './Side';
import ConfirmationModal from './ConfirmationModal';
export default {
    components: {
        Side,
    },
    computed: {
        showInfo() {
            return this.$store.state.media.showInfo;
        },
        imageSelected: {
            get: function () {
                return this.$store.state.media.imageSelected;
            },
            set: function (newValue) {
                this.$store.commit('media/setImageSelected', newValue);
            },
        },
    },
    methods: {
        async deleteImage(image) {
            const confirm = await this.$modal.show(ConfirmationModal, {
                body: `
                        Supprimer l'image ${image.name}
                    `,
            });
            if (!confirm) {
                return;
            }
            await this.$store.dispatch('media/deleteItem', image.media[0].id);
            if (this.imageSelected && this.imageSelected.id === image.id) {
                this.imageSelected = null;
            }
        },
    },
};
</script>
<style scoped>
.side-enter-active,
.side-leave-active {
    transition: all 0.35s ease-in-out;
}
.side-leave,
.side-enter-to {
    opacity: 1;
}
.side-enter,
.side-leave-to {
    width: 0;
    opacity: 0;
}
</style>
