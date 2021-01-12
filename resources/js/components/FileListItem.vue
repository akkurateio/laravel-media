<template>
    <div class="filelist-item" :class="[last && 'border-bottom']">
        <div class="icon-box">
            <Icon name="Image" />
        </div>
        <div class="filelist-item-meta">
            <div class="text-truncate">
                {{ item.file.name }}
            </div>
            <div class="text-2xs">
                {{ formatSize(item.file.size) }} •
                {{ formatFileType(item.file.type) }}
            </div>
        </div>
        <IconItem :item="item" @deleteItem="$emit('delete-item')" />
    </div>
</template>
<script>
import formatSize from '../utils/formatSize';
import formatFileType from '../utils/formatFileType';
import IconItem from './IconItem';
export default {
    props: ['item', 'last'],
    components: {
        IconItem,
    },
    methods: {
        formatSize,
        formatFileType,
    },
};
</script>
<style>
.filelist-item {
    display: flex;
    align-items: center;
}

.filelist-item-meta {
    width: calc(100% - 128px);
}
</style>
