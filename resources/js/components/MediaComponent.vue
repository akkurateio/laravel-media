<template>
    <div>
        <input
            v-if="!multiple"
            type="hidden"
            :name="fieldName"
            v-model="resource_id"
        />
        <input
            v-else
            type="hidden"
            :name="fieldName"
            v-model="multipleResourceIds"
        />
        <div>
            <div v-if="!imageSelected.length" @click="openModal">
                <img :src="thumbDefault" alt="" class="img-fluid" />
            </div>
            <div
                :class="[imageSelected.length !== 1 && 'list-images']"
                v-else
                @click="openModal"
            >
                <div
                    :key="image.id"
                    v-for="image of imageSelected"
                    class="position-relative akk-media-image-container"
                >
                    <div
                        class="image-options d-flex align-items-center justify-content-center"
                    >
                        <div
                            class="icon-box icon primary"
                            @click.stop="remove(image)"
                        >
                            <Icon name="Close" />
                        </div>
                        <div
                            class="icon-box icon primary"
                            @click.stop="preview(image)"
                        >
                            <ViewFilled />
                        </div>
                    </div>
                    <img :src="image.thumb" :alt="alt" class="img-fluid" />
                </div>
                <img
                    v-if="multiple"
                    :src="thumbDefault"
                    alt=""
                    class="img-fluid"
                />
            </div>
        </div>
        <Input v-if="altShow" v-model="alt" label="Text Alternatif" />
    </div>
</template>
<script>
import MediaModal from './MediaModal';
import Input from 'akkurate-back-components-ts/dist/akk-input';
import is from 'is_js';
import ViewFilled from '@carbon/icons-vue/es/view--filled/24';
import Preview from './Preview';
export default {
    props: {
        fieldName: String,
        altValue: String,
        imageDefault: Object,
        resource: [Object, Array],
        altShow: Boolean,
        multiple: {
            type: Boolean,
        },
    },
    components: { Input, ViewFilled },
    data() {
        return {
            imageSelected: [],
            origin: window.location.origin,
            resource_id: '',
            multipleResourceIds: [],
            alt: '',
        };
    },
    async mounted() {
        this.alt = this.altValue || '';
        if (!this.resource) {
            return;
        }
        if (is.array(this.resource)) {
            this.multipleResourceIds = this.resource.map((x) => x.id);
            this.imageSelected = this.resource;
        } else if (is.object(this.resource)) {
            this.resource_id = this.resource.id || '';
            this.imageSelected = [this.resource];
        }
    },
    methods: {
        async openModal() {
            const resource = await this.$modal.show(MediaModal);
            if (resource) {
                this.imageSelected = this.multiple
                    ? [...this.imageSelected, resource]
                    : [resource];
                if (this.multiple) {
                    this.multipleResourceIds.push(resource.id);
                } else {
                    this.resource_id = resource.id;
                }
                this.alt = resource.alt;
            }
        },
        remove(image) {
            this.imageSelected = this.imageSelected.filter(
                (x) => x.id !== image.id
            );
            if (this.multiple) {
                this.multipleResourceIds = this.multipleResourceIds.filter(
                    (id) => id !== image.id
                );
            } else {
                this.resource_id = '';
            }
        },
        preview(image) {
            //this.$modal.show(Lightbox);
            console.log(image);
            this.$modal.show(Preview, {
                currentImage: image,
            });
            console.log('Lancement de la modal de preview du visuel', image);
        },
    },
    computed: {
        thumbDefault() {
            return this.imageDefault || '/images/default-image.png';
        },
    },
};
</script>
<style lang="scss">
.list-images {
    display: grid;
    grid-template-columns: auto auto auto;
    grid-template-rows: auto auto auto;
    column-gap: 10px;
    row-gap: 15px;
}
.image-options {
    &::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;

        background: rgba(255, 255, 255, 0.8);
    }
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    transition: all 0.3s ease;
    svg {
        width: 24px;
        height: 24px;
        transform: scale(0);
        transition: all 0.3s ease;
    }
}
.akk-media-image-container:hover {
    .image-options {
        opacity: 1;
        svg {
            transform: scale(1);
        }
    }
}
</style>
