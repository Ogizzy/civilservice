{{-- <div class="sidebar-wrapper" data-simplebar="true"> --}}
<div class="sidebar-wrapper" id="sidebar" data-simplebar="true">

    <div class="sidebar-header">
        <div>
            <img src="{{ asset('backend/assets/images/login-images/benue-logo.png') }}" class="logo-icon" alt="logo icon">
        </div>
        <div>
            <h6>Civil Service Commission</h6>
        </div>


        {{-- <div class="toggle-icon ms-auto"><i class='bx bx-arrow-back'></i></div> --}}
         @if (auth()->check() && auth()->user()->role->role === 'Employee')
        <a href="{{ route('employee.dashboard') }}" class="toggle-icon ms-auto">
            <i class='bx bx-arrow-back'></i>
        </a>
          @elseif(auth()->check())
                <a href="{{ route('admin.dashboard') }}" class="toggle-icon ms-auto">
            <i class='bx bx-arrow-back'></i>
                </a>
            @endif
    </div>

    <!--navigation-->
    <ul class="metismenu" id="menu">
        <!-- Dashboard -->
        <li>
            @if (auth()->check() && auth()->user()->role->role === 'Employee')
                <a href="{{ route('employee.dashboard') }}">
                    <div class="parent-icon"><i class='bx bx-home-alt'></i></div>
                    <div class="menu-title">Dashboard</div>
                </a>
            @elseif(auth()->check())
                <a href="{{ route('admin.dashboard') }}">
                    <div class="parent-icon"><i class='bx bx-home-alt'></i></div>
                    <div class="menu-title">Dashboard</div>
                </a>
            @endif


            <!-- Employee Management Group -->
            @usercan('Employee Management', 'can_edit')
            <li>
                <a href="javascript:;" class="has-arrow">
                    <div class="parent-icon"><i class="bx bx-user-circle"></i></div>
                    <div class="menu-title">Employee Management</div>
                </a>
                <ul>
                    @usercan('Employee Management', 'can_create')
                        <li>
                            <a href="{{ route('employees.create') }}">
                                <i class='bx bx-radio-circle'></i>Create Employee
                            </a>
                        </li>
                    @endusercan

                    <li>
                        <a href="{{ route('employees.index') }}">
                            <i class='bx bx-radio-circle'></i>View Employees
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('import.employees.form') }}">
                            <i class='bx bx-radio-circle'></i>Import Employees
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('employees.index') }}">
                            <i class='bx bx-radio-circle'></i>Transfer Employee
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('employees.index') }}">
                            <i class='bx bx-radio-circle'></i>Promote Employee
                        </a>
                    </li>
                </ul>
            </li>
        @endusercan

      
        @usercan('Employee Management', 'can_create')
          <li class="menu-label">Organization Structure</li>
        <!-- HR Management -->
            <li>
                <a href="javascript:;" class="has-arrow">
                    <div class="parent-icon"><i class="bx bx-comment-check"></i></div>
                    <div class="menu-title">HR Management</div>
                </a>
                <ul>
                    <li>
                        <a href="{{ route('employees.index') }}">
                            <i class='bx bx-radio-circle'></i>Commendations/Awards
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('employees.index') }}">
                            <i class='bx bx-radio-circle'></i>Queries/Misconduct
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('employees.index') }}">
                            <i class='bx bx-radio-circle'></i>Manage Documents
                        </a>
                    </li>
                </ul>
            </li>
        @endusercan


        <!-- Organization Structure -->
        @usercan('Employee Management', 'can_create')
            <li>
                <a href="javascript:;" class="has-arrow">
                    <div class="parent-icon"><i class="bx bx-building"></i></div>
                    <div class="menu-title">Organization</div>
                </a>
                <ul>
                    @usercan('MDA Management', 'can_edit')
                        <li>
                            <a href="{{ route('mdas.index') }}">
                                <i class='bx bx-radio-circle'></i>Manage MDAs
                            </a>
                        </li>
                    @endusercan

                    @usercan('MDA Management', 'can_create')
                        <li>
                            <a href="{{ route('grade-levels.index') }}">
                                <i class='bx bx-radio-circle'></i>Grade Levels
                            </a>
                        </li>
                    @endusercan

                    @usercan('MDA Management', 'can_create')
                        <li>
                            <a href="{{ route('steps.index') }}">
                                <i class='bx bx-radio-circle'></i>Steps
                            </a>
                        </li>
                    @endusercan

                    @usercan('Manage Pay Group', 'can_create')
                        <li>
                            <a href="{{ route('pay-groups.index') }}">
                                <i class='bx bx-radio-circle'></i>Pay Groups
                            </a>
                        </li>
                    @endusercan
                </ul>
            </li>
        @endusercan

        <!-- System Administration -->
        @usercan('Employee Management', 'can_create')
            <li>
                <a href="javascript:;" class="has-arrow">
                    <div class="parent-icon"><i class="bx bx-cog"></i></div>
                    <div class="menu-title">Administration</div>
                </a>
                <ul>
                    <li>
                        <a href="{{ route('roles.index') }}">
                            <i class='bx bx-radio-circle'></i>User Roles
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('features.index') }}">
                            <i class='bx bx-radio-circle'></i>Features
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('permissions.index') }}">
                            <i class='bx bx-radio-circle'></i>Permissions
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('users.index') }}">
                            <i class='bx bx-radio-circle'></i>Manage Users
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('audit.logs') }}">
                            <i class='bx bx-radio-circle'></i>Audit Logs
                        </a>
                    </li>
                </ul>
            </li>
        @endusercan

        <!-- Leave Management -->
        @usercan('Employee Management', 'can_create')
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class="lni lni-timer"></i></div>
                <div class="menu-title">Manage Leaves</div>
            </a>
            <ul>
                @usercan('Employee Management', 'can_create')
                    <li>
                        <a href="{{ route('leave-types.index') }}">
                            <i class='bx bx-radio-circle'></i>Add Leave Type
                        </a>
                    </li>
                @endusercan

                <li>
                    <a href="{{ route('leaves.index') }}">
                        <i class='bx bx-radio-circle'></i>View Leaves
                    </a>
                </li>

                {{-- <li>
                    <a href="{{ route('leaves.create') }}">
                        <i class='bx bx-radio-circle'></i>Apply for Leave
                    </a>
                </li> --}}

                {{-- <li>
                    <a href="{{ route('leave.balance.show') }}">
                        <i class='bx bx-radio-circle'></i>Leave Balance
                    </a>
                </li> --}}
                <li>
                    <a href="{{ route('employee_leaves.history') }}">
                        <i class='bx bx-radio-circle'></i>Leave History
                    </a>
                </li>
            </ul>
        </li>
        @endusercan


        <!-- Reports -->
        @usercan('Employee Management', 'can_create')
            <li class="menu-label">Reports</li>
            <li>
                <a href="javascript:;" class="has-arrow">
                    <div class="parent-icon"><i class="bx bx-bar-chart-alt-2"></i></div>
                    <div class="menu-title">Reports</div>
                </a>
                <ul>
                    <li>
                        <a href="{{ route('reports.employees.per-lga') }}">
                            <i class='bx bx-radio-circle'></i>By LGA
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('reports.by-mda') }}">
                            <i class='bx bx-radio-circle'></i>By MDA
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('reports.by-rank') }}">
                            <i class='bx bx-radio-circle'></i>By Rank
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('reports.by-qualification') }}">
                            <i class='bx bx-radio-circle'></i>By Qualification
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('reports.by-pay-structure') }}">
                            <i class='bx bx-radio-circle'></i>By Pay Structure
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('reports.employees.retired') }}">
                            <i class='bx bx-radio-circle'></i>Retired Staff
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('reports.employees.retiring') }}">
                            <i class='bx bx-radio-circle'></i>Retiring Soon
                        </a>
                    </li>

                </ul>
            </li>
        @endusercan


        {{-- Start Employees Menue --}}
        @php
            $employee = auth()->user()->employee ?? null;
        @endphp

        @if ($employee)
            <li>
                <a href="javascript:;" class="has-arrow">
                    <div class="parent-icon"><i class="fadeIn animated bx bx-info-circle"></i></div>
                    <div class="menu-title">Employment Info</div>
                </a>
                <ul>

                    <li>
                        <a href="{{ route('employees.show', $employee->id) }}">
                            <i class='bx bx-radio-circle'></i>View Profile
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('employees.documents.index', $employee->id) }}">
                            <i class='bx bx-radio-circle'></i>My Documents
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('employees.promotions.index', $employee->id) }}">
                            <i class='bx bx-radio-circle'></i>Promotion History
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('employees.transfers.index', $employee->id) }}">
                            <i class='bx bx-radio-circle'></i>Transfer History
                        </a>
                    </li>

                </ul>
            </li>

            <li>
                <a href="javascript:;" class="has-arrow">
                    <div class="parent-icon"><i class="lni lni-timer"></i></div>
                    <div class="menu-title">Manage Leaves</div>
                </a>
                <ul>

                    <li>
                        <a href="{{ route('leaves.index') }}">
                            <i class='bx bx-radio-circle'></i>View Leaves
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('leaves.create') }}">
                            <i class='bx bx-radio-circle'></i>Apply for Leave
                        </a>
                    </li>

                    {{-- <li>
                        <a href="{{ route('leave.balance.show') }}">
                            <i class='bx bx-radio-circle'></i>Leave Balance
                        </a>
                    </li> --}}

                    <li>
                        <a href="{{ route('employee_leaves.history') }}">
                            <i class='bx bx-radio-circle'></i>Leave History
                        </a>
                    </li>
                </ul>
            </li>
        @endif
    </ul>
    <!--end navigation-->
</div>

<!-- CSS for collapsible menu and sidebar -->
<style>
    /* Sidebar styles */
    /* .sidebar-wrapper {
            width: 250px;
            height: 60px;
            position: relative;
            transition: all 0.3s ease;
            overflow: hidden;
        } */

    .sidebar-wrapper.collapsed {
        width: 70px;
    }

    .sidebar-wrapper.collapsed .sidebar-header h6,
    .sidebar-wrapper.collapsed .menu-title {
        display: none;
    }

    .sidebar-wrapper.collapsed .parent-icon {
        margin-right: 0;
        justify-content: center;
    }

    .sidebar-wrapper.collapsed .metismenu a {
        display: flex;
        justify-content: center;
        padding: 10px 0;
    }

    .sidebar-wrapper.collapsed .has-arrow::after {
        display: none;
    }

    /* Menu styles */
    .metismenu ul {
        display: none;
    }

    .metismenu .mm-show {
        display: block;
    }

    .metismenu .has-arrow::after {
        content: "\f105";
        font-family: 'boxicons';
        font-size: 16px;
        position: absolute;
        right: 15px;
        transition: transform 0.3s ease;
    }

    .metismenu .has-arrow[aria-expanded="true"]::after {
        transform: rotate(90deg);
    }

    /* Toggle icon styles */
    .toggle-icon {
        cursor: pointer;
        font-size: 1.5rem;
        transition: all 0.1s ease;
    }

    .sidebar-wrapper.collapsed .toggle-icon i {
        transform: rotate(180deg);
    }
</style>

<!-- JavaScript for sidebar and menu functionality -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Sidebar toggle functionality
        const toggleIcon = document.getElementById('sidebarToggle');
        const sidebarWrapper = document.querySelector('.sidebar-wrapper');

        if (toggleIcon && sidebarWrapper) {
            toggleIcon.addEventListener('click', function() {
                sidebarWrapper.classList.toggle('collapsed');

                // Change icon based on state
                const icon = this.querySelector('i');
                if (sidebarWrapper.classList.contains('collapsed')) {
                    icon.classList.remove('bx-arrow-back');
                    icon.classList.add('bx-arrow-forward');
                } else {
                    icon.classList.remove('bx-arrow-forward');
                    icon.classList.add('bx-arrow-back');
                }

                // Save state to localStorage
                localStorage.setItem('sidebarCollapsed', sidebarWrapper.classList.contains(
                    'collapsed'));
            });

            // Check for saved state on page load
            if (localStorage.getItem('sidebarCollapsed') === 'true') {
                sidebarWrapper.classList.add('collapsed');
                const icon = toggleIcon.querySelector('i');
                icon.classList.remove('bx-arrow-back');
                icon.classList.add('bx-arrow-forward');
            }
        }

        // Initialize metisMenu
        if (typeof MetisMenu !== 'undefined') {
            new MetisMenu('#menu');
        }

        // Add click handlers for menu items with has-arrow class
        document.querySelectorAll('.has-arrow').forEach(function(element) {
            element.addEventListener('click', function(e) {
                e.preventDefault();

                // Get the parent li element
                const parentLi = this.closest('li');

                // Close all other open submenus first
                document.querySelectorAll('.has-arrow').forEach(function(otherElement) {
                    if (otherElement !== element) {
                        otherElement.setAttribute('aria-expanded', 'false');
                        const otherSubmenu = otherElement.nextElementSibling;
                        if (otherSubmenu && otherSubmenu.tagName === 'UL') {
                            otherSubmenu.classList.remove('mm-show');
                        }
                    }
                });

                // Toggle the current submenu
                const expanded = this.getAttribute('aria-expanded') === 'true' || false;
                this.setAttribute('aria-expanded', !expanded);

                const submenu = this.nextElementSibling;
                if (submenu && submenu.tagName === 'UL') {
                    if (expanded) {
                        submenu.classList.remove('mm-show');
                    } else {
                        submenu.classList.add('mm-show');
                    }
                }
            });
        });
    });
</script>
