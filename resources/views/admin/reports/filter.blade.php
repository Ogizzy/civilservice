{{-- filters.blade.php --}}
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ url()->current() }}" method="GET" class="row g-3">
            @if(request()->routeIs('reports.by-lga'))
                <div class="col-md-4">
                    <label>LGA</label>
                    <input type="text" name="lga" value="{{ request('lga') }}" class="form-control" placeholder="Enter LGA">
                </div>
            @elseif(request()->routeIs('reports.by-mda'))
                <div class="col-md-4">
                    <label>MDA</label>
                    <select name="mda_id" class="form-control">
                        <option value="">Select MDA</option>
                        @foreach(App\Models\MDA::all() as $mda)
                            <option value="{{ $mda->id }}" {{ request('mda_id') == $mda->id ? 'selected' : '' }}>
                                {{ $mda->mda }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @elseif(request()->routeIs('reports.by-rank'))
                <div class="col-md-4">
                    <label>Rank</label>
                    <input type="text" name="rank" value="{{ request('rank') }}" class="form-control" placeholder="Enter Rank">
                </div>
            @elseif(request()->routeIs('reports.by-gender'))
                <div class="col-md-4">
                    <label>Gender</label>
                    <select name="gender" class="form-control">
                        <option value="">Select Gender</option>
                        <option value="Male" {{ request('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ request('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                    </select>
                </div>
            @elseif(request()->routeIs('reports.by-qualification'))
                <div class="col-md-4">
                    <label>Qualification</label>
                    <input type="text" name="qualification" value="{{ request('qualification') }}" class="form-control" placeholder="Enter Qualification">
                </div>
            @elseif(request()->routeIs('reports.by-pay-structure'))
                <div class="col-md-3">
                    <label>Pay Group</label>
                    <select name="paygroup_id" class="form-control">
                        <option value="">Select Pay Group</option>
                        @foreach(App\Models\PayGroup::all() as $group)
                            <option value="{{ $group->id }}" {{ request('paygroup_id') == $group->id ? 'selected' : '' }}>
                                {{ $group->paygroup }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Grade Level</label>
                    <select name="level_id" class="form-control">
                        <option value="">Select Level</option>
                        @foreach(App\Models\GradeLevel::all() as $level)
                            <option value="{{ $level->id }}" {{ request('level_id') == $level->id ? 'selected' : '' }}>
                                {{ $level->level }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Step</label>
                    <select name="step_id" class="form-control">
                        <option value="">Select Step</option>
                        @foreach(App\Models\Step::all() as $step)
                            <option value="{{ $step->id }}" {{ request('step_id') == $step->id ? 'selected' : '' }}>
                                {{ $step->step }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @elseif(request()->routeIs('reports.retired') || request()->routeIs('reports.retiring'))
                <div class="col-md-3">
                    <label>Start Date</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control">
                </div>
                <div class="col-md-3">
                    <label>End Date</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control">
                </div>
            @endif

            <div class="col-md-2 align-self-end">
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
        </form>
    </div>
</div>
