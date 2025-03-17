<?php
namespace App\Models;

use App\Models\Consumer;
use Illuminate\Http\Request;

class ConsumerFilter
{
    // crreate filters and returns users
    public static function apply(Request $filters)
    {
        $consumer = (new Consumer)->newQuery();
        
        // Search for users based on time created.
        if ($filters->has('start_date') && $filters->has('end_date')) {

            $startDate = date('Y-m-d H:i:s', strtotime($filters->start_date));
            $endDate = date('Y-m-d  23:59:59', strtotime($filters->end_date));
            
            $consumer->whereBetween('created_at', [$startDate, $endDate]);
        }
       
        // Search for users based on time created.
        if ($filters->has('start_date_second') && $filters->has('end_date_second')) {

            $startDate = date('Y-m-d H:i:s', strtotime($filters->start_date_second));
            $endDate = date('Y-m-d  23:59:59', strtotime($filters->end_date_second));
            
            $consumer->whereBetween('created_at', [$startDate, $endDate]);
        }

        $consumer->where('is_deleted', 0);
        // Get the results and return them.
        return $consumer->get();
    }

}