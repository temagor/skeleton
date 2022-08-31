<template>
  <div>
    <SignIn></SignIn>
    <SignUp></SignUp>
  </div>
</template>

<script>
import SignUp from "./guest/SignUp.vue";
import SignIn from "./guest/SignIn.vue";
export default {
  components: { SignUp, SignIn },
};
</script>

<style></style>
