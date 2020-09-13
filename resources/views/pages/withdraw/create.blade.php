@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Withdarw</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('withdraw.store') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="saldo" class="col-md-4 col-form-label text-md-right">{{ __('My Saldo') }}</label>

                            <div class="col-md-6">
                                <input id="saldo" disabled type="text" class="form-control @error('saldo') is-invalid @enderror" name="saldo" value="{{ number_format($store->saldo) }}" required autocomplete="saldo" autofocus>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="rekening" class="col-md-4 col-form-label text-md-right">{{ __('Rekening Tujuan') }}</label>

                            <div class="col-md-6">
                                <input id="rekening" disabled type="text" class="form-control @error('rekening') is-invalid @enderror" name="rekening" value="{{ $store->account_number }}" required autocomplete="saldo" autofocus>
                            </div>
                        </div>


                        <div class="form-group row">
                            <label for="nominal" class="col-md-4 col-form-label text-md-right">{{ __('Nominal Tarik') }}</label>

                            <div class="col-md-6">
                                <input id="nominal" type="number" class="form-control @error('nominal') is-invalid @enderror" name="nominal" value="{{ old('nominal') }}" required autocomplete="nominal">

                                @error('nominal')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Tarik') }}
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
