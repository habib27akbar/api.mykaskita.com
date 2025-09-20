	<!DOCTYPE html>
	<html lang="en">

	<head>
	    <meta charset="utf-8">
	    <meta name="viewport" content="width=device-width, initial-scale=1">
	   
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <meta name="theme-color" content="#0134d4">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
		<meta name="csrf-token" content="{{ csrf_token() }}">
	    <title>.:: KASKITA ::.</title>


	    <!----------- Fonts ------------------->
	    <link rel="preconnect" href="https://fonts.gstatic.com">
	    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&amp;display=swap" rel="stylesheet">
	    <!------------------------------------------------------->


	    <!------------------- Favicon ---------------------->
	    <link rel="icon" href="{{ asset('img/core-img/favicon.ico') }}">
	    <link rel="apple-touch-icon" href="{{ asset('img/icons/icon-96x96.png') }}">
		<link rel="apple-touch-icon" sizes="144x144" href="{{ asset('img/icons/icon-144x144.png') }}">
	    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('img/icons/icon-152x152.png') }}">
	    <link rel="apple-touch-icon" sizes="167x167" href="{{ asset('img/icons/icon-167x167.png') }}">
	    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('img/icons/icon-180x180.png') }}">
	    <!--------------------------------------------->


	    <!----------------------- CSS  -------------------------->
	    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
	    <link rel="stylesheet" href="{{ asset('css/bootstrap-icons.css') }}">
	    <link rel="stylesheet" href="{{ asset('css/tiny-slider.css') }}">
	    <link rel="stylesheet" href="{{ asset('css/baguetteBox.min.css') }}">
	    <link rel="stylesheet" href="{{ asset('css/rangeslider.css') }}">
	    <link rel="stylesheet" href="{{ asset('css/vanilla-dataTables.min.css') }}">
	    <link rel="stylesheet" href="{{ asset('css/apexcharts.css') }}">
	    <link rel="stylesheet" href="{{ asset('style.css') }}">
	    <link rel="manifest" href="{{ url('manifest.json') }}">
		<link rel="stylesheet" href="{{ asset('/msg/css/font-awesome.min.css') }}">
	    <!------------------------------------------------------>




	</head>

	<body>


	    <!---------- Preloader ----------------->
	    <div id="preloader">
	        <div class="spinner-grow text-primary" role="status"><span class="visually-hidden">Loading...</span></div>
	    </div>
	    <!---------------------------------------------->

	    <div class="internet-connection-status" id="internetStatus"></div>


	    <!---------------------------------------------->
	    <div class="login-wrapper d-flex align-items-center justify-content-center">
	        <div class="custom-container">
	            <div class="text-center px-4"><img class="login-intro-img" src="{{ asset('img/icons/login_bg.png') }}" alt=""></div>
				@if (false)
					<div class="form-group row justify-content-center">
						<div class="col-2" style="margin-top: 10px;">
			
							<img src="{{ asset('img/icons/ina.png')}}" class="img-fluid" style="border: 1px solid black; width:90%;" alt="">
						</div>
						<div class="col-2" style="margin-top: 10px;">
							<a href="/en">
								<img src="{{ asset('img/icons/eng.jpg')}}" class="img-fluid" style="width:90%" alt="">
							</a>
			
						</div>
					</div>
				@endif
				
	            <div class="register-form mt-4">
					@if ($errors->any())
						<div class="alert alert-danger">
							<ul>
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif
					@if (session('alert-danger'))
						<div class="alert alert-danger">
							{{Session::get('alert-danger')}}	
						</div>
					@endif


	                <form id="login-form">
                        
	                    <div class="form-group">
	                        <input class="form-control" type="email" id="email" name="email" placeholder="Masukan Email">
	                    </div>
	                    <div class="form-group position-relative">
	                        <input class="form-control" id="psw-input" type="password" name="password" placeholder="Masukan Katasandi">
	                        <div class="position-absolute" id="password-visibility"><i class="bi bi-eye"></i><i class="bi bi-eye-slash"></i></div>
	                    </div>
                        <div class="form-group position-relative">
							<label id="captcha-question">Berapa 1 + 2?</label>
							<input type="hidden" name="captcha_expected" id="captcha-expected">
	                        <input type="text" class="form-control" name="captcha" id="captcha" placeholder="Captcha">
	                    </div>
	                    <button class="btn btn-primary w-100" type="submit">Masuk</button>
						<br/><br/>
						
	                </form>
					<p id="status"></p>
	            </div>
	            <!---------------------------------------------->



	        </div>
	    </div>




	    <!-- All JavaScript Files -->
	    <script src="{{ asset('js/bootstrap.bundle.min.js')}}"></script>
	    <script src="{{ asset('js/slideToggle.min.js')}}"></script>
	    <script src="{{ asset('js/internet-status.js')}}"></script>
	    <script src="{{ asset('js/tiny-slider.js')}}"></script>
	    <script src="{{ asset('js/baguetteBox.min.js')}}"></script>
	    <script src="{{ asset('js/countdown.js')}}"></script>
	    <script src="{{ asset('js/rangeslider.min.js')}}"></script>
	    <script src="{{ asset('js/vanilla-dataTables.min.js')}}"></script>
	    <script src="{{ asset('js/index.js')}}"></script>
	    <script src="{{ asset('js/magic-grid.min.js')}}"></script>
	    <script src="{{ asset('js/dark-rtl.js')}}"></script>
	    <script src="{{ asset('js/active.js')}}"></script>
	    <!-- PWA -->
		<script src="{{ url('upup.min.js')}}"></script>
	    
        <script>
            // function refreshCaptcha() {
			// 	fetch("{{ route('captcha.refresh') }}")
			// 		.then(res => res.json())
			// 		.then(data => {
			// 			document.getElementById("captcha-img").src = data.captcha;
			// 		});
			// }
			document.addEventListener('DOMContentLoaded', function () {
				const a = Math.floor(Math.random() * 10) + 1;
				const b = Math.floor(Math.random() * 10) + 1;
				const question = `Berapa ${a} + ${b}?`;

				document.getElementById('captcha-question').textContent = question;
				document.getElementById('captcha-expected').value = a + b;
			});
			UpUp.start({
				'cache-version': 'v2',
				'content-url': 'https://app.mykaskita.com/',
				'assets': [
							
							'public/style.css',                
							
						],
				'content': 'Cannot reach site. Please check your internet connection.',
				'service-worker-url': 'https://app.mykaskita.com/upup.sw.min.js'
			});

			// Install dan cache file statis
			self.addEventListener('install', event => {
				event.waitUntil(
					caches.open(CACHE_NAME).then(cache => {
					return cache.addAll(ASSETS);
					})
				);
				self.skipWaiting();
			});

			// Fetch handler: cache-first, fallback ke network
			self.addEventListener('fetch', event => {
				event.respondWith(
					caches.match(event.request).then(response => {
					return response || fetch(event.request).then(res => {
						const resClone = res.clone();
						caches.open(CACHE_NAME).then(cache => {
						cache.put(event.request, resClone);
						});
						return res;
					});
					}).catch(() => {
					return caches.match('/');
					})
				);
			});

			

			// document.getElementById('login-form').addEventListener('submit', async function(e) {
			// 	e.preventDefault();

			// 	const email = document.getElementById('email').value;
			// 	const password = document.getElementById('psw-input').value;
			// 	const captcha = document.querySelector('input[name="captcha"]').value;

			// 	if (navigator.onLine) {
			// 		try {
			// 			const res = await fetch("{{ url('api/login') }}", { // Use correct URL
			// 				method: 'POST',
			// 				headers: {
			// 					'Content-Type': 'application/json',
			// 					'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
			// 				},
			// 				body: JSON.stringify({ email, password, captcha }) // Uncomment and send data
			// 			});
						
			// 			if (!res.ok) {
			// 				throw new Error(`HTTP error! status: ${res.status}`);
			// 			}

			// 			const data = await res.json();

			// 			if (data.success) {
			// 				localStorage.setItem('user', JSON.stringify({
			// 					email: data.user.email,
			// 					password: data.user.password
			// 				}));
			// 				console.log(data.user.email);
			// 				console.log(data.user.password);
			// 				window.location.href = '/home';
			// 			} else {
			// 				document.getElementById('status').textContent = data.message || 'Login failed!';
			// 			}
			// 		} catch (err) {
			// 			console.error('Login error:', err);
			// 			document.getElementById('status').textContent = 'Login error: ' + err.message;
			// 		}
			// 	} else {
			// 		// OFFLINE LOGIN
			// 		const savedUser = JSON.parse(localStorage.getItem('user'));
			// 		const hash = /* your hashing function here */ password;
					
			// 		if (savedUser && savedUser.email === email && savedUser.password_hash === hash) {
			// 			document.getElementById('status').textContent = 'Login offline berhasil!';
			// 			window.location.href = '/home';
			// 		} else {
			// 			document.getElementById('status').textContent = 'Login offline gagal!';
			// 		}
			// 	}
			// });

			document.getElementById('login-form').addEventListener('submit', async function(e) {
				e.preventDefault();

				const email = document.getElementById('email').value;
				const password = document.getElementById('psw-input').value;

				if (navigator.onLine) {
					// ONLINE MODE
					try {
						const res = await fetch("{{ url('api/login') }}", {
							method: 'POST',
							headers: {
								'Content-Type': 'application/json',
								'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
								'Accept': 'application/json'
							},
							body: JSON.stringify({ email, password })
						});

						const data = await res.json();

						if (data.success) {
							localStorage.setItem('user', JSON.stringify(data.user));
							localStorage.setItem('is_offline', 'false');
							var captcha_expected = document.getElementById('captcha-expected').value;
							var captcha_answers = document.getElementById('captcha').value;
							//console.log(data.user);
							if (
								//true
								captcha_answers == captcha_expected
							) {
								window.location.href = '/home';
							}else{
								document.getElementById('status').textContent = 'Capthca Salah';
							}
							
							
						} else {
							document.getElementById('status').textContent = data.message;
						}
					} catch (err) {
						document.getElementById('status').textContent = err;
					}
				} else {
					// OFFLINE MODE
					const saved = JSON.parse(localStorage.getItem('user'));
					if (saved && saved.email === email && saved.password === password) {
						localStorage.setItem('is_offline', 'true');
						window.location.href = '/home';
					} else {
						document.getElementById('status').textContent = 'Offline login gagal';
					}
				}
			});


        </script>
	</body>

	</html>