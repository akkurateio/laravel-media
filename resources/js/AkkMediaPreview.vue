<template>
    <div class="h-100">
        <div class="h-100 d-flex flex-column" v-if="media">
            <div class="media-preview__body d-flex align-items-center justify-content-between pl-3 border-bottom border-gray-100">
                <div class="text-lg">Aperçu</div>
                <div class="icon-box icon primary" @click="closePanel">
                    <Icon name="Close" />
                </div>
            </div>
            <div class="media-preview__body">
                <img :src="preview" :alt="media.name" class="img-fluid" />
                <div class="p-3">
                    <div class="font-bold text-truncate mb-2">{{ media.file_name }}</div>

                    <div class="mb-2">
                        <div class="text-3xs text-muted">Nom du fichier</div>
                        <div class="text-truncate">{{ media.name }}</div>
                    </div>
                    <div class="mb-2">
                        <div class="text-3xs text-muted">Modèle associé</div>
                        <div class="text-truncate">{{ modelName }}</div>
                    </div>
                    <div class="mb-2">
                        <div class="text-3xs text-muted">Format de fichier</div>
                        <div class="text-truncate">{{ media.mime_type }}</div>
                    </div>
                    <div class="mb-2">
                        <div class="text-3xs text-muted">Conversions disponibles</div>
                        <div class="text-xs" v-for="(conversion, index) in conversions" :key="index">
                            <div class="text-truncate">{{ conversion }}</div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="text-3xs text-muted">Poids du fichier</div>
                        <div class="text-truncate">{{ media.size }} octects</div>
                    </div>

                    <div class="mb-4" v-if="modelName == 'Resource'">
                        <div class="text-3xs text-muted mb-2">Usage</div>
                        <pre class="text-3xs"><code>getMediaResource('{{ model.slug }}', 'square')</code></pre>
                    </div>
                </div>
            </div>
        </div>
        <div class="h-100" v-else>Aperçu : sélectionnez un media</div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            model: null,
            preview: null,
            media: null,
            conversions: null,
        };
    },
    mounted() {
        this.$root.$on('previewMedia', (media) => {
            this.model = media.model;
            this.media = media.definition;
            this.preview = media.preview;
            this.conversions = media.conversions;
        });
    },
    methods: {
        closePanel() {
            console.log('ClosePanel');

            this.model = null;
            this.media = null;
            this.preview = null;
            this.conversions = null;
        },
    },
    computed: {
        modelName() {
            let model_type = this.media.model_type.split("\\");
            return model_type.slice(-1)[0];
        },
    }
};
</script>
