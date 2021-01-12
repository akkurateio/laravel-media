<template>
    <div class="head-media">
        <div style="flex: 1" class="d-flex search-input text-sm">
            <label for="search" class="icon-box m-0">
                <Icon name="Search" />
            </label>
            <input
                @input="searchDebounce($event.target.value)"
                placeholder="Rechercher..."
                id="search"
                name="search"
                type="search"
            />
        </div>
        <div
            @click="
                $store.commit(
                    'media/setMode',
                    modeSelected === GRID ? LIST : GRID
                )
            "
            style="fill: var(--primary); cursor: pointer"
            class="icon-box"
        >
            <Icon name="List" />
        </div>
        <transition name="side">
            <div
                v-show="imageSelected"
                @click="$store.dispatch('media/toggleInfo')"
                style="fill: var(--primary); cursor: pointer"
                class="icon-box"
            >
                <Icon name="Information" />
            </div>
        </transition>
    </div>
</template>

<script>
import { FILES, GRID, IMPORT, LIST } from '../constant';
import debounce from 'lodash/debounce';

export default {
    name: 'MediaTop',
    data() {
        return {
            FILES,
            IMPORT,
            GRID,
            LIST,
        };
    },
    methods: {
        searchDebounce: debounce(function (value) {
            this.$store.dispatch('media/searchAction', value);
        }, 350),
    },
    computed: {
        modeSelected() {
            return this.$store.state.media.modeSelected;
        },
        imageSelected() {
            return this.$store.state.media.imageSelected;
        },
    },
};
</script>

<style lang="scss" scoped>
.head-media {
    width: calc(100% - 640px);
    display: flex;
    background-color: white;
}
.search-input {
    position: relative;
    align-items: center;
    &::before {
        content: '';
        position: absolute;
        border-left: solid var(--primary) 1px;
        top: 12.5%;
        right: 0;
        height: 75%;
    }
    input {
        border: none;
        outline: none;
        padding-left: 20px;
        width: 100%;
        height: 100%;
    }
    input[type='search']::-webkit-search-cancel-button {
        transform: translateX(-20px);
    }
}
.side-enter-active,
.side-leave-active {
    transition: all 0.2s ease-in-out;
}
.side-leave,
.side-enter-to {
    transform: scale(1);
    width: 64px;
}
.side-enter,
.side-leave-to {
    transform: scale(0);
    width: 0;
}
</style>
