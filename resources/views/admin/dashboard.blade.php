@extends('admin.admin_dashboard')
@section('admin')

@php
    use Carbon\Carbon;
    $totalEmployees = \App\Models\Employee::count();
    $totalMdas = \App\Models\MDA::count();
    $retired = \App\Models\Employee::where('retirement_date', '<=', Carbon::today())->count();
    $retiring = \App\Models\Employee::whereBetween('retirement_date', [Carbon::today(), Carbon::today()->addMonths(6)])->count();
    $recentEmployees = \App\Models\Employee::latest()->take(5)->get();
@endphp

<div class="page-content">
    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">

       <div class="col">
         <div class="card radius-10 border-start border-0 border-4 border-info">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div>
                        <p class="mb-0 text-secondary">Total Employees</p>
                        <h4 class="my-1 text-info">{{ $totalEmployees }}</h4>
                        <p class="mb-0 font-13">Current Employees</p>
                    </div>
                    <div class="widgets-icons-2 rounded-circle bg-gradient-blues text-white ms-auto"><i class="fadeIn animated bx bx-user-check"></i>
                    </div>
                </div>
            </div>
         </div>
       </div>

       <div class="col">
        <div class="card radius-10 border-start border-0 border-4 border-danger">
           <div class="card-body">
               <div class="d-flex align-items-center">
                   <div>
                       <p class="mb-0 text-secondary">Retired Employees</p>
                       <h4 class="my-1 text-danger">{{ $retired }}</h4>
                       <p class="mb-0 font-13">Total Retirees</p>
                   </div>
                   <div class="widgets-icons-2 rounded-circle bg-gradient-burning text-white ms-auto"><i class="fadeIn animated bx bx-user-x"></i>
                   </div>
               </div>
           </div>
        </div>
      </div>

      
      <div class="col">
        <div class="card radius-10 border-start border-0 border-4 border-success">
           <div class="card-body">
               <div class="d-flex align-items-center">
                   <div>
                       <p class="mb-0 text-secondary">Retiring Soon</p>
                       <h4 class="my-1 text-success">{{ $retiring }}</h4>
                       <p class="mb-0 font-13 text-success">(In 6 Months)</p>
                   </div>
                   <div class="widgets-icons-2 rounded-circle bg-gradient-ohhappiness text-white ms-auto"><i class="fadeIn animated bx bx-user-minus"></i>
                   </div>
               </div>
           </div>
        </div>
      </div>

      <div class="col">
        <div class="card radius-10 border-start border-0 border-4 border-warning">
           <div class="card-body">
               <div class="d-flex align-items-center">
                   <div>
                       <p class="mb-0 text-secondary">Total MDAs</p>
                       <h4 class="my-1 text-warning">{{ $totalMdas}}</h4>
                       <p class="mb-0 font-13">MDAs in State</p>
                   </div>
                   <div class="widgets-icons-2 rounded-circle bg-gradient-orange text-white ms-auto"><i class="fadeIn animated bx bx-church"></i>
                   </div>
               </div>
           </div>
        </div>
      </div> 

      {{-- Male Total --}}
      <div class="col">
        <div class="card radius-10">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div>
                        <p class="mb-0 text-secondary">Total Male Employees</p>
                        <h4 class="my-1">{{ $maleCount }}</h4>
                        <p class="mb-0 font-13 text-success"><i class="bx bxs-up-arrow align-middle"></i>From last week</p>
                    </div>
                    <div class="widgets-icons bg-light-success text-success ms-auto"><i class="lni lni-users"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Female Total --}}
      <div class="col">
        <div class="card radius-10">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div>
                        <p class="mb-0 text-secondary">Total Female Employees</p>
                        <h4 class="my-1">{{ $femaleCount }}</h4>
                        <p class="mb-0 font-13 text-primary"><i class="bx bxs-up-arrow align-middle"></i>From last week</p>
                    </div>
                    <div class="widgets-icons bg-light-primary text-primary ms-auto"><i class="bx bxs-group"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
     </div><!--end row-->

    <div class="row">
      <div class="col-12 col-lg-12 d-flex">
          <div class="card radius-10 w-100">
              <div class="card-header">
                  <div class="d-flex align-items-center">
                      <div><h6 class="mb-0">Retirement Per Year</h6></div>
                  </div>
              </div>
              <div class="card-body">
                  <div class="chart-container-1">
                      <canvas id="retirementChart"></canvas>
                  </div>
              </div>
          </div>
      </div>
  </div>
  


<div class="row">
  <div class="col-12 col-lg-12 d-flex">
      <div class="card radius-10 w-100">
          <div class="card-header">
              <div class="d-flex align-items-center">
                  <div><h6 class="mb-0">Top MDA-wise Employee Distribution</h6></div>
              </div>
          </div>
          <div class="card-body">
              <div class="chart-container-1">
                  <canvas id="mdaChart"></canvas>
              </div>
          </div>
      </div>
  </div>
</div>


  

     <div class="card radius-10">
        <div class="card-header">
            <div class="d-flex align-items-center">
                <div>
                    <h6 class="mb-0">Top 5 Recently Added Employees</h6>
                </div>
                <div class="dropdown ms-auto">
                    <a class="dropdown-toggle dropdown-toggle-nocaret" href="#" data-bs-toggle="dropdown"><i class='bx bx-dots-horizontal-rounded font-22 text-option'></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="javascript:;">Action</a>
                        </li>
                        <li><a class="dropdown-item" href="javascript:;">Another action</a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="javascript:;">Something else here</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
             <div class="card-body">
             <div class="table-responsive">
               <table class="table align-middle mb-0">
                <thead class="table-light">
                 <tr>
                  <th>Employee No.</th>
                  <th>Name</th>
                  <th>MDA</th>
                  <th>Grade Level</th>
                  <th>Retirement</th>
                 </tr>
                 </thead>
                 <tbody>
                                                  
                 @foreach ($recentEmployees as $emp)
            <tr>
                <td>{{ $emp->employee_number }}</td>
                <td>{{ $emp->surname }} {{ $emp->first_name }}</td>
                <td>{{ $emp->mda->mda ?? 'N/A' }}</td>
                <td>GL{{ $emp->gradeLevel->level ?? 'N/A' }}</td>
                <td> <div class="badge rounded-pill text-success bg-light-success p-2 text-uppercase px-3"><i class="bx bxs-circle align-middle me-1"></i> {{ \Carbon\Carbon::parse ($emp->retirement_date)->format('d M, Y') }}</td></div>
            </tr>
        @endforeach
              
               
                </tbody>
              </table>
              </div>
             </div>
        </div>
          
</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Retirement per Year
    const retirementChart = new Chart(document.getElementById('retirementChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($retirementPerYear->keys()) !!},
            datasets: [{
                label: 'Retirements',
                data: {!! json_encode($retirementPerYear->values()) !!},
                backgroundColor: '#0dcaf0'
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } }
        }
    });

    // Gender Distribution
    const genderChart = new Chart(document.getElementById('genderChart'), {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($genderStats->keys()) !!},
            datasets: [{
                label: 'Employees',
                data: {!! json_encode($genderStats->values()) !!},
                backgroundColor: ['#198754', '#ffc107', '#0d6efd']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });

    // MDA Distribution
    const mdaChart = new Chart(document.getElementById('mdaChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($mdaStats->pluck('mda')) !!},
            datasets: [{
                label: 'Employees',
                data: {!! json_encode($mdaStats->pluck('employees_count')) !!},
                backgroundColor: '#6610f2'
            }]
        },
        options: {
            responsive: true,
            indexAxis: 'y',
            plugins: { legend: { display: false } }
        }
    });
</script>

@endsection