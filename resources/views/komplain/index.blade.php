@extends('layouts.master')
@section('title','User')
@section('content')

        <div class="page-content-wrapper py-3">
            @include('include.admin.alert')
			<!-- Pagination-->
			<div class="shop-pagination pb-3">
				<div class="container">
					
                    <div class="d-flex align-items-center justify-content-between">
                        
                            

                        <a href="{{ route('komplain.create') }}" class="btn btn-primary mb-3">Tambah</a>
                    </div>
                            
						
				</div>
			</div>

            <div class="shop-pagination pb-3">
				<div class="container">
					<div class="card">
						<div class="card-body p-2">
							<div class="d-flex align-items-center justify-content-between"><small class="ms-1 showing-info"></small>
								<form action="#">
									<select class="pe-4 form-select form-select-sm" id="defaultSelectSm" name="defaultSelectSm" aria-label="Default select example">
										<option value="newest" selected>Sort by Newest</option>
										<option value="oldest">Sort by Older</option>
										
									</select>
								</form>
							</div>
                            
						</div>
                        
					</div>
				</div>
			</div>

           

            

			<div class="top-products-area product-list-wrap">
				<div class="container">
					<div class="row g-3">

                    
						
					</div>
				</div>
			</div>


			<div class="shop-pagination pt-3">
				<div class="container">
					<div class="card">
						<div class="card-body py-3">
							<nav aria-label="Page navigation example">
								<ul class="pagination pagination-two justify-content-center">
									<li class="page-item"><a class="page-link" href="#" aria-label="Previous">
											<svg class="bi bi-chevron-left" width="16" height="16" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
												<path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"></path>
											</svg></a></li>
									<li class="page-item active"><a class="page-link" href="#">1</a></li>
									<li class="page-item"><a class="page-link" href="#">2</a></li>
									<li class="page-item"><a class="page-link" href="#">3</a></li>
									<li class="page-item"><a class="page-link" href="#">...</a></li>
									<li class="page-item"><a class="page-link" href="#">9</a></li>
									<li class="page-item"><a class="page-link" href="#" aria-label="Next">
											<svg class="bi bi-chevron-right" width="16" height="16" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
												<path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"></path>
											</svg></a></li>
								</ul>
							</nav>
						</div>
					</div>
				</div>
			</div>

		</div>
@section('js')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        loadProducts();

        document.getElementById("defaultSelectSm").addEventListener("change", function () {
            loadProducts(this.value);
        });
    });

function loadProducts(sort = 'newest', page = 1) {
    fetch(`{{ url('/api/komplain') }}?sort=${sort}&page=${page}`)
        .then(response => response.json())
        .then(data => {
            const komplainContainer = document.querySelector(".top-products-area .container .row");
            komplainContainer.innerHTML = "";

            data.data.forEach(komplain => {
                 //console.log(komplain.created_at_formatted);
                komplainContainer.innerHTML += `
                    <div class="col-12">
                        <div class="card single-product-card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="card-side-img">
                                        <a class="product-thumbnail d-block" href="catalog-detail.html?id=${komplain.id}">
                                            <img src="${komplain.gambar}">
                                        </a>
                                    </div>
                                    <div class="card-content px-4 py-2">
                                        <a class="product-title d-block text-truncate mt-0" href="${komplain.id}">
                                            ${komplain.pesan}
                                        </a>
                                        <a class="btn btn-outline-info btn-sm" href="#">
                                            ${komplain.updated_at_formatted ? komplain.updated_at_formatted : komplain.created_at_formatted}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });

            updatePagination(data);

            // Update teks "Showing x of y"
            document.querySelector(".showing-info").innerText = `Showing ${data.data.length} of ${data.total}`;
        })
        .catch(error => console.error("Error fetching products:", error));
}



function updatePagination(data) {
    const paginationContainer = document.querySelector(".pagination");
    paginationContainer.innerHTML = "";

    if (data.prev_page_url) {
        paginationContainer.innerHTML += `<li class="page-item"><a class="page-link" href="#" onclick="loadProducts('${document.getElementById("defaultSelectSm").value}', ${data.current_page - 1})">&laquo;</a></li>`;
    }

    for (let i = 1; i <= data.last_page; i++) {
        paginationContainer.innerHTML += `<li class="page-item ${i === data.current_page ? "active" : ""}">
            <a class="page-link" href="#" onclick="loadProducts('${document.getElementById("defaultSelectSm").value}', ${i})">${i}</a>
        </li>`;
    }

    if (data.next_page_url) {
        paginationContainer.innerHTML += `<li class="page-item"><a class="page-link" href="#" onclick="loadProducts('${document.getElementById("defaultSelectSm").value}', ${data.current_page + 1})">&raquo;</a></li>`;
    }
}
</script>
@endsection  

@endsection