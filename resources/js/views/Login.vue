<template>
  <div id="main" class="flex items-center justify-center w-full">
    <div class="flex flex-col w-screen items-center">
      <p class="text-3xl text-gray-800 font-semibold my-6">Welcome</p>
      <form class="w-full sm:w-1/2 md:w-1/2 lg:w-1/2 bg-white mb-4 pb-12 pt-10 px-20 rounded shadow-xs">
        <div class="mb-4">
          <label class="block text-gray-700 text-sm font-bold mb-2" for="email">Email</label>
          <input
            v-model="$v.form.email.$model"
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
            id="email"
            type="text"
            placeholder="Email"
          />
          <div v-if="formHasErrors">
            <p class="text-red-500 text-xs italic" v-if="!$v.form.email.required">Please fill the email.</p>
            <p class="text-red-500 text-xs italic" v-if="!$v.form.email.email">Please fill with a valid email.</p>
          </div>
          <div class="mt-1" v-if="formErrorsFromServer.email">
            <p
              class="text-red-500 text-xs italic"
              v-for="(errorMessage, index) in formErrorsFromServer.email" 
              :key="index"
            >
              {{ errorMessage }}
            </p>
          </div>
        </div>
        <div class="mb-6">
          <label class="block text-gray-700 text-sm font-bold mb-2" for="password">Password</label>
          <input
            v-model="$v.form.password.$model"
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline"
            id="password"
            type="password"
            placeholder="******************"
          />
          <div v-if="formHasErrors">
            <p class="text-red-500 text-xs italic" v-if="!$v.form.password.required">Please choose a password.</p>
          </div>
        </div>
        <div class="flex items-center justify-between">
          <button
            class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
            type="button"
            @click.prevent="login"
          >Sign In</button>
          <router-link
            class="inline-block underline align-baseline font-bold text-sm text-red-600 hover:text-red-800"
            :to="{ name: 'forgot-password' }"
          >Forgot Password?</router-link>
        </div>
        <div class="signup-call underline font-bold text-sm text-red-600 hover:text-red-800 mt-5">
          <router-link :to="{ name: 'register' }"> Don't have any account? Click here! </router-link>
        </div>
      </form>
      <p class="text-center text-gray-500 text-xs">&copy;2020 Pizzzaaa. All rights reserved.</p>
    </div>
  </div>
</template>

<script>
import { required, email} from 'vuelidate/lib/validators';

export default {
  name: "Login",

  data() {
    return {
      formHasErrors: false,

      formErrorsFromServer: {
        email: null,
      },

      form: {
        email: '',
        password: '',
      }
    };
  },

  validations: {
    form: {
      email: {
        required,
        email,
      },
      password: {
        required,
      },
    }
  },

  methods: {
    login() {
      // Verify form errors
      this.formHasErrors = this.$v.form.$invalid;

      if (this.formHasErrors) return;

      this.$store.dispatch('login', this.form)
        .then(response => {
          this.$router.push({ 
            name: 'home', 
          });
        })
        .catch(error => {
          if (! error.response) return;

          Object.entries(error.response.data).forEach(error => {
            const field = error[0];
            const errorsFromField = error[1];

            this.formErrorsFromServer[field] = errorsFromField;
          });
        })
    },
  }
};
</script>

<style lang="scss" scoped>
  #main {
    height: calc(100vh - 64px);
  }
</style>