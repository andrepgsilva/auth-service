<template>
  <div class="container">
    <div>
      <form class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <div class="mb-4">
          <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
            Email
          </label>
          <input
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
            id="email"
            type="text"
            v-model="form.email"
            placeholder="Email"
          />
        </div>
        <div class="mb-6">
          <label
            class="block text-gray-700 text-sm font-bold mb-2"
            for="password"
          >
            Password
          </label>
          <input
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline"
            id="password"
            type="text"
            v-model="form.password"
          />
        </div>
        <div class="flex items-center justify-between">
          <button
            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
            type="button"
            @click="login"
          >
            Sign In
          </button>
        </div>
      </form>

      <div class="mt-10">
        <button type="button" @click="getProfileData">
          Get my info
        </button>
      </div>

      <div class="mt-10">
        <button type="button" @click="refreshToken">
          Refresh token
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import { dispatchAction } from '../utils/dispatcher';

export default {
  data() {
    return {
      form :{
        email: '',
        password: ''
      }
    }
  },

  methods: {
    async login() {
      const response = await this.$axios.$post('/auth/login', {
        email: this.form.email,
        password: this.form.password,
      });

      localStorage.setItem('access_token_ttl', response.access_token_ttl);
      localStorage.setItem('refresh_token_ttl', response.refresh_token_ttl);
    },

    async getProfileData() {
      const profileDataAction = () => this.$axios.$get('/user');

      dispatchAction(profileDataAction, this);
    },

    async refreshToken() {
      return new Promise(async (resolve, reject) => {
        try {
          const response = await this.$axios.$post('/auth/refresh-token');

          resolve(response);
        } catch(error) {
          reject(error);
        }
      });
    }
  }
};
</script>

<style>
.container {
  margin: 0 auto;
  min-height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
  text-align: center;
}
</style>
