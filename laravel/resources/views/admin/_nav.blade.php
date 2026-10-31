{{-- Admin sub-navigation (segmented links). Usage: @include('admin._nav') --}}
@php
    $adminSections = [
        ['route' => 'admin.adoptions', 'match' => 'admin.adoptions*', 'label' => 'Adoptions'],
        ['route' => 'admin.pets', 'match' => 'admin.pets*', 'label' => 'Pets'],
        ['route' => 'admin.vaccinations', 'match' => 'admin.vaccinations*', 'label' => 'Vaccinations'],
        ['route' => 'admin.users', 'match' => 'admin.users*', 'label' => 'Users'],
    ];
@endphp
<nav class="admin-nav" aria-label="Admin sections">
    <div class="segmented segmented--scroll">
        @foreach ($adminSections as $section)
            @php $current = request()->routeIs($section['match']); @endphp
            <a class="segmented__btn {{ $current ? 'active' : '' }}" href="{{ route($section['route']) }}"
                @if ($current) aria-current="page" @endif>{{ $section['label'] }}</a>
        @endforeach
    </div>
</nav>
