<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Models\Category;
use App\Models\Product;
use App\Models\OrderDetail;
use App\Models\Order;
use App\Models\Discount;
use App\Models\Offer;
use App\Models\User;
use App\Models\Consumer;
use App\Models\Professional;
use App\Models\Permission;
use App\Setting;
use Carbon;
use DB;
use Validator;
use Redirect;
use Hash;
use Auth;

class AdminController extends Controller
{

    public function showLogin()
    {
        if(Auth::guard('web')->user() == null){
            return view('auth.login');

        }else{
            return redirect('admin/dashboard');
        }

    }

    public function showDashbaord(Request $request)
    {
        $date = \Carbon\Carbon::today()->subDays(30);

        $categoriesCount = Category::where('is_active', '1')->get()->count();
        $productsCount = Product::where('is_deleted', '0')->get()->count();

        $totalCompletedSalesThisMonth = Order::where('created_at', '>=', $date)
            ->where('order_status', 'Completed')
            ->count();

        $totalPendingSalesThisMonth = Order::where('created_at', '>=', $date)
            ->where('order_status', '!=', 'Completed')
            ->count();

        $totalOrdersInMonth = Order::select(DB::raw('count(id) as totalOrders, sum(net_total) as totalValue'))
            ->where('created_at', '>=', $date)
            ->first();

        $totalDiscounts = Discount::where('is_active', '1')
            ->get()
            ->count();

        $totalOffers = Offer::where('is_active', 1)
            ->get()
            ->count();

        $professionalMaxOrders = Order::select(DB::raw('count(id) as totalOrders'))
            ->where('created_at', '>=', $date)
            ->where('professional_id', '!=', null)
            ->first();

        $consumerMaxOrders = Order::select(DB::raw('count(id) as totalOrders'))
            ->where('created_at', '>=', $date)
            ->where('consumer_id', '!=', null)
            ->first();

        $registerConsumerInMonth = Consumer::where('created_at', '>=', $date)
            ->where('is_deleted', 0)
            ->count();
        $registerProfessionalInMonth = Professional::where('created_at', '>=', $date)
            ->where('is_deleted', 0)
            ->count();
        $topSellingProducts = OrderDetail::select(DB::raw('order_details.*, count(order_details.id) as orderDetailCount, SUM(quantity) as totalQuantity'))
        ->groupBy('product_id')
        ->orderBy('totalQuantity', 'desc')
        ->limit(5)
        ->get();

        $setting = Setting::first();

        return view('admin.dashboard.dashboard', [
            'categoriesCount' => $categoriesCount,
            'productsCount' => $productsCount,
            'totalCompletedSalesThisMonth' => $totalCompletedSalesThisMonth,
            'totalPendingSalesThisMonth' => $totalPendingSalesThisMonth,
            'topSellingProducts' => $topSellingProducts,
            'totalOrdersInMonth' => $totalOrdersInMonth,
            'totalDiscounts' => $totalDiscounts,
            'totalOffers' => $totalOffers,
            'professionalMaxOrders' => $professionalMaxOrders,
            'consumerMaxOrders' => $consumerMaxOrders,
            'registerProfessionalInMonth' =>  $registerProfessionalInMonth,
            'registerConsumerInMonth' =>  $registerConsumerInMonth,
            'setting' =>  $setting
        ]);
    }

    public function listAdmins(Request $request)
    {
        $admins = User::where('id', '!=', Auth::user()->id)->where('email', '!=', 'admin@postquam.com')->orderBy('created_at', 'DESC')->get();
        // dd($admins);
        return view('admin.admins.index',
            ['title' => 'Admins', 'admins' => $admins]
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createAdmin()
    {
        $permissions = Permission::all();
        return view('admin.admins.createAdmin', ['title' => 'Admins', 'permissions' => $permissions]);
    }

    public function storeAdmin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:150',
            'email' => 'required|string|email|max:150|unique:users',
            'phone' => 'required',
            'password' => 'required|string|min:6|confirmed',
            'permissions' => 'required',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput($request->except('password'));
        }

        $userData = [
            'full_name' => $request->input('full_name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'password' => Hash::make($request->input('password')),
            'user_type' => '2',
            'is_authorized' => 1,
        ];

        $user = User::create($userData);

        if ($request->has('permissions')) {

            $permissions = Permission::whereIn('id', $request->permissions)->get();
            $user->permissions()->attach($permissions);
        }
        return redirect()->route('listAdmins')->with('success', 'Record Added Successfully.');
    }

    public function editAdmin($id)
    {
        $admin = User::find($id);
        if ($admin == null) {
            return redirect()->back()->with('error', 'No Record Found.');
        }
        $permissions = Permission::all();
        return view('admin.admins.edit', [
            'title' => 'Admins',
            'admin' => $admin,
            'permissions' => $permissions,
            ]);
    }

    public function updateAdmin(Request $request)
    {

        $admin = User::find($request->admin_id);
        if ($admin == null) {
            return redirect()->back()->with('error', 'No Record Found.');
        }

        $rules = [
            'full_name' => 'required|string|max:150',
            'phone' => 'required',
            'permissions' => 'required|array'
        ];

        if (!empty($request->password) && !empty($request->password_confirmation)) {
            $rules['password'] = 'required|string|min:6|confirmed';
        }


        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput(Input::all());
        }

        $userData = [
            'full_name' => $request->input('full_name'),
            'phone' => $request->input('phone'),
            'is_active' => $request->input('is_active'),
        ];

        if (!empty($request->password)) {
            $userData['password'] = Hash::make($request->password);
        }

        if ($request->has('permissions')) {
            $admin->permissions()->detach();
            $permissions = Permission::whereIn('id', $request->permissions)->get();
            $admin->permissions()->attach($permissions);
        }
        $admin->update($userData);

        return redirect()->route('listAdmins')->with('success', 'Record Updated Successfully.');
    }

    public function updateDeliveryCharges(Request $request)
    {
        $match = ['id'=> 1];
        Setting::updateOrCreate($match,[
            'min_order_consumer' => $request->min_order_consumer,
            'delivery_charges_consumer' => $request->delivery_charges_consumer,
            'vat_consumer' => $request->vat_consumer,
            'min_order_professional' => $request->min_order_professional,
            'delivery_charges_professional' => $request->delivery_charges_professional,
            'vat_professional' => $request->vat_professional,
        ]);
        return redirect()->back()->with('success', 'Record Updated Successfully.');
    }
}
