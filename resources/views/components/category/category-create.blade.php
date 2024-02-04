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
                                <label for="categoryName" class="form-label">Category Name *</label>
                                <input type="text" class="form-control" id="categoryName">
                                <label for="categoryImg" class="form-label">Category Image *</label>
                                <div class="d-flex justify-content-between">
                                    <input type="file" class="form-control" id="categoryImg" style="width: 40%;"
                                        onchange="previewImage('categoryImg', 'imagePreviewContainer', 200, 150)">
                                    <div class="ms-3 img-fluid" id="imagePreviewContainer"></div>
                                </div>
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
        let categoryName = document.getElementById('categoryName').value;
        let categoryImage = document.getElementById('categoryImg').files[0];

        if (categoryName.trim() === "") {
            errorToast("Category Name is required!");
            return;
        }
        // console.log("Name:", categoryName);
        try {
            let formData = new FormData();
            formData.append('name', categoryName);

            if (categoryImage) {
                if (validateFileType(categoryImage) && validateFileSize(categoryImage)) {
                    formData.append('image', categoryImage);
                } else {
                    errorToast("File must be jpeg/png/jpg and maxSize 1024!");
                    return;
                }
            }
            let res = await axios.post("/api/create-category", formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                },
            });
            if (res.status === 200) {
                successToast('Request completed');
                document.getElementById("save-form").reset();
                await getList();
                $('#create-modal').modal('hide');

                previewImage('categoryImg', 'imagePreviewContainer', 200, 150);
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
