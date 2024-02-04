<div class="modal animated zoomIn" id="create-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel">Product Category</h6>
            </div>
            <div class="modal-body">
                <form id="save-form">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 p-1">
                                <div class="row mt-3">
                                    <div class="col-lg-6 col-xl-6 col-xxl-6">
                                        <label class="form-label">Category Name *</label>
                                        <input type="text" class="form-control" id="productName">
                                    </div>

                                    <div class="col-lg-6 col-xl-6 col-xxl-6">
                                        <label class="form-label">Category *</label>
                                        <select class="form-control" id="category">

                                        </select>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-lg-6 col-xl-6 col-xxl-6">
                                        <label class="form-label">Price *</label>
                                        <input type="text" class="form-control" id="price">
                                    </div>

                                    <div class="col-lg-6 col-xl-6 col-xxl-6">
                                        <label class="form-label">Unit *</label>
                                        <input type="text" class="form-control" id="unit">
                                    </div>
                                </div>
                                <label class="form-label mt-3">Quantity *</label>
                                <input type="number" class="form-control" id="qty">

                                <label class="form-label mt-3">Quantity *</label>
                                <div class="d-flex justify-content-between">
                                    <input type="file" class="form-control mb-3" id="productImg" style="width: 40%;"
                                        onchange="previewImage('productImg', 'imagePreviewContainer', 200, 150)">
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
                <button type="button" onclick="Save()" id="save-btn" class="btn bg-gradient-success">Save</button>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('assets/js/imageSave.js') }}"></script>
<script>
    getCategory();
    async function getCategory() {
        try {
            let categorySelect = $("#category");
            let response = await axios.get("/api/list-category");

            categorySelect.empty(); // Clear existing options
            categorySelect.append('<option value="">Select Category</option>'); // Add default option

            response.data.data.forEach(function(item) {
                let option = `<option value="${item['id']}">${item['name']}</option>`;
                categorySelect.append(option);
            });
        } catch (error) {
            console.error("Error fetching categories:", error);
        }
    }

    async function Save() {
        let productName = document.getElementById('productName').value;
        let category = document.getElementById('category').value;
        let price = document.getElementById('price').value;
        let unit = document.getElementById('unit').value;
        let qty = document.getElementById('qty').value;
        let productImg = document.getElementById('productImg').files[0]; // Update to 'productImg'

        if (productName.trim() === "") {
            errorToast("Product Name is required!");
            return;
        }
        if (category.trim() === "") {
            errorToast("Category is required!");
            return;
        }
        if (price.trim() === "") {
            errorToast("Price is required!");
            return;
        }
        if (unit.trim() === "") {
            errorToast("Unit is required!");
            return;
        }
        if (qty.trim() === "" || isNaN(qty)) {
            errorToast("Quantity must be a valid number!");
            return;
        } else {
            try {
                let formData = new FormData();
                formData.append('name', productName);
                formData.append('category_id', category);
                formData.append('price', price);
                formData.append('unit', unit);
                formData.append('qty', qty);

                if (productImg) {
                    if (validateFileType(productImg) && validateFileSize(productImg)) {
                        formData.append('image', productImg); // Update to 'image'
                    } else {
                        errorToast("File must be jpeg/png/jpg and maxSize 1024!");
                        return;
                    }
                }

                let res = await axios.post("/api/create-product", formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                    },
                });

                if (res.status === 200) {
                    successToast('Request completed');
                    document.getElementById("save-form").reset();
                    await getList();
                    $('#create-modal').modal('hide');
                    previewImage('productImg', 'imagePreviewContainer', 200, 150); // Update to 'productImg'
                } else {
                    errorToast("Request fail !");
                    return;
                }
            } catch (error) {
                console.error("Error:", error);
                errorToast("Request fail !");
            }
        }
    }
</script>
