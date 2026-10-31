{{--
    Shared pet form fields. Usage:
      @include('admin.pets._form', ['pet' => $pet ?? null])
    Expects to sit inside <form enctype="multipart/form-data"> with @csrf (and @method('PUT') for edit).
--}}
@php
    $pet = $pet ?? null;
    $species = old('species', $pet?->species);
@endphp

<div class="row g-3">
    <div class="col-sm-6">
        <label class="form-label" for="name">Name</label>
        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
            value="{{ old('name', $pet?->name) }}" maxlength="20" required>
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-sm-6">
        <label class="form-label" for="species">Species</label>
        <select class="form-select @error('species') is-invalid @enderror" id="species" name="species" required>
            <option value="" disabled @selected(! $species)>Choose…</option>
            <option value="Dog" @selected($species === 'Dog')>Dog</option>
            <option value="Cat" @selected($species === 'Cat')>Cat</option>
        </select>
        @error('species')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12">
        <label class="form-label" for="breed">Breed</label>
        <input type="text" class="form-control @error('breed') is-invalid @enderror" id="breed" name="breed"
            value="{{ old('breed', $pet?->breed) }}" maxlength="50" required>
        @error('breed')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-sm-6">
        <label class="form-label" for="age">Age <span class="text-body-secondary fw-normal">(years)</span></label>
        <input type="number" class="form-control @error('age') is-invalid @enderror" id="age" name="age"
            value="{{ old('age', $pet?->age) }}" min="0" max="255" step="1" inputmode="numeric" required>
        @error('age')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-sm-6">
        <label class="form-label" for="weight">Weight <span class="text-body-secondary fw-normal">(kg)</span></label>
        <input type="number" class="form-control @error('weight') is-invalid @enderror" id="weight" name="weight"
            value="{{ old('weight', $pet?->weight) }}" min="0.1" max="999" step="0.1" inputmode="decimal" required>
        @error('weight')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12">
        <label class="form-label" for="description">Description</label>
        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
            rows="4" maxlength="250" aria-describedby="description-help" required>{{ old('description', $pet?->description) }}</textarea>
        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
        <div class="form-text" id="description-help">Up to 250 characters. Shown on the pet's page.</div>
    </div>

    <div class="col-12">
        <label class="form-label" for="photo">Photo</label>
        <div class="photo-field">
            <img id="photo-preview" class="photo-field__img" src="{{ $pet?->photo_url }}" alt=""
                @unless ($pet) hidden @endunless>
            <div class="photo-field__input">
                <input type="file" class="form-control @error('photo') is-invalid @enderror" id="photo" name="photo"
                    accept="image/jpeg,image/png,image/gif,image/webp" data-preview="#photo-preview"
                    aria-describedby="photo-help" @unless ($pet) required @endunless>
                @error('photo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <div class="form-text" id="photo-help">
                    JPG, PNG, GIF or WebP, up to 2 MB.@if ($pet) Leave empty to keep the current photo.@endif
                </div>
            </div>
        </div>
    </div>
</div>
