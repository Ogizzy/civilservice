<div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserLabel" aria-hidden="true">
    <div class="modal-dialog">
      <form method="POST" action="{{ route('users.store') }}" class="modal-content">
          @csrf
          <div class="modal-header">
              <h5 class="modal-title">Create User</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
  
              <div class="mb-3">
                  <label>Surname</label>
                  <input type="text" name="surname" class="form-control" required>
              </div>
              <div class="mb-3">
                  <label>First Name</label>
                  <input type="text" name="first_name" class="form-control" required>
              </div>
              <div class="mb-3">
                  <label>Other Names</label>
                  <input type="text" name="other_names" class="form-control">
              </div>
              <div class="mb-3">
                  <label>Email</label>
                  <input type="email" name="email" class="form-control" required>
              </div>
              <div class="mb-3">
                  <label>Password</label>
                  <input type="password" name="password" class="form-control" required>
              </div>
              <div class="mb-3">
                  <label>Role</label>
                  <select name="role_id" class="form-control" required>
                      <option value="">-- Select Role --</option>
                      @foreach(\App\Models\UserRole::all() as $role)
                          <option value="{{ $role->id }}">{{ $role->role }}</option>
                      @endforeach
                  </select>
              </div>
              <div class="mb-3">
                  <label>Status</label>
                  <select name="status" class="form-control" required>
                      <option value="active">Active</option>
                      <option value="suspended">Suspended</option>
                      <option value="banned">Banned</option>
                  </select>
              </div>
  
          </div>
          <div class="modal-footer">
              <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              <button class="btn btn-primary">Create</button>
          </div>
      </form>
    </div>
  </div>
  