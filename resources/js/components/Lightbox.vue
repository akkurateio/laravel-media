<template>
    <div @click="close(false)" class="lightbox-media">
        <div
            @click.stop
            class="lightbox-header d-flex align-items-center akk-h-unit"
        >
            <div class="px-3 text-lg font-bold mr-auto">
                {{ loading ? 'Chargement...' : currentImage.name }}
            </div>
            <a
                :href="src"
                :download="currentImage.name"
                class="icon-box icon white"
            >
                <Icon name="Download" />
            </a>
            <button
                type="button"
                @click="deleteImage(items[current])"
                class="btn border-0 bg-transparent p-0 icon-box icon white"
            >
                <Icon name="TrashCan" />
            </button>
            <button
                @click="$emit('close', false)"
                type="button"
                class="btn border-0 bg-transparent p-0 icon-box icon white"
            >
                <Icon name="Close" />
            </button>
        </div>

        <div class="lightbox-body">
            <div
                v-if="loading"
                class="d-flex align-items-center justify-content-center w-100 h-100"
            >
                <div
                    class="spinner-border text-light"
                    style="width: 10rem; height: 10rem"
                    role="status"
                >
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
            <img
                v-show="!loading"
                @load="onImgLoad"
                @click.stop
                :src="src"
                :alt="currentImage.name"
            />
        </div>
        <div
            class="lightbox-footer akk-h-unit d-flex align-items-center justify-content-between px-3"
            v-show="!loading"
            @click.stop
        >
            <div class="text-sm font-bold">
                {{ formatFileType(currentImage.media[0].mime_type) }} •
                {{ formatSize(currentImage.media[0].size) }}
            </div>
            <div v-clipboard:copy="currentImage.slug">
                {{ currentImage.slug }}
            </div>
            <button
                v-if="canSelect"
                @click.stop="select"
                type="button"
                class="btn btn-outline-white"
            >
                Sélectionner
            </button>
        </div>

        <div @click.stop="prev" class="prev icon-box icon primary">
            <Icon name="ChevronLeft" />
        </div>
        <div @click.stop="next" class="next icon-box icon primary">
            <Icon name="ChevronRight" />
        </div>
    </div>
</template>
<script>
import formatSize from '../utils/formatSize';
import formatFileType from '../utils/formatFileType';
import ConfirmationModal from './ConfirmationModal';
const ESC = 'Escape';
const NEXT = 'ArrowRight';
const PREV = 'ArrowLeft';
export default {
    props: ['defaultId', 'canSelect'],
    data() {
        return {
            current: 0,
            loading: true,
        };
    },
    computed: {
        items() {
            return this.$store.state.media.items;
        },
        currentImage() {
            return this.items[this.current];
        },
        src() {
            const media = this.currentImage.media[0];
            return `/storage/${media.id}/${media.file_name}`;
        },
    },
    mounted() {
        this.current = this.items.findIndex((x) => x.id === this.defaultId);
        window.addEventListener('keyup', this.keyUp);
    },
    beforeDestroy() {
        window.removeEventListener('keyup', this.keyUp);
    },
    watch: {
        current() {
            this.loading = true;
        },
    },
    methods: {
        keyUp(ev) {
            switch (ev.key) {
                case ESC:
                    this.$emit('close', false);
                    return;
                case NEXT:
                    this.next();
                    return;
                case PREV:
                    this.prev();
                    return;
                default:
                    return;
            }
        },
        onImgLoad() {
            this.loading = false;
        },
        next() {
            if (this.current + 1 < this.items.length) {
                this.current++;
                return;
            }
            this.current = 0;
        },
        prev() {
            if (this.current === 0) {
                this.current = this.items.length - 1;
                return;
            }
            this.current--;
        },
        select() {
            this.$store.commit('media/setImageSelected', this.currentImage);
            this.close(true);
        },
        close(value = false) {
            this.$emit('close', value);
        },
        async deleteImage(image) {
            const confirm = await this.$modal.show(ConfirmationModal, {
                body: `
                        <p class="text-truncate">Supprimer l'image ${image.name}<p>
                    `,
            });
            if (!confirm) {
                return;
            }
            await this.$store.dispatch('media/deleteItem', image.media[0].id);
            if (this.imageSelected && this.imageSelected.id === image.id) {
                this.imageSelected = null;
            }
            this.close();
        },
        formatSize,
        formatFileType,
    },
};
</script>
<style lang="scss" scoped>
.lightbox-media {
    z-index: 100;
    bottom: 0;
    left: 0;
    position: fixed;
    right: 0;
    top: 0;
    background-color: rgba(0, 0, 0, 0.8);
    display: flex;
    flex-direction: column;
    color: white;
    justify-content: center;
    align-items: center;
}
.lightbox-body {
    max-width: 80%;
    height: 80%;
    img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }
}
.next,
.prev {
    padding: 15px;
    background-color: white;
    position: fixed;
    top: 50%;
    border-radius: 50%;
    cursor: pointer;
}
.prev {
    margin-top: -32px;
    left: 15px;
}
.next {
    margin-top: -32px;
    right: 15px;
}
.lightbox-header {
    background-color: rgba(0, 0, 0, 0.2);
    position: fixed;
    display: flex;
    top: 0;
    left: 0;
    right: 0;
}
.lightbox-footer {
    background-color: rgba(0, 0, 0, 0.2);
    position: fixed;
    display: flex;
    bottom: 0;
    left: 0;
    right: 0;
}
</style>
