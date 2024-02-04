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
                        <input type="password" name="password" id="password" class="form-control" value="admin"
                            placeholder="Password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button onclick="SubmitLogin()" class="btn btn-primary btn-block">Login</button>
                        </div>
                    </div>
                    <p class="mb-1 mt-3 d-flex justify-content-between">
                        <a href="#" class="mr-2 text-primary">Forgot Password</a>
                        <a href="{{ url('/new-login') }}" class="text-success">New Login</a>
                    </p>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    async function SubmitLogin() {
        let email = document.getElementById('email').value;
        let password = document.getElementById('password').value;

        console.log(email);
        console.log(password);
        if (email.length === 0) {
            errorToast("Email is required");
        } else if (password.length === 0) {
            errorToast("Password is required");
        } else {
            try {
                // showLoader();
                let res = await axios.post("/api/user-login", {
                    email: email,
                    password: password
                });

                if (res.status === 200 && res.data['msg'] === 'success') {
                    window.location.href = "/dashboard";
                } else {
                    errorToast(res.data['message']);
                }
            } catch (error) {
                // Handle network errors or unexpected issues
                console.error(error);
                errorToast("An unexpected error occurred. Please try again.");
            } finally {
                // hideLoader();
            }
        }
    }
</script>
