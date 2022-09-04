<template>
  <button class="button navigation__button button_profile">Profile</button>
</template>

<script>
import { mapState } from "pinia";
import { useUserStore } from "../../stores/user";
export default {
  computed: {
    ...mapState(useUserStore, ["user"]),
  },
};
</script>

<style></style>
