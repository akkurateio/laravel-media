<template>
    <div class="header-body">
        <div style="display: flex">
            <div
                :class="[tabSelected === FILES && 'selected', 'tab']"
                @click="$store.dispatch('media/selectTab', FILES)"
            >
                Vos fichiers
            </div>
            <div
                :class="[tabSelected === IMPORT && 'selected', 'tab']"
                @click="$store.dispatch('media/selectTab', IMPORT)"
            >
                Importer
            </div>
        </div>
        <BarMedia v-if="tabSelected === FILES && 'selected'" />
    </div>
</template>
<script>
import debounce from 'lodash/debounce';
import { FILES, IMPORT, GRID, LIST } from '../constant';
import BarMedia from './BarMedia';
export default {
    data() {
        return {
            FILES,
            IMPORT,
            GRID,
            LIST,
        };
    },
    components: {
        BarMedia,
    },
    methods: {
        searchDebounce: debounce(function (value) {
            this.$store.dispatch('media/searchAction', value);
        }, 350),
    },
    computed: {
        tabSelected() {
            return this.$store.state.media.tabSelected;
        },
        modeSelected() {
            return this.$store.state.media.modeSelected;
        },
    },
};
</script>
<style lang="scss" scoped>
.search-input {
    position: relative;
    align-items: center;
    &::before {
        content: '';
        position: absolute;
        border-left: solid var(--primary) 1px;
        top: 12.5%;
        height: 75%;
    }
    input {
        border: none;
        outline: none;
        padding-left: 20px;
    }
}
.header-body {
    width: 100%;
    height: 64px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    color: var(--primary);
    fill: var(--primary);
}
.tab {
    height: 64px;
    display: flex;
    align-items: center;
    padding-left: 20px;
    padding-right: 20px;
    border-bottom: var(--white) 4px solid;
    font-weight: 600;
    cursor: pointer;
}
.selected {
    border-bottom: var(--primary) 4px solid;
    font-weight: 600;
}
.icon {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 64px;
    width: 64px;
    cursor: pointer;
}
</style>
