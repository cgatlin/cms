<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //
    public function assignedCases()
    {
        return $this->hasMany(CaseRecords::class, 'category_id');
    }
}
