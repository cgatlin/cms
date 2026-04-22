<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['case_id', 'user_id', 'note'])]
class CaseNote extends Model
{
    //
    public function case()
    {
        return $this->belongsTo(CaseRecords::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
