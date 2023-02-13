@extends('layouts.db')

@section('title')
    Kumo ~ Subcategory Edit
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
                        <h3>Edit SubCategory</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('subcategory.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="" class="form-label">Category</label>
                                <input type="hidden" name="subcategory_id" value="{{ $subcatgories->id }}">
                                <select name="category_id" class="form-control" id="">
                                    <option value="">-- Select Category --</option>
                                    @foreach ($categorieys as $category)
                                        <option value="{{ $category->id }}" {{ $category->id == $subcatgories->category_id?'selected':'' }}>{{ $category->category_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label">SubCategory</label>
                                <input type="text" class="form-control" value="{{ $subcatgories->subcategory_name }}" name="subcategory_name" placeholder="Subcategory name">
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label">SubCategory Image</label>
                                <input type="file" class="form-control" onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])" name="subcategory_img">
                                <img class="mt-2" width="100" src="{{ asset('uploads/subcategory/'.$subcatgories->subcategory_img) }}" id="blah" alt="">
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
