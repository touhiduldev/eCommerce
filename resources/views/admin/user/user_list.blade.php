

@extends('layouts.db')

@section('title')
    Kumo ~ User List
@endsection

@section('content')
<div class="col-lg-10 m-auto">
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="card">
        <div class="card-header">
            <h3>User List</h3>
            <span style="color: blue; font-size: 18px;">Total User => {{ $total_users }}</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-responsive-md ck-table">
                    <thead>
                        <tr>
                            <th>Serial</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Image</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ( $users as $serial => $user )
                                <tr>
                                    <td>{{ $serial+1 }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @if ($user->image == null)
                                            <img width="50" src="{{ Avatar::create(Auth::user()->name)->toBase64(); }}" alt="">
                                        @else
                                            <img width="50" src="{{ asset('uploads/profile/'.$user->image) }}" alt="">
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            <a href="{{ route('user.delete', $user->id) }}" class="btn btn-danger shadow btn-xs sharp"><i class="fa fa-trash"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
