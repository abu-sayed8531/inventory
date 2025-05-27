<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6 center-screen">
            <div class="card animated fadeIn w-90 p-4">
                <div class="card-body">
                    <h4>SET NEW PASSWORD</h4>
                    <br/>
                    <label>New Password</label>
                    <input id="password" placeholder="New Password" class="form-control" type="password"/>
                    <br/>
                    <label>Confirm Password</label>
                    <input id="cpassword" placeholder="Confirm Password" class="form-control" type="password"/>
                    <br/>
                    <button onclick="ResetPass()" class="btn w-100 bg-gradient-primary">Next</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    async function ResetPass(){
        let password = document.getElementById('password').value;
        let cPassword = document.getElementById('cpassword').value;
        if(password.length === 0){
            errorToast('Password is required');
        }
        else if(cPassword.length === 0){
            errorToast('Confirm Password is required')
        }
        else{
            showLoader();
            try{
              let res = await axios.post('/reset-password',{
                password : password,
                password_confirmation : cPassword,
              });
              console.log(res);
              hideLoader();
              if(res.status === 200 && res.data.status === 'success'){
                successToast(res.data.message);
                setTimeout(function(){
                    window.location.href = '/user-login';
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
