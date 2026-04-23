<?php

declare(strict_types=1);

namespace App;

enum CaseRecordsStatus: string
{
    //
    case OPEN = 'open';
    case IN_PROGRESS = 'in_progress';
    case CLOSED = 'closed';

    public function label(): string
    {
        return match ($this) {
            self::OPEN => 'OPEN',
            self::IN_PROGRESS => 'IN PROGESS',
            self::CLOSED => 'CLOSED',
        };
    }
}
