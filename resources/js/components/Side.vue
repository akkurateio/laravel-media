<template>
    <div class="right">
        <div class="header-right">
            <div class="text-truncate">{{ name }}</div>
            <AkkDropdown style="fill: var(--primary)" class="icon-box">
                <Icon name="OverflowMenuHorizontal" />
                <template #items>
                    <AkkDropdownItem @click.stop="deleteImage">
                        Supprimer
                    </AkkDropdownItem>
                </template>
            </AkkDropdown>
        </div>
        <div class="image">
            <img
                :src="`${origin}/storage/${media[0].id}/${media[0].file_name}`"
                :alt="name"
            />
        </div>
        <div class="details text-2xs">
            <div>
                <div>Type</div>
                <div>
                    {{ media[0].mime_type.split('/').pop().toUpperCase() }}
                </div>
            </div>
            <div>
                <div>Taille</div>
                <div>
                    {{ formatSize(media[0].size) }}
                </div>
            </div>
            <div>
                <div>Propriétaire</div>
                <div>{{ user.firstname }} {{ user.lastname }}</div>
            </div>
            <div>
                <div>Ajouté le</div>
                <div>
                    {{ moment(created_at).format('DD MMM YYYY à HH:mm') }}
                </div>
            </div>
            <div>
                <div>Modifié le</div>
                <div>
                    {{ moment(updated_at).format('DD MMM YYYY à HH:mm') }}
                </div>
            </div>
        </div>
        <div class="form">
            <div class="form-group">
                <label class="control-label" for="alt">Texte alternatif</label>
                <input
                    id="alt"
                    type="text"
                    name="alt"
                    v-model="selected.alt"
                    @input="update"
                    autofocus="autofocus"
                    class="form-control form-control-sm"
                />
            </div>
            <div class="form-group">
                <label class="control-label" for="legend">Légende</label>
                <input
                    id="legend"
                    type="text"
                    name="legend"
                    @input="update"
                    v-model="selected.legend"
                    autofocus="autofocus"
                    class="form-control form-control-sm"
                />
            </div>
            <div class="form-group">
                <label class="control-label" for="tags">Tags</label>
                <vue-tags-input
                    id="tags"
                    class="input-tags"
                    v-model="tag"
                    :tags="selectedTags"
                    @tags-changed="(newTags) => (selectedTags = newTags)"
                    @before-adding-tag="addingTag"
                    @before-deleting-tag="removeTag"
                    placeholder="Ajouter un tag"
                />
            </div>
            <div class="d-flex align-items-center my-4">
                <button
                    class="btn btn-sm btn-primary mr-2"
                    v-clipboard:copy="formatSlug"
                    v-clipboard:success="onCopy"
                >
                    <Icon style="fill: var(--white)" name="Code" />
                </button>
                <transition name="clip">
                    <span v-if="clipboard" class="text-2xs"
                        >Copie Clipboard effectuée !
                    </span>
                </transition>
            </div>
        </div>
    </div>
</template>
<script>
import debounce from 'lodash/debounce';
import VueTagsInput, { createTags } from '@johmun/vue-tags-input';
import moment from 'moment';
import formatSize from '../utils/formatSize';
import AkkDropdown from 'akkurate-back-components-ts/dist/akk-dropdown';
import AkkDropdownItem from 'akkurate-back-components-ts/dist/akk-dropdown-item';
import ConfirmationModal from './ConfirmationModal';
export default {
    props: [
        'name',
        'slug',
        'resource',
        'created_at',
        'updated_at',
        'size',
        'file_name',
        'id',
        'mime_type',
        'media',
        'tags',
        'alt',
        'legend',
        'user',
    ],
    components: { VueTagsInput, AkkDropdown, AkkDropdownItem },
    data() {
        return {
            origin: window.location.origin,
            tag: '',
            selectedTags: [],
            selected: {
                alt: '',
                legend: '',
                tags: [],
            },
            clipboard: false,
            timeClip: null,
            moment,
        };
    },
    methods: {
        async deleteImage() {
            const confirm = await this.$modal.show(ConfirmationModal, {
                body: `
                        Supprimer l'image ${this.name}
                    `,
            });
            if (!confirm) {
                return;
            }
            await this.$store.dispatch('media/deleteItem', this.media[0].id);
            await this.$store.commit('media/setImageSelected', null);
        },
        onCopy() {
            this.clipboard = true;
            if (this.timeClip) clearTimeout(this.timeClip);
            this.timeClip = setTimeout(() => {
                this.clipboard = false;
            }, 3000);
        },
        async removeTag(value) {
            value.deleteTag();
            await this.$nextTick();
            try {
                await axios.post(
                    `${origin}/api/v1/accounts/${
                        location.pathname.split('/')[2]
                    }/packages/media/resources/${this.id}/tags/detach`,
                    { tag: value.tag.text }
                );
                this.$store.commit('media/updateTags', {
                    id: this.id,
                    tags: this.selectedTags.map((item) => ({
                        name: { en: item.text },
                    })),
                });
            } catch (e) {
                alert(e.message);
            }
        },
        async addingTag(value) {
            value.addTag();
            await this.$nextTick();
            try {
                await axios.post(
                    `${origin}/api/v1/accounts/${
                        location.pathname.split('/')[2]
                    }/packages/media/resources/${this.id}/tags/attach`,
                    { tag: value.tag.text }
                );
                this.$store.commit('media/updateTags', {
                    id: this.id,
                    tags: this.selectedTags.map((item) => ({
                        name: { en: item.text },
                    })),
                });
            } catch (e) {
                alert(e.message);
            }
        },
        formatSize,
        update: debounce(async function () {
            const form = {
                name: this.name,
                alt: this.selected.alt,
                legend: this.selected.legend,
            };
            this.loading = true;
            try {
                await this.$store.dispatch('media/updateItem', {
                    id: this.media[0].id,
                    form,
                });
            } catch (e) {
                alert(e.message);
            }
            this.loading = false;
        }, 350),
    },
    watch: {
        alt: {
            immediate: true,
            handler() {
                this.selected = {
                    alt: this.alt,
                    legend: this.legend,
                };
            },
        },
        tags: {
            immediate: true,
            deep: true,
            handler(value) {
                this.tag = '';
                if (!value) {
                    return;
                }
                this.selectedTags = createTags([
                    ...value.map((item) => item.name.en),
                ]);
                this.selected = {
                    alt: this.alt,
                    legend: this.legend,
                };
            },
        },
    },
    computed: {
        formatSlug() {
            return `{!! getMediaResource('${this.slug}') !!}`;
        },
    },
};
</script>
<style scoped>
.clip-leave-active {
    transition: all 0.5s ease-in;
}
.clip-enter-active {
    transition: all 0.2s;
}
.clip-enter,
.clip-leave-to {
    opacity: 0;
    transform: translateX(20px);
}
.right {
    width: 320px;
    background-color: #f5f5f5;
    overflow-y: auto;
}

.header-right {
    height: 64px;
    display: flex;
    justify-content: space-between;
    padding-left: 20px;
    align-items: center;
    font-size: 14px;
    font-weight: bold;
}
.right .image {
    width: 278px;
    height: 278px;
    margin: auto;
    background-color: var(--white);
    padding: 5px;
    border-radius: 3px;
    border: 3px #dddddd solid;
}
.right .image img {
    object-fit: contain;
    height: 100%;
    width: 100%;
}
.right .details {
    padding: 20px;
}
.right .details > div {
    display: flex;
    justify-content: space-between;
    margin-bottom: 5px;
    font-weight: 600;
}
.right .form {
    padding: 0 20px;
}
</style>
