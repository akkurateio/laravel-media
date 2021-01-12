<template>
    <div
        style="
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
        "
    >
        <div class="text-center font-bold">
            <div class="text-lg mb-4">Chargez vos fichier</div>
            <div class="text-sm">
                Glissez-déposez vos fichiers depuis votre Explorateur ou votre
                Finder.
            </div>
        </div>
        <div
            @drop.prevent="addFiles($event.dataTransfer.files)"
            @dragover.prevent="drag = true"
            @dragleave="drag = false"
        >
            <div
                class="drag-container"
                v-if="files.length === 0"
                :class="[drag && 'drag']"
            >
                <DragImage />
            </div>
            <transition-group
                v-if="files.length"
                class="filelist"
                :class="[drag && 'drag']"
                tag="div"
                ref="list"
                name="flip-list"
            >
                <FileListItem
                    v-for="(item, index) of files"
                    :last="index < files.length"
                    :item="item"
                    @deleteItem="deleteFile(item.id)"
                    :key="item.id"
                />
            </transition-group>
            <div class="d-flex justify-content-between align-items-center">
                <label
                    class="btn btn-outline-secondary btn-lg text-sm font-bold m-0"
                    for="files"
                    >Sélectionnez vos fichiers
                    <input
                        multiple
                        @change="addFiles($event.target.files)"
                        style="display: none"
                        type="file"
                        id="files"
                    />
                </label>
                <button
                    @click="submit"
                    type="button"
                    :disabled="!canImport || uploading"
                    class="btn btn-primary btn-lg text-sm font-bold"
                >
                    Importer
                </button>
            </div>
        </div>
    </div>
</template>
<script>
import { v4 } from 'uuid';
import formatSize from '../utils/formatSize';
import FileListItem from './FileListItem';
import DragImage from './DragImage';
import { FILES } from '../constant';
export default {
    data() {
        return {
            files: [],
            drag: false,
        };
    },
    components: { FileListItem, DragImage },
    methods: {
        formatSize,
        async uploadFile(item) {
            item.loading = true;
            const form = new FormData();
            form.append('image', item.file);
            form.append('name', item.file.name);
            let data;
            try {
                data = await axios.post(
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
                item.success = true;
            } catch (e) {
                console.log(e);
                item.success = false;
            }
            item.loading = false;
            item.submit = true;
            return data.data;
        },
        async submit() {
            this.uploading = true;
            let images;
            try {
                images = await Promise.all(
                    this.files
                        .filter((x) => !x.submit)
                        .map((item) => this.uploadFile(item))
                );
            } catch (e) {
                console.log(e);
            }
            if (this.files.length === 1) {
                this.$store.commit('media/setImageSelected', images[0]);
            }
            this.uploading = false;
            this.files = [];
            this.$store.dispatch('media/fetchItems');
            this.$store.commit('media/setTab', FILES);
            this.$emit('done');
        },
        async addFiles(files) {
            this.drag = false;
            for (const file of files) {
                this.files.push({
                    file,
                    loading: false,
                    success: false,
                    submit: false,
                    id: v4(),
                });
            }
            await this.$nextTick();
            const element = this.$refs.list.$el;
            element.scrollTop = element.scrollHeight;
        },
        deleteFile(id) {
            this.files = this.files.filter((item) => item.id !== id);
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
        canImport() {
            return this.files.filter((item) => !item.submit).length;
        },
    },
};
</script>
<style lang="scss" scoped>
.drag-container {
    width: 640px;
    height: 320px;
    margin: 20px auto;
    border: 2px solid transparent;
    transition: all 0.2s ease-in-out;
    &.drag {
        border: 2px solid var(--primary);
        fill: var(--primary);
        stroke: var(--primary);
    }
}
.filelist {
    border: 2px solid gray;
    border-radius: 2px;
    overflow-y: auto;
    margin: 20px;
    height: 320px;
    &.drag {
        border: 2px solid var(--primary);
    }
}
.border-bottom {
    border-bottom: 1px gray solid;
}
.flip-list-move {
    transition: all 600ms ease-in-out 50ms;
}
.flip-list-enter-active {
    transition: all 300ms ease-out;
}
.flip-list-leave-active {
    transition: all 0.3s ease;
}
.flip-list-enter,
.flip-list-leave-to {
    opacity: 0;
}

.content-enter-active,
.content-leave-active {
    transition: all 0.3s ease;
}
.content-enter,
.content-leave-to {
    opacity: 0;
}
</style>
