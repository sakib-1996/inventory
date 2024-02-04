<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-lg-12">
            <div class="card px-5 py-5">
                <div class="row justify-content-between ">
                    <div class="align-items-center col">
                        <h4>Products</h4>
                    </div>
                    <div class="align-items-center col">
                        <button data-bs-toggle="modal" data-bs-target="#create-modal"
                            class="float-end btn m-0 btn-primary">Create</button>
                    </div>
                </div>
                <hr class="bg-secondary" />
                <div class="table-responsive">
                    <table class="table" id="tableData">
                        <thead>
                            <tr class="bg-light">
                                <th style="width: 1%">No</th>
                                <th style="width: 5%">Product Image</th>
                                <th style="width: 10%">Product Name</th>
                                <th>Category</th>
                                <th style="width: 5%">Total Quantity</th>
                                <th>Price</th>
                                <th>Unit</th>
                                <th>Quantity</th>
                                <th style="width: 10%">Action</th>
                            </tr>
                        </thead>
                        <tbody id="tableList">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- DataTables JS -->
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js">
</script>


<script>
    getList();
    async function getList() {
        try {
            
            showLoader();
            let response = await axios.get("/api/list-product");
            hideLoader();

            let tableList = $("#tableList");
            let tableData = $("#tableData");

            tableData.DataTable().destroy();
            tableList.empty();

            let defaultImageUrl = 'https://upload.wikimedia.org/wikipedia/commons/1/14/No_Image_Available.jpg'
            response.data.data.forEach(function(item, index) {
                let row = `<tr>
                    <td class="align-middle fw-bold">${index + 1}</td>
                    <td class="align-middle"><img src="${item['img_url'] || defaultImageUrl}" alt="${item['name']}" class="img-fluid" style="width: 100px; height: 90px;"></td>
                    <td class="align-middle">${item['name']}</td>
                    <td class="align-middle">${item['category_id']}</td>
                    <td class="align-middle">${item['total_qty']}</td>
                    <td class="align-middle">${item['price']}</td>
                    <td class="align-middle">${item['unit']}</td>
                    <td class="align-middle">${item['qty']}</td>
                    <td class="align-middle">
                        <button style="width: 80px;" data-id="${item['id']}" class=" d-block m-1 btn editBtn btn-sm btn-outline-success">Edit</button>
                        <button style="width: 80px;" data-id="${item['id']}" class="m-1 d-block btn deleteBtn btn-sm btn-outline-danger">Delete</button>
                    </td>
                </tr>`;
                tableList.append(row);
            });

            $('.editBtn').on('click', async function() {
                let id = $(this).data('id');
                console.log(id);
                await FillUpUpdateForm(id);
                await getCategory(id);
                $("#update-modal").modal('show');
                
            });

            $('.deleteBtn').on('click', function() {
                let id = $(this).data('id');
                $("#delete-modal").modal('show');
                $("#deleteID").val(id);
            });

            new DataTable('#tableData', {
                order: [
                    [0, 'asc']
                ],
                pagingType: "simple",
                lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ],
            });
        } catch (error) {
            console.error(error);
            // Handle error if needed
        }
    }
</script>