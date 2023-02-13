@extends('layouts.db')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h4>Color List</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <tr>
                                <th>Serial</th>
                                <th>Product Name</th>
                                <th>Color Name</th>
                                <th>Size</th>
                                <th>Quantity</th>
                                <th>Action</th>
                            </tr>
                            @foreach ($inventories as $serial => $inventory)
                                <tr>
                                    <td>{{ $serial+1 }}</td>
                                    <td>{{ $inventory->rel_to_product->product_name }}</td>
                                    <td>{{ $inventory->rel_to_color->color_name }}</td>
                                    <td>{{ $inventory->rel_to_size->size }}</td>
                                    <td>{{ $inventory->quantity }}</td>
                                    <td>
                                        <button type="button" class="btn btn-success light sharp" data-toggle="dropdown">
                                            <svg width="20px" height="20px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"/><circle fill="#000000" cx="5" cy="12" r="2"/><circle fill="#000000" cx="12" cy="12" r="2"/><circle fill="#000000" cx="19" cy="12" r="2"/></g></svg>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="#">Edit</a>
                                            <a class="dropdown-item" href="#">Delete</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h3>Add Inventory</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('inventory.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <input type="text" readonly class="form-control" value="{{ $product_info->product_name }}">
                                <input type="hidden" name="product_id" class="form-control" value="{{ $product_info->id }}">
                            </div>
                            <div class="mb-3">
                                <select name="color_id" class="form-control">
                                    <option value="">--Select Color--</option>
                                    @foreach ($colors as $color)
                                        <option value="{{ $color->id }}">{{ $color->color_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <select name="size_id" class="form-control">
                                    <option value="">--Select Size--</option>
                                    @foreach ($sizes as $size)
                                        <option value="{{ $size->id }}">{{ $size->size }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <input type="number" class="form-control" name="quantity" placeholder="Quantity">
                            </div>
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary">Add Inventory</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
