@extends('layouts.db')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Responsive Table</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table header-border table-responsive-sm table-bordered">
                            <thead>
                                <tr>
                                    <th>Serial</th>
                                    <th>Order</th>
                                    <th>User</th>
                                    <th>Date</th>
                                    <th>Subtotal</th>
                                    <th>Charge</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $serial => $order )

                                <tr>
                                    <td>{{ $serial+1 }}</td>
                                    <td>{{ $order->order_id }}</td>
                                    <td>{{ $order->rel_to_customer->name }}</td>
                                    <td>{{ $order->created_at }}</td>
                                    <td>{{ number_format($order->sub_total) }}</td>
                                    <td>{{ $order->charge }}</td>
                                    <td>{{ number_format($order->total) }}</td>
                                    <td>
                                        @php
                                            if($order->status == 1){
                                                echo '<span class="badge badge-danger">On Hold</span>';
                                            }elseif ($order->status == 2) {
                                                echo '<span class="badge badge-warning">Processing</span>';
                                            }elseif ($order->status == 3) {
                                                echo '<span class="badge badge-info">Packeging</span>';
                                            }elseif ($order->status == 4) {
                                                echo '<span class="badge badge-secondary">Redy to Deliver</span>';
                                            }elseif ($order->status == 5) {
                                                echo '<span class="badge badge-primary">Shipped</span>';
                                            }else {
                                                echo '<span class="badge badge-success">Deliverd</span>';
                                            }
                                    @endphp
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button type="button" class="btn btn-success light sharp" data-toggle="dropdown">
                                                <svg width="20px" height="20px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"/><circle fill="#000000" cx="5" cy="12" r="2"/><circle fill="#000000" cx="12" cy="12" r="2"/><circle fill="#000000" cx="19" cy="12" r="2"/></g></svg>
                                            </button>
                                            <div class="dropdown-menu">
                                                <form action="{{ route('order.status') }}" method="post">
                                                    @csrf
                                                    <button name="status" value="{{ $order->order_id.','.'1' }}" class="dropdown-item status">On Hold</button>
                                                    <button name="status" value="{{ $order->order_id.','.'2' }}" class="dropdown-item status">Processing</button>
                                                    <button name="status" value="{{ $order->order_id.','.'3' }}" class="dropdown-item status">Packeging</button>
                                                    <button name="status" value="{{ $order->order_id.','.'4' }}" class="dropdown-item status">Redy to Deliver</button>
                                                    <button name="status" value="{{ $order->order_id.','.'5' }}" class="dropdown-item status">Shiped</button>
                                                    <button name="status" value="{{ $order->order_id.','.'6' }}" class="dropdown-item status">Delivered</button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach

                                </form>
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
