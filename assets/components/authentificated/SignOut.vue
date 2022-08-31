<template>
  <button class="button navigation__button button_signOut" @click="signOut()">
    SignOut
  </button>
</template>

<script>
import { mapActions } from "pinia";
import { useUserStore } from "../../stores/user";
import axios from "axios";
export default {
  methods: {
    signOut() {
      axios
        .post("/user/actions/api/sign-out")
        .then((response) => {
          console.log(response);
          if (response.data.success) {
            this.getUser();
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
