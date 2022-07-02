@if (app('request')->get('wishlist') == 'true')
    <a href="{{ url($crud->route.'/') }} "
       class="btn btn-xs btn-primary">
        <i class="fa fa-magnifying-glass-plus"></i> Wishlist
    </a>
@else
    <a href="{{ url($crud->route.'/?wishlist=true') }} "
       class="btn btn-xs btn-success">
        <i class="fa fa-magnifying-glass-minus"></i> Owned sets
    </a>
@endif
