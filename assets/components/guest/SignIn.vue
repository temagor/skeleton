<template>
  <button class="button navigation__button button_signUp" @click="show">
    SignIn
  </button>
  <div v-if="isVisible" class="modal-wrapper" @click="hide">
    <div class="content" @click.stop="">
      <div class="title">SignIn</div>
      <form class="form" action="/user/api/sign-in" method="post">
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
