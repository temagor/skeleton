<template>
  <AuthentificatedScope v-if="authenticated()"></AuthentificatedScope>
  <GuestScope v-if="!authenticated()"></GuestScope>
</template>

<script>
import { mapState } from "pinia";
import { mapActions } from "pinia";
import { useUserStore } from "../stores/user";
import AuthentificatedScope from "./AuthentificatedScope.vue";
import GuestScope from "./GuestScope.vue";

export default {
  mounted() {
    this.getUser();
  },
  components: { AuthentificatedScope, GuestScope },
  computed: {
    ...mapState(useUserStore, ["user"]),
  },
  methods: {
    ...mapActions(useUserStore, ["getUser", "authenticated"]),
  },
};
</script>

<style></style>
