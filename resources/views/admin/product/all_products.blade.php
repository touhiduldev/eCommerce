@extends('layouts.db')

@section('content')
<ul class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">All Products</li>
</ul>

    <div class="">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h3>All Products</h3>
                        <span style="color: #6C757D; font-size: 16px;"> {{ $count }} item</span>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <tr>
                                <th>Serial</th>
                                <th>Title</th>
                                <th>Price</th>
                                <th>Discount</th>
                                <th>Category</th>
                                <th>SubCategory</th>
                                <th>Date</th>
                                <th>Preview</th>
                                <th>Thumbnails</th>
                                <th>Action</th>
                            </tr>
                            @foreach ($all_products as $serial => $products)
                                <tr>
                                    <td>{{ $serial+1 }}</td>
                                    <td>{{ $products->product_name }}</td>
                                    <td>{{ $products->price }}</td>
                                    <td>{{ $products->discount }}%</td>
                                    <td>{{ $products->rel_to_cat->category_name }}</td>
                                    <td>{{ $products->rel_to_subcat->subcategory_name }}</td>
                                    <td>{{ $products->updated_at }}</td>
                                    <td>
                                        <img width="50" src="{{ asset('uploads/products/preview/'.$products->preview) }}" alt="">
                                    </td>
                                    <td>
                                        @foreach (App\Models\Thumbnail::where('product_id', $products->id)->get() as $thumbnail)
                                            <img width="30" src="{{ asset('uploads/products/thumbnails/'.$thumbnail->thumbnail) }}" alt="">
                                        @endforeach
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button type="button" class="btn btn-success light sharp" data-toggle="dropdown">
                                                <svg width="20px" height="20px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"/><circle fill="#000000" cx="5" cy="12" r="2"/><circle fill="#000000" cx="12" cy="12" r="2"/><circle fill="#000000" cx="19" cy="12" r="2"/></g></svg>
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="{{ route('inventory', $products->id) }}">Inventory</a>
                                                <a class="dropdown-item" href="{{ route('product.dlt', $products->id) }}">Delete</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
