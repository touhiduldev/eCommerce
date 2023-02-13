@extends('layouts.db')

@section('title')
    Kumo ~ Category Edit
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-6 m-auto">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                <div class="card">
                    <div class="card-header">
                        <h3>Edit Category</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('category.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <input type="hidden" class="form-control" value="{{ $category_info->id }}" name="category_id">
                                <label for="" class="form-label">Category</label>
                                <input type="text" class="form-control" name="category_name" value="{{ $category_info->category_name }}" placeholder="category name">
                                @error('category_name')
                                    <span style="color: red;">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label">Category Image</label>
                                <input type="file" class="form-control" onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])" name="category_img">
                                <img class="mt-2" width="100" src="{{ asset('uploads/category/'.$category_info->category_img) }}" id="blah" alt="">
                                @error('category_img')
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
