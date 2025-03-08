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
                                    <li class="active">Tambah User</li>
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
                                <strong class="card-title">Tambah User</strong>
                            </div>
                            <div class="card-body card-block">
                                 @include('include.admin.alert')


                                <form action="{{ route('auth.store') }}" method="post" enctype="multipart/form-data" class="form-horizontal">
                                    @csrf

                                    <div class="row form-group">
                                        <div class="col col-md-2">
                                            <label for="name" class="form-control-label">Nama</label>
                                        </div>
                                        <div class="col-12 col-md-5">
                                            <input type="text" id="name" name="name" placeholder="Nama" class="form-control" required value="{{ old('name') }}">
                                            @error('name')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row form-group">
                                        <div class="col col-md-2">
                                            <label for="no_hp" class="form-control-label">No. Handphone</label>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <input type="text" id="no_hp" name="no_hp" class="form-control" value="{{ old('no_hp') }}">
                                            @error('no_hp')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row form-group">
                                        <div class="col col-md-2">
                                            <label for="username" class="form-control-label">Username</label>
                                        </div>
                                        <div class="col-12 col-md-5">
                                            <input type="text" onkeyup="checkUsername(this.value)" id="username" name="username" placeholder="Username" class="form-control" required value="{{ old('username') }}">
                                            <span id="usernameFeedback" class="help-block form-text"></span>
                                            @error('username')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row form-group">
                                        <div class="col col-md-2">
                                            <label for="password" class="form-control-label">Password</label>
                                        </div>
                                        <div class="col-12 col-md-5">
                                            <input type="password" id="password" name="password" placeholder="Password" class="form-control" required value="{{ old('password') }}">
                                            @error('password')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row form-group">
                                        <div class="col col-md-2">
                                            <label for="password_confirmation" class="form-control-label">Konfirmasi Password</label>
                                        </div>
                                        <div class="col-12 col-md-5">
                                            <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Konfirmasi Password" class="form-control" required>
                                            @error('password_confirmation')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row form-group">
                                        <div class="col col-md-2">
                                            <label for="foto" class="form-control-label">Foto</label>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <input type="file" id="foto" name="foto" class="form-control">
                                            @error('foto')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row form-group">
                                        <div class="col col-md-2">
                                            <label class="form-control-label">Unit Kerja</label>
                                        </div>
                                        <div class="col col-md-5">
                                            <div class="form-check">
                                                @foreach($unit_kerja as $key => $value)
                                                    <div class="checkbox">
                                                        <label for="checkbox{{ $loop->iteration }}" class="form-check-label">
                                                            <input type="checkbox" id="checkbox{{ $loop->iteration }}" name="user_unit_kerja[]" value="{{ $value->id }}" class="form-check-input"
                                                                {{ is_array(old('user_unit_kerja')) && in_array($value->id, old('user_unit_kerja')) ? 'checked' : '' }}>
                                                            {{ $value->unit_kerja }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                            @error('user_unit_kerja')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>

                                    <button id="btnSave" type="submit" class="btn btn-primary">Simpan</button>
                                    <a href="{{ route('auth.index') }}" class="btn btn-default">Batal</a>
                                </form>

                            </div>
                           
                        </div>
                    </div>


                </div>
            </div><!-- .animated -->
        </div><!-- .content -->
        <!-- /.content -->

<script>
function checkUsername(username) {
    let feedback = document.getElementById("usernameFeedback");
    if (username.length > 0) {
        fetch(`{{ url('/check-username') }}?username=${username}`)
            .then(response => response.json())
            .then(data => {
                let inputField = document.querySelector('input[name="username"]');
                if (data.exists) {
                    inputField.style.borderColor = "red";
                    feedback.style.color = "red";
                    feedback.textContent = "Username sudah digunakan!";
                    btnSave.style.display = "none";
                } else {
                    inputField.style.borderColor = "green";
                    feedback.style.color = "green";
                    feedback.textContent = "Username tersedia!";
                    btnSave.style.display = "";
                }
            });
    } else {
        feedback.textContent = "";
        document.querySelector('input[name="username"]').style.borderColor = "";
    }
}
</script>


@endsection