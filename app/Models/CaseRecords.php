<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaseRecords extends Model
{
    //
    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function notes()
    {
        return $this->hasMany(CaseNote::class, 'case_id');
    }
}
