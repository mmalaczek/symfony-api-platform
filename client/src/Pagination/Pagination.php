<?php

namespace App\Pagination;

class Pagination
{
    const _LIMIT = 10;

    /**
     * @param $totalRecords
     * @return int
     */
    public function totalPages($totalRecords): int
    {
        if ($totalRecords === 0) {
            return 0;
        }

        return (int)ceil($totalRecords / self::_LIMIT);
    }

}
