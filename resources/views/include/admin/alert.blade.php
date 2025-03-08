@if(Session::has('alert-success'))
    <div class="sufee-alert alert with-close alert-primary alert-dismissible fade show">
        <span class="badge badge-pill badge-primary">Success !</span>
        {{Session::get('alert-success')}}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    
@elseif(Session::has('alert-danger'))
    
    <div class="sufee-alert alert with-close alert-danger alert-dismissible fade show">
        <span class="badge badge-pill badge-danger">Failed !</span>
        {{Session::get('alert-danger')}}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@elseif(Session::has('alert-warning'))
    <div class="sufee-alert alert with-close alert-warning alert-dismissible fade show">
        <span class="badge badge-pill badge-warning">Warning !</span>
        {{Session::get('alert-warning')}}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif