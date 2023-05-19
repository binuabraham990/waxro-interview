@extends('layouts')

@section('content')

<div class="row justify-content-center mt-5">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">Welcome {{ $data->name }}</div>
            <div class="card-body">
                <div>Your ID : {{ $data->email }}</div>
                <div>Your Balance : {{ $data->balance }}</div>
            </div>
        </div>
    </div>    
</div>

@endsection