@if(Session::has('alert-success'))
<div class="shop-pagination pb-3">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between">
            <div class="alert alert-primary">
                {{Session::get('alert-success')}}
            </div>
        </div>
    </div>
</div>
        
    
    
@elseif(Session::has('alert-danger'))
<div class="shop-pagination pb-3">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between">
            <div class="alert alert-danger">
                {{Session::get('alert-danger')}}
            </div>
        </div>
    </div>
</div>

    
@elseif(Session::has('alert-warning'))
<div class="shop-pagination pb-3">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between">
            <div class="alert alert-warning">
                {{Session::get('alert-warning')}}
            </div>
        </div>
    </div>
</div>
@endif