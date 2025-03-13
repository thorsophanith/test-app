<div class="section-menu-left">
    <div class="box-logo">
        <a href="{{route('admin.index')}}" id="site-logo-inner">
            <img id="logo_header_mobile" alt="Site Logo" src="{{ asset('assets/admin/images/logo/logo.png') }}",
                 data-light="{{ asset('assets/admin/images/logo/logo.png') }}",
                 data-dark="{{ asset('assets/admin/images/logo/logo.png') }}">
        </a>
        <div class="button-show-hide">
            <i class="icon-menu-left"></i>
        </div>
    </div>
    <div class="center">
        <div class="center-item">
            <div class="center-heading">Main Home</div>
            <ul class="menu-list">
                <li class="menu-item">
                    <a href="{{route('admin.index')}}" class="">
                        <div class="icon"><i class="icon-grid"></i></div>
                        <div class="text">Dashboard</div>
                    </a>
                </li>
            </ul>
        </div>
        <div class="center-item">
            <ul class="menu-list">
                <li class="menu-item has-children">
                    <a href="javascript:void(0);" class="menu-item-button">
                        <div class="icon"><i class="icon-shopping-cart"></i></div>
                        <div class="text">Products</div>
                    </a>
                    <ul class="sub-menu">
                        <li class="sub-menu-item">
                            <a href="{{ route('admin.product.add') }}" class="">
                                <div class="text">Add Product</div>
                            </a>
                        </li>
                        <li class="sub-menu-item">
                            <a href="{{ route('admin.products') }}" class="">
                                <div class="text">Products</div>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="menu-item has-children">
                    <a href="javascript:void(0);" class="menu-item-button">
                        <div class="icon"><i class="icon-layers"></i></div>
                        <div class="text">Brand</div>
                    </a>
                    <ul class="sub-menu">
                        <li class="sub-menu-item">
                            <a href="{{route('admin.brand.add')}}" class="">
                                <div class="text">New Brand</div>
                            </a>
                        </li>
                        <li class="sub-menu-item">
                            <a href="{{route('admin.brands')}}" class="">
                                <div class="text">Brands</div>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="menu-item has-children">
                    <a href="javascript:void(0);" class="menu-item-button">
                        <div class="icon"><i class="icon-layers"></i></div>
                        <div class="text">Category</div>
                    </a>
                    <ul class="sub-menu">
                        <li class="sub-menu-item">
                            <a href="{{ route('admin.categories.add') }}" class="">
                                <div class="text">New Category</div>
                            </a>
                        </li>
                        <li class="sub-menu-item">
                            <a href="{{ route('admin.categories') }}" class="">
                                <div class="text">Categories</div>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="menu-item has-children">
                    <a href="javascript:void(0);" class="menu-item-button">
                        <div class="icon"><i class="icon-file-plus"></i></div>
                        <div class="text">Order</div>
                    </a>
                    <ul class="sub-menu">
                        <li class="sub-menu-item">
                            <a href="{{ route('admin.orders') }}" class="">
                                <div class="text">Orders</div>
                            </a>
                        </li>
                        <li class="sub-menu-item">
                            <a href="javascript:void(0)" class="">
                                <div class="text">New Order tracking</div>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="menu-item">
                    <a href="{{ route('admin.slides') }}" class="">
                        <div class="icon"><i class="icon-image"></i></div>
                        <div class="text">Slider</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('admin.coupons') }}" class="">
                        <div class="icon"><i class="icon-grid"></i></div>
                        <div class="text">Coupns</div>
                    </a>
                </li>

                <li class="menu-item">
                    <a href="{{ route('admin.contacts') }}" class="">
                        <div class="icon"><i class="icon-mail"></i></div>
                        <div class="text">Messages</div>
                    </a>
                </li>

                <li class="menu-item">
                    <a href="{{ route('admin.user') }}" class="">
                        <div class="icon"><i class="icon-user"></i></div>
                        <div class="text">User</div>
                    </a>
                </li>

                <li class="menu-item">
                    <a href="{{route('admin.setting')}}" class="">
                        <div class="icon"><i class="icon-settings"></i></div>
                        <div class="text">Settings</div>
                    </a>
                </li>
                <li class="menu-item">
                    <form method="POST" action="{{ route( 'logout' )}}" id="logout-form">
                        @csrf
                        <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();" class="">
                            <div class="icon"><i class="icon-log-out"></i></div>
                            <div class="text">Logout</div>
                        </a>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>
