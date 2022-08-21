<template>
  <button
    class="button navigation__button button_signUp"
    @click="toggleVisible"
  >
    SignUp
  </button>
  <div v-if="isVisible">
    <div class="form form_popup">
      <div
        v-if="message"
        class="form__message"
        :class="{
          form__message_success: success,
          form__message_error: !success,
        }"
      >
        {{ message }}
      </div>
      <form action="#" method="post" @submit.prevent="register">
        <div v-if="errors.login">{{ errors.login }}</div>
        <input v-model="user.login" type="text" placeholder="Enter login" />
        <input v-model="user.protected" type="checkbox" id="switch" /><label
          for="switch"
          >Toggle</label
        >
        <Transition name="slide-fade">
          <div v-if="user.protected">
            <input
              v-model="credentialList.login.value"
              type="password"
              placeholder="Enter password"
            />
            <input
              v-model="confirmPassword"
              type="password"
              placeholder="Confirm password"
            />
          </div>
        </Transition>
        <button type="submit">Register</button>
      </form>
    </div>
  </div>
</template>

<script>
import { Transition } from "vue";
import axios from "axios";

export default {
  components: { Transition },
  data() {
    return {
      message: null,
      success: false,
      errors: {},
      isVisible: false,
      confirmPassword: "!@ChangeMe!",
      user: {
        login: "",
        protected: false,
      },
      credentialList: {
        login: {
          type: "login",
          value: "!@ChangeMe!",
        },
      },
    };
  },
  methods: {
    toggleVisible() {
      this.isVisible = !this.isVisible;
    },
    register() {
      axios
        .post("/user/actions/api/sign-up", {
          user: this.user,
          credentialList: this.credentialList,
        })
        .then((response) => {
          this.success = response.data.success;
          this.message = response.data.message;
          if (response.data.success) {
            setTimeout(() => {
              window.location.reload();
            }, 1500);
          }
        })
        .catch((error) => {
          if (error.response) {
            // The request was made and the server responded with a status code
            // that falls out of the range of 2xx
            if (error.response.status < 500) {
              this.success = error.response.data.success;
              this.message = error.response.data.message;
              this.errors = error.response.data.errors;
            } else {
              this.success = error.response.data.success;
              this.message = "server error";
              this.errors = {};
            }
          } else if (error.request) {
            // The request was made but no response was received
            // `error.request` is an instance of XMLHttpRequest in the browser and an instance of
            // http.ClientRequest in node.js
            this.success = error.response.data.success;
            this.message = "WoW! Server not available";
            this.errors = {};
            console.log(error.request);
          } else {
            // Something happened in setting up the request that triggered an Error
            console.log("Error", error.message);
          }
          console.log(error.config);
        });
    },
  },
};
</script>

<style>
.slide-fade-enter-active {
  transition: all 0.3s ease-out;
}

.slide-fade-leave-active {
  transition: all 0.8s cubic-bezier(1, 0.5, 0.8, 1);
}

.slide-fade-enter-from,
.slide-fade-leave-to {
  transform: translateX(20px);
  opacity: 0;
}
</style>
