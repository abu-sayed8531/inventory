<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6 center-screen">
            <div class="card animated fadeIn w-90  p-4">
                <div class="card-body">
                    <h4>ENTER OTP CODE</h4>
                    <br/>
                    <label>4 Digit Code Here</label>
                    <input id="otp" placeholder="Code" class="form-control" type="text"/>
                    <br/>
                    <button onclick="VerifyOtp()"  class="btn w-100 float-end bg-gradient-primary">Next</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    async function VerifyOtp(){
        let otp = document.getElementById('otp').value;
        console.log(otp);
        if(otp.length !== 4){
            errorToast('Otp can not be empty and must be a number of 4 digit');
        }
        showLoader();
        try{

            let res = await axios.post('/verify-otp',{
                otp : otp,
                email : sessionStorage.getItem('email'),
            });
            hideLoader();
            if(res.status === 200 && res.data.status === 'success'){
                successToast(res.data.message);
                sessionStorage.clear();
                setTimeout(function(){
                    window.location.href = '/reset-password';
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
</script>