@extends('layouts.task')

@section('content')

    <div class="container py-4">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Page Header with Search -->
        <div class="row g-3 align-items-center mb-4">
            <div class="col-md-4 d-flex align-items-center">
                <div class="me-3">
                    <i class="fas fa-tasks fa-3x icon"></i>
                </div>
                <div>
                    <h1 class="h3 mb-0 text-dark">Daftar Tugas</h1>
                    <p class="text-muted small mb-0">
                        <i class="fas fa-clipboard-list me-1"></i>
                        Kelola tugas-tugas Anda
                    </p>
                </div>
            </div>
            <div class="col-md-5">
                <form action="{{ route('tasks.index') }}" method="GET" class="position-relative">
                    <div class="input-group shadow-gray-50 inline">
                        <input type="text" class="form-control border-end-0 pe-0" name="search"
                            placeholder="Cari tugas..." value="{{ request('search') }}" id="searchInput">
                     
                        <button type="submit" class="btn btn-secondary border-0 "
                            id="clearSearchBtn">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
            <div class="col-md-3 text-md-end d-flex gap-2">
                <button class="btn btn-primary shadow-sm flex-grow-1" data-bs-toggle="modal" data-bs-target="#addTaskModal">
                    <i class="fas fa-plus-circle me-1"></i> Tambah Tugas
                </button>

            </div>
        </div>

        <!-- Task List Card -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <ul class="nav nav-tabs card-header-tabs d-flex justify-content-between">
                    <li class="nav-item flex-grow-1 text-center">
                        <a class="nav-link d-flex align-items-center justify-content-center {{ !request('status') ? 'active' : '' }}"
                            href="{{ route('tasks.index') }}" style="min-width: 100px;">
                            <i class="fas fa-list me-2"></i>
                            Semua
                            <span class="badge rounded-pill bg-secondary ms-2">{{ $tasks->count() }}</span>
                        </a>
                    </li>
                    <li class="nav-item flex-grow-1 text-center">
                        <a class="nav-link d-flex align-items-center justify-content-center {{ request('status') === 'tertunda' ? 'active' : '' }}"
                            href="{{ route('tasks.index', ['status' => 'tertunda']) }}" style="min-width: 100px;">
                            <i class="fas fa-clock me-2"></i>
                            Aktif
                            <span
                                class="badge rounded-pill bg-primary ms-2">{{ $tasks->where('status', 'tertunda')->count() }}</span>
                        </a>
                    </li>
                    <li class="nav-item flex-grow-1 text-center">
                        <a class="nav-link d-flex align-items-center justify-content-center {{ request('status') === 'selesai' ? 'active' : '' }}"
                            href="{{ route('tasks.index', ['status' => 'selesai']) }}" style="min-width: 100px;">
                            <i class="fas fa-check-circle me-2"></i>
                            Selesai
                            <span
                                class="badge rounded-pill bg-success ms-2">{{ $tasks->where('status', 'selesai')->count() }}</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="card-body p-0">
            @if ($tasks->isEmpty())
                <div class="text-center py-5 bg-white">
                    <div class="py-3">
                        <i class="fas fa-tasks fa-4x text-muted mb-3"></i>
                        <h4 class="text-gray-900">Belum Ada Tugas</h4>
                        <p class="text-muted mb-0">Mulai dengan menambahkan tugas baru</p>
                    </div>
                </div>
            @else
                <div class="list-group list-group-flush">
                    @foreach ($tasks as $task)
                        <div class="list-group-item p-3 {{ $task->status == 'selesai' ? 'bg-light' : '' }}">
                            <div class="row align-items-center g-3">
                                <div class="col-auto">
                                    <form action="{{ route('tasks.toggle', $task->id) }}" method="POST">
                                        @csrf
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input shadow-none"
                                                onchange="this.form.submit()"
                                                {{ $task->status == 'selesai' ? 'checked' : '' }}>
                                        </div>
                                    </form>
                                </div>
                                <div class="col">
                                    <h6
                                        class="mb-1 {{ $task->status == 'selesai' ? 'text-decoration-line-through text-muted' : 'text-gray-900' }}">
                                        {{ $task->judul }}
                                    </h6>
                                    @if ($task->deskripsi)
                                        <p class="text-muted small mb-1">{{ Str::limit($task->deskripsi, 100) }}</p>
                                    @endif
                                    <div class="d-flex align-items-center gap-2">
                                        @if ($task->deadline)
                                            <span
                                                class="badge {{ strtotime($task->deadline) < time() ? 'bg-danger' : 'bg-info' }}">
                                                <i class="far fa-calendar-alt me-1"></i>
                                                {{ $task->deadline->format('d M Y') }}
                                            </span>
                                        @endif
                                        <span class="badge {{ $task->status == 'selesai' ? 'bg-success' : 'bg-warning' }}">
                                            <i
                                                class="fas fa-{{ $task->status == 'selesai' ? 'check' : 'clock' }} me-1"></i>
                                            {{ ucfirst($task->status) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="btn-group">
                                        <button class="btn btn-light btn-sm border shadow-none" data-bs-toggle="modal"
                                            data-bs-target="#editTaskModal{{ $task->id }}" title="Edit">
                                            <i class="fas fa-edit text-primary"></i>
                                        </button>
                                        <button class="btn btn-light btn-sm border shadow-none" data-bs-toggle="modal"
                                            data-bs-target="#deleteTaskModal{{ $task->id }}" title="Hapus">
                                            <i class="fas fa-trash-alt text-danger"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
    </div>

    <!-- Add Task Modal -->
    <div class="modal fade" id="addTaskModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Tugas Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="addTaskForm" action="{{ route('tasks.store') }}" method="POST" class="needs-validation"
                    novalidate>
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Judul Tugas<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="judul" required
                                oninvalid="this.setCustomValidity('Judul tugas harus diisi')"
                                oninput="this.setCustomValidity('')">
                            <div class="invalid-feedback">
                                Judul tugas harus diisi
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi<span class="text-danger">*</span></label>
                            <textarea class="form-control" name="deskripsi" rows="3" required
                                oninvalid="this.setCustomValidity('Deskripsi tugas harus diisi')" oninput="this.setCustomValidity('')"></textarea>
                            <div class="invalid-feedback">
                                Deskripsi tugas harus diisi
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tenggat Waktu<span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="deadline" required
                                oninvalid="this.setCustomValidity('Tenggat waktu harus diisi')"
                                oninput="this.setCustomValidity('')">
                            <div class="invalid-feedback">
                                Tenggat waktu harus diisi
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status<span class="text-danger">*</span></label>
                            <select class="form-select" name="status" required
                                oninvalid="this.setCustomValidity('Status harus dipilih')"
                                oninput="this.setCustomValidity('')">
                                <option value="">Pilih status</option>
                                <option value="tertunda">Tertunda</option>
                                <option value="selesai">Selesai</option>
                            </select>
                            <div class="invalid-feedback">
                                Status harus dipilih
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal untuk setiap task -->
    @foreach ($tasks as $task)
        <!-- Edit Task Modal -->
        <div class="modal fade" id="editTaskModal{{ $task->id }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Tugas</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('tasks.update', $task->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Judul Tugas</label>
                                <input type="text" class="form-control" name="judul" value="{{ $task->judul }}"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Deskripsi</label>
                                <textarea class="form-control" name="deskripsi" rows="3">{{ $task->deskripsi }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Tenggat Waktu</label>
                                <input type="date" class="form-control" name="deadline"
                                    value="{{ $task->deadline->format('Y-m-d') }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select class="form-select" name="status">
                                    <option value="tertunda" {{ $task->status == 'tertunda' ? 'selected' : '' }}>Tertunda
                                    </option>
                                    <option value="selesai" {{ $task->status == 'selesai' ? 'selected' : '' }}>Selesai
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Delete Task Modal -->
        <div class="modal fade" id="deleteTaskModal{{ $task->id }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header border-0">
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body text-center pb-0">
                        <i class="fas fa-exclamation-circle text-warning display-3 mb-3"></i>
                        <h4>Hapus Tugas?</h4>
                        <p class="text-muted">Apakah Anda yakin ingin menghapus tugas:<br />"{{ $task->judul }}"?</p>
                        <p class="small text-danger">Tindakan ini tidak dapat dibatalkan</p>
                    </div>
                    <div class="modal-footer border-0 justify-content-center pb-4">
                        <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Batal</button>
                        <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger px-4">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <!-- Edit Task Modal -->
    <div class="modal fade" id="editTaskModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Tugas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editTaskForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Judul Tugas</label>
                            <input type="text" class="form-control" name="judul" id="edit_judul" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea class="form-control" name="deskripsi" id="edit_deskripsi" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tenggat Waktu</label>
                            <input type="date" class="form-control" name="deadline" id="edit_deadline">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status" id="edit_status">
                                <option value="tertunda">Tertunda</option>
                                <option value="selesai">Selesai</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Add this at the beginning of your scripts section
            (() => {
                'use strict'

                // Fetch all forms we want to apply validation to
                const forms = document.querySelectorAll('.needs-validation')

                // Loop over them and prevent submission
                Array.from(forms).forEach(form => {
                    form.addEventListener('submit', event => {
                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }
                        form.classList.add('was-validated')
                    }, false)
                })
            })()

            // Menambahkan fungsi pencarian
            document.getElementById('searchTask').addEventListener('keyup', function(e) {
                const searchText = e.target.value.toLowerCase();
                const tasks = document.querySelectorAll('.list-group-item');

                tasks.forEach(task => {
                    const title = task.querySelector('h6').textContent.toLowerCase();
                    task.style.display = title.includes(searchText) ? '' : 'none';
                });
            });

            // Menambahkan fungsi filter
            document.querySelectorAll('.nav-link').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const filter = this.dataset.filter;
                    const tasks = document.querySelectorAll('.list-group-item');

                    document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));
                    this.classList.add('active');

                    tasks.forEach(task => {
                        const isCompleted = task.querySelector('.form-check-input').checked;
                        if (filter === 'all') {
                            task.style.display = '';
                        } else if (filter === 'active' && !isCompleted) {
                            task.style.display = '';
                        } else if (filter === 'completed' && isCompleted) {
                            task.style.display = '';
                        } else {
                            task.style.display = 'none';
                        }
                    });
                });
            });

            function editTask(id) {
                fetch(`/tasks/edit/${id}`)
                    .then(response => response.json())
                    .then(task => {
                        document.getElementById('edit_judul').value = task.judul;
                        document.getElementById('edit_deskripsi').value = task.deskripsi;
                        document.getElementById('edit_deadline').value = task.deadline.split(' ')[0];
                        document.getElementById('edit_status').value = task.status;

                        const editForm = document.getElementById('editTaskForm');
                        editForm.action = `/tasks/${id}`;

                        new bootstrap.Modal(document.getElementById('editTaskModal')).show();
                    })
                    .catch(error => {
                        alert('Terjadi kesalahan saat mengambil data tugas');
                        console.error('Error:', error);
                    });
            }

            function deleteTask(id) {
                if (confirm('Apakah Anda yakin ingin menghapus tugas ini?')) {
                    fetch(`/tasks/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            alert(data.message);
                            window.location.reload();
                        })
                        .catch(error => {
                            alert('Terjadi kesalahan saat menghapus tugas');
                            console.error('Error:', error);
                        });
                }
            }
        </script>
    @endpush
