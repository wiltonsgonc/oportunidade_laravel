{{-- resources/views/auth/login-error.blade.php --}}
@extends('layouts.app')

@section('title', 'Erro na Autenticação')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body text-center p-5">
                    <i class="bi bi-exclamation-triangle-fill text-danger" style="font-size: 4rem;"></i>
                    
                    <h3 class="mt-4">Erro na Autenticação</h3>
                    
                    <p class="text-muted mt-3">
                        Ocorreu um erro durante o processo de autenticação.
                    </p>

                    @if(isset($errorDescription) && $errorDescription)
                    <div class="alert alert-danger mt-3">
                        <strong>Detalhes:</strong> {{ $errorDescription }}
                    </div>
                    @endif

                    <div class="mt-4">
                        <a href="{{ route('login') }}" class="btn btn-primary">
                            <i class="bi bi-arrow-left me-2"></i>Tentar novamente
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
