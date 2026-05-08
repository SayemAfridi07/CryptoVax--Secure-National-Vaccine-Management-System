<form action="" id="manage_user">
    @csrf
    @method('PUT')
    <input type="hidden" name="id" value="{{$user->id}}">
    <div class="row">
        <div class="col-md-12 border-right">
            <div class="form-group">
                <label for="" class="control-label">Name</label>
                <input type="text" name="name" class="form-control form-control-sm" required value="{{$user->name}}" readonly>
            </div>
            <div class="form-group">
                <label class="control-label">Email</label>
                <input type="email" class="form-control form-control-sm" name="email" required value="{{$user->email}}" readonly>
                <small id="#msg"></small>
            </div>
            <div class="form-group">
                <label class="control-label">NID</label>
                <input type="email" class="form-control form-control-sm" name="email" required value="{{$user->nid}}" readonly>
                <small id="#msg"></small>
            </div>
            <div class="form-group">
                <label class="control-label">Date of Birth</label>
                <input type="email" class="form-control form-control-sm" name="email" required value="{{$user->dob}}" readonly>
                <small id="#msg"></small>
            </div>
            <div class="form-group">
                <label class="control-label">Phone</label>
                <input type="email" class="form-control form-control-sm" name="email" required value="{{$user->phone}}" readonly>
                <small id="#msg"></small>
            </div>
            <div class="form-group">
                <label class="control-label">Role</label>
                <select class="form-select" aria-label="User role options" name="role" required disabled>
                    <option value="">Select Role</option>
                    <option value="1" {{$user->role == 1 ? 'selected' : ''}}>Admin</option>
                    <option value="2" {{$user->role == 2 ? 'selected' : ''}}>Operator</option>
                </select>
            </div>
        </div>
    </div>
</form>