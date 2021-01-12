<template>
    <div
        class="wrap d-flex flex-column justify-content-center align-items-center"
        v-if="loadingSearch || (items.length === 0 && loading)"
    >
        <div
            class="spinner-border text-primary"
            style="width: 10rem; height: 10rem"
            role="status"
        >
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <div
        class="wrap d-flex flex-column justify-content-center align-items-center"
        v-else-if="items.length === 0"
    >
        <Empty />
        <p>
            {{
                search.length > 0
                    ? 'Aucune image trouvée'
                    : 'Veuillez ajouter une image'
            }}
        </p>
    </div>
    <div v-else class="wrap" ref="wrap">
        <transition name="lightbox">
            <Lightbox
                :canSelect="!fromIndex"
                @close="closeL"
                :default-id="defaultID"
                v-if="openLightbox"
            />
        </transition>
        <template v-if="modeSelected === LIST">
            <div :class="['gallery', modeSelected === LIST && 'list']">
                <ImageContainer
                    @lightbox="openL"
                    @deleteImage="deleteImage(item)"
                    :selected="imageSelected && imageSelected.id === item.id"
                    :modeSelected="modeSelected"
                    @select="selectImage(item)"
                    v-for="item in items"
                    @delete-image="deleteImage(item)"
                    v-bind="item"
                    :key="item.id"
                />
            </div>
        </template>
        <template v-else>
            <transition-group
                name="flip-list"
                tag="div"
                :class="['gallery', modeSelected === LIST && 'list']"
            >
                <ImageContainer
                    @lightbox="openL"
                    :selected="imageSelected && imageSelected.id === item.id"
                    :modeSelected="modeSelected"
                    @select="selectImage(item)"
                    @delete-image="deleteImage(item)"
                    v-for="item in items"
                    v-bind="item"
                    :key="item.id"
                />
            </transition-group>
        </template>
        <mugen-scroll
            scroll-container="wrap"
            :handler="fetchData"
            :should-handle="!loading && hasNextPage"
        />
    </div>
</template>
<script>
import MugenScroll from 'vue-mugen-scroll';
import ImageContainer from './ImageContainer';
import Lightbox from './Lightbox';
import { FILES, GRID, IMPORT, LIST } from '../constant';
import Empty from './Empty';
import ConfirmationModal from './ConfirmationModal';
export default {
    props: ['fromIndex'],
    data() {
        return {
            FILES,
            IMPORT,
            GRID,
            LIST,
            openLightbox: false,
            defaultID: 0,
        };
    },
    mounted() {
        this.$store.dispatch('media/fetchItems');
    },
    components: {
        MugenScroll,
        ImageContainer,
        Lightbox,
        Empty,
    },
    methods: {
        fetchData() {
            this.$store.dispatch('media/fetchNextItem');
        },
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
        selectImage(value) {
            if (this.imageSelected && this.imageSelected.id === value.id) {
                this.$store.commit('media/setImageSelected', null);
                return;
            }
            this.$store.commit('media/setImageSelected', value);
        },
        openL(id) {
            this.openLightbox = true;
            this.defaultID = id;
        },
        closeL(value) {
            if (value) {
                this.$emit('close');
                return;
            }
            this.openLightbox = false;
        },
    },
    computed: {
        imageSelected: {
            get: function () {
                return this.$store.state.media.imageSelected;
            },
            set: function (newValue) {
                this.$store.commit('media/setImageSelected', newValue);
            },
        },
        loadingSearch() {
            return this.$store.state.media.loadingSearch;
        },
        loading() {
            return this.$store.state.media.loading;
        },
        modeSelected() {
            return this.$store.state.media.modeSelected;
        },
        items() {
            return this.$store.state.media.items;
        },
        hasNextPage() {
            return this.$store.getters['media/hasNextPage'];
        },
        search() {
            return this.$store.state.media.search;
        },
    },
};
</script>
<style scoped>
.wrap {
    height: calc(100% - 64px);
    overflow-y: auto;
    padding-bottom: 1.5rem;
}
.gallery {
    display: grid;
    grid-auto-columns: auto;
    grid-auto-rows: auto;
    grid-template-columns: repeat(auto-fill, minmax(192px, 1fr));
    grid-gap: 20px;
    padding: 20px;
    margin: auto;
    width: 100%;
}
.gallery.list {
    display: block;
}
.lightbox-enter-active,
.lightbox-leave-active {
    transition: opacity 0.5s;
}
.lightbox-enter, .lightbox-leave-to /* .lightbox-leave-active below version 2.1.8 */ {
    opacity: 0;
}
</style>
