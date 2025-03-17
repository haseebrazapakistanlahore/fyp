<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse">
        <ul class="nav" id="side-menu">

            <li>
                <a href="{{ route('adminDashboard') }}"><i class="fa fa-dashboard fa-fw"></i> Dashboard </a>
            </li>
            @if(Auth::user()->hasPermission('manage-banners'))
            <li>
                <a href="#"><i class="fa fa-image fa-fw"></i> Manage Banners <span class="fa fa-angle-right pull-right"></span></a>
                <ul class="nav nav-second-level">
                    <li>
                        <a href="{{ route('createBanner')}}">Add Banner</a>
                    </li>

                    <li>
                        <a href="{{ route('listBanners') }}">List Banners</a>
                    </li>
                </ul>
            </li>
            @endif

            @if(Auth::user()->hasPermission('manage-categories'))
            <li>
                <a href="#"><i class="fa fa-list-alt fa-fw"></i> Manage Categories <span class="fa fa-angle-right pull-right"></span></a>

                <ul class="nav nav-second-level">
                    <li>
                        <a href="{{ route('createCategory') }}">Add Category</a>
                    </li>

                    <li>
                        <a href="{{ route('listCategories') }}">List Categories</a>
                    </li>

                </ul>
            </li>

<!--            <li>
                <a href="#"><i class="fa fa-list-alt fa-fw"></i> Manage Child Categories <span class="fa fa-angle-right pull-right"></span></a>

                <ul class="nav nav-second-level">
                    <li>
                        <a href="{{ route('createSubCategory')}}">Add Child Category</a>
                    </li>

                    <li>
                        <a href="{{ route('listSubCategories')}}">List Child Categories</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="#"><i class="fa fa-list-alt fa-fw"></i> Manage Sub Child Categories <span class="fa fa-angle-right pull-right"></span></a>

                <ul class="nav nav-second-level">
                    <li>
                        <a href="{{ route('createSubChildCategory')}}">Add Child Sub Category</a>
                    </li>

                    <li>
                        <a href="{{ route('listSubChildCategories')}}">List Child Sub Categories</a>
                    </li>
                </ul>
            </li>-->
            @endif

            {{--@if(Auth::user()->hasPermission('manage-cmspages'))
            <li>
                <a href="{{ route('listPages') }}"><i class="fa fa-file fa-fw"></i> Manage CMS Pages </a>
            </li>
            @endif--}}
            {{-- <li>
                <a href="{{ route('listOffers') }}"><i class="fa fa-tag fa-lg"></i> Manage Offers</a>
            </li> --}}
            @if(Auth::user()->hasPermission('manage-offers'))
            <li>
                <a href="#"><i class="fa fa-tag fa-lg"></i> Manage Offers <span class="fa fa-angle-right pull-right"></span></a>
                <ul class="nav nav-second-level">
                    <li>
                        <a href="{{ route('createOffer')}}">Add Offer </a>
                    </li>

                    <li>
                        <a href="{{ route('listOffers') }}">List Offers</a>
                    </li>
                </ul>
            </li>
            @endif

            {{--@if(Auth::user()->hasPermission('manage-discounts'))
            <li>
                <a href="#"><i class="fa fa fa-percent"></i> Manage Discounts <span class="fa fa-angle-right pull-right"></span></a>
                <ul class="nav nav-second-level">
                    <li>
                        <a href="{{ route('createDiscount')}}">Add Discount </a>
                    </li>

                    <li>
                        <a href="{{ route('listDiscounts') }}">List Discounts</a>
                    </li>
                </ul>
            </li>
            @endif--}}

            @if(Auth::user()->hasPermission('manage-orders'))
            <li>
                <a href="#"><i class="fa fa-shopping-cart"></i> Manage Orders <span class="fa fa-angle-right pull-right"></span></a>
                <ul class="nav nav-second-level">
                    <li>
                        <a href="{{ route('listOrders')}}">List Orders </a>
                    </li>
                    <li>
                        <a href="{{ route('listDeletedOrders')}}">List Deleted Orders </a>
                    </li>
                </ul>
            </li>
            @endif

            @if(Auth::user()->hasPermission('manage-products'))
            <li>
                <a href="#"><i class="fa fa-gift fa-fw"></i> Manage Products <span class="fa fa-angle-right pull-right"></span></a>

                <ul class="nav nav-second-level">
                    <li>
                        <a href="{{ route('createProduct')}}">Add Product</a>
                    </li>

                    <li>
                        <a href="{{ route('listProducts') }}">List Products</a>
                    </li>
                </ul>
            </li>
            @endif

            @if(Auth::user()->hasPermission('manage-reviews'))
            <li>
                <a href="{{ route('listReviews')}}"><i class="fa fa-comments fa-fw"></i> Manage Reviews</a>
            </li>
            @endif

            {{--@if(Auth::user()->hasPermission('manage-reports'))
            <li>
                <a href="#"><i class="far fa-chart-bar"></i><i class="fa fa-bar-chart fa-fw"></i> Manage Reports <span class="fa fa-angle-right pull-right"></span></a>
                <ul class="nav nav-second-level">

                    <li>
                        <a href="{{ route('showConsumerMaxOrders') }}">Consumer Max. Orders</a>
                    </li>

                    <li>
                        <a href="{{ route('showProfessionalMaxOrders')}}">Professional Max. Orders</a>
                    </li>

                    <li>
                        <a href="{{ route('getTopSellingProducts') }}">Top Selling Products Report</a>
                    </li>

                </ul>
            </li>
            @endif--}}

            {{-- <li>
                <a href="{{ route('listSubscribers')}}"><i class="fa fa-users fa-fw"></i> List Subscribers</a>
            </li> --}}
            @if(Auth::user()->hasPermission('manage-users'))
            <li>
                <a href="#"><i class="fa fa-user fa-fw"></i> Manage Consumers <span class="fa fa-angle-right pull-right"></span></a>
                <ul class="nav nav-second-level">
                    <li>
                        <a href="{{ route('listConsumers')}}">List Consumers</a>
                    </li>
                    <li>
                        <a href="{{ route('deletedConsumers')}}">Restore Consumers</a>
                    </li>

                </ul>
            </li>
<!--            <li>
                <a href="#"><i class="fa fa-user fa-fw"></i> Manage Professionals <span class="fa fa-angle-right pull-right"></span></a>
                <ul class="nav nav-second-level">
                    <li>
                        <a href="{{ route('listProfessionals')}}">List Professionals</a>
                    </li>
                    <li>
                        <a href="{{ route('deletedProfessionals')}}">Restore Users</a>
                    </li>

                </ul>
            </li>-->

            @endif

            @if(Auth::user()->hasPermission('manage-admin-users'))
            <li>
                <a href="#"><i class="fa fa-user-secret fa-fw"></i> Manage Admin Users <span class="fa fa-angle-right pull-right"></span></a>
                <ul class="nav nav-second-level">

                    <li>
                        <a href="{{route('createAdminUser')}}">Add User</a>
                    </li>
                    <li>
                        <a href="{{route('listAdmins')}}">List Admin Users</a>
                    </li>

                </ul>
            </li>
            @endif
        </ul>
    </div>
    <!-- /.sidebar-collapse -->
</div>
<!-- /.navbar-static-side -->
