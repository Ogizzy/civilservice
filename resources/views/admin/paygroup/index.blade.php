@extends('admin.admin_dashboard')
@section('admin')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

@php
    $totalEmployees = \App\Models\Employee::count();
@endphp

<div class="page-content">
     <!-- Enhanced Action Header -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center flex-wrap">

                            <div>
                                <h4 class="mb-1 fw-bold text-primary">
                                    <i class="bx bx-collection me-2"></i> Pay Group Management
                                </h4>
                                <p class="text-muted mb-0">Manage employee payment categories and classifications</p>
                            </div>
                            
                            <div class="mt-3 mt-md-0">
                                <div class="input-group">
                                   
                                    <button type="button" class="btn btn-primary px-4 d-flex align-items-center" onclick="createPayGroup()">
                                        <i class="lni lni-circle-plus me-2"></i> Add New Pay Group
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
    <!-- Notification Alerts -->
    <div class="container px-0">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show border-start border-success border-4 py-3 shadow-sm" role="alert">
                <div class="d-flex align-items-center">
                    <i class="bx bx-check-circle fs-4 me-3"></i>
                    <div>
                        <strong>Success!</strong> {{ session('success') }}
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-start border-danger border-4 py-3 shadow-sm" role="alert">
                <div class="d-flex align-items-center">
                    <i class="bx bx-error-circle fs-4 me-3"></i>
                    <div>
                        <strong>Error!</strong> {{ session('error') }}
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif



        <!-- Dashboard Stats Summary -->
         <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
					<div class="col">
						<div class="card radius-10 bg-primary bg-gradient">
							<div class="card-body">
								<div class="d-flex align-items-center">
									<div>
										<p class="mb-0 text-white">Total Pay Groups</p>
										<h4 class="my-1 text-white">{{ $payGroups->count() }}</h4>
									</div>
									<div class="text-white ms-auto font-35"><i class="bx bx-group"></i>
									</div>
								</div>
							</div>
						</div>
					</div>
										
					<div class="col">
						<div class="card radius-10 bg-success bg-gradient">
							<div class="card-body">
								<div class="d-flex align-items-center">
									<div>
										<p class="mb-0 text-white">Active Groups</p>
										<h4 class="my-1 text-white">{{ $payGroups->where('status', 1)->count() }}</h4>
									</div>
									<div class="text-white ms-auto font-35"><i class="bx bx-check-circle"></i>
									</div>
								</div>
							</div>
						</div>
					</div>
					
                    <div class="col">
						<div class="card radius-10 bg-danger bg-gradient">
							<div class="card-body">
								<div class="d-flex align-items-center">
									<div>
										<p class="mb-0 text-white">Inactive Groups</p>
										<h4 class="my-1 text-white">{{ $payGroups->where('status', 0)->count() }}</h4>
									</div>
									<div class="text-white ms-auto font-35"><i class="bx bx-x-circle"></i>
									</div>
								</div>
							</div>
						</div>
					</div>
					
                      <div class="col">
						<div class="card radius-10 bg-warning bg-gradient">
							<div class="card-body">
								<div class="d-flex align-items-center">
									<div>
										<p class="mb-0 text-dark">Total Employees</p>
										<h4 class="text-dark my-1">{{ $totalEmployees }}</h4>
									</div>
									<div class="text-dark ms-auto font-35"><i class="bx bx-user-check"></i>
									</div>
								</div>
							</div>
						</div>
					</div> 
				</div>

        

        <!-- Main Content Area -->
        <div class="row">
            <div class="col-12">
               

                <!-- Enhanced Pay Group Table -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table id="payGroupsTable" class="table table-hover align-middle mb-0" style="width:100%">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center" style="width: 60px">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="selectAll">
                                            </div>
                                        </th>
                                        <th class="text-center">S/N</th>
                                        <th>Pay Group</th>
                                        <th>Code</th>
                                        <th>Last Updated</th>
                                        <th>Status</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($payGroups as $index => $group)
                                        <tr class="{{ !$group->status ? 'table-light opacity-75' : '' }}">
                                            <td class="text-center">
                                                <div class="form-check">
                                                    <input class="form-check-input group-checkbox" type="checkbox" value="{{ $group->id }}">
                                                </div>
                                            </td>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    {{-- <div class="avatar avatar-sm me-2 bg-light rounded">
                                                        <span class="avatar-text rounded text-primary fw-bold">{{ substr($group->paygroup, 0, 2) }}</span>
                                                    </div> --}}
                                                    <div>
                                                        <h6 class="mb-0 fw-semibold">{{ $group->paygroup }}</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark border">{{ $group->paygroup_code }}</span>
                                            </td>
                                            
                                            <td>
                                                <small class="text-muted">
                                                    {{ \Carbon\Carbon::parse($group->updated_at)->diffForHumans() }}
                                                </small>
                                            </td>
                                            <td>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input status-switch" type="checkbox" 
                                                        data-id="{{ $group->id }}" 
                                                        {{ $group->status ? 'checked' : '' }}
                                                        onchange="confirmStatusChange(this, {{ $group->id }}, {{ $group->status ? 1 : 0 }})">
                                                    <span class="badge bg-{{ $group->status ? 'success' : 'danger' }} rounded-pill">
                                                        {{ $group->status ? 'Active' : 'Inactive' }}
                                                    </span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                        Actions
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end shadow">
                                                        <li>
                                                            <a href="{{ route('pay-groups.show', $group) }}" class="dropdown-item">
                                                                <i class="lni lni-eye me-2"></i> View Details
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <button class="dropdown-item" onclick="editPayGroup('{{ $group->id }}', '{{ $group->paygroup }}', '{{ $group->paygroup_code }}')">
                                                                <i class="lni lni-pencil me-2"></i> Edit
                                                            </button>
                                                        </li>
                                                      
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li>
                                                            <button class="dropdown-item text-{{ $group->status ? 'danger' : 'success' }}" 
                                                                onclick="toggleStatus({{ $group->id }}, {{ $group->status }})">
                                                                <i class="lni lni-{{ $group->status ? 'close' : 'checkmark' }} me-2"></i> 
                                                                {{ $group->status ? 'Deactivate' : 'Activate' }}
                                                            </button>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-5">
                                                <div class="d-flex flex-column align-items-center">
                                                    <i class="lni lni-folder-alt fs-1 text-muted mb-3"></i>
                                                    <h5 class="fw-bold text-muted">No Pay Groups Found</h5>
                                                    <p class="text-muted">Get started by creating your first pay group</p>
                                                    <button type="button" class="btn btn-primary" onclick="createPayGroup()">
                                                        <i class="lni lni-circle-plus me-1"></i> Add New Pay Group
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            
                        </div>

                        <!-- Bulk Actions Footer -->
                        <div class="bg-light p-3 d-flex justify-content-between align-items-center border-top">
                            <div>
                                <span class="selected-count-text me-2">0 items selected</span>
                                <div class="btn-group btn-group-sm" role="group">
                                    <button type="button" class="btn btn-outline-primary" id="bulkActivateBtn" disabled>
                                        <i class="lni lni-checkmark me-1"></i> Activate
                                    </button>
                                    <button type="button" class="btn btn-outline-danger" id="bulkDeactivateBtn" disabled>
                                        <i class="lni lni-close me-1"></i> Deactivate
                                    </button>
                                </div>
                            </div>
                            <div class="pagination-container">
                                <!-- Pagination will be added by DataTables -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Enhanced Modal with Tabs -->
<div class="modal fade" id="payGroupModal" tabindex="-1" aria-labelledby="payGroupModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="payGroupModalLabel">
                    <i class="bx bx-plus-circle me-2"></i> Add New Pay Group
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <ul class="nav nav-tabs nav-primary mb-4" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#basicInfo" type="button" role="tab">
                            <i class="bx bx-info-circle me-1"></i> Basic Info
                        </button>
                    </li>
                </ul>

                <form id="payGroupForm" action="{{ route('pay-groups.store') }}" method="POST">
                    @csrf
                    <input type="hidden" id="method_field" name="_method" value="POST">
                    
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="basicInfo" role="tabpanel">
                            <div class="mb-3">
                                <label for="paygroup" class="form-label fw-medium">Pay Group Name <span class="text-danger">*</span></label>
                                <input type="text" id="paygroup" name="paygroup" class="form-control form-control-lg" 
                                    placeholder="e.g. Monthly Staff" required autofocus>
                                <div class="form-text">Enter a descriptive name for this pay group</div>
                                @error('paygroup') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="paygroup_code" class="form-label fw-medium">Pay Group Code <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">PG-</span>
                                    <input type="text" id="paygroup_code" name="paygroup_code" class="form-control form-control-lg" 
                                        placeholder="e.g. MSTF001" required>
                                </div>
                                <div class="form-text">A unique identifier code for this pay group</div>
                                @error('paygroup_code') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="status" name="status" value="1" checked>
                                <label class="form-check-label" for="status">Active Status</label>
                            </div>
                        </div>
                        
                      
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                    <i class="bx bx-x me-1"></i> Cancel
                </button>
                <button type="button" class="btn btn-primary px-4" onclick="submitForm()">
                    <i class="bx bx-save me-1"></i> <span id="submitBtnText">Save</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Confirm Action</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4" id="confirmModalBody">
                Are you sure you want to perform this action?
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmActionBtn">Confirm</button>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Initialize DataTable
    $(document).ready(function() {
        const table = $('#payGroupsTable').DataTable({
            dom: '<"row"<"col-md-6"l><"col-md-6"f>>rtip',
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
            pagingType: "full_numbers",
            language: {
                search: "",
                searchPlaceholder: "Search pay groups...",
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ pay groups",
                infoEmpty: "Showing 0 to 0 of 0 pay groups",
                infoFiltered: "(filtered from _MAX_ total pay groups)"
            },
            columnDefs: [
                { orderable: false, targets: [0, 7] }
            ],
            responsive: true,
            order: [[2, 'asc']]
        });

        // Move DataTables pagination to our container
        $('#payGroupsTable_wrapper .dataTables_paginate').appendTo('.pagination-container');

        // Handle "Select All" checkbox
        $('#selectAll').on('change', function() {
            $('.group-checkbox').prop('checked', $(this).prop('checked'));
            updateBulkActionButtons();
        });

        // Handle individual checkboxes
        $(document).on('change', '.group-checkbox', function() {
            updateBulkActionButtons();
            if (!$(this).prop('checked')) {
                $('#selectAll').prop('checked', false);
            } else if ($('.group-checkbox:checked').length === $('.group-checkbox').length) {
                $('#selectAll').prop('checked', true);
            }
        });

        // Show/hide inactive toggle
        $('#showInactiveSwitch').on('change', function() {
            const showInactive = $(this).prop('checked');
            table.draw();
        });
        
        // Export buttons
        $('#exportExcel').on('click', function() {
            table.button('.buttons-excel').trigger();
        });
        
        $('#exportPdf').on('click', function() {
            table.button('.buttons-pdf').trigger();
        });
        
        $('#printData').on('click', function() {
            table.button('.buttons-print').trigger();
        });

        // Handle bulk action buttons
        $('#bulkActivateBtn').on('click', function() {
            bulkStatusChange(1);
        });

        $('#bulkDeactivateBtn').on('click', function() {
            bulkStatusChange(0);
        });

        // Auto-generate paygroup code from name
        $('#paygroup').on('input', function() {
            if ($('#method_field').val() === 'POST') {
                const name = $(this).val().trim();
                if (name) {
                    // Create code from first letters of each word + 3 digits
                    const words = name.split(' ');
                    let code = '';
                    words.forEach(word => {
                        if (word) code += word[0].toUpperCase();
                    });
                    const randomNum = Math.floor(Math.random() * 900) + 100;
                    $('#paygroup_code').val(code + randomNum);
                }
            }
        });
    });

    // Update bulk action buttons based on selections
    function updateBulkActionButtons() {
        const selectedCount = $('.group-checkbox:checked').length;
        $('.selected-count-text').text(selectedCount + ' item' + (selectedCount !== 1 ? 's' : '') + ' selected');
        
        $('#bulkActivateBtn, #bulkDeactivateBtn').prop('disabled', selectedCount === 0);
    }

    // Bulk status change
    function bulkStatusChange(newStatus) {
        const selectedIds = [];
        $('.group-checkbox:checked').each(function() {
            selectedIds.push($(this).val());
        });

        if (selectedIds.length === 0) return;

        const actionText = newStatus ? 'activate' : 'deactivate';
        
        $('#confirmModalBody').html(`Are you sure you want to ${actionText} <strong>${selectedIds.length}</strong> selected pay groups?`);
        $('#confirmActionBtn').removeClass('btn-success btn-danger').addClass(newStatus ? 'btn-success' : 'btn-danger').text(newStatus ? 'Activate' : 'Deactivate');
        
        $('#confirmActionBtn').off('click').on('click', function() {
            // Here you would send AJAX request to process bulk action
            $.ajax({
                url: "",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    ids: selectedIds,
                    status: newStatus
                },
                success: function(response) {
                    $('#confirmModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: `Successfully ${actionText}d ${selectedIds.length} pay groups`,
                        timer: 2000
                    }).then(() => {
                        window.location.reload();
                    });
                },
                error: function(error) {
                    $('#confirmModal').modal('hide');
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'An error occurred while processing your request.'
                    });
                }
            });
        });
        
        $('#confirmModal').modal('show');
    }

    // Create new pay group
    function createPayGroup() {
        $('#payGroupModalLabel').html('<i class="bx bx-plus-circle me-2"></i> Add New Pay Group');
        $('#payGroupForm').trigger('reset');
        $('#payGroupForm').attr('action', '{{ route('pay-groups.store') }}');
        $('#method_field').val('POST');
        $('#submitBtnText').text('Save');
        $('#status').prop('checked', true);
        
        // Reset to first tab
        $('.nav-tabs .nav-link:first').tab('show');
        
        $('#payGroupModal').modal('show');
        setTimeout(() => $('#paygroup').focus(), 500);
    }

    // Edit pay group
    function editPayGroup(id, paygroup, paygroup_code) {
        $('#payGroupModalLabel').html('<i class="bx bx-edit me-2"></i> Edit Pay Group');
        $('#paygroup').val(paygroup);
        $('#paygroup_code').val(paygroup_code);
        $('#payGroupForm').attr('action', '{{ url("pay-groups") }}/' + id);
        $('#method_field').val('PUT');
        $('#submitBtnText').text('Update');
        
        // Get additional data via AJAX
        $.ajax({
            url: '{{ url("pay-groups") }}/' + id + '/edit',
            type: 'GET',
            success: function(data) {
                // Fill in additional fields if available
                if (data.description) $('#description').val(data.description);
                if (data.payment_schedule) $(`input[name="payment_schedule"][value="${data.payment_schedule}"]`).prop('checked', true);
                if (data.allow_overtime) $('#allow_overtime').prop('checked', data.allow_overtime === 1);
                if (data.track_attendance) $('#track_attendance').prop('checked', data.track_attendance === 1);
                $('#status').prop('checked', data.status === 1);
            },
            error: function() {
                // Handle error or continue with basic data
            }
        });
        
        // Reset to first tab
        $('.nav-tabs .nav-link:first').tab('show');
        
        $('#payGroupModal').modal('show');
        setTimeout(() => $('#paygroup').focus(), 500);
    }

    // Toggle status with confirmation
    function toggleStatus(groupId, currentStatus) {
        const action = currentStatus ? 'Deactivate' : 'Activate';
        const actionUrl = currentStatus
            ? "{{ url('pay-groups') }}/" + groupId + "/deactivate"
            : "{{ url('pay-groups') }}/" + groupId + "/activate";

        Swal.fire({
            title: `${action} Pay Group?`,
            text: currentStatus 
                ? "Deactivated pay groups will not be available for new assignments" 
                : "Activating will make this pay group available again",
            icon: currentStatus ? 'warning' : 'question',
            showCancelButton: true,
            confirmButtonText: `Yes, ${action}`,
            confirmButtonColor: currentStatus ? '#dc3545' : '#198754',
            cancelButtonText: 'Cancel',
        }).then(result => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = actionUrl;
                form.style.display = 'none';

                form.innerHTML = `
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="PATCH">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    // Confirm status change via toggle switch
    function confirmStatusChange(element, groupId, currentStatus) {
        // Immediately revert the toggle until confirmation
        $(element).prop('checked', currentStatus === 1);
        
        const newStatus = currentStatus === 1 ? 0 : 1;
        const action = currentStatus === 1 ? 'Deactivate' : 'Activate';
        
        Swal.fire({
            title: `${action} Pay Group?`,
            text: currentStatus === 1
                ? "Deactivated pay groups will not be available for new assignments" 
                : "Activating will make this pay group available again",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: `Yes, ${action}`,
            confirmButtonColor: currentStatus === 1 ? '#dc3545' : '#198754',
            cancelButtonText: 'Cancel',
        }).then(result => {
            if (result.isConfirmed) {
                const actionUrl = currentStatus === 1
                    ? "{{ url('pay-groups') }}/" + groupId + "/deactivate"
                    : "{{ url('pay-groups') }}/" + groupId + "/activate";
                    
                $.ajax({
                    url: actionUrl,
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        _method: "PATCH"
                    },
                    success: function() {
                        // Update UI without page reload
                        $(element).prop('checked', newStatus === 1);
                        
                        // Update badge
                        const badge = $(element).siblings('.badge');
                        badge.removeClass('bg-success bg-danger')
                              .addClass(newStatus === 1 ? 'bg-success' : 'bg-danger')
                              .text(newStatus === 1 ? 'Active' : 'Inactive');
                              
                        // Update row appearance
                        if (newStatus === 0) {
                            $(element).closest('tr').addClass('table-light opacity-75');
                        } else {
                            $(element).closest('tr').removeClass('table-light opacity-75');
                        }
                        
                        // Show toast notification
                        Swal.fire({
                            icon: 'success',
                            title: 'Status Updated',
                            text: `Pay group successfully ${newStatus === 1 ? 'activated' : 'deactivated'}`,
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                        });
                    },
                    error: function() {
                        // Revert toggle on error
                        $(element).prop('checked', currentStatus === 1);
                        
                        // Show error notification
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to update status. Please try again.',
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                        });
                    }
                });
            }
        });
    }

    // Submit form handler
    function submitForm() {
        // Add validation here if needed
        const paygroupValue = $('#paygroup').val().trim();
        const paygroupCodeValue = $('#paygroup_code').val().trim();
        
        if (!paygroupValue) {
            Swal.fire({
                icon: 'warning',
                title: 'Required Field',
                text: 'Please enter a pay group name',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
            $('#paygroup').focus();
            return;
        }
        
        if (!paygroupCodeValue) {
            Swal.fire({
                icon: 'warning',
                title: 'Required Field',
                text: 'Please enter a pay group code',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
            $('#paygroup_code').focus();
            return;
        }
        
        // Show loading state
        const submitBtn = $('#submitBtn');
        const originalText = $('#submitBtnText').text();
        $('#submitBtnText').text('Processing...');
        
        // Submit form through AJAX to avoid page reload
        const formData = $('#payGroupForm').serialize();
        const actionUrl = $('#payGroupForm').attr('action');
        const method = $('#method_field').val();
        
        $.ajax({
            url: actionUrl,
            type: 'POST',
            data: formData,
            success: function(response) {
                $('#payGroupModal').modal('hide');
                
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: method === 'POST' ? 'Pay group created successfully' : 'Pay group updated successfully',
                    timer: 2000
                }).then(() => {
                    // Reload page to show updated data
                    window.location.reload();
                });
            },
            error: function(xhr) {
                // Reset button state
                $('#submitBtnText').text(originalText);
                
                // Handle validation errors
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    let errorMessage = 'Please fix the following errors:<ul>';
                    
                    for (const field in errors) {
                        errorMessage += `<li>${errors[field][0]}</li>`;
                    }
                    
                    errorMessage += '</ul>';
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        html: errorMessage
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Something went wrong. Please try again.'
                    });
                }
            }
        });
    }

    // Filter function for DataTables
    $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
        const showInactive = $('#showInactiveSwitch').prop('checked');
        const status = data[6]; // Status column
        
        if (!showInactive && status.includes('Inactive')) {
            return false;
        }
        return true;
    });
</script>

@endsection