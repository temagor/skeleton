<template>
  <button
    class="button navigation__button button_signUp"
    @click="toggleVisible"
  >
    SignUp
  </button>
  <div v-if="isVisible">
    <div class="form form_popup">
      <form action="#" method="post" @submit.prevent="register">
        <input v-model="user.login" type="text" placeholder="Enter login" />
        <input
          v-model="needPasswordProtection"
          type="checkbox"
          id="switch"
        /><label for="switch">Toggle</label>
        <Transition name="slide-fade">
          <div v-if="needPasswordProtection">
            <input
              v-model="user.password"
              type="password"
              placeholder="Enter password"
            />
            <input
              v-model="user.confirmPassword"
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
      isVisible: false,
      needPasswordProtection: false,
      user: {
        login: "",
        password: "!@ChangeMe!",
        confirmPassword: "!@ChangeMe!",
      },
    };
  },
  methods: {
    toggleVisible() {
      this.isVisible = !this.isVisible;
    },
    register(e) {
      axios.post("/api/user/create", this.user);
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
