{{--
    Reusable "this used to be edited here, now it has its own screen" panel.
    Params: icon, title, description, route, permission, count, countLabel
--}}
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-5 text-center">
        <i class="bi {{ $icon }} fs-1 text-primary opacity-50 d-block mb-3"></i>
        <h6 class="fw-bold mb-2">{{ $title }}</h6>
        <p class="text-muted small mb-4 mx-auto" style="max-width:520px;">{{ $description }}</p>
        <span class="badge bg-light text-muted rounded-pill px-3 py-2 mb-4 d-inline-block">
            {{ $count }} {{ $countLabel }}
        </span>
        <div>
            @can($permission)
            <a href="{{ route($route) }}" class="btn btn-primary rounded-pill px-4 fw-bold">
                <i class="bi bi-box-arrow-up-right me-1"></i> {{ __('الذهاب إلى شاشة الإدارة') }}
            </a>
            @endcan
        </div>
    </div>
</div>
