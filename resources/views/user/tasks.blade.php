@extends('layouts.task')

@section('content')
    <div class="container py-4">
        @if (session('success') || session('error'))
            <div class="alert alert-{{ session('success') ? 'success' : 'danger' }} alert-dismissible fade show" role="alert">
                {{ session('success') ?? session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Page Header with Search -->
        <div class="row g-3 align-items-center mb-4">
            <div class="col-md-4">
                <div class="d-flex align-items-center">
                    <i class="fas fa-tasks fa-3x me-3"></i>
                    <div>
                        <h1 class="h3 mb-0">Daftar Tugas</h1>
                        <p class="text-muted small mb-0">
                            <i class="fas fa-clipboard-list me-1"></i>Kelola tugas-tugas Anda
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <form action="{{ route('tasks.index') }}" method="GET">
                    <div class="input-group">
                        <input type="text" class="form-control" name="search" placeholder="Cari tugas..." 
                            value="{{ request('search') }}" id="searchInput">
                        <button type="submit" class="btn btn-secondary">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
            <div class="col-md-3">
                <button class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#addTaskModal">
                    <i class="fas fa-plus-circle me-1"></i> Tambah Tugas
                </button>
            </div>
        </div>

        <!-- Task List Card -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <ul class="nav nav-tabs card-header-tabs">
                    <li class="nav-item flex-grow-1 text-center">
                        <a class="nav-link {{ !request('status') ? 'active' : '' }}" 
                            href="{{ route('tasks.index') }}">
                            <i class="fas fa-list me-2"></i>Semua
                            <span class="badge bg-secondary ms-2">{{ $tasks->count() }}</span>
                        </a>
                    </li>
                    <li class="nav-item flex-grow-1 text-center">
                        <a class="nav-link {{ request('status') === 'tertunda' ? 'active' : '' }}" 
                            href="{{ route('tasks.index', ['status' => 'tertunda']) }}">
                            <i class="fas fa-clock me-2"></i>Aktif
                            <span class="badge bg-primary ms-2">{{ $tasks->where('status', 'tertunda')->count() }}</span>
                        </a>
                    </li>
                    <li class="nav-item flex-grow-1 text-center">
                        <a class="nav-link {{ request('status') === 'selesai' ? 'active' : '' }}" 
                            href="{{ route('tasks.index', ['status' => 'selesai']) }}">
                            <i class="fas fa-check-circle me-2"></i>Selesai
                            <span class="badge bg-success ms-2">{{ $tasks->where('status', 'selesai')->count() }}</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="card-body p-0">
                @if ($tasks->isEmpty())
                    <div class="text-center py-5">
                        <i class="fas fa-tasks fa-4x text-muted mb-3"></i>
                        <h4>Belum Ada Tugas</h4>
                        <p class="text-muted">Mulai dengan menambahkan tugas baru</p>
                    </div>
                @else
                    <div class="list-group list-group-flush">
                        @foreach ($tasks as $task)
                            <div class="list-group-item p-3 {{ $task->status == 'selesai' ? 'bg-light' : '' }}">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <form action="{{ route('tasks.toggle', $task->id) }}" method="POST">
                                            @csrf
                                            <input type="checkbox" class="form-check-input" 
                                                onchange="this.form.submit()"
                                                {{ $task->status == 'selesai' ? 'checked' : '' }}>
                                        </form>
                                    </div>
                                    <div class="col">
                                        <h6 class="{{ $task->status == 'selesai' ? 'text-decoration-line-through text-muted' : '' }}">
                                            {{ $task->judul }}
                                        </h6>
                                        @if ($task->deskripsi)
                                            <p class="text-muted small mb-1">{{ Str::limit($task->deskripsi, 100) }}</p>
                                        @endif
                                        <div class="d-flex gap-2">
                                            @if ($task->deadline)
                                                <span class="badge {{ strtotime($task->deadline) < time() ? 'bg-danger' : 'bg-info' }}">
                                                    <i class="far fa-calendar-alt me-1"></i>
                                                    {{ $task->deadline->format('d M Y') }}
                                                </span>
                                            @endif
                                            <span class="badge {{ $task->status == 'selesai' ? 'bg-success' : 'bg-warning' }}">
                                                <i class="fas fa-{{ $task->status == 'selesai' ? 'check' : 'clock' }} me-1"></i>
                                                {{ ucfirst($task->status) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <button class="btn btn-sm btn-light" data-bs-toggle="modal" 
                                            data-bs-target="#editTaskModal{{ $task->id }}">
                                            <i class="fas fa-edit text-primary"></i>
                                        </button>
                                        <button class="btn btn-sm btn-light" data-bs-toggle="modal" 
                                            data-bs-target="#deleteTaskModal{{ $task->id }}">
                                            <i class="fas fa-trash-alt text-danger"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    @include('components.task-modals')
@endsection

@push('scripts')
    <script>
        (() => {
            'use strict'
            document.querySelectorAll('.needs-validation').forEach(form => {
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                })
            })
        })()
    </script>
@endpush
