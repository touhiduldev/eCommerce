@extends('layouts.db')

@section('content')
<ul class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Coupon</li>
</ul>
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h3>View Coupon List</h3>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <tr>
                        <th>Serial</th>
                        <th>Coupon</th>
                        <th>Discount</th>
                        <th>Validity</th>
                        <th>Action</th>
                    </tr>
                    @foreach ($coupons as $serial=> $coupon)
                    <tr>
                        <td>{{ $serial+1 }}</td>
                        <td>{{ $coupon->coupon_name }}</td>
                        <td>{{ $coupon->discount }} {{ ($coupon->type == 1)?'%':'TK' }}</td>
                        <td>{{ $coupon->validity }}</td>
                        <td>
                            <div class="d-flex">
                                <a href="{{ route('dlt.coupon', $coupon->id) }}" class="btn btn-danger shadow btn-xs sharp"><i class="fa fa-trash"></i></a>
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
                <h3>Create Coupon</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('coupon') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="" class="form-lable">Coupon<span class="text-danger">*</span></label>
                        <input type="text" name="coupon_name" id="" class="form-control" placeholder="coupon name">
                    </div>
                    <div class="mb-3">
                        <select name="type" id="" class="form-control">
                            <option value="">Select Type of Discount</option>
                            <option value="1">Percentage</option>
                            <option value="2">Solid Amount</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-lable">Discount<span class="text-danger">*</span></label>
                        <input type="text" name="discount" id="" class="form-control" placeholder="type amount of discount">
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-lable">Validity<span class="text-danger">*</span></label>
                        <input type="date" name="validity" id="" class="form-control">
                    </div>
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary">Create Coupon</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
