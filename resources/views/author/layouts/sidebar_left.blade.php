<style>
    .brand-link{
        white-space: initial !important;
    }
</style>
<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{url('/author/dashboard')}}" class="brand-link top_title">
        <span class="w-100"><img style="margin: 0 auto; max-height:40px " class="d-block" src="{{url($logo)}}"></span>
        <p class="brand-text font-weight-light text-center">{{$appName}}</p>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="true">
                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="{{url('author/dashboard')}}" class="nav-link {{activeMenu(2, 'dashboard')}}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                @if (dAuth()->hasAnyAccess(['author.articles','author.category','author.home_items']))
                    <li class="nav-item {{menuOpen(2, ['category','articles','home-items','tags'])}}">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fa fa-edit"></i>
                            <p>Content
                                <i class="right fas fa-angle-down"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @if (dAuth()->hasAccess(['author.articles']))
                                <li class="nav-item">
                                    <a href="{{url('author/articles')}}" class="nav-link {{activeMenu(2, 'articles')}}">
                                        <i class="fas fa-circle"></i>
                                        <p>Article Manager</p>
                                    </a>
                                </li>
                            @endif

                            @if (dAuth()->hasAccess(['author.tags']))
                                <li class="nav-item">
                                    <a href="{{url('author/tags')}}" class="nav-link {{activeMenu(2, 'tags')}}">
                                        <!-- <i class="nav-icon fas fa-user-alt"></i> -->
                                        <i class="fas fa-circle"></i>
                                        <p>Tag Manager</p>
                                    </a>
                                </li>
                            @endif

                            @if (dAuth()->hasAccess(['author.category']))
                                <li class="nav-item">
                                    <a href="{{url('author/category')}}" class="nav-link {{activeMenu(2, 'category')}}">
                                        <!-- <i class="nav-icon fas fa-user-alt"></i> -->
                                        <i class="fas fa-circle"></i>
                                        <p>Category Manager</p>
                                    </a>
                                </li>
                            @endif

                            @if (dAuth()->hasAccess(['author.home_items']))
                                <li class="nav-item">
                                    <a href="{{url('author/home-items')}}" class="nav-link {{activeMenu(2, 'home-items')}}">
                                        <!-- <i class="nav-icon fas fa-user-alt"></i> -->
                                        <i class="fas fa-circle"></i>
                                        <p>Home Page Items</p>
                                    </a>
                                </li>
                            @endif
                            {{--                            @if (dAuth()->hasAccess(['author.change_password']))--}}
                            <li class="nav-item">
                                <a href="" class="nav-link">
                                    <!-- <i class="nav-icon fas fa-user-alt"></i> -->
                                    <i class="fas fa-circle"></i>
                                    <p>Media Manager</p>
                                </a>
                            </li>
                            {{--                            @endif--}}
                        </ul>
                    </li>
                @endif

                @if (dAuth()->hasAnyAccess(['author.menu','author.menu_types']))
                    <li class="nav-item {{menuOpen(2, ['menu','menu-types'])}}">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fa fa-th-list"></i>
                            <p>Menu
                                <i class="right fas fa-angle-down"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @if (dAuth()->hasAccess(['author.menu']))
                                <li class="nav-item">
                                    <a href="{{url('author/menu')}}" class="nav-link {{activeMenu(2, 'menu')}}">
                                        <i class="fas fa-circle"></i>
                                        <p>Menu Manager</p>
                                    </a>
                                </li>
                            @endif

                            @if (dAuth()->hasAccess(['author.menu_types']))
                                <li class="nav-item">
                                    <a href="{{url('author/menu-types')}}" class="nav-link {{activeMenu(2, 'menu-types')}}">
                                        <i class="fas fa-circle"></i>
                                        <p>Menu Type Manager</p>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

                @if (dAuth()->hasAnyAccess(['author.newsletter', 'author.contact_us']))
                    <li class="nav-item {{menuOpen(2, ['newsletter', 'contact-us', 'mail-sending','mail-template'])}}">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fa fa-envelope-open"></i>
                            <p>E-mail
                                <i class="right fas fa-angle-down"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @if (dAuth()->hasAccess(['author.newsletter']))
                                <li class="nav-item">
                                    <a href="{{url('author/mail-sending')}}" class="nav-link {{activeMenu(2, 'mail-sending')}}">
                                        <i class="fas fa-circle"></i>
                                        <p>E-mail Sending</p>
                                    </a>
                                </li>
                            @endif
                                @if (dAuth()->hasAccess(['author.newsletter']))
                                    <li class="nav-item">
                                        <a href="{{url('author/mail-template')}}" class="nav-link {{activeMenu(2, 'mail-template')}}">
                                            <i class="fas fa-circle"></i>
                                            <p>Mail Template</p>
                                        </a>
                                    </li>
                                @endif

                            @if (dAuth()->hasAccess(['author.newsletter']))
                                <li class="nav-item">
                                    <a href="{{url('author/newsletter')}}" class="nav-link {{activeMenu(2, 'newsletter')}}">
                                        <i class="fas fa-circle"></i>
                                        <p>Newsletter</p>
                                    </a>
                                </li>
                            @endif
                            @if (dAuth()->hasAccess(['author.contact_us']))
                                <li class="nav-item">
                                    <a href="{{url('author/contact-us')}}" class="nav-link {{activeMenu(2, 'contact-us')}}">
                                        <i class="fas fa-circle"></i>
                                        <p>Contact </p>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

                @if (dAuth()->hasAnyAccess(['author.user_create','auth.users','system.user_permission','system.core.logs']))
                    <li class="nav-item {{menuOpen(2, ['users','permissions','change-password'])}}">
                        <a href="#" class="nav-link ">
                            <i class="nav-icon fas fa-book-dead"></i>
                            <p>
                                System
                                <i class="right fas fa-angle-down"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @if (dAuth()->hasAccess(['author.user_create']))
                                <li class="nav-item">
                                    <a href="{{url('author/user/create')}}" class="nav-link {{Request::segment(2)=='user' && Request::segment(3)=='create'?'active':''}}">
                                        <!-- <i class="nav-icon fas fa-user-alt"></i> -->
                                        <i class="fas fa-circle"></i>
                                        <p>Add User</p>
                                    </a>
                                </li>
                            @endif

                            @if (dAuth()->hasAccess(['author.users']))
                                <li class="nav-item">
                                    <a href="{{url('author/users')}}" class="nav-link {{Request::segment(2)=='users' && Request::segment(3)==''?'active':''}}">
                                        <!-- <i class="nav-icon fas fa-user-alt"></i> -->
                                        <i class="fas fa-circle"></i>
                                        <p>User Manager</p>
                                    </a>
                                </li>
                            @endif

                            @if (dAuth()->hasAccess(['author.permission']))
                                <li class="nav-item">
                                    <a href="{{url('author/permissions')}}" class="nav-link {{activeMenu(2, 'permissions')}}">
                                        <!-- <i class="nav-icon fas fa-user-alt"></i> -->
                                        <i class="fas fa-circle"></i>
                                        <p>Permissions</p>
                                    </a>
                                </li>
                            @endif
                            @if (dAuth()->hasAccess(['author.change_password']))
                                <li class="nav-item">
                                    <a href="{{url('author/change-password')}}" class="nav-link {{activeMenu(2, 'change-password')}}">
                                        <!-- <i class="nav-icon fas fa-user-alt"></i> -->
                                        <i class="fas fa-circle"></i>
                                        <p>Change Password</p>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif


                @if (dAuth()->hasAnyAccess(['base_setting','robots', 'sitemap']))
                    <li class="nav-item {{menuOpen(2, ['base_setting','file-edit'])}}">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-cog"></i>
                            <p>Settings
                                <i class="right fas fa-angle-down"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @if (dAuth()->hasAccess(['base_setting']))
                                <li class="nav-item">
                                    <a href="{{url('author/base_setting')}}" class="nav-link {{activeMenu(2, 'base_setting')}}">
                                        <i class="fas fa-circle"></i>
                                        <p>Common Settings</p>
                                    </a>
                                </li>
                            @endif

{{--                            @if (dAuth()->hasAccess(['author.robots']))--}}
                                <li class="nav-item">
                                    <a href="{{url('author/file-edit/robots')}}" class="nav-link {{activeMenu(3, 'robots')}}">
                                        <i class="fas fa-circle"></i>
                                        <p>Edit Robots</p>
                                    </a>
                                </li>
{{--                            @endif--}}
{{--                                @if (dAuth()->hasAccess(['author.sitemap']))--}}
                                    <li class="nav-item">
                                        <a href="{{url('author/file-edit/sitemap')}}" class="nav-link {{activeMenu(3, 'sitemap')}}">
                                            <i class="fas fa-circle"></i>
                                            <p>Edit Sitemap</p>
                                        </a>
                                    </li>
{{--                                @endif--}}
                        </ul>
                    </li>
                @endif


                <li class="nav-item">
                    <a href="{{url('author/logout')}}" class="nav-link">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>Logout</p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->


    </div>
    <!-- /.sidebar -->
</aside>
