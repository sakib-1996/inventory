<div class="modal animated zoomIn" id="create-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel">Create Category</h6>
            </div>
            <div class="modal-body">
                <form id="save-form">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 p-1">

                                <label for="firstName" class="form-label">First Name *</label>
                                <input type="text" class="form-control" id="firstName">
                                <label for="lastName" class="form-label">Last Name *</label>
                                <input type="text" class="form-control" id="lastName">
                                <label for="email" class="form-label">Email Address *</label>
                                <input type="text" class="form-control" id="email">
                                <label for="mobile" class="form-label">Mobile *</label>
                                <input type="text" class="form-control" id="mobile">

                            </div>
                            <label for="role" style="width: 60%" class="form-label">Role *</label>
                            <select style="width: 60%" class="form-select roleSelector" name="" id="role">
                                <option value="admin">Admin</option>
                                <option value="manager">Manager</option>
                                <option value="user">User</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button id="modal-close" class="btn bg-gradient-primary" data-bs-dismiss="modal"
                    aria-label="Close">Close</button>
                <button onclick="Save()" id="save-btn" class="btn bg-gradient-success">Save</button>
            </div>
        </div>
    </div>
</div>

<script>
    async function Save() {
        let firstName = document.getElementById('firstName').value;
        let lastName = document.getElementById('lastName').value;
        let email = document.getElementById('email').value;
        let mobile = document.getElementById('mobile').value;
        let role = document.getElementById('role').value;

        let required = firstName.trim() && lastName.trim() && email.trim() && mobile.trim() && role.trim();

        if (required === "") {
            errorToast("All field are required !");
            return;
        }
        // console.log("Name:", categoryName);
        try {
            let formData = new FormData();
            formData.append('firstName', firstName);
            formData.append('lastName', lastName);
            formData.append('email', email);
            formData.append('mobile', mobile);
            formData.append('role', role);

            let res = await axios.post("/api/CreateUser", formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                },
            });
            if (res.status === 201) {
                successToast('Request completed');
                document.getElementById("save-form").reset();
                await getList();
                $('#create-modal').modal('hide');
            } else {
                errorToast("Request fail !");
                return;
            }
        } catch (error) {
            // console.error("Error:", error);
            errorToast("Request fail !");
        }
    }
</script>
