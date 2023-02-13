@extends('frontend.master')

@section('content')
<div class="gray py-3">
    <div class="container">
        <div class="row">
            <div class="colxl-12 col-lg-12 col-md-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Pages</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Login</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<!-- ======================= Top Breadcrubms ======================== -->

<!-- ======================= Login Detail ======================== -->
<section class="middle">
    <div class="container">
        <div class="row align-items-start justify-content-between">

            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                <div class="mb-3">
                    <h3>Login</h3>
                </div>
                @if (session('login'))
                    <div class="alert alert-warning">{{ session('login') }}</div>
                @endif
                @if (session('warning'))
                    <div class="alert alert-warning">{{ session('warning') }}</div>
                @endif
                <form class="border p-3 rounded" action="{{ route('customer.login') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Email *</label>
                        <input type="email" class="form-control" name="email" placeholder="Email*">
                    </div>

                    <div class="form-group">
                        <label>Password *</label>
                        <input type="password" class="form-control" name="password" placeholder="********">
                    </div>

                    <div class="my-3">
                        {{-- {!! NoCaptcha::display() !!} --}}

                        @error('g-recaptcha-response')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="eltio_k2">
                                <a href="{{ route('reset.request.password') }}">Forgot Your Password?</a>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-md full-width bg-dark text-light fs-md ft-medium">Login</button>
                    </div>
                    <strong>You can login with your social accounts</strong>
                    <div class="mt-3 text-center">
                        <a href="{{ route('github.redirect') }}" class="btn btn-light">Github <img src="https://i.postimg.cc/26Q90Hdk/002-github-sign.png" width="20" alt=""></a>
                        <a href="{{ route('google.redirect') }}" class="btn btn-light">Google <img src="https://i.postimg.cc/3R5bCMvf/001-google.png" width="20" alt=""></a>
                        <a href="" class="btn btn-light">Facebook <img src="https://i.postimg.cc/Y0tnZ181/003-facebook.png" width="20" alt=""></a>
                    </div>
                </form>
            </div>

            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 mfliud">
                <div class="mb-3">
                    <h3>Register</h3>
                </div>
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if (session('verified'))
                    <div class="alert alert-success">{{ session('verified') }}</div>
                @endif
                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        <span class="text-danger">{{ $error }}</span>
                        <br>
                    @endforeach
                @endif
                <form class="border p-3 rounded" action="{{ route('customer.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label>Full Name *</label>
                            <input type="text" class="form-control" name="name" placeholder="Full Name">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Email *</label>
                        <input type="email" class="form-control" name="email" placeholder="Email*">
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label>Password *</label>
                            <input type="password" class="form-control" name="password" placeholder="********">
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-md full-width bg-dark text-light fs-md ft-medium">Create An Account</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</section>
@endsection
