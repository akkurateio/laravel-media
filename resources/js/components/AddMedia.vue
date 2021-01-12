<template>
    <div class="view__add">
        <button
            @click="openModal"
            type="button"
            class="btn btn-sm btn-primary shadow-lg rounded-full d-flex align-items-center justify-content-center"
        >
            <i class="icon white">
                <Icon name="Add" size="32" />
            </i>
        </button>
    </div>
</template>
<script>
import ImportModal from './ImportModal';
export default {
    methods: {
        openModal() {
            this.$modal.show(ImportModal);
        },
    },
};
</script>
