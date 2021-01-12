<template>
    <div>
        <div
            v-if="modeSelected === GRID"
            @click="click"
            @dblclick="dbClick"
            :class="['wrap-image', selected && 'selected']"
        >
            <div class="image">
                <img :src="thumb" :alt="name" />
            </div>
            <div class="label text-truncate">
                {{ name }}
            </div>
        </div>
        <div
            v-else-if="modeSelected === LIST"
            @click="click"
            @dblclick="dbClick"
            :class="['wrap-image', selected && 'selected', 'list']"
        >
            <div
                style="
                    height: 48px;
                    width: 48px;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                "
            >
                <Icon name="Image" />
            </div>
            <div style="flex: 1; width: 192px">
                <div class="text-truncate font-bold">{{ name }}</div>
                <div>
                    {{ media[0].mime_type.split('/').pop().toUpperCase() }},
                    {{ formatSize(media[0].size) }}
                </div>
            </div>
            <div
                style="
                    display: flex;
                    flex: 1;
                    justify-content: space-between;
                    align-items: center;
                "
            >
                <div style="width: 175px">
                    <div>
                        Ajouté par {{ user.firstname }} {{ user.lastname }}
                    </div>
                    <div>Association multiple</div>
                </div>
                <div style="width: 175px">
                    <div>
                        Ajouté le
                        {{ moment(created_at).format('DD MMM YYYY à HH:mm') }}
                    </div>
                    <div>
                        Dernière modif. {{ moment(updated_at).fromNow() }}
                    </div>
                </div>
                <AkkDropdown style="fill: var(--primary)" class="icon-box">
                    <Icon name="OverflowMenuHorizontal" />
                    <template #items>
                        <AkkDropdownItem @click.stop="$emit('delete-image')">
                            Supprimer
                        </AkkDropdownItem>
                    </template>
                </AkkDropdown>
            </div>
        </div>
    </div>
</template>
<script>
import { GRID, LIST } from '../constant';
import moment from 'moment';
import formatSize from '../utils/formatSize';
import AkkDropdown from 'akkurate-back-components-ts/dist/akk-dropdown';
import AkkDropdownItem from 'akkurate-back-components-ts/dist/akk-dropdown-item';
export default {
    props: [
        'id',
        'name',
        'selected',
        'media',
        'modeSelected',
        'created_at',
        'updated_at',
        'thumb',
        'user',
    ],
    components: { AkkDropdown, AkkDropdownItem },
    data() {
        return {
            origin: window.location.origin,
            GRID,
            LIST,
            moment,
            timer: 0,
            delay: 200,
            prevent: false,
        };
    },
    methods: {
        formatSize,
        click() {
            this.timer = setTimeout(() => {
                if (!this.prevent) {
                    this.$emit('select');
                }
                this.prevent = false;
            }, this.delay);
        },
        dbClick() {
            clearTimeout(this.timer);
            this.prevent = true;
            this.$emit('lightbox', this.id);
        },
    },
};
</script>
<style scoped>
.wrap-image {
    border: solid 2px #dddddd;
    border-radius: 2px;
    display: flex;
    flex-direction: column;
    cursor: pointer;
}
.wrap-image.list {
    flex-direction: row;
    font-size: 11px;
    justify-content: space-between;
    align-items: center;
    border: none;
    border-top: 1px solid #dddddd;
}
.wrap-image.selected {
    border: solid 2px var(--primary);
}
.wrap-image.selected.list {
    border: none;
    position: relative;
    border-top: 1px solid #dddddd;
}
.wrap-image.selected.list::before {
    content: '';
    background-color: var(--primary);
    position: absolute;
    top: 0;
    left: 0;
    height: 100%;
    width: 100%;
    opacity: 0.2;
}
.wrap-image .image {
    margin: 5px;
    flex: 1;
}
.image img {
    height: 100%;
    width: 100%;
}
.label {
    font-size: 11px;
    font-weight: bold;
    padding: 10px;
}
</style>
