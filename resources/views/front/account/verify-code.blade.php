@extends('front.layouts.app')

@section('content')

<section class="section-5 pt-3 pb-3 mb-3 bg-white">
    <div class="container">
        <div class="light-font">
            <ol class="breadcrumb primary-color mb-0">
                <li class="breadcrumb-item"><a class="white-text" href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item">Verify Code</li>
            </ol>
        </div>
    </div>
</section>

<section class=" section-10">
    <div class="container">
        @if (Session::has('success'))
        <div class="col-md-12">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{  Session::get('success')  }}
            </div>
        </div>
        @endif

        @if (Session::has('error'))
        <div class="col-md-12">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{  Session::get('error')  }}
            </div>
        </div>
        @endif
        <div class="login-form">
            <form action="{{ route('account.verifyCode') }}" method="post">
                @csrf
                <h4 class="modal-title">Verify Code</h4>

                <div class="form-group">
                    <input type="number" class="form-control" @error('code') is-invalid @enderror placeholder="Verification Code" required="required" name="code" id="code">
                    @error('code')
                        <p class="invalid-feedback">{{ $message }}</p>
                    @enderror
                </div>
                <div class="form-group">
                    @php
                        $phone = Session::get('phone_number');
                    @endphp
                    <input type="text" readOnly class="form-control" @error('phone_number') is-invalid @enderror placeholder="Phone" required="required" name="phone_number" id="phone_number" value="{{ $phone }}">
                    @error('phone_number')
                        <p class="invalid-feedback">{{ $message }}</p>
                    @enderror
                </div>

                <input type="submit" class="btn btn-dark btn-block btn-lg" value="Verify">
            </form>
            <div class="text-center small">Remember your password? <a href="{{ route('account.login') }}">Login</a></div>
        </div>
    </div>
</section>

@endsection
