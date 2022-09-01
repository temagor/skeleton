<template>
  <button class="button navigation__button button_signUp" @click="show">
    SignIn
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
      <div class="title">SignIn</div>
      <form class="form" @submit="signIn">
        <Transition name="slide-fade">
          <input
            v-if="fieldVisibleList.login"
            v-model="credentialList.login"
            type="text"
            placeholder="Enter login"
          />
        </Transition>
        <Transition name="slide-fade">
          <input
            v-if="fieldVisibleList.email"
            v-model="credentialList.email"
            type="email"
            placeholder="Enter email"
          />
        </Transition>
        <Transition name="slide-fade">
          <input
            v-if="fieldVisibleList.phoneNumber"
            v-model="credentialList.phoneNumber"
            type="text"
            placeholder="Enter phone number"
          />
        </Transition>
        <button type="button">Sign In</button>
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
      isVisible: false,
      fieldVisibleList: {
        login: true,
        email: true,
        phoneNumber: true,
      },
      credentialList: {
        login: "",
        email: "",
        phoneNumber: "",
        password: "!@ChangeMe!",
      },
      message: null,
      success: false,
      errors: {},
    };
  },
  watch: {
    credentialList: {
      handler(credentialList, oldValue) {
        if (this.canShowAllInputs()) {
          this.showAllInputs();
        } else {
          this.hideNotUsedCredentials();
        }
      },
      deep: true,
    },
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
    canShowAllInputs() {
      return (
        this.credentialList.login.length === 0 &&
        this.credentialList.email.length === 0 &&
        this.credentialList.phoneNumber.length === 0
      );
    },
    showAllInputs() {
      this.fieldVisibleList.login = true;
      this.fieldVisibleList.email = true;
      this.fieldVisibleList.phoneNumber = true;
    },
    hideNotUsedCredentials() {
      if (this.credentialList.login.length === 0) {
        this.fieldVisibleList.login = false;
      }

      if (this.credentialList.email.length === 0) {
        this.fieldVisibleList.email = false;
      }

      if (this.credentialList.phoneNumber.length === 0) {
        this.fieldVisibleList.phoneNumber = false;
      }
    },
    signIn() {
      axios
        .post("/user/actions/api/sign-in", credentialList)
        .then((response) => {
          this.success = response.data.success;
          this.message = response.data.message;
          if (response.data.success) {
            this.getUser();
            setTimeout(() => {
              this.hide();
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

<style></style>
