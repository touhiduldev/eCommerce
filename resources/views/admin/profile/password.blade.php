@extends('layouts.db')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-7 m-auto">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                <div class="card">
                    <div class="card-header">
                        <h4>Update Your Password</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('update.password') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="" class="form-label">Old Password <span style="color: red;">*</span></label>
                                <input type="password" class="form-control" name="old_password" placeholder="Old Password">
                                @error('old_password')
                                    <span style="color: red;">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label">New Password <span style="color: red;">*</span></label>
                                <input type="password" id="email" class="form-control" name="new_password" placeholder="New Password">
                                @error('new_password')
                                    <span style="color: red;">{{ $message }}</span>
                                @enderror
                                @if (session('faild'))
                                    <span style="color: red;">{{ session('faild') }}</span>
                                @endif
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label">Confirm Password <span style="color: red;">*</span></label>
                                <input type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password">
                                @error('password_confirmation')
                                    <span style="color: red;">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
