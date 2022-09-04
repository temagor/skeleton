<template>
  <button class="button navigation__button button_profile" @click="show">
    Profile
  </button>
  <div v-if="isVisible" class="modal-wrapper" @click="hide">
    <div class="content" @click.stop="">
      <div class="title">Profile</div>
    </div>
  </div>
</template>

<script>
import { mapState } from "pinia";
import { useUserStore } from "../../stores/user";
export default {
  data() {
    return {
      isVisible: false,
    };
  },
  computed: {
    ...mapState(useUserStore, ["user"]),
  },
  methods: {
    show() {
      if (!this.isVisible) {
        this.isVisible = true;
      }
    },
    hide() {
      if (this.isVisible) {
        this.isVisible = false;
      }
    },
  },
};
</script>

<style></style>
