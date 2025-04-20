<div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1" aria-labelledby="editUserLabel" aria-hidden="true">
    <div class="modal-dialog">
      <form method="POST" action="{{ route('users.update', $user->id) }}" class="modal-content">
          @csrf
          @method('PUT')
          <div class="modal-header">
              <h5 class="modal-title">Edit User</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
  
              <div class="mb-3">
                  <label>Surname</label>
                  <input type="text" name="surname" class="form-control" value="{{ $user->surname }}" required>
              </div>
              <div class="mb-3">
                  <label>First Name</label>
                  <input type="text" name="first_name" class="form-control" value="{{ $user->first_name }}" required>
              </div>
              <div class="mb-3">
                  <label>Other Names</label>
                  <input type="text" name="other_names" class="form-control" value="{{ $user->other_names }}">
              </div>
              <div class="mb-3">
                  <label>Email</label>
                  <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
              </div>
              <div class="mb-3">
                  <label>New Password <small class="text-muted">(Leave blank to keep current password)</small></label>
                  <input type="password" name="password" class="form-control">
              </div>
              <div class="mb-3">
                  <label>Role</label>
                  <select name="role_id" class="form-select" required>
                      @foreach(\App\Models\UserRole::all() as $role)
                          <option value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected' : '' }}>
                              {{ $role->role }}
                          </option>
                      @endforeach
                  </select>
              </div>
              <div class="mb-3">
                  <label>Status</label>
                  <select name="status" class="form-select" required>
                      <option value="active" {{ $user->status == 'active' ? 'selected' : '' }}>Active</option>
                      <option value="suspended" {{ $user->status == 'suspended' ? 'selected' : '' }}>Suspended</option>
                      <option value="banned" {{ $user->status == 'banned' ? 'selected' : '' }}>Banned</option>
                  </select>
              </div>
  
          </div>
          <div class="modal-footer">
              <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              <button class="btn btn-primary">Update</button>
          </div>
      </form>
    </div>
  </div>
  