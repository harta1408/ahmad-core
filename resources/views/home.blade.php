@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    Selamat datang di Gerakan AHSOHA, anda telah terdaftar namun demikian untuk akses
                    ke fasilitas, silakan hubungi Admin
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
