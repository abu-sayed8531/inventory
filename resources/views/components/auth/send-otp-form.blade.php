<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6 center-screen">
            <div class="card animated fadeIn w-90  p-4">
                <div class="card-body">
                    <h4>EMAIL ADDRESS</h4>
                    <br/>
                    <label>Your email address</label>
                    <input id="email" placeholder="User Email" class="form-control" type="email"/>
                    <br/>
                    <button onclick="VerifyEmail()"  class="btn w-100 float-end bg-gradient-primary">Next</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    async function VerifyEmail(){
        let email = document.getElementById('email').value;
        
        if(email.length === 0){
            errorToast('Email field is required');
        }
        else{
            showLoader();
            try{
                let res = await axios.post('/send-otp',
                {email : email}
                );
                console.log(res);
                hideLoader();
                if(res.status === 200 && res.data.status === 'success'){
                    sessionStorage.setItem('email',email);
                    successToast(res.data.message);
                    setTimeout(function(){
                        window.location.href = '/verify-otp';
                    },2000);
                }
                else {
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