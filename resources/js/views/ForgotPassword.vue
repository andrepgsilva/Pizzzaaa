<template>
  <div id="main" class="flex items-center justify-center w-full">
    <div class="flex flex-col w-screen items-center">
      <p class="text-3xl text-gray-800 font-semibold my-6">Forgot Password</p>
      <form class="w-full sm:w-1/2 md:w-1/2 lg:w-1/2 bg-white mb-4 pb-12 pt-10 px-20 rounded shadow-xs">
        <div class="mb-4">
          <label class="block text-gray-700 text-sm font-bold mb-2" for="email">Email</label>
          <input
            v-model="$v.form.email.$model"
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
            id="email"
            type="text"
            placeholder="The reset password email will be send to the adress in this box"
          />
          <div class="mt-1" v-if="formHasErrors">
            <p class="text-red-500 text-xs italic" v-if="!$v.form.email.required">Please fill the email.</p>
            <p class="text-red-500 text-xs italic" v-if="!$v.form.email.email">Please fill with a valid email.</p>
          </div>
          <p v-if="formSubmited" class="text-green-500 text-xs italic mt-1">
            The reset email password was sended
          </p>
        </div>
        <div class="flex items-center justify-between">
          <button
            class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none"
            type="button"
            @click.prevent="submit"
          >Reset Password</button>
        </div>
      </form>
      <p class="text-center text-gray-500 text-xs">&copy;2020 Pizzzaaa. All rights reserved.</p>
    </div>
  </div>
</template>

<script>
import { required, email} from 'vuelidate/lib/validators';

export default {
  data() {
    return {
      formHasErrors: null,
      formSubmited: false,

      form: {
        email: '',
      }
    }
  },

  validations: {
    form: {
      email: {
        required,
        email,
      },
    }
  },

  methods: {
    submit() {
      // Verify form errors
      this.formHasErrors = this.$v.form.$invalid;

      if (this.formHasErrors) return;

      this.$store.dispatch('forgotPassword', this.form.email);
      this.formSubmited = true;
    },
  }
}
</script>

<style lang="scss" scoped>
  #main {
    height: calc(100vh - 64px);
  }
</style>