<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-lg-12">
            <div class="card px-5 py-5">
                <div class="row justify-content-between ">
                    <div class="align-items-center col">
                        <h4>Role Mangement</h4>
                    </div>
                    <div class="align-items-center col">
                        <button data-bs-toggle="modal" data-bs-target="#create-modal"
                            class="float-end btn m-0 btn-primary">Create User</button>
                    </div>
                </div>
                <hr class="bg-secondary" />
                <div class="table-responsive">
                    <table class="table" id="tableData">
                        <thead>
                            <tr class="bg-light">
                                <th style="width: 8%;">ID</th>
                                <th style="width: 30%;">User Name</th>
                                <th style="width: 45%;">Email</th>
                                <th>Role</th>
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
            let response = await axios.get("/api/user-list");

            // console.log(response.data.data);
            //  if (response.data.msg === "fail" && response.data.data === "authorized") {
            //     window.location.href = "/dashboard";
            //     return;
            // }

            let tableList = $("#tableList");
            let tableData = $("#tableData");

            tableData.DataTable().destroy();
            tableList.empty();

            response.data.data.forEach(function(item, index) {
                let fullName = `${item['firstName']} ${item['lastName']}`;
                let row = `<tr>
                    <td class="align-middle fw-bold">${item['id']}</td>
                    <td class="align-middle">${fullName}</td>
                    <td class="align-middle fw-bold">${item['email']}</td>
                    <td class="align-middle">
                        <select class="form-select roleSelector" name="roleSelector" data-user-id="${item['id']}">
                            <option value="admin">Admin</option>
                            <option value="manager">Manager</option>
                            <option value="user">User</option>
                        </select>
                    </td>
                </tr>`;

                tableList.append(row);
            });

            response.data.data.forEach(function(item) {
                let userRole = item['role'];
                $(`.roleSelector[data-user-id="${item['id']}"]`).val(userRole);
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
            // $('.roleSelector').on('click', function() {
            //     let selectedRole = $('.roleSelector').val();
            //     console.log(selectedRole);
            // })

        } catch (error) {
            console.error(error);
        }
    }
</script>
