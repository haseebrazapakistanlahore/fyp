<?php
namespace App;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderFilter
{
    // crreate filters and returns users
    public static function apply(Request $filters)
    {
        $orders = (new Order)->newQuery();
        
        // Search for orders based on time or registeration.
        if ($filters->has('start_date') && $filters->get('start_date') != null && $filters->has('end_date') && $filters->get('end_date') != null) {

            $startDate = date('Y-m-d H:i:s', strtotime($filters->start_date));
            $endDate = date('Y-m-d 23:59:59', strtotime($filters->end_date));

            $orders->whereBetween('created_at', [$startDate, $endDate]);
        }
       
        // Search for orders based on time or registeration.
        if ($filters->has('start_date_second') && $filters->get('start_date_second') != null && $filters->has('end_date_second') && $filters->get('end_date_second') != null){

            $startDate = date('Y-m-d H:i:s', strtotime($filters->start_date_second));
            $endDate = date('Y-m-d 23:59:59', strtotime($filters->end_date_second));
            
            $orders->whereBetween('created_at', [$startDate, $endDate]);
        }

        // Search for a orders based on their status.
        if ($filters->has('order_status')) {
            $orders->where('order_status', $filters->input('order_status'));
        }

        // Get the results and return them.
        return $orders->get();
    }

}