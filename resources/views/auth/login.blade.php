@extends('home')

@section('content')

@if(session('error'))
    <div class="alert alert-danger" style="text-align:center;">
        {{session('error')}}
    </div>
@endif
<div class="page-inner">
    <div id="main-wrapper">
        <div class="row">
            <div class="col-md-3 center">
                <div class="login-box">
                    <a href="index.html" class="logo-name text-lg text-center">Gestok - Gestão de Estoque</a>
                    
                    <form method="POST" action="{{ route('login') }}">
                        <br><br>
                        @csrf
                        <div class="form-group">
                        
                            <input id="email" type="email" placeholder="E-mail" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>Usuário e/ou senha incorretos!</strong>
                                </span>
                            @enderror
                        </div>
                        <br>
                        <div class="form-group">
                            <input id="password" placeholder="Senha" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                        </div>
                        <br>
                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                        <br>
                        <button type="submit" class="btn btn-success btn-block">{{ __('Enter') }}</button>
                        
                    </form>
                    
                </div>
            </div>
        </div><!-- Row -->
    </div><!-- Main Wrapper -->
</div><!-- Page Inner -->
                
@endsection
