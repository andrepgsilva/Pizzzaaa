<?php

namespace App\Helpers;

use Illuminate\Pagination\Paginator as LaravelPaginator;
use Illuminate\Pagination\LengthAwarePaginator;

class Paginator 
{
    public static function paginate($items, $perPage = 5, $currentPage = null, $path='/', $options = [])
    {
        $pageStart = \Request::get('page', 1);
        $offSet = ($pageStart * $perPage) - $perPage; 

        // Get only the items you need using array_slice
        $itemsForCurrentPage = array_slice($items, $offSet, $perPage, true);

        $paginator = new LengthAwarePaginator(
            $itemsForCurrentPage, 
            count($items), 
            $perPage,
            LaravelPaginator::resolveCurrentPage(), 
            ['path' => LaravelPaginator::resolveCurrentPath()]
        );

        return $paginator;
    }
}