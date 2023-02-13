@extends('layouts.db')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-7 m-auto">
                @if (session('updated'))
                    <div class="alert alert-success">{{ session('updated') }}</div>
                @endif
                <div class="card">
                    <div class="card-header">
                        <h4>Update Your Profile</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('update.profile') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="" class="form-label">Name</label>
                                <input type="text" id="name" class="form-control" value="{{ Auth::user()->name }}" name="name" placeholder="Full Name">
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label">Email</label>
                                <input type="text" id="email" class="form-control" value="{{ Auth::user()->email }}" name="email" placeholder="Email">
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label">Image</label>
                                <input type="file" class="form-control" name="image" placeholder="Email">
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
