<template>
    <div class="entry entry-media" @click="show" :class="selected ? 'selected' : ''">
        <div class="p-1">
            <div class="inner">
                <slot></slot>
            </div>
            <div class="text-2xs text-truncate px-2 py-1">{{ media.definition.name }}</div>
        </div>
    </div>
</template>
<script>
export default {
    props: ['parentProps'],
    data() {
        return {
            selected: false,
        };
    },
    mounted() {
        this.$root.$on('previewMedia', (media) => {
            if(media.definition.id != this.media.definition.id) {
                this.selected = false;
            }
        });
    },
    methods: {
        show() {
            console.log('ShowPreview');

            this.selected = true;

            this.$root.$emit('previewMedia', this.media);
        },
    },
    computed: {
        media() {
            return this.parentProps;
        },
    }
};
</script>
