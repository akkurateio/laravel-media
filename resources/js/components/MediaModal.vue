<template>
    <div class="container-modal">
        <div class="header-modal">
            <div>Mediathèque</div>
            <div @click="close(false)" style="fill: white" class="icon-box">
                <Icon name="Close" />
            </div>
        </div>
        <div class="body-modal">
            <div class="content-body">
                <div class="left">
                    <div>
                        <HeaderBody />
                        <template v-if="tabSelected === FILES">
                            <GalleryMedia @close="close(imageSelected)" />
                        </template>
                        <div
                            v-show="tabSelected === IMPORT && 'selected'"
                            style="
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                height: calc(100% - 64px);
                            "
                        >
                            <div
                                v-if="loadingUpload"
                                class="loader d-flex justify-content-center align-items-center w-100 h-100"
                            >
                                <div
                                    class="spinner-border upload"
                                    style="color: var(--primary)"
                                    role="status"
                                >
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>
                            <Import v-if="!loadingUpload" />
                        </div>
                    </div>
                </div>
                <transition name="side">
                    <Side
                        v-bind="imageSelected"
                        @delete-image="deleteImage(imageSelected)"
                        v-if="showInfo && tabSelected === FILES && 'selected'"
                    />
                </transition>
            </div>
        </div>
        <div class="footer-modal">
            <button
                @click="close(false)"
                :disabled="uploading"
                class="btn btn-lg btn-outline-secondary text-sm font-medium"
            >
                Annuler
            </button>
            <div style="flex: 1; display: flex; justify-content: center">
                <div
                    class="font-medium text-xs"
                    style="display: flex; align-items: center"
                    v-if="loading"
                >
                    <div
                        style="
                            width: 1rem;
                            height: 1rem;
                            border: 0.15em solid currentColor;
                            border-right-color: transparent;
                        "
                        class="spinner-border mr-2"
                        role="status"
                    >
                        <span class="sr-only">Loading...</span>
                    </div>
                    Chargement en cours...
                </div>
            </div>
            <button
                @click="close(imageSelected)"
                :disabled="uploading"
                class="btn btn-lg btn-primary text-sm font-medium"
            >
                Importer la sélection
            </button>
        </div>
    </div>
</template>
<script>
import GalleryMedia from './GalleryMedia';
import HeaderBody from './HeaderBody';
import Side from './Side';
import Import from './Import';
import moment from 'moment';
import { FILES, IMPORT, GRID, LIST } from '../constant';
import ConfirmationModal from './ConfirmationModal';
export default {
    data() {
        return {
            loadingUpload: false,
            FILES,
            IMPORT,
            GRID,
            LIST,
            moment,
            origin: window.location.origin,
            fileName: '',
            form: {
                name: '',
                alt: '',
                legend: '',
                image: null,
            },
        };
    },
    components: {
        Side,
        HeaderBody,
        Import,
        GalleryMedia,
    },
    computed: {
        showInfo() {
            return this.$store.state.media.showInfo;
        },
        items() {
            return this.$store.state.media.items;
        },
        tabSelected() {
            return this.$store.state.media.tabSelected;
        },
        loading() {
            return this.$store.state.media.loading;
        },
        imageSelected: {
            get: function () {
                return this.$store.state.media.imageSelected;
            },
            set: function (newValue) {
                this.$store.commit('media/setImageSelected', newValue);
            },
        },
        uploading: {
            get() {
                return this.$store.state.media.uploading;
            },
            set(value) {
                this.$store.commit('media/setUploading', value);
            },
        },
    },
    methods: {
        close(value) {
            if (this.uploading) {
                return;
            }
            this.$emit('close', value);
        },
        async deleteImage(image) {
            const confirm = await this.$modal.show(ConfirmationModal, {
                body: `
                        <p class="text-truncate">Supprimer l'image ${image.name}</p>
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
        formImage(file) {
            this.form.image = file;
            this.form.name = file.name;
            this.fileName = file.name;
        },
        async submit() {
            this.loadingUpload = true;
            const { image, alt, legend, name } = this.form;
            const form = new FormData();
            form.append('image', image);
            form.append('alt', alt);
            form.append('legend', legend);
            form.append('name', name);

            try {
                const { data } = await axios.post(
                    `${origin}/api/v1/accounts/${
                        location.pathname.split('/')[2]
                    }/packages/media/medias`,
                    form,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data',
                        },
                    }
                );
                this.$store.commit('media/setItems', [
                    ...this.$store.state.media.items,
                    data,
                ]);
                this.imageSelected = data;
                this.$store.dispatch('media/selectTab', FILES);
                this.form = {
                    name: '',
                    alt: '',
                    legend: '',
                    image: null,
                };
            } catch (e) {
                alert(e.message);
            }
            this.loadingUpload = false;
        },
    },
};
</script>
<style scoped>
.icon {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 64px;
    width: 64px;
    cursor: pointer;
}
.container-modal {
    height: 100vh;
    width: 100vw;
    background-color: var(--white);
}
.header-modal {
    display: flex;
    font-size: 18px;
    font-weight: 600;
    width: 100%;
    padding-left: 20px;
    justify-content: space-between;
    align-items: center;
    background-color: var(--primary);
    color: var(--white);
}
.body-modal {
    height: calc(100% - 128px);
}
.footer-modal {
    width: 100%;
    display: flex;
    height: 64px;
    padding: 0 20px;
    align-items: center;
    justify-content: flex-start;
    box-shadow: 0 0 20px -10px rgba(0, 0, 0, 0.35) !important;
}
.content-body {
    flex: 1;
    display: flex;
    position: relative;
    height: 100%;
}
.left {
    flex: 1;
    display: flex;
    flex-direction: column;
    position: relative;
}
.left > div {
    position: absolute;
    top: 0;
    left: 0;
    height: 100%;
    width: 100%;
    overflow-y: hidden;
}
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
.flip-list-move {
    transition: transform 350ms;
}
.spinner-border.upload {
    width: 6rem;
    height: 6rem;
}
</style>
