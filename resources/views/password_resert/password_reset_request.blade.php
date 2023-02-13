@extends('frontend.master')

@section('title')
    Kumo - Password reset request
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
                        <li class="breadcrumb-item active" aria-current="page">Password Reset</li>
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
                <h3>Password Reset Request</h3>
            </div>
            @if (session('send'))
                <div class="alert alert-success">{{ session('send') }}</div>
            @endif
            <form class="border p-3 rounded" action="{{ route('reset.request.send') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label>Email *</label>
                    <input type="email" class="form-control" name="email" placeholder="Email*">
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-md full-width bg-dark text-light fs-md ft-medium">Send Request</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
