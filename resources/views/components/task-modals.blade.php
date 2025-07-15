<!-- Add Task Modal -->
<div class="modal fade" id="addTaskModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Tugas Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('tasks.store') }}" method="POST" class="needs-validation" novalidate>
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Judul Tugas</label>
                        <input type="text" class="form-control" name="judul" required>
                        <div class="invalid-feedback">Judul tugas harus diisi</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea class="form-control" name="deskripsi" rows="3" required></textarea>
                        <div class="invalid-feedback">Deskripsi tugas harus diisi</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deadline</label>
                        <input type="date" class="form-control" name="deadline" required>
                        <div class="invalid-feedback">Deadline harus diisi</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="status" required>
                            <option value="tertunda">Tertunda</option>
                            <option value="selesai">Selesai</option>
                        </select>
                        <div class="invalid-feedback">Status harus dipilih</div>
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

<!-- Edit Task Modals -->
@foreach ($tasks as $task)
<div class="modal fade" id="editTaskModal{{ $task->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Tugas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('tasks.update', $task->id) }}" method="POST" class="needs-validation" novalidate>
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Judul Tugas</label>
                        <input type="text" class="form-control" name="judul" value="{{ $task->judul }}" required>
                        <div class="invalid-feedback">Judul tugas harus diisi</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea class="form-control" name="deskripsi" rows="3" required>{{ $task->deskripsi }}</textarea>
                        <div class="invalid-feedback">Deskripsi tugas harus diisi</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deadline</label>
                        <input type="date" class="form-control" name="deadline" 
                            value="{{ $task->deadline ? $task->deadline->format('Y-m-d') : '' }}" required>
                        <div class="invalid-feedback">Deadline harus diisi</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="status" required>
                            <option value="tertunda" {{ $task->status == 'tertunda' ? 'selected' : '' }}>Tertunda</option>
                            <option value="selesai" {{ $task->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        </select>
                        <div class="invalid-feedback">Status harus dipilih</div>
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
            <div class="modal-header">
                <h5 class="modal-title">Hapus Tugas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus tugas ini?</p>
                <p class="text-muted mb-0">{{ $task->judul }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach