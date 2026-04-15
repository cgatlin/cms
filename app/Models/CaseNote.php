<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaseNote extends Model
{
    //
    public function case()
    {
        return $this->belongsTo(Cases::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
