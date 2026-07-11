@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show admin-alert" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show admin-alert" role="alert">
        <i class="fas fa-circle-exclamation me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(($showValidationErrors ?? true) && $errors->any() && !session('form_type'))
    <div class="alert alert-danger alert-dismissible fade show admin-alert" role="alert">
        <strong>
            <i class="fas fa-exclamation-triangle me-2"></i>
            Please fix the following errors:
        </strong>
        <ul class="mb-0 mt-2">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
