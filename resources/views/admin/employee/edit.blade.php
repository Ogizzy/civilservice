@extends('admin.admin_dashboard')
@section('admin')

<div class="page-content">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center shadow-sm rounded-top px-4 py-3">
                        <h4 class="mb-0 font-weight-bold text-white">
                            <i class="fas fa-user-edit mr-2"></i> Edit Employee
                        </h4>
                        <a href="{{ route('employees.index') }}" class="btn btn-light btn-sm d-flex align-items-center shadow-sm border-0">
                            <i class="bx bxs-chevron-left mr-1 text-danger"></i> Back
                        </a>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('employees.update', $employee->id) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <!-- Personal Information Section -->
                                <div class="col-md-12 mb-4">
                                    <h5 class="border-bottom pb-2">Personal Information</h5>
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="surname" class="form-label">Surname <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control @error('surname') is-invalid @enderror" id="surname" name="surname" value="{{ old('surname', $employee->surname) }}" required>
                                                @error('surname')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control @error('first_name') is-invalid @enderror" id="first_name" name="first_name" value="{{ old('first_name', $employee->first_name) }}" required>
                                                @error('first_name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="middle_name" class="form-label">Middle Name</label>
                                                <input type="text" class="form-control @error('middle_name') is-invalid @enderror" id="middle_name" name="middle_name" value="{{ old('middle_name', $employee->middle_name) }}">
                                                @error('middle_name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group mb-3">
                                                <label for="gender" class="form-label">Gender</label>
                                                <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender">
                                                    <option value="">Select Gender</option>
                                                    <option value="Male" {{ old('gender', $employee->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                                    <option value="Female" {{ old('gender', $employee->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                                                </select>
                                                @error('gender')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group mb-3">
                                                <label for="dob" class="form-label">Date of Birth</label>
                                                <input type="date" class="form-control @error('dob') is-invalid @enderror" id="dob" name="dob" value="{{ old('dob', $employee->dob ? $employee->dob->format('Y-m-d') : '') }}">
                                                @error('dob')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group mb-3">
                                                <label for="marital_status" class="form-label">Marital Status</label>
                                                <select class="form-select @error('marital_status') is-invalid @enderror" id="marital_status" name="marital_status">
                                                    <option value="">Select Status</option>
                                                    <option value="Single" {{ old('marital_status', $employee->marital_status) == 'Single' ? 'selected' : '' }}>Single</option>
                                                    <option value="Married" {{ old('marital_status', $employee->marital_status) == 'Married' ? 'selected' : '' }}>Married</option>
                                                    <option value="Divorced" {{ old('marital_status', $employee->marital_status) == 'Divorced' ? 'selected' : '' }}>Divorced</option>
                                                    <option value="Widowed" {{ old('marital_status', $employee->marital_status) == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                                                </select>
                                                @error('marital_status')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group mb-3">
                                                <label for="religion" class="form-label">Religion</label>
                                                <select class="form-select @error('religion') is-invalid @enderror" id="religion" name="religion">
                                                    <option value="">Select Religion</option>
                                                    <option value="Christianity" {{ old('religion', $employee->religion) == 'Christianity' ? 'selected' : '' }}>Christianity</option>
                                                    <option value="Islam" {{ old('religion', $employee->religion) == 'Islam' ? 'selected' : '' }}>Islam</option>
                                                    <option value="Others" {{ old('religion', $employee->religion) == 'Others' ? 'selected' : '' }}>Others</option>
                                                </select>
                                                @error('religion')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="state_id" class="form-label">State of Origin <span class="text-danger">*</span></label>
                                                <select class="form-select @error('state_id') is-invalid @enderror" id="state_id" name="state_id" required>
                                                    <option value="">Select State</option>
                                                    @foreach($states as $state)
                                                        <option value="{{ $state->id }}" {{ old('state_id', $employee->state_id) == $state->id ? 'selected' : '' }}>
                                                            {{ $state->state }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('state_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="lga" class="form-label">LGA of Origin</label>
                                                <select class="form-select @error('lga') is-invalid @enderror" id="lga" name="lga">
                                                    <option value="">Select LGA</option>
                                                    @foreach($lgas as $lga)
                                                        <option value="{{ $lga->lga }}" {{ old('lga', $employee->lga) == $lga->lga ? 'selected' : '' }}>
                                                            {{ $lga->lga }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('lga')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="passport" class="form-label">Passport Photo</label>
                                                <input type="file" class="form-control @error('passport') is-invalid @enderror" id="passport" name="passport">
                                                @error('passport')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                @if($employee->passport)
                                                    <div class="mt-2">
                                                        <img src="{{ asset('storage/' . $employee->passport) }}" class="img-thumbnail" style="height: 80px;" alt="Current Passport">
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Contact Information Section -->
                                <div class="col-md-12 mb-4">
                                    <h5 class="border-bottom pb-2">Contact Information</h5>
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="email" class="form-label">Email Address</label>
                                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $employee->email) }}">
                                                @error('email')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="phone" class="form-label">Phone Number</label>
                                                <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $employee->phone) }}">
                                                @error('phone')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="contact_address" class="form-label">Contact Address</label>
                                                <textarea class="form-control @error('contact_address') is-invalid @enderror" id="contact_address" name="contact_address" rows="2">{{ old('contact_address', $employee->contact_address) }}</textarea>
                                                @error('contact_address')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Employment Details Section -->
                                <div class="col-md-12 mb-4">
                                    <h5 class="border-bottom pb-2">Employment Details</h5>
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="employee_number" class="form-label">Employee Number <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control @error('employee_number') is-invalid @enderror" id="employee_number" name="employee_number" value="{{ old('employee_number', $employee->employee_number) }}" required>
                                                @error('employee_number')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="mda_id" class="form-label">MDA <span class="text-danger">*</span></label>
                                                <select class="form-select @error('mda_id') is-invalid @enderror" id="mda_id" name="mda_id" required>
                                                    <option value="">Select MDA</option>
                                                    @foreach($mdas as $mda)
                                                        <option value="{{ $mda->id }}" {{ old('mda_id', $employee->mda_id) == $mda->id ? 'selected' : '' }}>
                                                            {{ $mda->mda }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('mda_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="rank" class="form-label">Rank</label>
                                                <input type="text" class="form-control @error('rank') is-invalid @enderror" id="rank" name="rank" value="{{ old('rank', $employee->rank) }}">
                                                @error('rank')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="paygroup_id" class="form-label">Pay Group <span class="text-danger">*</span></label>
                                                <select class="form-select @error('paygroup_id') is-invalid @enderror" id="paygroup_id" name="paygroup_id" required>
                                                    <option value="">Select Pay Group</option>
                                                    @foreach($payGroups as $payGroup)
                                                        <option value="{{ $payGroup->id }}" {{ old('paygroup_id', $employee->paygroup_id) == $payGroup->id ? 'selected' : '' }}>
                                                            {{ $payGroup->paygroup }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('paygroup_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="level_id" class="form-label">Grade Level <span class="text-danger">*</span></label>
                                                <select class="form-select @error('level_id') is-invalid @enderror" id="level_id" name="level_id" required>
                                                    <option value="">Select Grade Level</option>
                                                    @foreach($gradeLevels as $level)
                                                        <option value="{{ $level->id }}" {{ old('level_id', $employee->level_id) == $level->id ? 'selected' : '' }}>
                                                            {{ $level->level }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('level_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="step_id" class="form-label">Step <span class="text-danger">*</span></label>
                                                <select class="form-select @error('step_id') is-invalid @enderror" id="step_id" name="step_id" required>
                                                    <option value="">Select Step</option>
                                                    @foreach($steps as $step)
                                                        <option value="{{ $step->id }}" {{ old('step_id', $employee->step_id) == $step->id ? 'selected' : '' }}>
                                                            {{ $step->step }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('step_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Service Dates Section -->
                                <div class="col-md-12 mb-4">
                                    <h5 class="border-bottom pb-2">Service Dates</h5>
                                    <div class="row g-3">
                                        <div class="col-md-3">
                                            <div class="form-group mb-3">
                                                <label for="first_appointment_date" class="form-label">First Appointment Date</label>
                                                <input type="date" class="form-control @error('first_appointment_date') is-invalid @enderror" id="first_appointment_date" name="first_appointment_date" value="{{ old('first_appointment_date', $employee->first_appointment_date ? $employee->first_appointment_date->format('Y-m-d') : '') }}">
                                                @error('first_appointment_date')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group mb-3">
                                                <label for="confirmation_date" class="form-label">Confirmation Date</label>
                                                <input type="date" class="form-control @error('confirmation_date') is-invalid @enderror" id="confirmation_date" name="confirmation_date" value="{{ old('confirmation_date', $employee->confirmation_date ? $employee->confirmation_date->format('Y-m-d') : '') }}">
                                                @error('confirmation_date')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group mb-3">
                                                <label for="present_appointment_date" class="form-label">Present Appointment Date</label>
                                                <input type="date" class="form-control @error('present_appointment_date') is-invalid @enderror" id="present_appointment_date" name="present_appointment_date" value="{{ old('present_appointment_date', $employee->present_appointment_date ? $employee->present_appointment_date->format('Y-m-d') : '') }}">
                                                @error('present_appointment_date')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <!-- Additional Details Section -->
                                <div class="col-md-12 mb-4">
                                    <h5 class="border-bottom pb-2">Additional Details</h5>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="qualifications" class="form-label">Qualifications</label>
                                                <textarea class="form-control @error('qualifications') is-invalid @enderror" id="qualifications" name="qualifications" rows="3">{{ old('qualifications', $employee->qualifications) }}</textarea>
                                                @error('qualifications')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="net_pay" class="form-label">Net Pay (₦)</label>
                                                <input type="number" step="0.01" class="form-control @error('net_pay') is-invalid @enderror" id="net_pay" name="net_pay" value="{{ old('net_pay', $employee->net_pay) }}">
                                                @error('net_pay')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                                             
                            </div>

                            <div class="mt-4 text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bx bx-save mr-1"></i> Update Employee
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    const stateIdToName = {
@foreach ($states as $state)
    "{{ $state->id }}": "{{ $state->state }}",
@endforeach
};

const stateLGA = {
   "Abia": ["Aba North", "Aba South", "Arochukwu", "Bende", "Ikwuano", "Isiala Ngwa North", "Isiala Ngwa South", "Isuikwuato", "Obi Ngwa", "Ohafia", "Osisioma", "Ugwunagbo", "Ukwa East", "Ukwa West", "Umuahia North", "Umuahia South", "Umunneochi"],
    "Adamawa": ["Demsa", "Fufore", "Ganye", "Girei", "Gombi", "Guyuk", "Hong", "Jada", "Lamurde", "Madagali", "Maiha", "Mayo-Belwa", "Michika", "Mubi North", "Mubi South", "Numan", "Shelleng", "Song", "Toungo", "Yola North", "Yola South"],
    "Akwa Ibom": ["Abak", "Eastern Obolo", "Eket", "Esit Eket", "Essien Udim", "Etim Ekpo", "Etinan", "Ibeno", "Ibesikpo Asutan", "Ibiono Ibom", "Ika", "Ikono", "Ikot Abasi", "Ikot Ekpene", "Ini", "Itu", "Mbo", "Mkpat-Enin", "Nsit Atai", "Nsit Ibom", "Nsit Ubium", "Obot Akara", "Okobo", "Onna", "Oron", "Oruk Anam", "Udung-Uko", "Ukanafun", "Uruan", "Urue-Offong/Oruko", "Uyo"],
    "Anambra": ["Aguata", "Anambra East", "Anambra West", "Anaocha", "Awka North", "Awka South", "Ayamelum", "Dunukofia", "Ekwusigo", "Idemili North", "Idemili South", "Ihiala", "Njikoka", "Nnewi North", "Nnewi South", "Ogbaru", "Onitsha North", "Onitsha South", "Orumba North", "Orumba South", "Oyi"],
    "Bauchi": ["Alkaleri", "Bauchi", "Bogoro", "Damban", "Darazo", "Dass", "Gamawa", "Ganjuwa", "Giade", "Itas/Gadau", "Jama'are", "Katagum", "Kirfi", "Misau", "Ningi", "Shira", "Tafawa Balewa", "Toro", "Warji", "Zaki"],
    "Bayelsa": ["Brass", "Ekeremor", "Kolokuma/Opokuma", "Nembe", "Ogbia", "Sagbama", "Southern Ijaw", "Yenagoa"],
    "Benue": ["Ado", "Agatu", "Apa", "Buruku", "Gboko", "Guma", "Gwer East", "Gwer West", "Katsina-Ala", "Konshisha", "Kwande", "Logo", "Makurdi", "Obi", "Ogbadibo", "Ohimini", "Oju", "Okpokwu", "Otukpo", "Tarka", "Ukum", "Ushongo", "Vandeikya"],
    "Borno": ["Abadam", "Askira/Uba", "Bama", "Bayo", "Biu", "Chibok", "Damboa", "Dikwa", "Gubio", "Guzamala", "Gwoza", "Hawul", "Jere", "Kaga", "Kala/Balge", "Konduga", "Kukawa", "Kwaya Kusar", "Mafa", "Magumeri", "Maiduguri", "Marte", "Mobbar", "Monguno", "Ngala", "Nganzai", "Shani"],
    "Cross River": ["Abi", "Akamkpa", "Akpabuyo", "Bakassi", "Bekwarra", "Biase", "Boki", "Calabar Municipal", "Calabar South", "Etung", "Ikom", "Obanliku", "Obubra", "Obudu", "Odukpani", "Ogoja", "Yakurr", "Yala"],
    "Delta": ["Aniocha North", "Aniocha South", "Bomadi", "Burutu", "Ethiope East", "Ethiope West", "Ika North East", "Ika South", "Isoko North", "Isoko South", "Ndokwa East", "Ndokwa West", "Okpe", "Oshimili North", "Oshimili South", "Patani", "Sapele", "Udu", "Ughelli North", "Ughelli South", "Ukwuani", "Uvwie", "Warri North", "Warri South", "Warri South West"],
    "Ebonyi": ["Abakaliki", "Afikpo North", "Afikpo South", "Ebonyi", "Ezza North", "Ezza South", "Ikwo", "Ishielu", "Ivo", "Izzi", "Ohaozara", "Ohaukwu", "Onicha"],
    "Edo": ["Akoko-Edo", "Egor", "Esan Central", "Esan North-East", "Esan South-East", "Esan West", "Etsako Central", "Etsako East", "Etsako West", "Igueben", "Ikpoba-Okha", "Oredo", "Orhionmwon", "Ovia North-East", "Ovia South-West", "Owan East", "Owan West", "Uhunmwonde"],
    "Ekiti": ["Ado Ekiti", "Efon", "Ekiti East", "Ekiti South-West", "Ekiti West", "Emure", "Gbonyin", "Ido-Osi", "Ijero", "Ikere", "Ikole", "Ilejemeje", "Irepodun/Ifelodun", "Ise/Orun", "Moba", "Oye"],
    "Enugu": ["Aninri", "Awgu", "Enugu East", "Enugu North", "Enugu South", "Ezeagu", "Igbo-Etiti", "Igbo-Eze North", "Igbo-Eze South", "Isi Uzo", "Nkanu East", "Nkanu West", "Nsukka", "Oji River", "Udenu", "Udi", "Uzo-Uwani"],
    "FCT": ["Abaji", "Bwari", "Gwagwalada", "Kuje", "Kwali", "Municipal Area Council"],
    "Gombe": ["Akko", "Balanga", "Billiri", "Dukku", "Funakaye", "Gombe", "Kaltungo", "Kwami", "Nafada", "Shongom", "Yamaltu/Deba"],
    "Imo": ["Aboh Mbaise", "Ahiazu Mbaise", "Ehime Mbano", "Ezinihitte", "Ideato North", "Ideato South", "Ihitte/Uboma", "Ikeduru", "Isiala Mbano", "Isu", "Mbaitoli", "Ngor Okpala", "Njaba", "Nkwerre", "Nwangele", "Obowo", "Oguta", "Ohaji/Egbema", "Okigwe", "Onuimo", "Orlu", "Orsu", "Oru East", "Oru West", "Owerri Municipal", "Owerri North", "Owerri West"],
    "Jigawa": ["Auyo", "Babura", "Biriniwa", "Birnin Kudu", "Buji", "Dutse", "Gagarawa", "Garki", "Gumel", "Guri", "Gwaram", "Gwiwa", "Hadejia", "Jahun", "Kafin Hausa", "Kaugama", "Kazaure", "Kiri Kasama", "Kiyawa", "Maigatari", "Malam Madori", "Miga", "Ringim", "Roni", "Sule Tankarkar", "Taura", "Yankwashi"],
    "Kaduna": ["Birnin Gwari", "Chikun", "Giwa", "Igabi", "Ikara", "Jaba", "Jema'a", "Kachia", "Kaduna North", "Kaduna South", "Kagarko", "Kajuru", "Kaura", "Kauru", "Kubau", "Kudan", "Lere", "Makarfi", "Sabon Gari", "Sanga", "Soba", "Zangon Kataf", "Zaria"],
    "Kano": ["Ajingi", "Albasu", "Bagwai", "Bebeji", "Bichi", "Bunkure", "Dala", "Dambatta", "Dawakin Kudu", "Dawakin Tofa", "Doguwa", "Fagge", "Gabasawa", "Garko", "Garun Mallam", "Gaya", "Gezawa", "Gwale", "Gwarzo", "Kabo", "Kano Municipal", "Karaye", "Kibiya", "Kiru", "Kumbotso", "Kunchi", "Kura", "Madobi", "Makoda", "Minjibir", "Nasarawa", "Rano", "Rimin Gado", "Rogo", "Shanono", "Sumaila", "Takai", "Tarauni", "Tofa", "Tsanyawa", "Tudun Wada", "Ungogo", "Warawa", "Wudil"],
    "Katsina": ["Bakori", "Batagarawa", "Batsari", "Baure", "Bindawa", "Charanchi", "Dandume", "Danja", "Dan Musa", "Daura", "Dutsi", "Dutsin Ma", "Faskari", "Funtua", "Ingawa", "Jibia", "Kafur", "Kaita", "Kankara", "Kankia", "Katsina", "Kurfi", "Kusada", "Mai'Adua", "Malumfashi", "Mani", "Mashi", "Matazu", "Musawa", "Rimi", "Sabuwa", "Safana", "Sandamu", "Zango"],
    "Kebbi": ["Aleiro", "Arewa Dandi", "Argungu", "Augie", "Bagudo", "Birnin Kebbi", "Bunza", "Dandi", "Fakai", "Gwandu", "Jega", "Kalgo", "Koko/Besse", "Maiyama", "Ngaski", "Sakaba", "Shanga", "Suru", "Wasagu/Danko", "Yauri", "Zuru"],
    "Kogi": ["Adavi", "Ajaokuta", "Ankpa", "Bassa", "Dekina", "Ibaji", "Idah", "Igalamela-Odolu", "Ijumu", "Kabba/Bunu", "Kogi", "Lokoja", "Mopa-Muro", "Ofu", "Ogori/Magongo", "Okehi", "Okene", "Olamaboro", "Omala", "Yagba East", "Yagba West"],
    "Kwara": ["Asa", "Baruten", "Edu", "Ekiti", "Ifelodun", "Ilorin East", "Ilorin South", "Ilorin West", "Irepodun", "Isin", "Kaiama", "Moro", "Offa", "Oke Ero", "Oyun", "Pategi"],
    "Lagos": ["Agege", "Ajeromi-Ifelodun", "Alimosho", "Amuwo-Odofin", "Apapa", "Badagry", "Epe", "Eti Osa", "Ibeju-Lekki", "Ifako-Ijaiye", "Ikeja", "Ikorodu", "Kosofe", "Lagos Island", "Lagos Mainland", "Mushin", "Ojo", "Oshodi-Isolo", "Shomolu", "Surulere"],
    "Nasarawa": ["Akwanga", "Awe", "Doma", "Karu", "Keana", "Keffi", "Kokona", "Lafia", "Nasarawa", "Nasarawa Egon", "Obi", "Toto", "Wamba"],
    "Niger": ["Agaie", "Agwara", "Bida", "Borgu", "Bosso", "Chanchaga", "Edati", "Gbako", "Gurara", "Katcha", "Kontagora", "Lapai", "Lavun", "Magama", "Mariga", "Mashegu", "Mokwa", "Moya", "Paikoro", "Rafi", "Rijau", "Shiroro", "Suleja", "Tafa", "Wushishi"],
    "Ogun": ["Abeokuta North", "Abeokuta South", "Ado-Odo/Ota", "Egbado North", "Egbado South", "Ewekoro", "Ifo", "Ijebu East", "Ijebu North", "Ijebu North East", "Ijebu Ode", "Ikenne", "Imeko Afon", "Ipokia", "Obafemi Owode", "Odeda", "Odogbolu", "Ogun Waterside", "Remo North", "Shagamu", "Yewa North", "Yewa South"],
    "Ondo": ["Akoko North-East", "Akoko North-West", "Akoko South-West", "Akoko South-East", "Akure North", "Akure South", "Ese Odo", "Idanre", "Ifedore", "Ilaje", "Ile Oluji/Okeigbo", "Irele", "Odigbo", "Okitipupa", "Ondo East", "Ondo West", "Ose", "Owo"],
    "Osun": ["Atakunmosa East", "Atakunmosa West", "Aiyedaade", "Aiyedire", "Boluwaduro", "Boripe", "Ede North", "Ede South", "Egbedore", "Ejigbo", "Ife Central", "Ife East", "Ife North", "Ife South", "Ifedayo", "Ifelodun", "Ila", "Ilesa East", "Ilesa West", "Irepodun", "Irewole", "Isokan", "Iwo", "Obokun", "Odo Otin", "Ola Oluwa", "Olorunda", "Oriade", "Orolu", "Osogbo"],
    "Oyo": ["Afijio", "Akinyele", "Atiba", "Atisbo", "Egbeda", "Ibadan North", "Ibadan North-East", "Ibadan North-West", "Ibadan South-East", "Ibadan South-West", "Ibarapa Central", "Ibarapa East", "Ibarapa North", "Ido", "Irepo", "Iseyin", "Itesiwaju", "Iwajowa", "Kajola", "Lagelu", "Ogbomosho North", "Ogbomosho South", "Ogo Oluwa", "Olorunsogo", "Oluyole", "Ona Ara", "Orelope", "Ori Ire", "Oyo East", "Oyo West", "Saki East", "Saki West", "Surulere"],
    "Plateau": ["Barkin Ladi", "Bassa", "Bokkos", "Jos East", "Jos North", "Jos South", "Kanam", "Kanke", "Langtang North", "Langtang South", "Mangu", "Mikang", "Pankshin", "Qua'an Pan", "Riyom", "Shendam", "Wase"],
    "Rivers": ["Abua/Odual", "Ahoada East", "Ahoada West", "Akuku-Toru", "Andoni", "Asari-Toru", "Bonny", "Degema", "Eleme", "Emuoha", "Etche", "Gokana", "Ikwerre", "Khana", "Obio/Akpor", "Ogba/Egbema/Ndoni", "Ogu/Bolo", "Okrika", "Omuma", "Opobo/Nkoro", "Oyigbo", "Port Harcourt", "Tai"],
    "Sokoto": ["Binji", "Bodinga", "Dange Shuni", "Gada", "Goronyo", "Gudu", "Gwadabawa", "Illela", "Isa", "Kebbe", "Kware", "Rabah", "Sabon Birni", "Shagari", "Silame", "Sokoto North", "Sokoto South", "Tambuwal", "Tangaza", "Tureta", "Wamako", "Wurno", "Yabo"],
    "Taraba": ["Ardo Kola", "Bali", "Donga", "Gashaka", "Gassol", "Ibi", "Jalingo", "Karim Lamido", "Kurmi", "Lau", "Sardauna", "Takum", "Ussa", "Wukari", "Yorro", "Zing"],
    "Yobe": ["Bade", "Bursari", "Damaturu", "Fika", "Fune", "Geidam", "Gujba", "Gulani", "Jakusko", "Karasuwa", "Machina", "Nangere", "Nguru", "Potiskum", "Tarmuwa", "Yunusari", "Yusufari"],
    "Zamfara": ["Anka", "Bakura", "Birnin Magaji/Kiyaw", "Bukkuyum", "Bungudu", "Gummi", "Gusau", "Kaura Namoda", "Maradun", "Maru", "Shinkafi", "Talata Mafara", "Tsafe", "Zurmi"]
};

document.addEventListener('DOMContentLoaded', function () {
    const stateSelect = document.getElementById('state_id');
    const lgaSelect = document.getElementById('lga');

    stateSelect.addEventListener('change', function () {
        const selectedStateId = this.value;
        const selectedStateName = stateIdToName[selectedStateId];
        lgaSelect.innerHTML = '<option value="">-- Select LGA --</option>';

        if (stateLGA[selectedStateName]) {
            stateLGA[selectedStateName].forEach(function (lga) {
                const option = document.createElement('option');
                option.value = lga;
                option.textContent = lga;
                lgaSelect.appendChild(option);
            });
        }
    });

    // For retaining old values after validation error
    const oldStateId = "{{ old('state_id') }}";
    const oldLga = "{{ old('lga') }}";
    if (oldStateId && stateIdToName[oldStateId]) {
        const stateName = stateIdToName[oldStateId];
        if (stateLGA[stateName]) {
            stateLGA[stateName].forEach(function (lga) {
                const option = document.createElement('option');
                option.value = lga;
                option.textContent = lga;
                if (oldLga === lga) option.selected = true;
                lgaSelect.appendChild(option);
            });
        }
    }
});
</script>


@endsection