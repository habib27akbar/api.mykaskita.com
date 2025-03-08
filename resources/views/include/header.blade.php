 <!-- Header-->
        <header id="header" class="header">
            <div class="top-left">
                <div class="navbar-header">
                    <a class="navbar-brand" href="./"><img style="width: 135px;" src="{{ asset('img/core-img/logo.png') }}" alt="Logo"></a>
                    <a class="navbar-brand hidden" href="./"><img src="{{ asset('img/core-img/logo.png') }}" alt="Logo"></a>
                    <a id="menuToggle" class="menutoggle"><i class="fa fa-bars"></i></a>
                </div>
            </div>
            <div class="top-right">
                <div class="header-menu">
                    <div class="header-left">
                       
                    </div>

                    <div class="user-area dropdown float-right">
                        <a href="#" class="dropdown-toggle active" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img class="user-avatar rounded-circle" src="images/admin.jpg" alt="User Avatar">
                        </a>

                        <div class="user-menu dropdown-menu">
                           
                            <form action="{{ route('logout') }}" method="post">
                                @csrf
                                <button class="nav-link"><i class="menu-icon fa fa-sign-out"></i>Log Out</button>
                            </form>
                   
                           
                        </div>
                    </div>

                </div>
            </div>
        </header>
        <!-- /#header -->