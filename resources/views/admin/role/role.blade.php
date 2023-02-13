@extends('layouts.db')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h3>Role List</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <th>Role Name</th>
                                <th>Permission</th>
                                <th>Action</th>
                            </tr>
                            @foreach ($roles as $role)
                                <tr>
                                    <td>{{ $role->name }}</td>
                                    <td>
                                        @foreach ($role->getPermissionNames() as $roll)
                                            <span class="badge badge-primary my-1">{{ $roll }}</span>
                                        @endforeach
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button type="button" class="btn btn-success light sharp" data-toggle="dropdown">
                                                <svg width="20px" height="20px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"/><circle fill="#000000" cx="5" cy="12" r="2"/><circle fill="#000000" cx="12" cy="12" r="2"/><circle fill="#000000" cx="19" cy="12" r="2"/></g></svg>
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item text-info" href="{{ route('edit.permission', $role->id) }}">Edit</a>
                                                <a class="dropdown-item text-danger" href="{{ route('delete.permission', $role->id) }}">Delete</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3>User List & Role</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <th>User Name</th>
                                <th>Role Name</th>
                                <th>Action</th>
                            </tr>
                            @foreach ($all_users as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>
                                        @forelse ($user->getRoleNames() as $role)
                                            <span class="badge badge-info">{{ $role }}</span>
                                        @empty
                                            <span class="badge badge-warning">Not Assigned</span>
                                        @endforelse
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button type="button" class="btn btn-success light sharp" data-toggle="dropdown">
                                                <svg width="20px" height="20px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"/><circle fill="#000000" cx="5" cy="12" r="2"/><circle fill="#000000" cx="12" cy="12" r="2"/><circle fill="#000000" cx="19" cy="12" r="2"/></g></svg>
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item text-danger" href="{{ route('remove.role', $user->id) }}">Remove Role</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">

                <div class="card">
                    <div class="card-header">
                        <h3>Add Permission</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('permisson.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="" class="form-label">Add Permission</label>
                                <input type="text" name="permission_name" class="form-control" id="">
                            </div>
                            <div class="mb-3">
                                <button type="submit" class="btn btn-info">Add Permission</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3>Add Role</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('role.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="" class="form-label">Role Name</label>
                                <input type="text" name="role_name" class="form-control" id="">
                            </div>
                            <div class="mb-3">
                                <strong class="mb-3">Select Permission</strong>
                                <div class="form-group">
                                    @foreach ($permissions as $permission)
                                        <div class="form-check form-check-inline">
                                            <label class="form-check-label">
                                                <input type="checkbox" name="permission[]" class="form-check-input" value="{{ $permission->id }}">{{ $permission->name }}
                                            </label>
                                        </div>
                                    @endforeach

                                </div>
                            </div>
                            <div class="mb-3">
                                <button type="submit" class="btn btn-info">Add Permission</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3>Assign Role</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('assign.role') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <select name="user_id" id="" class="form-control">
                                    <option value="">--Select User--</option>
                                    @foreach ($all_users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <select name="role_id" id="" class="form-control">
                                    <option value="">--Select Role--</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <button type="submit" class="btn btn-info">Assign Permission</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
