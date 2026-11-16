@extends('admin.layouts.admin')

@section('title', 'Service Requests')
@section('page_title', 'Service Requests')
@section('page_icon', 'fas fa-file-lines')

@section('content')
<h1 class="h4">Service Requests</h1>
<p class="text-muted">Choose a request module you are permitted to access.</p>
<div class="row g-3">
    @foreach([
        ['allowed' => $canClearance, 'title' => 'Barangay Clearance', 'route' => 'admin.clearance.index'],
        ['allowed' => $canResidency, 'title' => 'Residency', 'route' => 'admin.residency.index'],
        ['allowed' => $canIndigency, 'title' => 'Indigency', 'route' => 'admin.indigency.index'],
    ] as $module)
        @if($module['allowed'])
            <div class="col-md-6 col-xl-4">
                <section class="card h-100">
                    <div class="card-body">
                        <h2 class="h5">{{ $module['title'] }}</h2>
                        <a class="btn btn-primary mt-2" href="{{ route($module['route']) }}">Open requests &rarr;</a>
                    </div>
                </section>
            </div>
        @endif
    @endforeach
    @if(!$canClearance && !$canResidency && !$canIndigency)
        <div class="col-12">
            <section class="card">
                <div class="card-body">
                    <h2 class="h5">No module permission</h2>
                    <p class="text-muted mb-0">Your account has View Requests but no specific document-module permission. Ask a Super Admin to update your permissions.</p>
                </div>
            </section>
        </div>
    @endif
</div>
@endsection
