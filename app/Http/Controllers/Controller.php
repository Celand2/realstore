<?php

namespace App\Http\Controllers;

use Vinkla\Hashids\Facades\Hashids;

abstract class Controller
{
    protected function decodeId(string|int $encodedId): int
    {
        $decoded = Hashids::decode((string) $encodedId);

        abort_if(empty($decoded), 404);

        return (int) $decoded[0];
    }
}
