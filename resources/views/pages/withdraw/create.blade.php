@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Withdraw</div>

                <div class="card-body">
                    @if (session('errors'))
                        <div class="alert alert-danger" role="alert">
                            @foreach (session('errors') as $error)
                                {{ $error }}
                            @endforeach
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
                            <label for="account_name" class="col-md-4 col-form-label text-md-right">{{ __('Rekening Tujuan') }}</label>

                            <div class="col-md-6">
                                <input id="account_number" disabled type="text" class="form-control @error('account_name') is-invalid @enderror" name="account_name" value="{{ $store->account_number }}" required autocomplete="saldo" autofocus>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="account_name" class="col-md-4 col-form-label text-md-right">{{ __('Rekening Name') }}</label>

                            <div class="col-md-6">
                                <input id="account_name" disabled type="text" class="form-control @error('account_name') is-invalid @enderror" name="account_name" value="{{ $store->account_name }}" required autocomplete="saldo" autofocus>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="bank_code" class="col-md-4 col-form-label text-md-right">{{ __('Rekening Name') }}</label>

                            <div class="col-md-6">
                                <input id="bank_code" disabled type="text" class="form-control @error('bank_code') is-invalid @enderror" name="bank_code" value="{{ $store->bank_code }}" required autocomplete="saldo" autofocus>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="amount" class="col-md-4 col-form-label text-md-right">{{ __('Nominal Tarik') }}</label>

                            <div class="col-md-6">
                                <input id="amount" type="number" class="form-control @error('amount') is-invalid @enderror" name="amount" value="{{ old('amount') }}" required autocomplete="nominal">

                                @error('amount')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="remark" class="col-md-4 col-form-label text-md-right">{{ __('Note') }}</label>

                            <div class="col-md-6">
                                <input id="remark" type="text" class="form-control @error('remark') is-invalid @enderror" name="remark" value="{{ old('remark') }}" required autocomplete="remark">

                                @error('remark')
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
