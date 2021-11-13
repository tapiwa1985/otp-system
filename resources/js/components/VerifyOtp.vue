<template>
   <div>
      <loading
         :active.sync="isLoading"
         :can-cancel="true"
         :on-cancel="onCancel"
         :is-full-page="fullPage"
         />
      <FlashMessage></FlashMessage>
      <h3 class="text-center">Verify OTP</h3>
      <div class="row">
         <div class="col-md-12">
            <form @submit.prevent>
               <div class="form-group">
                  <div class="form-group">
                     <input 
                        class="form-control" 
                        placeholder="Email Address" 
                        type="text"
                        v-model="requestBody.email"
                        >
                     <div
                        v-if="submitted && !$v.requestBody.email.required"
                        style="color: red"
                        >
                        Email Address is required
                     </div>
                  </div>
                  <input 
                     class="form-control" 
                     placeholder="OTP" 
                     type="text" 
                     v-model="requestBody.otp">
                  <div
                     v-if="submitted && !$v.requestBody.otp.required"
                     style="color: red"
                     >
                     OTP is required
                  </div>
               </div>
               <button class="btn btn-block btn-primary" @click="submitOtpVerificationRequest" type="submit">VERIFY OTP</button>
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
                 otp: { required }
             }
         },
         components: {
             Loading
         },
          methods: {
              submitOtpVerificationRequest() {
                 this.submitted = true;
                 this.$v.$touch();
                 if (this.$v.$invalid) {
                     this.isLoading = false;
                     return;
                 }
                 this.isLoading = true;
                  OTPService.verify(this.requestBody)
                      .then(response => {
                          this.flashMessage.success({
                             status: 'success',
                             title: 'Success',
                             message: 'Verification Successful!',
                             time: 5000,
                         });
                         this.isLoading = false;
                         return;
                      })
                      .catch(function (error){
                          console.log(error)
                              if (error.response) {
                                  if(error.response.status == 401){
                                      this.flashMessage.error({
                                      status: 'Fail',
                                      title: 'Verification Failure',
                                      message: 'OTP Verification failed',
                                      time: 5000,
                         });
                                  }
                              }
                      })
                      .finally(() => this.loading = false)
              },
              onCancel() {
                 console.log('User cancelled the loader.')
             }
          }
      }
</script>