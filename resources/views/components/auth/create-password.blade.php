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
                        <input type="password" name="password" id="password"
                            class="form-control @error('email') is-invalid @enderror" placeholder="Inter your password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" name="repet_password" id="repet_password" class="form-control"
                            placeholder="Repet Password">
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
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    async function SubmitLogin() {
        let password = document.getElementById('password').value;
        let repet_password = document.getElementById('repet_password').value;

        // console.log(password);
        // console.log(repet_password);
        if (password.length === 0) {
            errorToast("Repet password is required");
        } else if (repet_password.length === 0) {
            errorToast("Password is required");
        } else if (password != repet_password) {
            errorToast("Password and Repet password not match");
        } else {
            try {
                // showLoader();
                let res = await axios.post("/api/CreatePasswordNewUser", {
                    password: password,
                    password_repeat: repet_password
                });

                if (res.status === 200 && res.data['msg'] === 'success') {
                    window.location.href = "/dashboard";
                } else {
                    errorToast(res.data['message']);
                }
            } catch (error) {

                console.error(error);
                errorToast("An unexpected error occurred. Please try again.");
                // hideLoader();
            }
        }
    }
</script>
