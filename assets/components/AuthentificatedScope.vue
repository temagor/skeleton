<template>
  <Profile></Profile>
  <SignOut></SignOut>
</template>

<script>
import Profile from "./authentificated/Profile.vue";
import SignOut from "./authentificated/SignOut.vue";

export default {
  components: { Profile, SignOut },
};
</script>

<style></style>
