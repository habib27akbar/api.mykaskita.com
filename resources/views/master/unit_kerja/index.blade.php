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
                                    <li class="active">Data Unit Kerja</li>
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
                                <strong class="card-title">Data Unit Kerja</strong>
                            </div>
                            <div class="card-body">
                                @include('include.admin.alert')
                                <a href="{{ route('unit_kerja.create') }}" class="btn btn-primary mb-3">Tambah</a>
                                
                                <table id="bootstrap-data-table" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            
                                            <th>Unit Kerja</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($unit_kerja as $key => $value)
                                            <tr>
                                                 <td>{{ $loop->iteration }}</td>
                                                 
                                                 <td>{{ $value->unit_kerja }}</td>
                                                 <td>
                                                     <form method="POST" action="{{ route('unit_kerja.destroy', ['unit_kerja' => $value->id]) }}">
                                                        <div class="btn-group">
                                    
                                                            <a href="{{ route('unit_kerja.edit', $value->id) }}" class="btn btn-warning"><i class="fa fa-edit"></i></a>
                                                           
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