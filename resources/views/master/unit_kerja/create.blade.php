@extends('layouts.master')
@section('title','Unit Kerja')
@section('content')
        
        <div class="breadcrumbs">
            <div class="breadcrumbs-inner">
                <div class="row m-0">
                    <div class="col-sm-4">
                        <div class="page-header float-left">
                            <div class="page-title">
                                <h1>Unit Kerja</h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <div class="page-header float-right">
                            <div class="page-title">
                                <ol class="breadcrumb text-right">
                                    <li><a href="#">Master</a></li>
                                    <li><a href="#">Unit Kerja</a></li>
                                    <li class="active">Tambah Unit Kerja</li>
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
                                <strong class="card-title">Tambah Unit Kerja</strong>
                            </div>
                            <div class="card-body card-block">
                                <form action="{{ route('unit_kerja.store') }}" method="post" enctype="multipart/form-data" class="form-horizontal">
                                    @csrf
                                    <div class="row form-group">
                                        <div class="col col-md-3"><label for="text-input" class=" form-control-label">Unit Kerja</label></div>
                                        <div class="col-12 col-md-9">
                                            <input type="text" id="text-input" name="unit_kerja" placeholder="Unit Kerja" class="form-control" required>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary">
                                        Simpan
                                    </button>
                                    <a href="{{ route('unit_kerja.index') }}" class="btn btn-default">
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