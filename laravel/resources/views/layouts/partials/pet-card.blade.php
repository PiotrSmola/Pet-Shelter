{{--
    Pet card. Usage:
      @include('layouts.partials.pet-card', ['pet' => $pet])
      @include('layouts.partials.pet-card', ['pet' => $pet, 'compact' => true, 'level' => 'h3'])
    The whole card is a link (stretched link on the name). Add 'reveal' => false to skip the scroll reveal.
--}}
@php
    $compact = $compact ?? false;
    $level = in_array($level ?? 'h3', ['h2', 'h3', 'h4'], true) ? ($level ?? 'h3') : 'h3';
    $photo = $pet->photo_url ?? ($pet->photo_path ? asset('storage/' . $pet->photo_path) : null);
    $age = (int) $pet->age;
    $ageLabel = $age < 1 ? 'under 1 yr' : $age . ' ' . ($age === 1 ? 'yr' : 'yrs');
    $weight = rtrim(rtrim(number_format((float) $pet->weight, 1, '.', ''), '0'), '.');
@endphp
<article class="pet-card {{ $compact ? 'pet-card--compact' : '' }}" @if ($reveal ?? true) data-reveal @endif>
    <div class="pet-card__media">
        @if ($photo)
            <img src="{{ $photo }}" alt="" loading="lazy" decoding="async">
        @endif
    </div>
    <div class="pet-card__body">
        <{{ $level }} class="pet-card__name">
            <a class="stretched-link" href="{{ route('pets.show', $pet) }}">{{ $pet->name }}</a>
        </{{ $level }}>
        @unless ($compact)
            <p class="pet-card__meta">{{ $pet->breed }} · {{ $ageLabel }} · {{ $weight }} kg</p>
        @endunless
    </div>
</article>
