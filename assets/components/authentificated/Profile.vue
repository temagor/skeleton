<template>
  <button class="button navigation__button button_profile" @click="show">
    Profile
  </button>
  <div v-if="isVisible" class="modal-wrapper" @click="hide">
    <div class="content" @click.stop="">
      <div class="title">Profile</div>
      <form class="form">
        <h3>Base info</h3>
        <input
          type="text"
          name="login"
          :value="user.login"
          @change="(event) => (updated.user.login = event.target.value)"
        />
        <div class="toggle">
          <input
            :checked="user.protected"
            type="checkbox"
            name="protected"
            id="switch"
            @change="(event) => (updated.user.protected = event.target.checked)"
          />
          <label for="switch">Toggle</label>
          <div>Password protection</div>
        </div>
        <div>protected: {{ user.protected ? "yep" : "no protection" }}</div>
        <h3>Credential info</h3>
        <input type="text" />
        <div v-for="credential in updated.user.credentials">
          <div>{{ credential.type }} : {{ credential.value }}</div>
        </div>
        <h3>Profile info</h3>
        <div v-for="(value, field) in updated.user.profile" :key="field">
          <div>{{ field }} : {{ value }}</div>
        </div>
      </form>
      <button @click="update">Update</button>
    </div>
  </div>
</template>

<script>
import { mapState } from "pinia";
import { mapActions } from "pinia";
import { useUserStore } from "../../stores/user";
import axios from "axios";
export default {
  data() {
    return {
      isVisible: false,
      updated: {
        user: {
          login: "",
          protected: "",
        },
      },
    };
  },
  props: {},
  computed: {
    ...mapState(useUserStore, ["user"]),
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
    update() {
      axios
        .put(`/profile/actions/api/${this.user.login}/update`, this.updated)
        .then((response) => {
          this.getUser();
          this.updated.user.login = "";
          this.updated.user.protected = "";
        });
    },
    ...mapActions(useUserStore, ["getUser"]),
  },
};
</script>

<style></style>
