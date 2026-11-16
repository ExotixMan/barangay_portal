@php
    $reviewNameParts = \App\Support\ResidentName::suggestedParts($concern->correct_value, $resident);
    if ($isCorrectionRetry && array_key_exists('name_firstname', session()->getOldInput())) {
        foreach (array_keys($reviewNameParts) as $part) {
            $reviewNameParts[$part] = old('name_'.$part);
        }
    }
    $fullNameReview = \App\Support\ResidentName::isFullNameField($concern->field_name);
@endphp
<div class="correction-review-content">
    @if($isCorrectionRetry && $errors->any())
        <div class="alert alert-danger" role="alert"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif
    <div class="correction-review-values">
        <div><span>Current value</span><strong>{{ $concern->current_value ?: 'Not provided' }}</strong></div>
        <div><span>Resident's requested correction</span><strong>{{ $concern->correct_value }}</strong></div>
    </div>
    <section aria-label="Resident saved ID" class="correction-review-ids">
        <h3>Resident's saved valid ID</h3>
        <p class="small">Status: {{ str($resident->valid_id_status ?: ($resident->valid_id_verified ? 'verified' : 'not_verified'))->replace('_', ' ')->title() }}. Compare the requested correction with the saved ID before approving.</p>
        <div class="correction-review-id-grid">
            @foreach(['valid_id' => 'Front', 'valid_id_back' => 'Back'] as $field => $label)
                @php
                    $idPath = $resident->{$field};
                    $isIdImage = in_array(strtolower(pathinfo((string) $idPath, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'webp']);
                @endphp
                <div class="correction-review-id-card">
                    <h4>{{ $label }} ID</h4>
                    @if($idPath)
                        @if($isIdImage)
                            <a href="{{ route('admin.secure.file', ['path' => $idPath]) }}" target="_blank" rel="noopener noreferrer">
                                <img src="{{ route('admin.secure.file', ['path' => $idPath]) }}" alt="Resident's saved {{ strtolower($label) }} valid ID" data-review-id-image>
                            </a>
                            <p data-id-unavailable hidden>The saved image could not be loaded. Open the file below or check the uploaded document.</p>
                        @else
                            <p class="correction-review-file"><i class="fas fa-file-pdf" aria-hidden="true"></i> Saved document</p>
                        @endif
                        <a href="{{ route('admin.secure.file', ['path' => $idPath]) }}" target="_blank" rel="noopener noreferrer">Open {{ strtolower($label) }} ID in full size</a>
                    @else
                        <p>No {{ strtolower($label) }} ID saved.</p>
                    @endif
                </div>
            @endforeach
        </div>
    </section>
    @if($fullNameReview)
        <div class="correction-review-name" aria-live="polite"><span>Name to save</span><strong data-reviewed-name>{{ $concern->correct_value }}</strong></div>
        <details data-review-name-parts @if($isCorrectionRetry && $errors->hasAny(['name_firstname', 'name_lastname', 'name_middlename', 'name_suffix'])) open @endif>
            <summary>Check or adjust name parts (optional)</summary>
            <p class="small">The request is already filled in. Check these parts against the saved ID, especially compound first or last names. Adjust a part only if needed.</p>
            <div class="correction-review-name-grid">
                @foreach(['firstname' => 'First name', 'middlename' => 'Middle name', 'lastname' => 'Last name', 'suffix' => 'Suffix'] as $part => $label)
                    <label for="review-name-{{ $part }}-{{ $concern->id }}">{{ $label }}{{ in_array($part, ['firstname', 'lastname']) ? ' *' : ' (optional)' }}
                        <input id="review-name-{{ $part }}-{{ $concern->id }}" data-review-part="{{ $part }}" class="form-control" type="text" maxlength="{{ $part === 'suffix' ? 10 : 255 }}" value="{{ $reviewNameParts[$part] }}">
                    </label>
                @endforeach
            </div>
        </details>
    @endif
    <label class="correction-review-remarks" for="review-remarks-{{ $concern->id }}">Review remarks <span aria-hidden="true">*</span>
        <textarea id="review-remarks-{{ $concern->id }}" data-review-remarks class="form-control" rows="2" maxlength="500" required>{{ $isCorrectionRetry ? old('remarks') : '' }}</textarea>
    </label>
    <label class="correction-review-notify"><input data-review-notify type="checkbox" @checked($isCorrectionRetry ? old('notify_email') : true) @disabled(!$resident->email)> Notify the resident by email</label>
</div>
