<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DataTable with Live Search & Filters</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            padding: 20px;
        }
        .card {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .card-header {
            background-color: #4e73df;
            color: white;
            border-radius: 10px 10px 0 0 !important;
        }
        .table-header {
            background-color: #4e73df;
            color: white;
        }
        .dataTables_filter {
            margin-bottom: 15px;
        }
        .filter-section {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .btn-add {
            background-color: #4e73df;
            border-color: #4e73df;
            color: white;
        }
        .btn-add:hover {
            background-color: #2e59d9;
            border-color: #2e59d9;
        }
        .status-active {
            background-color: #1cc88a;
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.85rem;
        }
        .status-inactive {
            background-color: #e74a3b;
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.85rem;
        }
        .pagination .page-item.active .page-link {
            background-color: #4e73df;
            border-color: #4e73df;
        }
        .dataTables_info {
            padding-top: 15px !important;
        }
        .action-buttons .btn {
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header py-3">
                        <h5 class="m-0 font-weight-bold">Item Request System</h5>
                    </div>
                    <div class="card-body">
                        <!-- Filter Section -->
                        <div class="row filter-section">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="statusFilter">Status Filter</label>
                                    <select id="statusFilter" class="form-control">
                                        <option value="">All Statuses</option>
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="typeFilter">Type Filter</label>
                                    <select id="typeFilter" class="form-control">
                                        <option value="">All Types</option>
                                        <option value="CPU">CPU</option>
                                        <option value="Monitor">Monitor</option>
                                        <option value="Printer">Printer</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="searchInput">Live Search</label>
                                    <input type="text" class="form-control" id="searchInput" placeholder="Search...">
                                </div>
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button id="add_item" class="btn btn-add w-100">
                                    <i class="fas fa-plus"></i> Add New Item
                                </button>
                            </div>
                        </div>

                        <!-- DataTable -->
                        <table id="requestTable" class="table table-bordered table-striped">
                            <thead class="table-header">
                                <tr>
                                    <th>ID</th>
                                    <th>Item</th>
                                    <th>Description</th>
                                    <th>Remarks</th>
                                    <th>Status</th>
                                    <th>Date Added</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>CPU</td>
                                    <td>Intel Core i7, 16GB RAM</td>
                                    <td>Urgent requirement</td>
                                    <td><span class="status-active">Active</span></td>
                                    <td>2023-10-15</td>
                                    <td class="action-buttons">
                                        <button class="btn btn-sm btn-info"><i class="fas fa-edit"></i></button>
                                        <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Monitor</td>
                                    <td>24 inch LED Display</td>
                                    <td>For new employee</td>
                                    <td><span class="status-active">Active</span></td>
                                    <td>2023-10-14</td>
                                    <td class="action-buttons">
                                        <button class="btn btn-sm btn-info"><i class="fas fa-edit"></i></button>
                                        <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>Printer</td>
                                    <td>Laser Printer</td>
                                    <td>Marketing department</td>
                                    <td><span class="status-inactive">Inactive</span></td>
                                    <td>2023-10-13</td>
                                    <td class="action-buttons">
                                        <button class="btn btn-sm btn-info"><i class="fas fa-edit"></i></button>
                                        <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>CPU</td>
                                    <td>AMD Ryzen 5, 8GB RAM</td>
                                    <td>Replacement for old system</td>
                                    <td><span class="status-active">Active</span></td>
                                    <td>2023-10-12</td>
                                    <td class="action-buttons">
                                        <button class="btn btn-sm btn-info"><i class="fas fa-edit"></i></button>
                                        <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>5</td>
                                    <td>Monitor</td>
                                    <td>27 inch 4K Display</td>
                                    <td>For design team</td>
                                    <td><span class="status-inactive">Inactive</span></td>
                                    <td>2023-10-11</td>
                                    <td class="action-buttons">
                                        <button class="btn btn-sm btn-info"><i class="fas fa-edit"></i></button>
                                        <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Item Modal -->
    <div class="modal fade" id="addItemModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Add New Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="item_body">
                        <div class="item-row mb-3">
                            <div class="form-group">
                                <label>Item</label>
                                <select class="form-control" name="items[]">
                                    <option value="">Select Requested item</option>
                                    <option value="CPU">CPU</option>
                                    <option value="Monitor">Monitor</option>
                                    <option value="Printer">Printer</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Description</label>
                                <input type="text" class="form-control" name="item_desc[]">
                            </div>
                            <div class="form-group">
                                <label>Remarks</label>
                                <input type="text" class="form-control" name="remarks[]">
                            </div>
                            <div class="text-center">
                                <button type="button" class="btn btn-danger btn-sm remove-row" disabled>
                                    <i class="fas fa-trash"></i> Remove
                                </button>
                            </div>
                        </div>
                    </div>
                    <button type="button" id="add_more_items" class="btn btn-success btn-sm mt-2">
                        <i class="fas fa-plus"></i> Add Another Item
                    </button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize DataTable with enhanced options
            let table = $('#requestTable').DataTable({
                "pageLength": 10,
                "responsive": true,
                "searching": true,
                "paging": true,
                "ordering": true,
                "info": true,
                "dom": '<"top"lf>rt<"bottom"ip><"clear">',
                "language": {
                    "search": "Filter records:",
                    "lengthMenu": "Show _MENU_ entries",
                    "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                    "paginate": {
                        "previous": "&laquo;",
                        "next": "&raquo;"
                    }
                }
            });

            // Status filter
            $('#statusFilter').on('change', function() {
                let status = $(this).val();
                if (status === '') {
                    table.columns(4).search('').draw();
                } else {
                    table.columns(4).search(status, true, false).draw();
                }
            });

            // Type filter
            $('#typeFilter').on('change', function() {
                let type = $(this).val();
                if (type === '') {
                    table.columns(1).search('').draw();
                } else {
                    table.columns(1).search(type, true, false).draw();
                }
            });

            // Live search
            $('#searchInput').on('keyup', function() {
                table.search(this.value).draw();
            });

            // Add new item row
            $('#add_more_items').on('click', function(e) {
                e.preventDefault();
                
                const newRow = `
                    <div class="item-row mb-3 border-top pt-3">
                        <div class="form-group">
                            <label>Item</label>
                            <select class="form-control" name="items[]">
                                <option value="">Select Requested item</option>
                                <option value="CPU">CPU</option>
                                <option value="Monitor">Monitor</option>
                                <option value="Printer">Printer</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <input type="text" class="form-control" name="item_desc[]">
                        </div>
                        <div class="form-group">
                            <label>Remarks</label>
                            <input type="text" class="form-control" name="remarks[]">
                        </div>
                        <div class="text-center">
                            <button type="button" class="btn btn-danger btn-sm remove-row">
                                <i class="fas fa-trash"></i> Remove
                            </button>
                        </div>
                    </div>`;

                $('#item_body').append(newRow);
                $('#item_body .remove-row').prop('disabled', false);
            });

            // Remove row (delegated)
            $(document).on('click', '.remove-row', function(e) {
                e.preventDefault();
                $(this).closest('.item-row').remove();
                
                // If only one row remains, disable its remove button
                if ($('#item_body .item-row').length === 1) {
                    $('#item_body .remove-row').prop('disabled', true);
                }
            });

            // Show modal when Add Item button is clicked
            $('#add_item').on('click', function() {
                $('#addItemModal').modal('show');
            });

            // Initialize the remove button state on page load
            if ($('#item_body .item-row').length === 1) {
                $('#item_body .remove-row').prop('disabled', true);
            }
        });
    </script>
</body>
</html>