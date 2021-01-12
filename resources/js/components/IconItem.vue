<template>
    <div class="icon-box">
        <div
            v-if="item.loading"
            style="
                width: 1rem;
                height: 1rem;
                border: 0.15em solid currentColor;
                border-right-color: transparent;
            "
            class="spinner-border"
            role="status"
        >
            <span class="sr-only">Loading...</span>
        </div>
        <Icon
            v-else-if="item.success && item.submit"
            style="fill: var(--success)"
            name="CheckmarkOutline"
        />
        <Icon
            v-else-if="!item.success && item.submit"
            style="fill: var(--danger)"
            name="MisuseOutline"
        />
        <span v-else @click="$emit('delete-item')">
            <Icon style="cursor: pointer" name="Close" />
        </span>
    </div>
</template>
<script>
export default {
    props: ['item'],
};
</script>
