@extends('admin.admin_dashboard')
@section('admin')

<style>
    .balance-container {
        max-width: 600px;
        margin: 0 auto;
        padding: 2rem;
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    
    .balance-header {
        text-align: center;
        margin-bottom: 2rem;
    }
    
    .balance-title {
        font-size: 1.5rem;
        color: #2d3748;
        font-weight: 600;
    }
    
    .balance-year {
        background: #f0f4f8;
        color: #4a5568;
        padding: 0.25rem 1rem;
        border-radius: 20px;
        display: inline-block;
        font-size: 0.9rem;
        margin-top: 0.5rem;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
        margin-bottom: 2rem;
    }
    
    .stat-card {
        padding: 1.5rem;
        border-radius: 8px;
        text-align: center;
    }
    
    .stat-value {
        font-size: 1.75rem;
        font-weight: 700;
        margin-bottom: 0.25rem;
    }
    
    .stat-label {
        font-size: 0.9rem;
        color: #718096;
    }
    
    .entitled-stat {
        background: #ebf8ff;
        color: #3182ce;
    }
    
    .used-stat {
        background: #fff5f5;
        color: #e53e3e;
    }
    
    .remaining-stat {
        background: #f0fff4;
        color: #38a169;
    }
    
    .no-balance {
        text-align: center;
        padding: 2rem;
        background: #f8fafc;
        border-radius: 8px;
        margin-top: 1rem;
    }
    
    .progress-container {
        margin-top: 1.5rem;
    }
    
    .select-container {
        margin-bottom: 2rem;
    }
    
    .loading-spinner {
        display: none;
        text-align: center;
        padding: 2rem;
    }
</style>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="balance-container">
                <div class="balance-header">
                    <h2 class="balance-title">
                        <i class="fas fa-calendar-alt me-2"></i>My Leave Balance
                    </h2>
                    <div class="balance-year">{{ date('Y') }}</div>
                </div>
                
                <div class="select-container">
                    <label for="leaveTypeSelect" class="form-label">Select Leave Type</label>
                    <select id="leaveTypeSelect" class="form-select">
                        <option value="">-- Choose Leave Type --</option>
                        @foreach($leaveTypes as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div id="loadingSpinner" class="loading-spinner">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Loading your leave balance...</p>
                </div>
                
                <div id="balanceResults">
                    <div class="no-balance">
                        <i class="fas fa-info-circle fa-3x mb-3" style="color: #a0aec0;"></i>
                        <h5>No Leave Type Selected</h5>
                        <p class="text-muted">Please select a leave type to view your balance</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const leaveTypeSelect = document.getElementById('leaveTypeSelect');
    const balanceResults = document.getElementById('balanceResults');
    const loadingSpinner = document.getElementById('loadingSpinner');
    
    leaveTypeSelect.addEventListener('change', function() {
        const leaveTypeId = this.value;
        
        if (!leaveTypeId) {
            balanceResults.innerHTML = `
                <div class="no-balance">
                    <i class="fas fa-info-circle fa-3x mb-3" style="color: #a0aec0;"></i>
                    <h5>No Leave Type Selected</h5>
                    <p class="text-muted">Please select a leave type to view your balance</p>
                </div>
            `;
            return;
        }
        
        // Show loading state
        balanceResults.style.display = 'none';
        loadingSpinner.style.display = 'block';
        
        // Fetch leave balance data
        fetch(`/leaves/balance?leave_type_id=${leaveTypeId}`, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            // Hide loading spinner
            loadingSpinner.style.display = 'none';
            
            if (data.entitled_days === 0 && data.used_days === 0 && data.remaining_days === 0) {
                balanceResults.innerHTML = `
                    <div class="no-balance">
                        <i class="fas fa-exclamation-triangle fa-3x mb-3" style="color: #e53e3e;"></i>
                        <h5>No Leave Balance Recorded</h5>
                        <p class="text-muted">You currently have no leave balance for this leave type.</p>
                        <a href="{{ route('leaves.create') }}" class="btn btn-primary mt-2">
                            <i class="fas fa-plus me-2"></i>Apply for Leave
                        </a>
                    </div>
                `;
            } else {
                balanceResults.innerHTML = `
                    <div class="stats-grid">
                        <div class="stat-card entitled-stat">
                            <div class="stat-value">${data.entitled_days}</div>
                            <div class="stat-label">Entitled Days</div>
                        </div>
                        <div class="stat-card used-stat">
                            <div class="stat-value">${data.used_days}</div>
                            <div class="stat-label">Used Days</div>
                        </div>
                        <div class="stat-card remaining-stat">
                            <div class="stat-value">${data.remaining_days}</div>
                            <div class="stat-label">Remaining Days</div>
                        </div>
                    </div>
                    
                    ${data.entitled_days > 0 ? `
                    <div class="progress-container">
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-success" 
                                 role="progressbar" 
                                 style="width: ${(data.remaining_days / data.entitled_days) * 100}%" 
                                 aria-valuenow="${data.remaining_days}" 
                                 aria-valuemin="0" 
                                 aria-valuemax="${data.entitled_days}">
                            </div>
                        </div>
                        <small class="text-muted d-block mt-2 text-center">
                            ${Math.round((data.remaining_days / data.entitled_days) * 100)}% of leave balance remaining
                        </small>
                    </div>
                    ` : ''}
                `;
            }
            
            balanceResults.style.display = 'block';
        })
        .catch(error => {
            loadingSpinner.style.display = 'none';
            balanceResults.innerHTML = `
                <div class="no-balance">
                    <i class="fas fa-exclamation-circle fa-3x mb-3" style="color: #e53e3e;"></i>
                    <h5>Error Loading Balance</h5>
                    <p class="text-muted">Unable to fetch leave balance. Please try again.</p>
                    <button onclick="location.reload()" class="btn btn-outline-primary mt-2">
                        <i class="fas fa-sync-alt me-2"></i>Retry
                    </button>
                </div>
            `;
            balanceResults.style.display = 'block';
        });
    });
});
</script>

@endsection