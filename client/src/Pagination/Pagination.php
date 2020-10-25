<?php

namespace App\Pagination;

class Pagination
{
    const _LIMIT = 10;

    public function totalPages($totalRecords): int
    {
        return (int)ceil($totalRecords / self::_LIMIT);
    }

}
