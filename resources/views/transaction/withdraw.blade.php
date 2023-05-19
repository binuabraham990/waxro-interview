@extends('layouts')

@section('content')
<div class="row justify-content-center mt-5">
    <div class="col-md-8">

        <div class="card">
            <div class="card-header">Withdraw Money</div>
            <div class="card-body">
                <form action="{{ route('save_withdraw') }}" method="post">
                    @csrf
                    <div class="mb-3 row">
                        <label for="amount" class="col-md-4 col-form-label text-md-end text-start">Amount</label>
                        <div class="col-md-6">
                            <input type="number" class="form-control @error('amount') is-invalid @enderror" id="amount" name="amount" value="{{ old('amount') }}">
                            @if ($errors->has('amount'))
                            <span class="text-danger">{{ $errors->first('amount') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <input type="submit" class="col-md-3 offset-md-5 btn btn-primary" value="Withdraw">
                    </div>

                </form>
            </div>
        </div>
    </div>    
</div>
@endsection