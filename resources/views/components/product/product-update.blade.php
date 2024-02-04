<div class="modal animated zoomIn" id="update-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-success" id="exampleModalLabel">Update Category</h5>
            </div>
            <div class="modal-body">
                <form id="update-form">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 p-1">

                                <div class="row mt-3">
                                    <div class="col-lg-6 col-xl-6 col-xxl-6">
                                        <label class="form-label">Product Name *</label>
                                        <input type="text" class="form-control" id="productNameUpdate">
                                    </div>
                                    <div class="col-lg-6 col-xl-6 col-xxl-6">
                                        <label class="form-label">Category *</label>
                                        <select class="form-control" id="categoryUpdate">

                                        </select>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-lg-6 col-xl-6 col-xxl-6">
                                        <label class="form-label">Price *</label>
                                        <input type="text" class="form-control" id="priceUpdate">
                                    </div>

                                    <div class="col-lg-6 col-xl-6 col-xxl-6">
                                        <label class="form-label">Unit *</label>
                                        <input type="text" class="form-control" id="unitUpdate">
                                    </div>
                                </div>
                                <input class="d-none" id="updateID">
                                <label class="form-label mt-3">Quantity *</label>
                                <input type="number" class="form-control" id="qtyUpdate">

                                <label class="form-label fw-bold pt-2">Category Image</label>
                                <div class="d-flex justify-content-between">
                                    <input type="file" class="form-control" id="productImgUpdate" style="width: 40%;"
                                        onchange="previewImage('productImgUpdate', 'updateImagePreviewContainer', 200, 150)">
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
    async function getCategory() {
        try {
            let categorySelect = $("#categoryUpdate");
            let response = await axios.get("/api/list-category");

            categorySelect.empty(); // Clear existing options
            categorySelect.append('<option value="">Select Category</option>'); // Add default option

            response.data.data.forEach(function(item) {
                let option = `<option value="${item.id}">${item.name}</option>`;
                categorySelect.append(option);
            });
        } catch (error) {
            console.error("Error fetching categories:", error);
        }
    }

    async function FillUpUpdateForm(id) {
        document.getElementById('updateID').value = id;

        try {
            const response = await axios.get("/api/product-by-id", {
                params: {
                    id: id
                }
            });

            const data = response.data.data;
            document.getElementById('categoryUpdate').value = data.category_id;

            document.getElementById('productNameUpdate').value = data.name;
            document.getElementById('priceUpdate').value = data.price;
            document.getElementById('unitUpdate').value = data.unit;
            document.getElementById('qtyUpdate').value = data.qty;
            const updateImagePreviewContainer = document.getElementById('updateImagePreviewContainer');
            if (data.img_url) {
                updateImagePreviewContainer.innerHTML =
                    `<img src="${data.img_url}" alt="${data.name}" class="img-fluid" style="width: 200px; height: 150px;">`;
            } else {
                updateImagePreviewContainer.innerHTML =
                    ` <div class="align-items-center"><p class="text-danger fw-bold">No image</p></div>`;
            }
        } catch (error) {
            console.error("Error fetching product details:", error);
        }
    }

    async function Update() {
        let productNameUpdate = document.getElementById('productNameUpdate').value;
        let categoryUpdate = document.getElementById('categoryUpdate').value;
        let priceUpdate = document.getElementById('priceUpdate').value;
        let unitUpdate = document.getElementById('unitUpdate').value;
        let qtyUpdate = document.getElementById('qtyUpdate').value;

        let productImgUpdate = document.getElementById('productImgUpdate').files[0];
        let updateID = document.getElementById('updateID').value;

        if (!productNameUpdate.trim() || !categoryUpdate.trim() || !priceUpdate.trim() || !unitUpdate.trim() ||
            isNaN(qtyUpdate.trim())) {
            errorToast("Please fill in all required fields with valid values.");
            return;
        }

        document.getElementById('update-modal-close').click();

        try {
            let formData = new FormData();
            formData.append('name', productNameUpdate);
            formData.append('category_id', categoryUpdate);
            formData.append('price', priceUpdate);
            formData.append('unit', unitUpdate);
            formData.append('qty', qtyUpdate);
            formData.append('id', updateID);

            if (productImgUpdate) {
                if (validateFileType(productImgUpdate) && validateFileSize(productImgUpdate)) {
                    formData.append('image', productImgUpdate);
                } else {
                    errorToast("File must be jpeg/png/jpg and have a max size of 1024KB.");
                    return;
                }
            }

            let res = await axios.post("/api/update-product", formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                },
            });

            if (res.status === 200) {
                successToast('Product updated successfully.');
                document.getElementById("update-form").reset();
                await getList();
                $('#update-modal').modal('hide');
            } else {
                errorToast("Failed to update product. Please try again.");
            }
        } catch (error) {
            console.error("Error during update:", error);
            errorToast("Failed to update product. Please try again.");
        }
    }
</script>
