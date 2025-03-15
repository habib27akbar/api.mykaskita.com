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
								
								<iframe 
										id="embedMaps"
										width="100%"
										height="400"
										frameborder="0"
										style="border:0"
										src="https://maps.google.com/maps?q={{ $kunjungan->latitude }},{{ $kunjungan->longitude }}&output=embed"
										allowfullscreen>
								</iframe>
								<div class="form-group mb-3">
									
									<label for="file-input">
										<img src="{{ asset('img/camera-icon.jpg') }}" style="width: 35%" onclick="getLocation()" alt="Camera Icon">
									</label>
									<input style="opacity: 0; position: absolute; width: 1px; height: 1px;" capture id="file-input" class="form-control" type="file" name="gambar" accept="image/*" onchange="loadFile(event)">
									<img id="preview" style="{{ $kunjungan->gambar?'display:block;':'display: none;' }} max-width: 100px; margin-top: 10px;"  src="{{ asset('img/kunjungan/'.$kunjungan->gambar) }}" />
									<input type="hidden" name="gambar_old" value="{{ $kunjungan->gambar }}">
									<input type="hidden" id="latitude" name="latitude" value="{{ $kunjungan->latitude }}" readonly>
									<input type="hidden" id="longitude" name="longitude" value="{{ $kunjungan->longitude }}" readonly>
									
								</div>

								<div class="form-group mb-3">
									
									<label for="file-input-galeri">
										<img src="{{ asset('img/1375106.png') }}" style="width: 14%" onclick="getLocation()" alt="Camera Icon">
									</label>
									<input style="opacity: 0; position: absolute; width: 1px; height: 1px;" id="file-input-galeri" class="form-control" type="file" name="gambar_galeri" accept="image/*" onchange="loadFileGaleri(event)">
									<img id="preview_galeri" style="{{ $kunjungan->gambar_galeri?'display:block;':'display: none;' }} max-width: 100px; margin-top: 10px;" src="{{ asset('img/kunjungan/'.$kunjungan->gambar_galeri) }}" />
									<input type="hidden" name="gambar_galeri_old" value="{{ $kunjungan->gambar_galeri }}">
									
									
								</div>

								<div class="form-group mb-3">
									<input type="text" name="alamat" required class="form-control" placeholder="Tempat" value="{{ $kunjungan->alamat }}">
								</div>
								<div class="form-group mb-3">
									<textarea class="form-control" name="catatan" cols="30" rows="10" placeholder="Catatan">
										{{ $kunjungan->catatan }}
									</textarea>
								</div>
								<button type="submit" class="btn btn-primary w-100">Kirim Pesan</button>
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
	function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                document.getElementById("latitude").value = position.coords.latitude;
                document.getElementById("longitude").value = position.coords.longitude;
				var mapSrc = `https://maps.google.com/maps?q=${position.coords.latitude},${position.coords.longitude}&output=embed`;
				document.getElementById("embedMaps").src = mapSrc;
            }, function(error) {
                console.error("Error mendapatkan lokasi: ", error);
                alert("Gagal mendapatkan lokasi. Pastikan GPS diaktifkan.");
            });
        } else {
            alert("Geolocation tidak didukung oleh browser ini.");
        }
    }
    function loadFile(event) {
        const output = document.getElementById("preview");
        output.style.display = "block";
        output.src = URL.createObjectURL(event.target.files[0]);
        
    }

	function loadFileGaleri(event) {
        const output = document.getElementById("preview_galeri");
        output.style.display = "block";
        output.src = URL.createObjectURL(event.target.files[0]);
        
    }
</script>
@endsection
@endsection