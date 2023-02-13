@extends('layouts.db')

@section('content')
    <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Add New Product</li>
    </ul>
    <div class="">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h3>Add New Product</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <select class="form-control" name="category_id" id="category_id">
                                            <option value="">-- Select Category --</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <select class="form-control" name="subcategory_id" id="subcategory_id">
                                            <option value="">-- Select SubCategory --</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="Brand" name="brand">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="Product Name" name="product_name">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="Product Price" name="price">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <input type="Number" class="form-control" placeholder="Discount %" name="discount">
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="" class="py-2" class="form-label"> <strong>Short Description</strong> </label>
                                        <textarea id="" name="short_desp" class="form-control" placeholder="Short Description"></textarea>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="" class="py-2"><strong>Description</strong></label>
                                        <textarea id="summernote" name="long_desp" class="form-control">  </textarea>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="">Product Preview</label>
                                        <input type="file" name="preview" class="form-control" value="">
                                    </div>
                                  </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="" class="form-label">Product Thumnails</label>
                                        <input type="file" name="thumbnails[]" multiple class="form-control" value="">
                                    </div>
                                </div>
                                <div class="col-lg-12 text-center mt-3 rounded-button">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-rounded btn-outline-primary">Publish</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer_script')
    <script>
        $('#summernote').summernote();
    </script>
    <script>
        $('#category_id').change(function(){
            var category_id = $(this).val();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url:'/subcategory',
                type: 'POST',
                data:{'category_id': category_id},
                success:function(data){
                   $('#subcategory_id').html(data);
                }
            });

        });
    </script>
@endsection
