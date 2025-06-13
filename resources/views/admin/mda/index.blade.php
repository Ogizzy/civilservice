@extends('admin.admin_dashboard')

@section('admin')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

    <!-- SweetAlert2 CSS & JS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <div class="page-content">
        <!-- Page Heading -->
        <div class="card bg-gradient-primary-to-secondary mb-4 shadow-sm border-0">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h3 class="mb-1 fw-bold text-white">MDAs Management</h3>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0 p-0 bg-transparent small">
                                <li class="breadcrumb-item"><a href="#" class="text-white opacity-75"><i class="fas fa-home"></i></a></li>
                                <li class="breadcrumb-item active text-white opacity-75" aria-current="page">Ministries, Departments & Agencies</li>
                            </ol>
                        </nav>
                    </div>
                    <button type="button" class="btn btn-light btn-lg shadow-sm" data-bs-toggle="modal" data-bs-target="#createMdaModal">
                        <i class="fas fa-plus-circle me-2"></i> Add New MDA
                    </button>
                </div>
            </div>
        </div>

        <!-- Flash Messages -->
        <div class="container-fluid px-0">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm border-start border-success border-4" role="alert">
                    <div class="d-flex">
                        <div class="me-3">
                            <i class="fas fa-check-circle fa-2x text-success"></i>
                        </div>
                        <div>
                            <h5 class="alert-heading mb-1">Success!</h5>
                            <p class="mb-0">{{ session('success') }}</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm border-start border-danger border-4" role="alert">
                    <div class="d-flex">
                        <div class="me-3">
                            <i class="fas fa-exclamation-triangle fa-2x text-danger"></i>
                        </div>
                        <div>
                            <h5 class="alert-heading mb-1">Error!</h5>
                            <p class="mb-0">{{ session('error') }}</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- MDAs Table Card -->
            <div class="card shadow-sm rounded-3 border-0 mb-4">
                <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                    <h5 class="mb-0 fw-bold text-primary">
                        <i class="fas fa-building me-2"></i> Registered MDAs
                    </h5>
                    <div class="d-flex">
                        <div class="input-group me-2">
                            <span class="input-group-text bg-light border-0">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                            <input type="text" class="form-control border-0 bg-light" id="tableSearch" placeholder="Search MDAs...">
                        </div>
                    </div>
                </div>
                <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example2" class="table table-striped table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                    <th class="ps-4" width="60">S/N</th>
                                    <th>Name</th>
                                    <th>Code</th>
                                    <th>Status</th>
                                    <th class="text-center" width="180">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($mdas as $index => $mda)
                                    <tr class="border-top">
                                        <td class="ps-4 fw-bold text-muted">{{ $index + 1 }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="icon-box bg-light rounded-3 p-2 me-3">
                                                    <i class="fas fa-building text-primary"></i>
                                                </div>
                                                <span class="fw-medium">{{ $mda->mda }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark rounded-pill px-3 py-2">{{ $mda->mda_code }}</span>
                                        </td>
                                        <td>
                                            <div class="form-check form-switch ms-1 d-flex align-items-center mb-0">
                                                <input class="form-check-input status-switch me-2" type="checkbox" role="switch" 
                                                    onchange="toggleMdaStatus({{ $mda->id }}, this.checked)" 
                                                    {{ $mda->status ? 'checked' : '' }}>
                                                <span class="status-label {{ $mda->status ? 'text-success' : 'text-danger' }}">
                                                    {{ $mda->status ? 'Active' : 'Inactive' }}
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-center gap-2">
                                                <a href="{{ route('mdas.show', $mda->id) }}" class="btn btn-sm btn-primary" title="View Details">
                                                    <i class="fadeIn animated bx bx-list-ul"></i>
                                                </a>

                                                <button class="btn btn-sm btn-warning edit-mda-btn" title="Edit MDA"
                                                    data-id="{{ $mda->id }}" data-name="{{ $mda->mda }}"
                                                    data-code="{{ $mda->mda_code }}">
                                                    <i class="bx bx-edit"></i>
                                                </button>

                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center p-5">
                                            <div class="empty-state">
                                                <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                                                <h5 class="text-muted">No MDAs Found</h5>
                                                <p class="text-muted small mb-3">No MDAs have been added yet.</p>
                                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#createMdaModal">
                                                    <i class="fas fa-plus-circle me-1"></i> Add Your First MDA
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                </div>
                <div class="card-footer bg-white d-flex justify-content-between align-items-center">
                    <div class="text-muted small">Showing <span class="fw-medium">{{ count($mdas) }}</span> MDAs</div>
                    {{-- <div class="btn-group">
                        <button class="btn btn-sm btn-outline-primary" id="exportCSV">
                            <i class="fas fa-file-csv me-1"></i> Export CSV
                        </button>
                        <button class="btn btn-sm btn-outline-primary" id="exportPDF">
                            <i class="fas fa-file-pdf me-1"></i> Export PDF
                        </button>
                    </div> --}}
                </div>
            </div>
            <!-- End Table -->
        </div>
    </div>

    <!-- Create MDA Modal -->
    <div class="modal fade" id="createMdaModal" tabindex="-1" aria-labelledby="createMdaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form action="{{ route('mdas.store') }}" method="POST" class="modal-content border-0 shadow">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fadeIn animated bx bx-circle-plus"></i> Add New MDA
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-4">
                        <label class="form-label fw-medium">MDA Name</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0">
                                <i class="fas fa-building text-primary"></i>
                            </span>
                            <input type="text" name="mda" class="form-control border-0 bg-light" 
                                placeholder="e.g., Ministry of Health" required>
                        </div>
                        <div class="form-text text-muted small">Enter the full name of the MDA</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">MDA Code</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0">
                                <i class="fas fa-hashtag text-primary"></i>
                            </span>
                            <input type="text" name="mda_code" class="form-control border-0 bg-light" 
                                placeholder="e.g., MOH" required>
                        </div>
                        <div class="form-text text-muted small">A unique identifier code for this MDA</div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">
                        <i class="fadeIn animated bx bx-x-circle"></i> Cancel
                    </button>
                    <button class="btn btn-primary px-4">
                        <i class="fadeIn animated bx bx-save"></i> Create MDA
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Offcanvas -->
    <div class="offcanvas offcanvas-end shadow-sm" tabindex="-1" id="editMdaCanvas" aria-labelledby="editMdaCanvasLabel">
        <div class="offcanvas-header bg-primary">
            <h5 class="offcanvas-title fw-bold" id="editMdaCanvasLabel">
                <i class="bx bx-edit"></i> Edit MDA
            </h5>
            <button type="button" class="btn-close text-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-4">
            <form id="editMdaForm" method="POST">
                @csrf @method('PUT')
                <input type="hidden" name="id" id="editMdaId">
                
                <div class="mb-4">
                    <label class="form-label fw-medium">MDA Name</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0">
                            <i class="fas fa-building text-primary"></i>
                        </span>
                        <input type="text" name="mda" class="form-control border-0 bg-light" id="editMdaName" required>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="form-label fw-medium">MDA Code</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0">
                            <i class="bx bx-message-alt-edit text-primary"></i>
                        </span>
                        <input type="text" name="mda_code" class="form-control border-0 bg-light" id="editMdaCode" required>
                    </div>
                </div>
                
                <button class="btn btn-primary w-100 py-2 mt-3">
                    <i class="bx bx-save"></i> Update MDA
                </button>
            </form>
        </div>
    </div>

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // Initialize DataTable with improved options
            const table = $('#example2').DataTable({
                lengthChange: false,
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'copy',
                        className: 'btn btn-sm btn-outline-primary',
                        text: '<i class="fas fa-copy"></i> Copy'
                    },
                    {
                        extend: 'excel',
                        className: 'btn btn-sm btn-outline-success',
                        text: '<i class="fas fa-file-excel"></i> Excel'
                    },
                    {
                        extend: 'pdf',
                        className: 'btn btn-sm btn-outline-danger',
                        text: '<i class="fas fa-file-pdf"></i> PDF'
                    },
                    {
                        extend: 'print',
                        className: 'btn btn-sm btn-outline-info',
                        text: '<i class="fas fa-print"></i> Print'
                    }
                ]
            });
            
            // Custom search box
            $('#tableSearch').on('keyup', function() {
                table.search(this.value).draw();
            });
            
            // Export buttons functionality
            $('#exportCSV').on('click', function() {
                table.button('.buttons-excel').trigger();
            });
            
            $('#exportPDF').on('click', function() {
                table.button('.buttons-pdf').trigger();
            });
        
            // Edit MDA
           $('#example2').on('click', '.edit-mda-btn', function() {
                const id = $(this).data('id');
                const name = $(this).data('name');
                const code = $(this).data('code');
        
                $('#editMdaId').val(id);
                $('#editMdaName').val(name);
                $('#editMdaCode').val(code);
                $('#editMdaForm').attr('action', `/mdas/${id}`);
        
                const editCanvas = new bootstrap.Offcanvas('#editMdaCanvas');
                editCanvas.show();
            });
            
            // Status switch styling
            $('.status-switch').on('change', function() {
                const label = $(this).next('.status-label');
                if ($(this).is(':checked')) {
                    label.removeClass('text-danger').addClass('text-success').text('Active');
                } else {
                    label.removeClass('text-success').addClass('text-danger').text('Inactive');
                }
            });
        });
        
        // Toggle MDA Status with improved SweetAlert
        function toggleMdaStatus(id, isChecked) {
            let action = isChecked ? 'activate' : 'deactivate';
            let status = isChecked ? 'Active' : 'Inactive';
            let url = `/mdas/${id}/${action}`;
            
            Swal.fire({
                title: `Change Status to ${status}?`,
                text: isChecked ? 
                    'This MDA will be active and accessible in the system.' : 
                    'This MDA will be inactive and hidden from regular users.',
                icon: isChecked ? 'question' : 'warning',
                showCancelButton: true,
                confirmButtonColor: isChecked ? '#28a745' : '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: `Yes, make it ${status.toLowerCase()}`,
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading state
                    Swal.fire({
                        title: 'Processing...',
                        html: `Switching MDA status to ${status.toLowerCase()}`,
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    // Redirect to the update URL
                    window.location.href = url;
                } else {
                    // Revert switch state
                    const switchElement = document.querySelector(`input[onchange="toggleMdaStatus(${id}, ${isChecked})"]`);
                    switchElement.checked = !isChecked;
                    const statusLabel = switchElement.nextElementSibling;
                    if (statusLabel) {
                        statusLabel.className = isChecked ? 'status-label text-danger' : 'status-label text-success';
                        statusLabel.textContent = isChecked ? 'Inactive' : 'Active';
                    }
                }
            });
        }
        </script>
        
@endsection