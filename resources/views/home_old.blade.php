@extends('layouts.master')
@section('title','Home')
@section('content')
        <!-- Content -->
        <div class="page-content-wrapper py-3">
	        <!-- Pagination-->
	        <div class="shop-pagination pb-3">
	            <div class="container">
	                <div class="card">
	                    <div class="card-body p-2">
	                        <div class="d-flex align-items-center justify-content-between"><small class="ms-1">Showing 6 of 31</small>
	                            <form action="#">
	                                <select class="pe-4 form-select form-select-sm" id="defaultSelectSm" name="defaultSelectSm" aria-label="Default select example">
	                                    <option value="1" selected>Sort by Newest</option>
	                                    <option value="2">Sort by Older</option>
	                                    <option value="3">Sort by Ratings</option>
	                                    <option value="4">Sort by Sales</option>
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


	                    <!-------------------------------------------------------->
	                    <div class="col-12">
	                        <div class="card single-product-card">
	                            <div class="card-body">
	                                <div class="d-flex align-items-center">
	                                    <div class="card-side-img">
	                                        <a class="product-thumbnail d-block" href="catalog-detail.html"><img src="img/bg-img/p1.jpg" alt=""></a>
	                                    </div>
	                                    <div class="card-content px-4 py-2">
	                                        <a class="product-title d-block text-truncate mt-0" href="catalog-detail.html">Nama Produk</a>
	                                        <p class="sale-price">$3.36<span>$5.99</span></p>
	                                        <a class="btn btn-outline-info btn-sm" href="#">
	                                            <svg class="bi bi-cart me-2" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
	                                                <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l1.313 7h8.17l1.313-7H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"></path>
	                                            </svg>Add to Cart</a>
	                                    </div>
	                                </div>
	                            </div>
	                        </div>
	                    </div>
	                    <!-------------------------------------------------------->
	                    <!-------------------------------------------------------->
	                    <div class="col-12">
	                        <div class="card single-product-card">
	                            <div class="card-body">
	                                <div class="d-flex align-items-center">
	                                    <div class="card-side-img">
	                                        <a class="product-thumbnail d-block" href="catalog-detail.html"><img src="img/bg-img/p2.jpg" alt=""></a>
	                                    </div>
	                                    <div class="card-content px-4 py-2">
	                                        <a class="product-title d-block text-truncate mt-0" href="catalog-detail.html">Nama Produk</a>
	                                        <p class="sale-price">$3.36<span>$5.99</span></p>
	                                        <a class="btn btn-outline-info btn-sm" href="#">
	                                            <svg class="bi bi-cart me-2" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
	                                                <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l1.313 7h8.17l1.313-7H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"></path>
	                                            </svg>Add to Cart</a>
	                                    </div>
	                                </div>
	                            </div>
	                        </div>
	                    </div>
	                    <!-------------------------------------------------------->
	                    <!-------------------------------------------------------->
	                    <div class="col-12">
	                        <div class="card single-product-card">
	                            <div class="card-body">
	                                <div class="d-flex align-items-center">
	                                    <div class="card-side-img">
	                                        <a class="product-thumbnail d-block" href="catalog-detail.html"><img src="img/bg-img/p3.jpg" alt=""></a>
	                                    </div>
	                                    <div class="card-content px-4 py-2">
	                                        <a class="product-title d-block text-truncate mt-0" href="catalog-detail.html">Nama Produk</a>
	                                        <p class="sale-price">$3.36<span>$5.99</span></p>
	                                        <a class="btn btn-outline-info btn-sm" href="#">
	                                            <svg class="bi bi-cart me-2" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
	                                                <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l1.313 7h8.17l1.313-7H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"></path>
	                                            </svg>Add to Cart</a>
	                                    </div>
	                                </div>
	                            </div>
	                        </div>
	                    </div>
	                    <!-------------------------------------------------------->

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
        <!-- /.content -->

@endsection