<template>
  <SignIn></SignIn>
  <SignUp></SignUp>
</template>

<script>
import SignUp from "./guest/SignUp.vue";
import SignIn from "./guest/SignIn.vue";
export default {
  components: { SignUp, SignIn },
};
</script>

<style></style>
