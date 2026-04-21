<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['first_name', 'last_name', 'email', 'phone'])]
class Client extends Model
{
    //
    public function cases()
    {
        return $this->hasMany(CaseRecords::class, 'client_id');
    }
}
