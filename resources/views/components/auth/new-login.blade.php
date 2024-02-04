<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-6">
            <div class="card card-outline card-primary">
                <div class="card-header text-center">
                    <a href="#" class="h3">Administrative Panel</a>
                </div>
                <div class="card-body">
                    <p class="login-box-msg">Sign in to start your session</p>

                    <div class="input-group mb-3">
                        <input type="email" name="email" id="email" value="admin@gmail.com"
                            class="form-control @error('email') is-invalid @enderror" placeholder="Email">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="otp" name="otp" id="otp" class="form-control"
                            placeholder="Input your OTP code">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button onclick="SubmitLogin()" class="btn btn-primary btn-block">Submit</button>
                        </div>
                    </div>
                    <p class="mb-1 mt-3 d-flex justify-content-between">
                        <a href="{{ url('/login') }}" class="mr-2 text-primary">LogIn</a>
                    </p>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    async function SubmitLogin() {
        let email = document.getElementById('email').value;
        let otp = document.getElementById('otp').value;

        console.log(email);
        console.log(otp);
        if (email.length === 0) {
            errorToast("Email is required");
        } else if (otp.length === 0) {
            errorToast("OTP is required");
        } else {
            try {
                // showLoader();
                let res = await axios.post("/api/VarifyNewUser", {
                    email: email,
                    otp: otp
                });

                if (res.status === 200 && res.data['msg'] === 'success') {
                    window.location.href = "/create-password";
                } else {
                    errorToast(response.data.data.error);
                }
            } catch (error) {
                // Handle network errors or unexpected issues
                console.error(error);
                errorToast("An unexpected error occurred. Please try again.");
                // hideLoader();
            }
        }
    }
</script>
