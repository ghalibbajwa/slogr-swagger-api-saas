<div class="rightbar">
    <!-- Start Topbar Mobile -->
    <div class="topbar-mobile">
        <div class="row align-items-center">
            <div class="col-md-12">
                <div class="mobile-logobar">
                    <a href="{{ url('/') }}" class="mobile-logo"><img src="{{ asset('assets/images/slogricon.png')}}" 
                            class=" slogrlogo2 " alt="logo"></a>
                </div>
                <div class="mobile-togglebar">
                    <ul class="list-inline mb-0">
                        <li class="list-inline-item">
                            <div class="topbar-toggle-icon">
                                <a class="topbar-toggle-hamburger" href="javascript:void();">
                                    <img src="assets/images/svg-icon/horizontal.svg"
                                        class="img-fluid menu-hamburger-horizontal" alt="horizontal">
                                    <img src="assets/images/svg-icon/verticle.svg"
                                        class="img-fluid menu-hamburger-vertical" alt="verticle">
                                </a>
                            </div>
                        </li>
                        <li class="list-inline-item">
                            <div class="menubar">
                                <a class="menu-hamburger navbar-toggle bg-transparent" href="javascript:void();"
                                    data-toggle="collapse" data-target="#navbar-menu" aria-expanded="true">
                                    <img src="assets/images/svg-icon/collapse.svg"
                                        class="img-fluid menu-hamburger-collapse" alt="collapse">
                                    <img src="assets/images/svg-icon/close.svg" class="img-fluid menu-hamburger-close"
                                        alt="close">
                                </a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- Start Topbar -->
    <!-- -->
    <!-- End Navigationbar -->
    <!-- Start Breadcrumbbar -->

    <div class="breadcrumbbar">

        <div class="container-fluid">


            <div class="row">

                <div class="col-lg-2 col-md-2">
                    <a href="{{ url('/') }}"><img  src="{{ asset('assets/images/logo-slogr.png')}}"  class="img-fluid slogrlogo"
                            alt="logo"></a>

                </div>
                <!-- Start Horizontal Nav -->

                <div class="col-lg-8 col-md-8">
                    <nav class="horizontal-nav mobile-navbar fixed-navbar">
                        <div class="collapse navbar-collapse" id="navbar-menu">
                            <ul class="horizontal-menu">

                                <li class="scroll dropdown">
                                    <a href="{{ url('/') }}"><i
                                            class="mdi mdi-home"></i><span><strong>Home</strong></span></a>

                                </li>


                                <li class="scroll dropdown">
                                    <a href="{{ url('agents') }}"><i
                                            class="mdi mdi-desktop-tower-monitor"></i><span><strong>Agents</strong></span></a>

                                </li>

                                <li class="scroll dropdown">
                                    <a href="{{ url('sessions') }}"><i
                                            class="mdi mdi-lan-connect"></i><span><strong>Sessions</strong></span></a>

                                </li>




                                <li class="scroll dropdown">
                                    <a href="{{ url('profiles') }}"><i
                                            class="mdi mdi-rhombus-split"></i><span><strong>Profiles</strong></span></a>

                                </li>

                                <li class="scroll dropdown">
                                    <a href="{{ url('groups') }}"><i
                                            class="mdi mdi mdi-group"></i><span><strong>Groups</strong></span></a>

                                </li>

                                {{-- <li class="scroll dropdown">
                                    <a href="javaScript:void();" class="dropdown-toggle" data-toggle="dropdown"><i class="mdi mdi-home"></i><span><strong>Home</strong></span></a>
                                    <ul class="dropdown-menu">
                                        <li><a href="{{ url('/') }}">CRM</a></li>
                                        <li><a href="{{ url('/dashboard-ecommerce') }}">eCommerce</a></li>
                                        <li><a href="{{ url('/dashboard-hospital') }}">Hospital</a></li>
                                        <li><a href="{{ url('/dashboard-crypto') }}">Crypto</a></li>
                                        <li><a href="{{ url('/dashboard-school') }}">School</a></li>
                                    </ul>
                                </li> --}}




                            </ul>
                        </div>
                    </nav>
                    <!-- End Horizontal Nav -->
                </div>
                <div class="col-lg-2 col-md-2">
                    <div class="searchbar">
                        <form>
                            <div class="input-group" style="border:1px solid #8CB63D; border-radius:0.5rem">
                                <input type="search" class="form-control" placeholder="Search" aria-label="Search"
                                    aria-describedby="button-addon2">
                                <div class="input-group-append"
                                    style="background:#8CB63D ; border-radius:0rem 0.3rem 0.3rem 0rem; padding-left:10px">
                                    <button class="btn" type="submit" id="button-addon2"><i
                                            class="mdi mdi mdi-feature-search"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- End container-fluid -->
    </div>
    <!-- End Breadcrumbbar -->
    @yield('rightbar-content')
    <!-- Start Footerbar -->

    <!-- End Footerbar -->
</div>
