<template>
  <div ref="modal" class="modal-default">
    <div class="p-2" style="flex: 1">
      <img style="display: block; max-width: 100%" ref="img" />
    </div>
    <div style="display: flex; justify-content: flex-end">
      <div></div>
      <button @click="close" type="button" class="btn btn-primary">
        Valider
      </button>
    </div>
  </div>
</template>

<script>
import Cropper from "cropperjs";
import "cropperjs/dist/cropper.min.css";
export default {
  props: ["file"],
  data() {
    return {
      cropper: null,
      detail: null,
    };
  },
  mounted() {
    const reader = new FileReader();
    reader.onload = (e) => {
      if (!e.target.result) {
        return;
      }
      const img = this.$refs.img;
      img.src = e.target.result;
      this.cropper = new Cropper(img, {
        aspectRatio: 1 / 1,
        dragMode: "move",
        crop: this.tugudu,
      });
    };
    reader.readAsDataURL(this.file);
  },
  methods: {
    close(value) {
      if (value === "cancel") {
        this.$emit("close", false);
      }
      this.$emit("close", {
        detail: this.detail,
        url: this.cropper.getCroppedCanvas().toDataURL(),
      });
    },
    tugudu(e) {
      this.detail = e.detail;
    },
  },
};
</script>
<style scoped>
.modal-default {
  display: flex;
  flex-direction: column;
  width: 400px;
  background: #fff;
  border-radius: 3px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
  padding: 15px;
  z-index: 101;
}
</style>
