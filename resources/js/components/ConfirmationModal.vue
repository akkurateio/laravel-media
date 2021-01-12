<template>
    <div ref="modal" class="akk-modal akk-modal-default">
        <div class="d-flex align-items-center justify-content-between mb-5">
            <h5>Supprimer</h5>
            <a href="#" class="close" @click.stop="close(false)">
                <Icon name="Close" />
            </a>
        </div>
        <div style="flex: 1" class="mb-4" v-html="body"></div>
        <div class="d-flex align-items-center justify-content-between">
            <button
                @click="close(false)"
                type="button"
                class="btn btn-outline-secondary"
                data-dismiss="modal"
            >
                Annuler
            </button>
            <button @click="close(true)" type="button" class="btn btn-danger">
                Supprimer
            </button>
        </div>
    </div>
</template>

<script>
export default {
    props: ["body"],
    methods: {
        close(value) {
            this.$emit("close", value);
        },
    },
};
</script>
