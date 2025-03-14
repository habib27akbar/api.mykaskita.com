@extends('layouts.master')
@section('title','Komplain')
@section('content')

        <div class="page-content-wrapper py-3">
			<div class="container">
				<!-- Contact Form -->
				<div class="card mb-3">
					<div class="card-body">
						<h5 class="mb-3">Formulir Komplain (Ubah)</h5>
						<div class="contact-form">
							<form action="{{ route('auth.update', ['auth' => $user->id]) }}" method="post" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
								<div class="form-group mb-3">
									<input class="form-control" type="text" placeholder="Nama">
								</div>

								<div class="form-group mb-3">
									<input class="form-control" type="email" placeholder="Email">
								</div>

								<div class="form-group mb-3">
									<input class="form-control" type="text" placeholder="Telpon/WA">
								</div>

								<div class="form-group mb-3">
									<input class="form-control" type="text" placeholder="Nama Perusahaan">
								</div>

								<div class="form-group mb-3">
									<textarea class="form-control" name="textarea" cols="30" rows="10" placeholder="pesan"></textarea>
								</div>
								<button class="btn btn-primary w-100">Kirim Pesan</button>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>

@endsection