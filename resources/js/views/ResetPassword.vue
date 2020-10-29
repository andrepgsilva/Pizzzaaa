<template>
  <div id="main" class="flex items-center justify-center w-full">
    <div class="flex flex-col h-full w-screen items-center">
      <p class="text-3xl text-gray-800 font-semibold my-6 text-center">Reset your password here</p>
      <form
        class="w-full sm:w-1/2 md:w-1/2 lg:w-1/2 bg-white mb-4 pb-12 pt-10 px-20 rounded shadow-xs"
        method="POST"
      >
        <input type="hidden" name="token" :value="this.$route.query.token">
        <div class="mb-4">
          <label class="block text-gray-700 text-sm font-bold mb-2" for="email">Email</label>
          <input
            v-model="$v.form.email.$model"
            class="shadow appearance-none border rounded w-full py-2 px-3 mb-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
            name="email"
            id="email"
            type="text"
            placeholder="youremail@example.com"
          />
          <div v-if="formHasErrors">
            <p class="text-red-500 text-xs italic" v-if="!$v.form.email.required">Please fill the email.</p>
            <p class="text-red-500 text-xs italic" v-if="!$v.form.email.email">Please fill with a valid email.</p>
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
        <div class="mb-6">
          <label
            class="block text-gray-700 text-sm font-bold mb-2"
            for="password-confirmation"
          >Password Confirmation</label>
          <input
            v-model="$v.form.password_confirmation.$model"
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline"
            id="password-confirmation"
            type="password"
            placeholder="******************"
          />
          <div v-if="formHasErrors">
            <p class="text-red-500 text-xs italic" v-if="!$v.form.password_confirmation.required">Please confirm password.</p>
            <p class="text-red-500 text-xs italic" v-if="!$v.form.password_confirmation.sameAsPassword">The passwords do not match.</p>
          </div>
        </div>
        <div class="flex-col flex sm:flex-row md:flex-row lg:flex-row items-center justify-between">
          <button
            class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
            @click.prevent="resetPassword"
          >Reset Password</button>
          <router-link  
            v-if="hasErrorsFromServer"
            class="block underline font-bold text-sm text-red-600 hover:text-red-800 mt-5" 
            :to="{ name: 'forgot-password' }"
          >
            The reset password request was invalid. Click here for try again.  
          </router-link>
        </div>
      </form>
      <p class="text-center text-gray-500 text-xs">&copy;2020 Pizzzaaa. All rights reserved.</p>
    </div>
  </div>
</template>

<script>
import { required, email, sameAs } from 'vuelidate/lib/validators'

export default {
  name: "Register",

  data() {
    return {
      formHasErrors: false,
      hasErrorsFromServer: false,

      form: {
        email: '',
        password: '',
        password_confirmation: '',
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
      password_confirmation: {
        required,
        sameAsPassword: sameAs('password')
      }
    }
  },

  mounted() {
    this.form.email = this.$route.query.email;
  },

  methods: {
    resetPassword() {
      // Verify form errors
      this.formHasErrors = this.$v.form.$invalid;

      if (this.formHasErrors) return;

      let data = this.form;
      data['token'] = this.$route.query.token;

      this.$store.dispatch('resetPassword', data)
        .then(response => {
          this.$router.push({ 
            name: 'login', 
          });
        })
        .catch(error => {
          //Token was wrong, redirect to forgot password page
          this.hasErrorsFromServer = true;


          // Object.entries(error.response.data).forEach(error => {
          //   const field = error[0];
          //   const errorsFromField = error[1];

          //   this.formErrorsFromServer[field] = errorsFromField;
          // });
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