 <!-- Left Panel -->
    <aside id="left-panel" class="left-panel">
        <nav class="navbar navbar-expand-sm navbar-default">
            <div id="main-menu" class="main-menu collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    <li class="{{ Request::is('home') ? 'active' : '' }}">
                        <a href="{{ route('home') }}"><i class="menu-icon fa fa-laptop"></i>Dashboard </a>
                    </li>
                    
                    <li class="menu-item-has-children {{ Request::is('unit_kerja') || Request::is('auth') || Request::is('customer') ? 'active' : '' }} dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="menu-icon fa fa-cogs"></i>Master</a>
                        <ul class="sub-menu children dropdown-menu">
                            <li class=""><i class="fa fa-building-o"></i> <a href="{{ route('unit_kerja.index') }}">Unit Kerja</a></li>
                            <li><i class="fa fa-user"></i> <a href="{{ route('auth.index') }}">User</a></li>
                            <li><i class="fa fa-users"></i> <a href="{{ route('customer.index') }}">Customer</a></li>
                            <li><i class="fa fa-users"></i> <a href="{{ route('vendor_app.index') }}">Vendor</a></li>
                            <li><i class="fa fa-inbox"></i> <a href="{{ route('produk.index') }}">Produk</a></li>
                            
                        </ul>
                    </li>
                    
                    <li class="menu-item-has-children dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="menu-icon fa fa-th"></i>Data Transaksi</a>
                        <ul class="sub-menu children dropdown-menu">
                            <li><i class="menu-icon fa fa-th"></i><a href="#">Order</a></li>
                            <li><i class="menu-icon fa fa-th"></i><a href="#">Inventory</a></li>
                            <li><i class="menu-icon fa fa-th"></i><a href="#">Kunjungan Teknisi</a></li>
                            <li><i class="menu-icon fa fa-th"></i><a href="#">Komplain</a></li>
                        </ul>
                    </li>

                    <li class="menu-item-has-children dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="menu-icon fa fa-files-o"></i>Laporan</a>
                        <ul class="sub-menu children dropdown-menu">
                            <li><i class="fa fa-files-o"></i><a href="#">Rekap Order</a></li>
                            <li><i class="fa fa-files-o"></i><a href="#">Rekap Customer</a></li>
                            <li><i class="fa fa-files-o"></i><a href="#">Rekap Inventory</a></li>
                            <li><i class="fa fa-files-o"></i><a href="#">Rekap Produk After Sales</a></li>
                            <li><i class="fa fa-files-o"></i><a href="#">Rekap Komplain</a></li>
                            <li><i class="fa fa-files-o"></i><a href="#">Rekap Pesan Masuk</a></li>
                            <li><i class="fa fa-files-o"></i><a href="#">Rekap Kunjungan</a></li>
                        </ul>
                    </li>
                   
                    
                       
                    <li class="menu-item-has-children dropdown">
                        <form action="{{ route('logout') }}" method="post">
                            @csrf
                            <button><i class="menu-icon fa fa-sign-out"></i>Log Out</button>
                        
                        </form>
                    </li>
                    
                </ul>
                
            </div><!-- /.navbar-collapse -->
        </nav>
    </aside>
    <!-- /#left-panel -->