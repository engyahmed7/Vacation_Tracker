@if(Auth::check() && Auth::user()->hasRole('hr'))

<div>
    <a href="" class="btn btn-primary">Send Vacation Request</a>
    <a href="" class="btn btn-secondary">Manage Vacation Requests</a>
</div>
@endif
