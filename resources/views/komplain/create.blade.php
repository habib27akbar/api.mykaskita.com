@extends('layouts.master')
@section('title','Komplain')
@section('content')

        <div class="page-content-wrapper py-3">
			<div class="container">
				<!-- Contact Form -->
				<div class="card mb-3">
					<div class="card-body">
						<h5 class="mb-3">Formulir Komplain (Tambah)</h5>
						<div class="contact-form">
							<form id="komplainForm" action="{{ route('komplain.store') }}" method="post" enctype="multipart/form-data">
								@csrf

								<div class="form-group mb-3">
									<label for="file-input">
										<img src="{{ asset('img/camera-icon.jpg') }}" style="width: 35%" alt="Camera Icon">
									</label>
									<input style="opacity: 0; position: absolute; width: 1px; height: 1px;" id="file-input" class="form-control" type="file" name="gambar" accept="image/*" onchange="loadFile(event)" required>
									<img id="preview" style="display: none; max-width: 100px; margin-top: 10px;" />
									<p id="error-message" style="color: red; display: none;">File gambar wajib diunggah!</p>
								</div>

								<div class="form-group mb-3">
									<textarea class="form-control" name="pesan" cols="30" rows="10" placeholder="pesan" required></textarea>
								</div>
								<button type="submit" class="btn btn-primary w-100">Kirim Pesan</button>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
@section('js')
<script>
    document.getElementById("komplainForm").addEventListener("submit", function(event) {
        const fileInput = document.getElementById("file-input");
        const errorMessage = document.getElementById("error-message");

        if (!fileInput.files.length) {
            errorMessage.style.display = "block"; // Tampilkan pesan error
            event.preventDefault(); // Mencegah form terkirim
        } else {
            errorMessage.style.display = "none"; // Sembunyikan pesan jika file dipilih
        }
    });

    function loadFile(event) {
        const output = document.getElementById("preview");
        output.style.display = "block";
        output.src = URL.createObjectURL(event.target.files[0]);
        document.getElementById("error-message").style.display = "none"; // Sembunyikan pesan error jika file dipilih
    }
</script>
@endsection
@endsection