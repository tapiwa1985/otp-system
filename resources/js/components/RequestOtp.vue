<template>
   <div>
      <loading
         :active.sync="isLoading"
         :can-cancel="true"
         :on-cancel="onCancel"
         :is-full-page="fullPage"
         />
      <FlashMessage></FlashMessage>
      <nav class="navbar navbar-expand-lg navbar-light bg-light">
         <div class="collapse navbar-collapse">
            <div class="navbar-nav">
               <router-link to="/" class="nav-item nav-link">RESEND OTP</router-link>
               <router-link to="/verify" class="nav-item nav-link">VERIFY OTP</router-link>
            </div>
         </div>
      </nav>
      <h3 class="text-center">Request OTP</h3>
      <div class="row">
         <div class="col-md-12">
            <form @submit.prevent>
               <div class="form-group">
                  <input type="text" 
                     required="true" 
                     placeholder="Email Address" 
                     class="form-control" 
                     v-model="requestBody.email"
                     >
                  <div
                     v-if="submitted && !$v.requestBody.email.required"
                     style="color: red"
                     >
                     Email Address is required
                  </div>
               </div>
               <button type="submit" class="btn btn-block btn-primary"  @click="submitOtpRequest">SUBMIT</button>
            </form>
         </div>
      </div>
   </div>
</template>
<script>
   import Loading from 'vue-loading-overlay';
   import 'vue-loading-overlay/dist/vue-loading.css';
   import { required, email } from 'vuelidate/lib/validators';
   import OTPService from "../services/OTPService";
   export default {
       data() {
           return {
               isLoading: false,
               fullPage: true,
               errors: [],
               requestBody: {},
               submitted: false
           }
       },
       validations: {
           requestBody: {
               email: { required, email },
           }
       },
       components: {
           Loading
       },
       methods: {
           submitOtpRequest() {
               this.submitted = true;
               this.$v.$touch();
               if (this.$v.$invalid) {
                   this.isLoading = false;
                   return;
               }
               this.isLoading = true;
               OTPService.requestOtp(this.requestBody)
                   .then(response => {
                        this.flashMessage.success({
                           status: 'success',
                           title: 'Success',
                           message: 'OTP sent to your email address!',
                           time: 5000,
                       });
                       this.isLoading = false;
                       return;
                    })
                           .catch(err => console.log(err))
                           .finally(() => this.loading = false);
                       this.isLoading = false;
                       return;
                   this.isLoading = false;
                   return;
           },
           onCancel() {
               console.log('User cancelled the loader.')
           }
       }
   }
</script>