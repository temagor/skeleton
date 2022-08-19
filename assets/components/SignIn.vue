<template>
  <button
    class="button navigation__button button_signUp"
    @click="toggleVisible"
  >
    SignIn
  </button>
  <div v-if="isVisible">
    <div class="form form_popup">
      <form action="/user/api/sign-in" method="post">
        <input v-model="user.login" type="text" placeholder="Enter login" />
        <input
          v-model="user.password"
          type="password"
          placeholder="Enter password"
        />
        <button type="button">Sign In</button>
      </form>
    </div>
  </div>
</template>

<script>
import axios from "axios";
export default {
  data() {
    return {
      isVisible: false,
      user: {
        login: "",
        password: "",
      },
    };
  },
  methods: {
    toggleVisible() {
      this.isVisible = !this.isVisible;
    },
  },
};
</script>

<style></style>
