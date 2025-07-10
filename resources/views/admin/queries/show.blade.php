@extends('admin.admin_dashboard')
@section('admin')
<div class="page-content">
    <!-- Page Header -->
    <div class="page-header mb-6">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title mb-1">Query Details</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#">Employees</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('employees.queries.index', $employee->id) }}">{{ $queriesMisconduct->employee->first_name }} {{ $queriesMisconduct->employee->surname }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Query Details</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('employees.queries.index', $employee->id) }}" class="btn btn-outline-primary btn-icon">
                <i class="bi bi-arrow-left me-2"></i>Back to Queries
            </a>
        </div>
    </div>

    <div class="row g-4">
        <!-- Employee Info Card -->
        <div class="col-lg-4">
            <div class="card employee-card h-100">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <div class="avatar-lg mx-auto mb-3">
                            <div class="avatar-circle">
                                <i class="bi bi-person-fill"></i>
                            </div>
                        </div>
                        <h4 class="employee-name mb-1">{{ $queriesMisconduct->employee->surname }}, {{ $queriesMisconduct->employee->first_name }}</h4>
                        <div class="employee-id">
                            <i class="bi bi-badge-cc me-2"></i>
                            <span>ID: {{ $queriesMisconduct->employee->employee_number ?? 'N/A' }}</span>
                        </div>
                    </div>
                    
                    <div class="employee-stats">
                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="bi bi-calendar-event"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-label">Date Issued</div>
                                <div class="stat-value">{{ \Carbon\Carbon::parse($queriesMisconduct->date_issued)->format('M d, Y') }}</div>
                            </div>
                        </div>
                        
                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="bi bi-person-check"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-label">Issued By</div>
                                <div class="stat-value">{{ $queriesMisconduct->user->surname ?? 'N/A' }} {{ $queriesMisconduct->user->first_name ?? 'N/A' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Query Details Card -->
        <div class="col-lg-8">
            <div class="card query-card h-100">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-file-text-fill me-2"></i>Query Summary
                        </h5>
                        <button class="btn btn-sm btn-ghost toggle-btn" type="button" data-bs-toggle="collapse" data-bs-target="#queryText" aria-expanded="true" aria-controls="queryText" onclick="toggleQueryText(this)">
                            <i class="bi bi-chevron-up me-1"></i>
                            <span>Collapse</span>
                        </button>
                    </div>
                </div>
                
                <div class="card-body p-5">
                    <div class="query-content-wrapper">
                        <div id="queryText" class="collapse show">
                            <div class="query-text">
                                {{ $queriesMisconduct->query }}
                            </div>
                        </div>
                    </div>
                </div>
                
                @if($queriesMisconduct->document)
                <div class="card-footer">
                    <div class="document-attachment">
                        <div class="d-flex align-items-center">
                            <div class="attachment-icon">
                                <i class="bi bi-file-earmark-pdf"></i>
                            </div>
                            <div class="attachment-info">
                                <div class="attachment-name">Query Letter Document</div>
                                <div class="attachment-meta">PDF Document</div>
                            </div>
                            <a href="{{ asset('storage/' . $queriesMisconduct->document->document) }}" target="_blank" class="btn btn-primary btn-sm ms-auto">
                                <i class="bi bi-download me-1"></i>Download
                            </a>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Action Bar -->
    <div class="action-bar mt-5">
        <div class="d-flex justify-content-between align-items-center">
            <div class="action-info">
                <small class="text-muted">
                    <i class="bi bi-clock me-1"></i>
                    Last updated: {{ $queriesMisconduct->updated_at->diffForHumans() }}
                </small>
            </div>
            <div class="action-buttons">
                <button class="btn btn-outline-secondary me-2" onclick="window.print()">
                    <i class="bi bi-printer me-2"></i>Print
                </button>
                <a href="{{ route('employees.queries.index', $employee->id) }}" class="btn btn-primary">
                    <i class="bi bi-arrow-left me-2"></i>Back to List
                </a>
            </div>
        </div>
    </div>
</div>

<style>
/* Modern Design System */
:root {
    --primary-50: #f0f4ff;
    --primary-100: #e0edff;
    --primary-500: #3b82f6;
    --primary-600: #2563eb;
    --primary-700: #1d4ed8;
    --gray-50: #f9fafb;
    --gray-100: #f3f4f6;
    --gray-200: #e5e7eb;
    --gray-300: #d1d5db;
    --gray-400: #9ca3af;
    --gray-500: #6b7280;
    --gray-600: #4b5563;
    --gray-700: #374151;
    --gray-800: #1f2937;
    --gray-900: #111827;
    --success-50: #f0fdf4;
    --success-500: #22c55e;
    --warning-50: #fffbeb;
    --warning-500: #f59e0b;
    --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    --border-radius: 12px;
    --border-radius-lg: 16px;
    --border-radius-xl: 20px;
}

/* Page Structure */
.page-content {
    padding: 2rem;
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    min-height: 100vh;
}

.page-header {
    margin-bottom: 2rem;
}

.page-title {
    font-size: 2rem;
    font-weight: 700;
    color: var(--gray-900);
    margin-bottom: 0.5rem;
}

.breadcrumb {
    background: none;
    padding: 0;
    margin: 0;
    font-size: 0.875rem;
}

.breadcrumb-item + .breadcrumb-item::before {
    content: "→";
    color: var(--gray-400);
}

.breadcrumb-item a {
    color: var(--primary-600);
    text-decoration: none;
}

.breadcrumb-item a:hover {
    color: var(--primary-700);
}

.breadcrumb-item.active {
    color: var(--gray-600);
}

/* Cards */
.card {
    border: none;
    border-radius: var(--border-radius-lg);
    box-shadow: var(--shadow-lg);
    background: white;
    transition: all 0.3s ease;
}

.card:hover {
    box-shadow: var(--shadow-xl);
    transform: translateY(-2px);
}

.card-header {
    background: linear-gradient(135deg, var(--primary-50) 0%, var(--primary-100) 100%);
    border-bottom: 1px solid var(--gray-200);
    padding: 1.5rem;
    border-radius: var(--border-radius-lg) var(--border-radius-lg) 0 0;
}

.card-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: var(--gray-800);
}

.card-footer {
    background: var(--gray-50);
    border-top: 1px solid var(--gray-200);
    padding: 1.5rem;
    border-radius: 0 0 var(--border-radius-lg) var(--border-radius-lg);
}

/* Employee Card */
.employee-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
}

.employee-card .card-body {
    position: relative;
    overflow: hidden;
}

.employee-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" patternUnits="userSpaceOnUse" width="100" height="100"><circle cx="50" cy="50" r="1" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    pointer-events: none;
}

.avatar-lg {
    width: 80px;
    height: 80px;
    position: relative;
    z-index: 1;
}

.avatar-circle {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: white;
    border: 2px solid rgba(255, 255, 255, 0.3);
}

.employee-name {
    font-size: 1.5rem;
    font-weight: 700;
    color: white;
    position: relative;
    z-index: 1;
}

.employee-id {
    color: rgba(255, 255, 255, 0.8);
    font-size: 0.875rem;
    font-weight: 500;
    position: relative;
    z-index: 1;
}

.employee-stats {
    margin-top: 2rem;
    position: relative;
    z-index: 1;
}

.stat-item {
    display: flex;
    align-items: center;
    padding: 1rem;
    margin-bottom: 1rem;
    background: rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.stat-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    font-size: 1.125rem;
    color: white;
}

.stat-content {
    flex: 1;
}

.stat-label {
    font-size: 0.75rem;
    color: rgba(255, 255, 255, 0.7);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0.25rem;
}

.stat-value {
    font-size: 0.875rem;
    font-weight: 600;
    color: white;
}

/* Query Card */
.query-card {
    background: white;
}

.query-content-wrapper {
    position: relative;
}

.query-text {
    font-size: 1rem;
    line-height: 1.7;
    color: var(--gray-700);
    padding: 2rem;
    background: var(--gray-50);
    border-radius: var(--border-radius);
    border-left: 4px solid var(--primary-500);
    position: relative;
    min-height: 200px;
    white-space: pre-wrap;
}

.query-text::before {
    content: '';
    position: absolute;
    top: 1rem;
    left: 1rem;
    width: 24px;
    height: 24px;
    background: var(--primary-100);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.query-text::after {
    content: '"';
    position: absolute;
    top: 1rem;
    left: 1rem;
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    color: var(--primary-600);
    font-weight: bold;
}

/* Document Attachment */
.document-attachment {
    padding: 1rem;
    background: var(--gray-50);
    border-radius: var(--border-radius);
    border: 1px solid var(--gray-200);
}

.attachment-icon {
    width: 48px;
    height: 48px;
    border-radius: var(--border-radius);
    background: var(--primary-100);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    color: var(--primary-600);
    font-size: 1.5rem;
}

.attachment-info {
    flex: 1;
}

.attachment-name {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--gray-800);
    margin-bottom: 0.25rem;
}

.attachment-meta {
    font-size: 0.75rem;
    color: var(--gray-500);
}

/* Buttons */
.btn {
    border-radius: var(--border-radius);
    font-weight: 500;
    transition: all 0.2s ease;
    border: none;
    padding: 0.75rem 1.5rem;
}

.btn-primary {
    background: linear-gradient(135deg, var(--primary-500) 0%, var(--primary-600) 100%);
    color: white;
    box-shadow: var(--shadow-sm);
}

.btn-primary:hover {
    background: linear-gradient(135deg, var(--primary-600) 0%, var(--primary-700) 100%);
    transform: translateY(-1px);
    box-shadow: var(--shadow-md);
}

.btn-outline-primary {
    border: 2px solid var(--primary-500);
    color: var(--primary-600);
    background: white;
}

.btn-outline-primary:hover {
    background: var(--primary-500);
    color: white;
    transform: translateY(-1px);
}

.btn-outline-secondary {
    border: 2px solid var(--gray-300);
    color: var(--gray-600);
    background: white;
}

.btn-outline-secondary:hover {
    background: var(--gray-100);
    border-color: var(--gray-400);
    color: var(--gray-700);
}

.btn-ghost {
    background: transparent;
    color: var(--gray-600);
    border: 1px solid transparent;
}

.btn-ghost:hover {
    background: var(--gray-100);
    color: var(--gray-700);
}

.btn-icon {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-sm {
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
}

.toggle-btn {
    font-size: 0.875rem;
    padding: 0.5rem 1rem;
}

/* Action Bar */
.action-bar {
    background: white;
    border-radius: var(--border-radius-lg);
    padding: 1.5rem;
    box-shadow: var(--shadow-md);
}

.action-info {
    display: flex;
    align-items: center;
    color: var(--gray-500);
    font-size: 0.875rem;
}

.action-buttons {
    display: flex;
    gap: 0.75rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .page-content {
        padding: 1rem;
    }
    
    .page-header {
        margin-bottom: 1.5rem;
    }
    
    .page-title {
        font-size: 1.5rem;
    }
    
    .col-lg-4,
    .col-lg-8 {
        margin-bottom: 1rem;
    }
    
    .card-body {
        padding: 1.5rem !important;
    }
    
    .query-text {
        padding: 1rem;
    }
    
    .action-bar {
        padding: 1rem;
    }
    
    .action-bar .d-flex {
        flex-direction: column;
        gap: 1rem;
    }
    
    .action-buttons {
        justify-content: center;
    }
}

/* Print Styles */
@media print {
    .page-content {
        background: white;
        padding: 0;
    }
    
    .page-header,
    .action-bar,
    .toggle-btn,
    .btn {
        display: none !important;
    }
    
    .card {
        box-shadow: none;
        border: 1px solid var(--gray-200);
        margin-bottom: 1rem;
    }
    
    .employee-card {
        background: white !important;
        color: var(--gray-900) !important;
    }
    
    .employee-card .employee-name,
    .employee-card .employee-id,
    .employee-card .stat-value,
    .employee-card .stat-label {
        color: var(--gray-900) !important;
    }
    
    .avatar-circle {
        background: var(--gray-100) !important;
        color: var(--gray-700) !important;
    }
    
    .stat-item {
        background: var(--gray-50) !important;
        border: 1px solid var(--gray-200) !important;
    }
    
    .stat-icon {
        background: var(--gray-200) !important;
        color: var(--gray-700) !important;
    }
}

/* Animation */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.card {
    animation: fadeIn 0.5s ease-out;
}

.card:nth-child(1) {
    animation-delay: 0.1s;
}

.card:nth-child(2) {
    animation-delay: 0.2s;
}

/* Scrollbar Styling */
.query-text::-webkit-scrollbar {
    width: 6px;
}

.query-text::-webkit-scrollbar-track {
    background: var(--gray-100);
    border-radius: 3px;
}

.query-text::-webkit-scrollbar-thumb {
    background: var(--gray-300);
    border-radius: 3px;
}

.query-text::-webkit-scrollbar-thumb:hover {
    background: var(--gray-400);
}
</style>

<script>
function toggleQueryText(button) {
    const isCollapsed = button.querySelector('span').innerText === 'Expand';
    const icon = button.querySelector('i');
    const text = button.querySelector('span');
    
    if (isCollapsed) {
        icon.classList.replace('bi-chevron-down', 'bi-chevron-up');
        text.innerText = 'Collapse';
    } else {
        icon.classList.replace('bi-chevron-up', 'bi-chevron-down');
        text.innerText = 'Expand';
    }
}

// Add smooth scrolling behavior
document.addEventListener('DOMContentLoaded', function() {
    // Add loading animation
    const cards = document.querySelectorAll('.card');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            card.style.transition = 'all 0.5s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
    
    // Add print functionality
    const printBtn = document.querySelector('[onclick="window.print()"]');
    if (printBtn) {
        printBtn.addEventListener('click', function(e) {
            e.preventDefault();
            window.print();
        });
    }
});
</script>
@endsection