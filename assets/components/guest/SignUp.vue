<template>
  <button class="button navigation__button button_signUp" @click="show">
    SignUp
  </button>
  <div v-if="isVisible" class="modal-wrapper" @click="hide">
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
    <div class="content" @click.stop="">
      <div class="title">SignUp</div>
      <form class="form" action="#" method="post" @submit.prevent="register">
        <!-- login -->
        <div v-if="errors.login">{{ errors.login }}</div>
        <input v-model="user.login" type="text" placeholder="Enter login" />
        <!-- email -->
        <div v-if="errors.email">{{ errors.email }}</div>
        <input
          v-model="credentialList.email"
          type="email"
          placeholder="Enter email"
        />
        <!-- phoneNumber -->
        <div v-if="errors.phoneNumber">{{ errors.phoneNumber }}</div>
        <input
          v-model="credentialList.phoneNumber"
          type="tel"
          placeholder="Enter phone"
        />
        <!-- protection -->
        <div class="toggle">
          <input v-model="user.protected" type="checkbox" id="switch" />
          <label for="switch">Toggle</label>
          <div>Password protection</div>
        </div>
        <Transition name="slide-fade">
          <div v-if="user.protected">
            <input
              v-model="user.password"
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
        <!-- submit -->
        <button type="submit">Register</button>
      </form>
    </div>
  </div>
</template>

<script>
import { mapState } from "pinia";
import { mapActions } from "pinia";
import { useUserStore } from "../../stores/user";
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
      confirmPassword: "",
      user: {
        login: "",
        protected: false,
        password: "",
      },
      credentialList: {
        email: "",
        phoneNumber: "",
      },
    };
  },
  computed: {
    // gives access to this.count inside the component
    // same as reading from store.count
    ...mapState(useUserStore, ["userData"]),
  },
  methods: {
    toggleVisible() {
      this.isVisible = !this.isVisible;
    },
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
    register() {
      if (
        this.user.login.length === 0 &&
        this.credentialList.email.length !== 0
      ) {
        this.user.login = this.credentialList.email.split("@")[0];
      }

      if (
        this.user.login.length === 0 &&
        this.credentialList.phoneNumber.length !== 0
      ) {
        this.user.login = this.credentialList.phoneNumber;
      }

      axios
        .post("/user/actions/api/sign-up", {
          user: this.user,
          credentialList: this.credentialList,
        })
        .then((response) => {
          this.success = response.data.success;
          this.message = response.data.message;
          if (response.data.success) {
            this.getUser();
            setTimeout(() => {
              this.toggleVisible();
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
        });
    },
    ...mapActions(useUserStore, ["getUser"]),
  },
};
</script>

<style>
.form_popup {
  z-index: 100;
  opacity: unset;
}
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
