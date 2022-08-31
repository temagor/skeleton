import { defineStore } from 'pinia'
import axios from 'axios'

export const useUserStore = defineStore('user', {
    state: () => ({
        user: {},
    }),
    actions: {
        async getUser() {
            try {
                const response = await axios.get('/security/actions/api/get-authentificated-user')
                console.log(response);
                if (response.data.success) {
                    this.user = response.data.data;
                }
            } catch (error) {
                console.log(error)
            }
        },
        authenticated() {
            return Object.keys(this.user).length !== 0;
        }
    },
})