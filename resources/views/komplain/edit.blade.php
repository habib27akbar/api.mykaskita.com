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
							<form action="{{ route('komplain.update', ['komplain' => $komplain->id]) }}" method="post" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

								<div class="form-group mb-3">
									<label for="file-input">
										<img src="{{ asset('img/camera-icon.jpg') }}" style="width: 35%" alt="Camera Icon">
									</label>
									<input style="opacity: 0; position: absolute; width: 1px; height: 1px;" id="file-input" class="form-control" type="file" name="gambar" accept="image/*" onchange="loadFile(event)">
									<img id="preview" style="{{ $komplain->gambar?'display:block;':'display: none;' }}max-width: 100px; margin-top: 10px;" src="{{ asset('img/komplain/'.$komplain->gambar) }}" />
									<input type="hidden" name="gambar_old" value="{{ $komplain->gambar }}">
									
								</div>

								<div class="form-group mb-3">
									
									<label for="file-input-galeri">
										<img src="{{ asset('img/1375106.png') }}" style="width: 14%" alt="Camera Icon">
									</label>
									<input style="opacity: 0; position: absolute; width: 1px; height: 1px;" id="file-input-galeri" class="form-control" type="file" name="gambar_galeri" accept="image/*" onchange="loadFileGaleri(event)">
									<img id="preview_galeri" style="{{ $komplain->gambar_galeri?'display:block;':'display: none;' }}max-width: 100px; margin-top: 10px;"  src="{{ asset('img/komplain/'.$komplain->gambar_galeri) }}"/>
									
									
									
								</div>
								
								<div class="form-group mb-3">
									<textarea class="form-control" cols="30" rows="10" name="pesan" placeholder="pesan" required>{{ $komplain->pesan }}</textarea>
								</div>
								<button class="btn btn-primary w-100">Kirim Pesan</button>
							</form>
							<form method="POST" action="{{ route('komplain.destroy', ['komplain' => $komplain->id]) }}">
								
								@method('DELETE')
								@csrf
								<button style="margin-top: 10px; background-color:red;" onclick="return confirm('Apakah anda yakin ingin hapus komplain ini?')" class="btn btn-danger w-100">Hapus</button>
									
								
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
@section('js')
<script>
    

    function loadFile(event) {
        const output = document.getElementById("preview");
        output.style.display = "block";
        output.src = URL.createObjectURL(event.target.files[0]);
        document.getElementById("error-message").style.display = "none"; // Sembunyikan pesan error jika file dipilih
    }

	function loadFileGaleri(event) {
        const output = document.getElementById("preview_galeri");
        output.style.display = "block";
        output.src = URL.createObjectURL(event.target.files[0]);
        
    }
</script>
@endsection
@endsection