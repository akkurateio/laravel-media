<template>
    <div  @click="close(false)" class="lightbox-media">
              <div v-if="image"
            @click.stop
            class="lightbox-header d-flex align-items-center akk-h-unit"
        >
            <div class="px-3 text-lg font-bold mr-auto">
                {{ loading ? "Chargement..." : image.name }}
            </div>
            <a :href="src" :download="image.name" class="icon-box icon white">
                <Icon name="Download" />
            </a>
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
            <img v-show="!loading" @click.stop :src="src" :alt="image.name" />
        </div>
        <div
            class="lightbox-footer akk-h-unit d-flex align-items-center justify-content-between px-3"
            v-show="!loading"
            @click.stop
        >
            <div class="text-sm font-bold">
                {{ image.media[0].mime_type | formatFileType }} •
                {{ image.media[0].size | formatSize }}
            </div>
            <div v-clipboard:copy="image.slug">
                {{ image.slug }}
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
    </div>
    </template>
        <div v-else
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
</template>
<script>
import resourceService from "../services/resources.service";
import formatSize from "../utils/formatSize";
import formatFileType from "../utils/formatFileType";
export default {
    props: {
        currentImage: {
            required: true,
        },
    },
    data() {
        return {
            image: null,
            loading: true,
        };
    },
    async mounted() {
        const { data } = await resourceService.getById(this.currentImage.id);
        this.image = data.data;
        this.loading = false;
    },
    method: {
        close(value = false) {
            this.$emit("close", value);
        },
    },
    filters: {
        formatSize,
        formatFileType,
    },
    computed: {
        src() {
            const media = this.image.media[0];
            return `/storage/${media.id}/${media.file_name}`;
        },
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
