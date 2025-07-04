<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7 animated fadeIn col-lg-6 center-screen">
            <div class="card w-90  p-4">
                <div class="card-body">
                    <h4>SIGN IN</h4>
                    <br/>
                    <input id="email" placeholder="User Email" class="form-control" type="email"/>
                    <br/>
                    <input id="password" placeholder="User Password" class="form-control" type="password"/>
                    <br/>
                    <button onclick="SubmitLogin()" class="btn w-100 bg-gradient-primary">Next</button>
                    <hr/>
                    <div class="float-end mt-3">
                        <span>
                            <a class="text-center ms-3 h6" href="{{url('/user-registration')}}">Sign Up </a>
                            <span class="ms-1">|</span>
                            <a class="text-center ms-3 h6" href="{{url('/send-otp')}}">Forget Password</a>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    
   window.SubmitLogin =  async function (){
        let email = document.getElementById('email').value;
        let password = document.getElementById('password').value;
        if(email.length === 0){
            errorToast('Email field is required');
        }
        else if (password.length === 0 ){
            errorToast('Password field is required');
        }
        else{
            showLoader();
            try{
               let res = await axios.post('/user-login',{
                email : email,
                password : password,
               });
               hideLoader();
               if(res.status === 200 && res.data.status == 'success'){
                successToast(res.data.message);
                setTimeout(function(){
                    window.location.href = '/dashboard';
                },2000);
               }
               else{
                errorToast(res.data.message);
               }
            }
            catch(err){
                hideLoader();
                
                if (err.response.status === 422 && err.response.data.status == 'failed'){

                    errors = err.response.data.errors;
                    for(let field in errors){
                    
                        errorToast(errors[field][0]);
                    }
                }
               else if(err.response.status === 401 && err.response.data.status === 'failed'){
                     errorToast(err.response.data.message);
                }
                else{
                    errorToast('Internal server error');
                }
        }
    }
}
</script>
{{-- 
//  <script>
//     async function SubmitLogin() {
//         let email = document.getElementById('email').value;
//         let password = document.getElementById('password').value;
    
//         if (email.length === 0) {
//             errorToast("Email is required");
//         } else if (password.length === 0) {
//             errorToast("Password is required");
//         } else {
//             showLoader();
//             try {
//                 let res = await axios.post("/user-login", {
//                     email: email,
//                     password: password
//                 });
//                 hideLoader();

//                 if (res.status === 200 && res.data.status === 'success') {
//                     successToast(res.data.message);
//                     setTimeout(function () {
//                         window.location.href = "/dashboard";
//                     }, 2000);
//                 } else {
//                     errorToast(res.data.message);
//                 }
//             } catch (err) {
//                 hideLoader();
//                 if (err.response && err.response.status === 422) {
//                     let errors = err.response.data.errors;
//                     for (let field in errors) {
//                         if (errors.hasOwnProperty(field)) {
//                             errorToast(errors[field][0]);
//                         }
//                     }
//                 } else if (err.response && err.response.status === 401) {
//                     errorToast(err.response.data.message);
//                 } else {
//                     errorToast(err.response.data.message);
//                 }
//             }
//         }
//     }
//     </script>  --}}