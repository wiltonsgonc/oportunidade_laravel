@extends('layouts.app')

@section('title', 'Dashboard - Sistema de Vagas')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Dashboard</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card text-white bg-primary mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">Vagas Abertas</h5>
                                    <h2 class="card-text">0</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-white bg-success mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">Vagas Encerradas</h5>
                                    <h2 class="card-text">0</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-white bg-info mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">Total de Vagas</h5>
                                    <h2 class="card-text">0</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <h5>Bem-vindo, {{ Auth::user()->nome ?? Auth::user()->usuario }}!</h5>
                        <p class="text-muted">Selecione uma opção no menu superior para gerenciar o sistema.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection