@extends('admin.admin_dashboard')
@section('admin')

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Import Employees</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <h5>Instructions:</h5>
                            <ol>
                                <li>Download the template file below</li>
                                <li>Fill in employee information in the Excel file</li>
                                <li>Upload the completed file</li>
                                <li>Preview the data and confirm import</li>
                            </ol>
                            <a href="{{ route('employees.template.download') }}" class="btn btn-info">
                                <i class="fas fa-download"></i> Download Template
                            </a>
                        </div>
                        
                        <form id="importForm" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="importFile" class="form-label">Select Excel File</label>
                                <input type="file" name="file" id="importFile" class="form-control" required accept=".xlsx,.xls,.csv">
                                <small class="text-muted">Supported formats: .xlsx, .xls, .csv</small>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-eye"></i> Preview Import
                            </button>
                        </form>
                        
                        <div id="loadingIndicator" class="text-center my-4" style="display: none;">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p>Processing file, please wait...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function(){
    $('#importForm').on('submit', function(e){
        e.preventDefault();
        
        // Validate file input
        let fileInput = $('#importFile')[0];
        if (fileInput.files.length === 0) {
            Swal.fire({
                icon: 'error',
                title: 'No File Selected',
                text: 'Please select an Excel file to import'
            });
            return;
        }
        
        // Check file extension
        let fileName = fileInput.files[0].name;
        let extension = fileName.substring(fileName.lastIndexOf('.')).toLowerCase();
        if (!['.xlsx', '.xls', '.csv'].includes(extension)) {
            Swal.fire({
                icon: 'error',
                title: 'Invalid File',
                text: 'Please select a valid Excel file (.xlsx, .xls) or CSV file'
            });
            return;
        }
        
        // Show loading indicator
        $('#loadingIndicator').show();
        
        let formData = new FormData(this);
        
        $.ajax({
            url: "{{ route('employee.preview-import') }}",
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response){
                // Hide loading indicator
                $('#loadingIndicator').hide();
                
                let employees = response.employees;
                let totalRows = response.total_rows;
                let previewRows = response.preview_rows;
                
                if (employees.length === 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Empty File',
                        text: 'No data found in the uploaded file or headers are missing'
                    });
                    return;
                }
                
                // Build table headers based on first row keys
                let headers = Object.keys(employees[0]);
                
                let table = '<div class="table-responsive"><table class="table table-bordered table-striped"><thead><tr>';
                table += '<th>#</th>';
                
                // Add header cells
                headers.forEach(function(header) {
                    let formattedHeader = header.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                    table += `<th>${formattedHeader}</th>`;
                });
                
                table += '</tr></thead><tbody>';
                
                // Add data rows
                employees.forEach(function(emp, index) {
                    table += '<tr>';
                    table += `<td>${index+1}</td>`;
                    
                    // Add cells for each field
                    headers.forEach(function(header) {
                        table += `<td>${emp[header] !== '' ? emp[header] : '-'}</td>`;
                    });
                    
                    table += '</tr>';
                });
                
                table += '</tbody></table></div>';
                
                // Add notice if only showing partial data
                let noticeHtml = '';
                if (totalRows > previewRows) {
                    noticeHtml = `<div class="alert alert-info">
                        Showing ${previewRows} of ${totalRows} total rows in preview. 
                        All ${totalRows} rows will be imported if you confirm.
                    </div>`;
                }
                
                Swal.fire({
                    title: 'Preview Uploaded Employees',
                    html: noticeHtml + table,
                    width: '90%',
                    confirmButtonText: 'Confirm & Import',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#dc3545',
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Submit actual import now
                        importConfirmed();
                    }
                });
            },
            error: function(xhr) {
                // Hide loading indicator
                $('#loadingIndicator').hide();
                
                let errorMessage = 'Failed to preview import';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                
                Swal.fire({
                    icon: 'error',
                    title: 'Preview Failed',
                    text: errorMessage
                });
            }
        });
    });
});

function importConfirmed(){
    // Show loading indicator
    $('#loadingIndicator').show();
    
    let formData = new FormData($('#importForm')[0]);
    
    $.ajax({
        url: "{{ route('employees.import') }}",
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function(response){
            // Hide loading indicator
            $('#loadingIndicator').hide();
            
            if(response.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Import Successful!',
                    text: response.message,
                    confirmButtonColor: '#28a745',
                }).then(() => {
                    location.reload();
                });
            } else {
                let errorHtml = response.message;
                
                // Add download link if available
                if (response.error_file) {
                    errorHtml += `<div class="mt-3">
                        <a href="${response.error_file}" class="btn btn-warning" download>
                            <i class="fas fa-download"></i> Download Error Report
                        </a>
                    </div>`;
                }
                
                Swal.fire({
                    icon: 'error',
                    title: 'Import Failed',
                    html: errorHtml,
                    confirmButtonColor: '#17a2b8',
                });
            }
        },
        error: function(xhr) {
            // Hide loading indicator
            $('#loadingIndicator').hide();
            
            let errorMessage = 'Failed to import';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            }
            
            Swal.fire({
                icon: 'error',
                title: 'Import Failed',
                text: errorMessage
            });
        }
    });
}
</script>

@endsection