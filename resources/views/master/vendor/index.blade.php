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
                                    <li class="active">Data Vendor</li>
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
                                <strong class="card-title">Data Vendor</strong>
                            </div>
                            <div class="card-body">
                                @include('include.admin.alert')
                                <a href="{{ route('vendor_app.create') }}" class="btn btn-primary mb-3">Tambah</a>
                                
                                <table id="bootstrap-data-table" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>vendor</th>
                                            <th>Telp</th>
                                            <th>Email</th>
                                            <th>Alamat</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($vendor as $key => $value)
                                            <tr>
                                                 <td>{{ $loop->iteration }}</td>
                                                 
                                                 <td>{{ $value->nama_vendor }}</td>
                                                 <td>{{ $value->telp }}</td>
                                                 <td>{{ $value->email }}</td>
                                                 <td>{{ $value->alamat }}</td>
                                                
                                                
                                                 <td>
                                                     <form method="POST" action="{{ route('vendor_app.destroy', ['vendor_app' => $value->id]) }}">
                                                        <div class="btn-group">
                                    
                                                            <a href="{{ route('vendor_app.edit', $value->id) }}" class="btn btn-warning"><i class="fa fa-edit"></i></a>
                                                           
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