@extends('layouts.master')
@section('title','User')
@section('content')
        
        <div class="breadcrumbs">
            <div class="breadcrumbs-inner">
                <div class="row m-0">
                    <div class="col-sm-4">
                        <div class="page-header float-left">
                            <div class="page-title">
                                <h1>User</h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <div class="page-header float-right">
                            <div class="page-title">
                                <ol class="breadcrumb text-right">
                                    <li><a href="#">Master</a></li>
                                    <li><a href="#">User</a></li>
                                    <li class="active">Data User</li>
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
                                <strong class="card-title">Data User</strong>
                            </div>
                            <div class="card-body">
                                @include('include.admin.alert')
                                <a href="{{ route('auth.create') }}" class="btn btn-primary mb-3">Tambah</a>
                                <table id="bootstrap-data-table" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama</th>
                                            <th>No HP</th>
                                            <th>Unit Kerja</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($user as $key => $value)
                                            <tr>
                                                 <td>{{ $loop->iteration }}</td>
                                                 
                                                 <td>{{ $value->name }}</td>
                                                 <td>{{ $value->no_hp }}</td>
                                                 <td>
                                                    @foreach ($value->unitKerja as $unit)
                                                        {{ $unit->unitKerjaDetail->unit_kerja ?? 'Tidak Ada' }}<br>
                                                    @endforeach
                                                 </td>

                                                 <td>
                                                     <form method="POST" action="{{ route('auth.destroy', ['auth' => $value->id]) }}">
                                                        <div class="btn-group">
                                    
                                                            <a href="{{ route('auth.edit', $value->id) }}" class="btn btn-warning"><i class="fa fa-edit"></i></a>
                                                           
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