@extends('layouts')

@section('content')
<div class="row justify-content-center mt-5">
    <div class="col-md-8">

        <div class="card">
            <div class="card-header">Statement of Account</div>
            <div class="card-body">
                <table class="table table-bordered table-primary">
                    <tr>
                        <th>#</th>
                        <th>Date Time</th>
                        <th>Amount</th>
                        <th>Type</th>
                        <th>Details</th>
                        <th>Balance</th>
                    </tr>
                    @foreach($data as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->created_at }}</td>
                        <td>{{ $item->amount }}</td>
                        <td>{{ $item->type }}</td>
                        <td>{{ $item->details }}</td>
                        <td>{{ $item->balance }}</td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>    
</div>
@endsection