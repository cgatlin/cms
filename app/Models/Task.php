<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'title',
    'description',
    'assigned_to',
    'due_date',
])]
class Task extends Model
{
    //
    protected $casts = [
        'due_date' => 'date', // or 'datetime'
    ];

    public function case()
    {
        return $this->belongsTo(CaseRecords::class, 'case_id');
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
