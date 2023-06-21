<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="./" class="brand-link">
        <img src="{{ asset('vendor/adminlte/dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
             style="opacity: .8">
        <span class="brand-text font-weight-light">پنل مدیریت</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar" style="direction: ltr">
        <div style="direction: rtl">
            <!-- Sidebar user panel (optional) -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex justify-content-center align-items-center">
                <div class="info d-block text-center">
                    <a href="#" class="d-block">{{auth()->user()->name}}</a>
                    <form class="d-block mt-3" method="POST" action="{{ route('logout') }}">
                        @csrf

                        <a type="button" class="btn btn-outline-danger btn-sm"
                           onclick="event.preventDefault();
                                                this.closest('form').submit();">
                            خروج
                        </a>
                    </form>
                </div>
            </div>

            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <!-- Add icons to the links using the .nav-icon class
                         with font-awesome or any other icon font library -->
                    @if(auth()->user()->type == 0)
                        <li class="nav-item">
                            <a href="#" class="nav-link has-treeview">
                                <i class="nav-icon fa fa-user"></i>
                                <p>
                                    کاربر
                                    <i class="fa fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{url('/user/userInfo')}}" class="nav-link active">
                                        <i class="fa fa-users nav-icon"></i>
                                        <p>همه کاربران</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{url('/user/addUser')}}" class="nav-link">
                                        <i class="fa fa-user-plus nav-icon"></i>
                                        <p>اضافه کردن کاربر</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif

                    @if(auth()->user()->type == 0)
                        <li class="nav-item">
                            <a href="#" class="nav-link has-treeview">
                                <i class="nav-icon fa fa-user"></i>
                                <p>
                                    دستگاه
                                    <i class="fa fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{url('/device/allDevices')}}" class="nav-link active">
                                        <i class="fa fa-users nav-icon"></i>
                                        <p>همه دستگاه ها</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{url('/device/createDevice/'.Auth::user()->id)}}" class="nav-link">
                                        <i class="fa fa-user-plus nav-icon"></i>
                                        <p>اضافه کردن دستگاه</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a href="{{url('/device/show/'.Auth::user()->id)}}" class="nav-link has-treeview">
                                <i class="nav-icon fa fa-user"></i>
                                <p>
                                    دستگاه
                                    <i class="fa fa-angle-left right"></i>
                                </p>
                            </a>
                        </li>
                    @endif


                    @if(auth()->user()->type == 0)
                        <li class="nav-item">
                            <a href="" class="nav-link has-treeview">
                                <i class="nav-icon fa fa-map-marker "></i>
                                <p>
                                    جایگاه
                                    <i class="fa fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{url('/position/show')}}" class="nav-link active">
                                        <i class="fa fa-map-marker nav-icon"></i>
                                        <p>همه جایگاه ها</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{url('/position/create')}}" class="nav-link">
                                        <i class="fa fa-plus-circle nav-icon"></i>
                                        <p>اضافه کردن جایگاه</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif


                    @if(auth()->user()->type == 0)
                        <li class="nav-item">
                            <a href="" class="nav-link has-treeview">
                                <i class="nav-icon fa fa-hdd-o "></i>
                                <p>
                                    موجودیت
                                    <i class="fa fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{url('/entities')}}" class="nav-link active">
                                        <i class="fa fa-hdd-o nav-icon"></i>
                                        <p>همه موجودیت ها</p>
                                    </a>

                                </li>
                                <li class="nav-item">
                                    <a href="{{url('/entity/addEntity')}}" class="nav-link">
                                        <i class="fa fa-plus-circle nav-icon"></i>
                                        <p>اضافه کردن موجودیت</p>
                                    </a>
                                </li>
                            </ul>
                        </li>  
                    
                    @endif

                    @if(auth()->user()->type == 0)
                        <li class="nav-item">
                            <a href="" class="nav-link has-treeview">
                                <i class="nav-icon fa fa-hdd-o "></i>
                                <p>
                                    پروژه
                                    <i class="fa fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{url('/projects/allProjects')}}" class="nav-link active">
                                        <i class="fa fa-hdd-o nav-icon"></i>
                                        <p>همه پروژه ها</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif

                    @if(auth()->user()->type == 0)
                        <li class="nav-item">
                            <a href="#" class="nav-link has-treeview">
                                <i class="nav-icon fa fa-user"></i>
                                <p>
                                    تیکت
                                    <i class="fa fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{url('/all_tickets')}}" class="nav-link active">
                                        <i class="fa fa-users nav-icon"></i>
                                        <p>همه تیکت ها</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{url('/tickets/create/'.Auth::user()->id)}}" class="nav-link">
                                        <i class="fa fa-user-plus nav-icon"></i>
                                        <p>اضافه کردن تیکت</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @else

                    <li class="nav-item">
                        <a href="#" class="nav-link has-treeview">
                            <i class="nav-icon fa fa-user"></i>
                            <p>
                                تیکت
                                <i class="fa fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{url('/ticket/'.Auth::user()->id)}}" class="nav-link active">
                                    <i class="fa fa-users nav-icon"></i>
                                    <p> تیکت ها</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{url('/tickets/create/'.Auth::user()->id)}}" class="nav-link">
                                    <i class="fa fa-user-plus nav-icon"></i>
                                    <p>اضافه کردن تیکت</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endif

                    @if(auth()->user()->type == 0)
                        <li class="nav-item">
                            <a href="#" class="nav-link has-treeview">
                                <i class="nav-icon fa fa-user"></i>
                                <p>
                                    مایع
                                    <i class="fa fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{url('/user/all_liquids/')}}" class="nav-link active">
                                        <i class="fa fa-users nav-icon"></i>
                                        <p>مایعات</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{url('/user/createLiquid/'.Auth::user()->id)}}" class="nav-link">
                                        <i class="fa fa-user-plus nav-icon"></i>
                                        <p>اضافه کردن مایع</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @else

                    <li class="nav-item">
                        <a href="{{url('/user/liquids/'.Auth::user()->id)}}" class="nav-link has-treeview">
                            <i class="nav-icon fa fa-user"></i>
                            <p>
                                مایع
                                <i class="fa fa-angle-left right"></i>
                            </p>
                        </a>
                    </li>
                    @endif

                    @if(auth()->user()->type == 0)
                        <li class="nav-item">
                            <a href="#" class="nav-link has-treeview">
                                <i class="nav-icon fa fa-user"></i>
                                <p>
                                    تنظیمات
                                    <i class="fa fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{url('/options/show')}}" class="nav-link active">
                                        <i class="fa fa-users nav-icon"></i>
                                        <p>تنظیمات</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{url('/options/create/'.Auth::user()->id)}}" class="nav-link">
                                        <i class="fa fa-user-plus nav-icon"></i>
                                        <p>اضافه کردن تنظیمات</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
    </div>
    <!-- /.sidebar -->
</aside>
