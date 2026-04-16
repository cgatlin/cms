<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    //
    public function cases()
    {
        return $this->hasMany(CaseRecords::class, 'client_id');
    }
}
