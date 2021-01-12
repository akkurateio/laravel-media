<template>
    <div>
        <label for="images">
            <img ref="img" :class="classNames" :src="img" alt="" />
        </label>
        <input
            ref="file"
            @change="readFile"
            hidden
            type="file"
            name="image"
            id="images"
        />
        <input hidden type="text" name="crop" id="crop" v-model="crop" />
    </div>
</template>

<script>
import Cropper from 'cropperjs';
import Crop from './Crop';

export default {
    props: ['images', 'classNames', '$form', 'withCrop'],
    data() {
        return {
            crop: '',
            img: '',
        };
    },
    mounted() {
        this.img = this.images;
    },
    methods: {
        async readFile(e) {
            if (this.needCrop) {
                const res = await this.$modal.show(Crop, {
                    file: e.target.files[0],
                });
                this.img = res.url;
                this.crop = JSON.stringify(res.detail);
            } else {
                const file = e.target.files[0];
                this.img = URL.createObjectURL(file);
            }
        },
    },
    computed: {
        needCrop() {
            return this.withCrop && this.withCrop == true ? true : false;
        },
    },
};
</script>
