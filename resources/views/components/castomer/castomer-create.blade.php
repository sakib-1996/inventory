<div class="modal animated zoomIn" id="create-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel">Create Castomer</h6>
            </div>
            <div class="modal-body">
                <form id="save-form">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 p-1">
                                <label class="form-label">Castomer Name *</label>
                                <input type="text" class="form-control" id="castomerName">
                                <label class="form-label">Castomer Email *</label>
                                <input type="text" class="form-control" id="castomerEmail">
                                <label class="form-label">Castomer Mobile *</label>
                                <input type="text" class="form-control" id="castomerMobile">
                            </div>
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

<script src="{{ asset('assets/js/imageSave.js') }}"></script>
<script>
    async function Save() {
        let castomerName = document.getElementById('castomerName').value;
        let castomerEmail = document.getElementById('castomerEmail').value;
        let castomerMobile = document.getElementById('castomerMobile').value;

        if (castomerName.trim() === "") {
            errorToast("Customer Name is required!");
            return;
        }
        if (castomerEmail.trim() === "") {
            errorToast("Customer Email is required!");
            return;
        }
        if (castomerMobile.trim() === "") {
            errorToast("Customer Mobile is required!");
            return;
        }
        try {
            let formData = new FormData();
            formData.append('name', castomerName);
            formData.append('email', castomerEmail);
            formData.append('mobile', castomerMobile);

            let res = await axios.post("/api/create-customer", formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                },
            });

            if (res.status !== 201 || res.data.msg === "fail") {
                errorToast("Request failed!");
            } else {
                successToast('Request completed');
                document.getElementById("save-form").reset();
                await getList();
                $('#create-modal').modal('hide');
            }
        } catch (error) {
            console.error("Error:", error);
            errorToast("Request failed!");
        }
    }
</script>
