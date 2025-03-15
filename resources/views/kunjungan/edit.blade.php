@extends('layouts.master')
@section('title','Kunjungan')
@section('content')

        <div class="page-content-wrapper py-3">
			<div class="container">
				<!-- Contact Form -->
				<div class="card mb-3">
					<div class="card-body">
						<h5 class="mb-3">Formulir Kunjungan (Ubah)</h5>
						<div class="contact-form">
							<form action="{{ route('kunjungan.update', ['kunjungan' => $kunjungan->id]) }}" method="post" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

								<div class="form-group mb-3">
									<label for="file-input">
										<img src="{{ asset('img/camera-icon.jpg') }}" style="width: 35%" alt="Camera Icon">
									</label>
									<input style="opacity: 0; position: absolute; width: 1px; height: 1px;" id="file-input" class="form-control" type="file" name="gambar" accept="image/*" onchange="loadFile(event)">
									<img id="preview" style="max-width: 100px; margin-top: 10px; display:block;" src="{{ asset('img/kunjungan/'.$kunjungan->gambar) }}" />
									<input type="hidden" name="gambar_old" value="{{ $kunjungan->gambar }}">
									<p id="error-message" style="color: red; display: none;">File gambar wajib diunggah!</p>
								</div>
								
								<div class="form-group mb-3">
									<textarea class="form-control" cols="30" rows="10" name="pesan" placeholder="pesan" required>{{ $kunjungan->pesan }}</textarea>
								</div>
								<button class="btn btn-primary w-100">Kirim Pesan</button>
							</form>
							<form method="POST" action="{{ route('kunjungan.destroy', ['kunjungan' => $kunjungan->id]) }}">
								
								@method('DELETE')
								@csrf
								<button style="margin-top: 10px; background-color:red;" onclick="return confirm('Apakah anda yakin ingin hapus kunjungan ini?')" class="btn btn-danger w-100">Hapus</button>
									
								
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
@section('js')
<script>
    document.getElementById("kunjunganForm").addEventListener("submit", function(event) {
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