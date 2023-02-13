@extends('frontend.master')

@section('title')
    Kumo - Password Reset Form
@endsection

@section('content')
    <!-- ======================= Top Breadcrubms ======================== -->
    <div class="gray py-3">
        <div class="container">
            <div class="row">
                <div class="colxl-12 col-lg-12 col-md-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">New Password Generate</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- ======================= Top Breadcrubms ======================== -->
    <div class="container my-5">
        <div class="row">
            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 m-auto">
                <div class="mb-3">
                    <h3>New Password Generate Form</h3>
                </div>
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form class="border p-3 rounded" action="{{ route('new.pw.generate') }}" method="POST">
                    @csrf

                    <input type="hidden" name="token" id="" value="{{ $token }}">

                    <div class="form-group">
                        <label>Password *</label>
                        <input type="password" class="form-control" name="password" placeholder="********">
                        @error('password')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Confirm Password *</label>
                        <input type="password" class="form-control" name="password_confirmation" placeholder="********">
                        @error('password_confirmation')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-md full-width bg-dark text-light fs-md ft-medium">Generate
                            Your New Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
