<template>
  {{ user.protected }}
</template>

<script>
import { mapState } from "pinia";
import { mapActions } from "pinia";
import { useUserStore } from "../stores/user";

export default {
  mounted() {
    this.getUser();
  },
  computed: {
    ...mapState(useUserStore, ["user"]),
  },
  methods: {
    ...mapActions(useUserStore, ["getUser", "authenticated"]),
  },
};
</script>

<style></style>
