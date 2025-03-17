<?php
namespace App\Models;

use App\Models\User;
use Illuminate\Http\Request;

class UserFilter
{
    // crreate filters and returns users
    public static function apply(Request $filters)
    {
        $users = (new User)->newQuery();
        
        // Search for users based on time created.
        if ($filters->has('start_date') && $filters->has('end_date')) {

            $startDate = date('Y-m-d H:i:s', strtotime($filters->start_date));
            $endDate = date('Y-m-d  23:59:59', strtotime($filters->end_date));
            
            $users->whereBetween('created_at', [$startDate, $endDate]);
        }
       
        // Search for users based on time created.
        if ($filters->has('start_date_second') && $filters->has('end_date_second')) {

            $startDate = date('Y-m-d H:i:s', strtotime($filters->start_date_second));
            $endDate = date('Y-m-d  23:59:59', strtotime($filters->end_date_second));
            
            $users->whereBetween('created_at', [$startDate, $endDate]);
        }

        if($filters->has('user_type')){
            $users->where('user_type', $filters->user_type);
            
        }else{
            $users->where('user_type', '!=', '2');

        }

        $users->where('is_deleted', 0);
        // Get the results and return them.
        return $users->get();
    }

}