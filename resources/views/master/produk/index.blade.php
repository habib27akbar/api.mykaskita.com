@extends('layouts.master')
@section('title','Produk')
@section('content')
        
        <div class="breadcrumbs">
            <div class="breadcrumbs-inner">
                <div class="row m-0">
                    <div class="col-sm-4">
                        <div class="page-header float-left">
                            <div class="page-title">
                                <h1>Produk</h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <div class="page-header float-right">
                            <div class="page-title">
                                <ol class="breadcrumb text-right">
                                    <li><a href="#">Master</a></li>
                                    <li><a href="#">Produk</a></li>
                                    <li class="active">Data Produk</li>
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
                                <strong class="card-title">Data Produk</strong>
                            </div>
                            <div class="card-body">
                                @include('include.admin.alert')
                                <a href="{{ route('produk.create') }}" class="btn btn-primary mb-3">Tambah</a>
                                
                                <table id="bootstrap-data-table" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            
                                            <th>Produk</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($produk as $key => $value)
                                            <tr>
                                                 <td>{{ $loop->iteration }}</td>
                                                 
                                                 <td>{{ $value->nama_produk }}</td>
                                                 <td>
                                                     <form method="POST" action="{{ route('produk.destroy', ['produk' => $value->id]) }}">
                                                        <div class="btn-group">
                                    
                                                            <a href="{{ route('produk.edit', $value->id) }}" class="btn btn-warning"><i class="fa fa-edit"></i></a>
                                                           
                                                                @method('DELETE')
                                                                @csrf
                                                                <button style="margin-left: 10px;" onclick="return confirm('Apakah anda yakin?')" class="btn btn-danger"><i class="fa fa-trash-o"></i></button>
                                                           
                                                        </div>
                                                     </form>
                                                 </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>


                </div>
            </div><!-- .animated -->
        </div><!-- .content -->
        <!-- /.content -->

@endsection