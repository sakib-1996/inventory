<div class="modal animated zoomIn" id="update-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-success" id="exampleModalLabel">Update Category</h5>
            </div>
            <div class="modal-body">
                <form id="update-form">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 p-1">
                                <label for="categoryNameUpdate" class="form-label fw-bold">Category Name *</label>
                                <input type="text" class="form-control" id="categoryNameUpdate">
                                <input class="d-none" id="updateID">
                                <label for="categoryImgUpdate" class="form-label fw-bold pt-2">Category Image</label>
                                <div class="d-flex justify-content-between">
                                    <input type="file" class="form-control" id="categoryImgUpdate"
                                        style="width: 40%;"
                                        onchange="previewImage('categoryImgUpdate', 'updateImagePreviewContainer', 200, 150)">
                                    <div class="ms-3 img-fluid" id="updateImagePreviewContainer"></div>

                                </div>
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

        const response = await axios.get("/api/category-by-id", {
            params: {
                id: id
            }
        });
        const data = response.data.data;
        document.getElementById('categoryNameUpdate').value = data.name;
        const updateImagePreviewContainer = document.getElementById('updateImagePreviewContainer');
        if (data.image) {
            updateImagePreviewContainer.innerHTML =
                `<img src="${data.image}" alt="${data.name}" class="img-fluid" style="width: 200px; height: 150px;">`;
            // console.log('Form filled up successfully with data:', data);
        } else {
            updateImagePreviewContainer.innerHTML =
                ` <div class="align-items-center"><p class="text-danger fw-bold">No image</p></div>`
        }

    }


    async function Update() {
        let categoryName = document.getElementById('categoryNameUpdate').value;
        let categoryImgUpdate = document.getElementById('categoryImgUpdate').files[0];
        let updateID = document.getElementById('updateID').value;

        if (categoryName.length === 0) {
            errorToast("Category Name Required!");
        } else {
            document.getElementById('update-modal-close').click();
            // showLoader();

            try {
                let formData = new FormData();
                formData.append('name', categoryName);
                formData.append('id', updateID);

                if (categoryImgUpdate) {
                    if (validateFileType(categoryImgUpdate) && validateFileSize(categoryImgUpdate)) {
                        formData.append('image', categoryImgUpdate);
                    } else {
                        errorToast("File must be jpeg/png/jpg or maxSize 1024!");
                        return;
                    }
                }
                // console.log('FormData:', formData);
                let res = await axios.post("/api/update-category", formData, {
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
