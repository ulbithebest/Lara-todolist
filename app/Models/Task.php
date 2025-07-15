<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul',
        'deskripsi',
        'deadline',
        'status',
        'id_user'
    ];
    protected $casts = [
        'deadline' => 'datetime'
    ];

    protected $attributes = [
        'status' => 'tertunda'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

     function scopeSearch($query, $filters)
    {
        return $query
            ->when($filters['search'] ?? false, function($query, $search) {
                $query->where('judul', 'like', '%' . $search . '%');
            })
            ->when($filters['status'] ?? null, fn($query, $status) => $query->where('status', $status))
            ->where('id_user', auth()->id());
    }

    public function scopeCountByStatus($query, $status = null)
    {
        return $query->where('id_user', auth()->id())
            ->when($status, fn($query) => $query->where('status', $status))
            ->count();
    }
}