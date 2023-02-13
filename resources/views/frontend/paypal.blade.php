@extends('frontend.master')

@section('title')
    Pay with Paypal - Kumos
@endsection

@section('content')

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" />
</head>
<body>
    <div class="container my-5 text-center">
        <h2>Pay with world famous secure payment method PayPal</h2>
        <a href="{{ route('processTransaction') }}" class="btn btn-primary mt-3">Pay $224 via Paypal</a>
    </div>
</body>
</html>
@endsection
