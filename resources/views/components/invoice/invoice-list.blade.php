<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-lg-12">
            <div class="card px-5 py-5">
                <div class="row justify-content-between ">
                    <div class="align-items-center col">
                        <h5>Invoices</h5>
                    </div>
                    <div class="align-items-center col">
                        <a href="{{ url('/sale-page') }}" class="float-end btn m-0 btn-primary">Create Sale</a>
                    </div>
                </div>
                <hr class="bg-dark " />
                <table class="table" id="tableData">
                    <thead>
                        <tr class="bg-light">
                            <th>No</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Total</th>
                            <th>Vat</th>
                            <th>Discount</th>
                            <th>Payable</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="tableList">

                    </tbody>
                </table>
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
    // Fetch and display the invoice list
    getList();

    async function getList() {
        showLoader();
        let res = await axios.get("/api/invoice-select");
        hideLoader();

        let tableList = $("#tableList");
        let tableData = $("#tableData");

        // Destroy existing DataTable before rebuilding
        tableData.DataTable().destroy();
        tableList.empty();

        res.data.forEach(function(item, index) {
            let row = `<tr>
                    <td>${index + 1}</td>
                    <td>${item['customer']['name']}</td>
                    <td>${item['customer']['mobile']}</td>
                    <td>${item['total']}</td>
                    <td>${item['vat']}</td>
                    <td>${item['discount']}</td>
                    <td>${item['payable']}</td>
                    <td>
                        <button data-id="${item['id']}" data-cus="${item['customer']['id']}" class="viewBtn btn btn-outline-success text-sm px-3 py-1 btn-sm m-0"><i class="fa text-sm fa-eye"></i></button>
                        <button data-id="${item['id']}" data-cus="${item['customer']['id']}" class="deleteBtn btn btn-outline-danger text-sm px-3 py-1 btn-sm m-0"><i class="fa text-sm  fa-trash-alt"></i></button>
                    </td>
                 </tr>`;
            tableList.append(row);
        });

        // Attach event listeners to buttons
        $('.viewBtn').on('click', async function() {
            let id = $(this).data('id');
            let cus = $(this).data('cus');
            await InvoiceDetails(cus, id);
        });

        $('.deleteBtn').on('click', function() {
            let id = $(this).data('id');
            document.getElementById('deleteID').value = id;
            $("#delete-modal").modal('show');
        });

        // Initialize DataTable after rendering the table in the DOM
        new DataTable('#tableData', {
            order: [
                [0, 'desc']
            ],
            pagingType: "simple",
            lengthMenu: [5, 10, 15, 20, 30]
        });
    }
</script>
