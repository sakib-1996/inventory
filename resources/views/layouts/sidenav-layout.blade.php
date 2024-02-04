<div class="bg-dark" id="sidebar-wrapper">
    <div class="sidebar-heading text-center py-4 primary-text fs-4 fw-bold text-uppercase border-bottom"><i
            class="fas fa-user-secret me-2"></i>Codersbite</div>
    <div class="list-group list-group-flush my-3">
        <a href="{{ url('/dashboard') }}" class="list-group-item list-group-item-action bg-transparent second-text"><i
                class="fas fa-tachometer-alt me-2"></i>Dashboard</a>
        <a href="{{ url('/category') }}" class="list-group-item bg-transparent second-text fw-bold"><i
                class="nav-icon fas fa-file-alt me-2"></i>Category</a>
        <a href="{{ url('/product') }}"
            class="list-group-item list-group-item-action bg-transparent second-text fw-bold"><i
                class="fas fa-gift me-2"></i>Products</a>
        <a href="{{ url('/customer') }}"class="list-group-item list-group-item-action bg-transparent second-text fw-bold"><i class="fas fa-chart-line me-2"></i>Customers</a>
        <a href="{{ url('/sale-page') }}"class="list-group-item list-group-item-action bg-transparent second-text fw-bold"><i class="fas fa-paperclip me-2"></i>Sale</a>
        <a href="{{ url('/invoicePage') }}"class="list-group-item list-group-item-action bg-transparent second-text fw-bold"><i class="fas fa-paperclip me-2"></i>Invoices</a>
        <a href="#" class="list-group-item list-group-item-action bg-transparent second-text fw-bold"><i
                class="fas fa-shopping-cart me-2"></i>Store Mng</a>
        <a href="#" class="list-group-item list-group-item-action bg-transparent second-text fw-bold"><i
                class="fas fa-comment-dots me-2"></i>Chat</a>
        <a href="{{ url('/role-management') }}"
            class="list-group-item list-group-item-action bg-transparent second-text fw-bold"><i
                class="fas fa-comment-dots me-2"></i>Role Management</a>
        <a href="#" class="list-group-item list-group-item-action bg-transparent second-text fw-bold"><i
                class="fas fa-map-marker-alt me-2"></i>Outlet</a>
        <a href="#" class="list-group-item list-group-item-action bg-transparent text-danger fw-bold"><i
                class="fas fa-power-off me-2"></i>Logout</a>
    </div>
</div>
