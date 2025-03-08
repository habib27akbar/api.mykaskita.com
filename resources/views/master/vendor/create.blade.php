@extends('layouts.master')
@section('title','Vendor')
@section('content')
        
        <div class="breadcrumbs">
            <div class="breadcrumbs-inner">
                <div class="row m-0">
                    <div class="col-sm-4">
                        <div class="page-header float-left">
                            <div class="page-title">
                                <h1>Vendor</h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <div class="page-header float-right">
                            <div class="page-title">
                                <ol class="breadcrumb text-right">
                                    <li><a href="#">Master</a></li>
                                    <li><a href="#">Vendor</a></li>
                                    <li class="active">Tambah Vendor</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Content -->
        <div class="content">
            <div class="animated fadeIn">
                <div class="row">

                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <strong class="card-title">Tambah Vendor</strong>
                            </div>
                            <div class="card-body card-block">
                                <form action="{{ route('vendor_app.store') }}" method="post" enctype="multipart/form-data" class="form-horizontal">
                                    @csrf
                                    <div class="row form-group">
                                        <div class="col col-md-2"><label for="text-input" class=" form-control-label">vendor</label></div>
                                        <div class="col-12 col-md-7">
                                            <input type="text" id="text-input" name="nama_vendor" placeholder="Nama vendor" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="row form-group">
                                        <div class="col col-md-2"><label for="text-input" class=" form-control-label">Telepon</label></div>
                                        <div class="col-12 col-md-4">
                                            <input type="text" id="text-input" name="telp" placeholder="Telepon" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="row form-group">
                                        <div class="col col-md-2"><label for="text-input" class=" form-control-label">Email</label></div>
                                        <div class="col-12 col-md-4">
                                            <input type="email" id="text-input" name="email" placeholder="Email" class="form-control">
                                        </div>
                                    </div>

                                    <div class="row form-group">
                                        <div class="col col-md-2"><label for="text-input" class=" form-control-label">Alamat</label></div>
                                        <div class="col-12 col-md-4">
                                            <input type="text" id="text-input" name="alamat" placeholder="Alamat" class="form-control">
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary">
                                        Simpan
                                    </button>
                                    <a href="{{ route('vendor_app.index') }}" class="btn btn-default">
                                        Batal
                                    </button>
                                </form>
                            </div>
                           
                        </div>
                    </div>


                </div>
            </div><!-- .animated -->
        </div><!-- .content -->
        <!-- /.content -->

@endsection