<div class="modal animated zoomIn" id="update-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-success" id="exampleModalLabel">Update Castomer</h5>
            </div>
            <div class="modal-body">
                <form id="update-form">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 p-1">
                                <input class="d-none" id="updateID">
                                <label class="form-label">Castomer Name *</label>
                                <input type="text" class="form-control" id="castomerNameUpdate">
                                <label class="form-label">Castomer Email *</label>
                                <input type="text" class="form-control" id="castomerEmailUpdate">
                                <label class="form-label">Castomer Mobile *</label>
                                <input type="text" class="form-control" id="castomerMobileUpdate">
                            </div>
                        </div>
                </form>
            </div>
            <div class="modal-footer">
                <button id="update-modal-close" class="btn btn-danger" data-bs-dismiss="modal"
                    aria-label="Close">Close</button>
                <button onclick="Update()" id="update-btn" class="btn btn-success">Update</button>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('assets/js/imageSave.js') }}"></script>

<script>
    async function FillUpUpdateForm(id) {
        document.getElementById('updateID').value = id;

        const response = await axios.get("/api/customer-by-id", {
            params: {
                id: id
            }
        });
        const data = response.data.data;
        document.getElementById('castomerNameUpdate').value = data.name;
        document.getElementById('castomerEmailUpdate').value = data.email;
        document.getElementById('castomerMobileUpdate').value = data.mobile;
    }


    async function Update() {
        let castomerNameUpdate = document.getElementById('castomerNameUpdate').value;
        let castomerEmailUpdate = document.getElementById('castomerEmailUpdate').value;
        let castomerMobileUpdate = document.getElementById('castomerMobileUpdate').value;

        let updateID = document.getElementById('updateID').value;

        if (castomerNameUpdate.length === 0) {
            errorToast("Catomer Name Required!");
            return;
        }
        if (castomerEmailUpdate.length === 0) {
            errorToast("Catomer Email Required!");
            return;
        }
        if (castomerMobileUpdate.length === 0) {
            errorToast("Catomer Mobile Required and Uniq!");
            return;
        } else {
            document.getElementById('update-modal-close').click();
            // showLoader();

            try {
                let formData = new FormData();
                formData.append('name', castomerNameUpdate);
                formData.append('email', castomerEmailUpdate);
                formData.append('mobile', castomerMobileUpdate);
                formData.append('id', updateID);

                // console.log('FormData:', formData);
                let res = await axios.post("/api/update-customer", formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                    },
                });

                if (res.status === 200) {
                    successToast('Request completed');
                    document.getElementById("update-form").reset();
                    await getList();
                    $('#update-modal').modal('hide');
                } else {
                    errorToast("Request fail !");
                    return;
                }
            } catch (error) {
                console.error("Error during update:", error);
                errorToast("Request fail !");
            }
        }
    }
</script>
