@extends('layouts.app')

@section('title', 'Users')

@php
    // The user whose edit form failed validation (its modal is reopened with old() values).
    $errorUserId = $errors->any() ? (int) old('user_id') : null;
@endphp

@section('content')
    <div class="container page">
        @include('admin._nav')

        <header class="page-header">
            <div>
                <h1 class="page-header__title">Users</h1>
                <p class="page-header__subtitle">Customer and administrator accounts.</p>
            </div>
        </header>

        @if ($users->isEmpty())
            <div class="empty-state">
                <i class="bi bi-people empty-state__icon" aria-hidden="true"></i>
                <h2 class="empty-state__title">No users yet</h2>
                <p class="empty-state__text">Accounts appear here after people register.</p>
            </div>
        @else
            <div class="table-wrap">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Phone</th>
                            <th scope="col">Role</th>
                            <th scope="col"><span class="visually-hidden">Actions</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>
                                    {{ $user->name }} {{ $user->last_name }}
                                    @if ($user->is(auth()->user()))
                                        <span class="cell-muted">(you)</span>
                                    @endif
                                </td>
                                <td>{{ $user->email }}</td>
                                <td class="text-nowrap">{{ $user->phone_number }}</td>
                                <td>{{ $user->isAdmin() ? 'Admin' : 'Customer' }}</td>
                                <td class="table-actions">
                                    <button type="button" class="btn btn-quiet btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#edit-user-{{ $user->id }}">
                                        Edit<span class="visually-hidden"> {{ $user->name }} {{ $user->last_name }}</span>
                                    </button>
                                    @unless ($user->is(auth()->user()))
                                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                            data-confirm="Delete {{ $user->name }} {{ $user->last_name }}? Their adoptions will be removed too.">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                                Delete<span class="visually-hidden"> {{ $user->name }} {{ $user->last_name }}</span>
                                            </button>
                                        </form>
                                    @endunless
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{ $users->links('layouts.partials.pagination') }}
        @endif
    </div>

    {{-- One edit modal per user (kept outside the table for valid markup). --}}
    @foreach ($users as $user)
        @php
            $hasErrors = $errorUserId === $user->id;
            $isSelf = $user->is(auth()->user());
            $val = fn (string $field) => $hasErrors ? old($field) : $user->{$field};
            $isAdminChecked = $isSelf || ($hasErrors ? (bool) old('admin') : $user->isAdmin());
            $fid = 'u' . $user->id;
        @endphp
        <div class="modal fade" id="edit-user-{{ $user->id }}" tabindex="-1" aria-labelledby="edit-user-{{ $user->id }}-title" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <form class="modal-content" method="POST" action="{{ route('admin.users.update', $user) }}" novalidate>
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                    <div class="modal-header">
                        <h2 class="modal-title fs-5" id="edit-user-{{ $user->id }}-title">Edit user</h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            @foreach (['name' => 'First name', 'last_name' => 'Last name'] as $field => $label)
                                <div class="col-sm-6">
                                    <label class="form-label" for="{{ $fid }}-{{ $field }}">{{ $label }}</label>
                                    <input type="text" class="form-control {{ $hasErrors && $errors->has($field) ? 'is-invalid' : '' }}"
                                        id="{{ $fid }}-{{ $field }}" name="{{ $field }}" value="{{ $val($field) }}"
                                        maxlength="{{ $field === 'name' ? 20 : 30 }}" required>
                                    @if ($hasErrors && $errors->has($field))
                                        <div class="invalid-feedback">{{ $errors->first($field) }}</div>
                                    @endif
                                </div>
                            @endforeach
                            <div class="col-12">
                                <label class="form-label" for="{{ $fid }}-email">Email</label>
                                <input type="email" class="form-control {{ $hasErrors && $errors->has('email') ? 'is-invalid' : '' }}"
                                    id="{{ $fid }}-email" name="email" value="{{ $val('email') }}" maxlength="100" required autocomplete="off">
                                @if ($hasErrors && $errors->has('email'))
                                    <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                                @endif
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="{{ $fid }}-phone">Phone number</label>
                                <input type="tel" class="form-control {{ $hasErrors && $errors->has('phone_number') ? 'is-invalid' : '' }}"
                                    id="{{ $fid }}-phone" name="phone_number" value="{{ $val('phone_number') }}"
                                    maxlength="15" inputmode="numeric" pattern="[0-9]+" required>
                                @if ($hasErrors && $errors->has('phone_number'))
                                    <div class="invalid-feedback">{{ $errors->first('phone_number') }}</div>
                                @endif
                            </div>
                            <div class="col-12">
                                <div class="form-check">
                                    @if ($isSelf)
                                        {{-- A disabled checkbox is not submitted; keep the role explicitly. --}}
                                        <input type="hidden" name="admin" value="1">
                                    @endif
                                    <input class="form-check-input" type="checkbox" id="{{ $fid }}-admin" name="admin" value="1"
                                        @checked($isAdminChecked) @disabled($isSelf)
                                        @if ($isSelf) aria-describedby="{{ $fid }}-admin-help" @endif>
                                    <label class="form-check-label" for="{{ $fid }}-admin">Administrator</label>
                                    @if ($isSelf)
                                        <div class="form-text" id="{{ $fid }}-admin-help">You can't remove your own admin role.</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-quiet" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach
@endsection

@if ($errorUserId)
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var modal = document.getElementById('edit-user-{{ $errorUserId }}');
                if (modal && window.bootstrap) bootstrap.Modal.getOrCreateInstance(modal).show();
            });
        </script>
    @endpush
@endif
