@extends('layouts.app')

@section('content')

    <h1>Edit User</h1>
    <form action="{{ route('users.update', $user->id) }}" method="POST">
        @csrf
        @method('patch')

        <div>
            <label for="work_space_id">Workspace ID (Optional):</label>
            <input type="number" id="work_space_id" name="work_space_id" value="{{ old('work_space_id', $user->work_space_id) }}">
        </div>

        <div>
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required>
        </div>

        <div>
            <label for="role">Role:</label>
            <select id="role" name="role" required>
                @foreach ($roles as $role)
                    <option value="{{ $role }}" {{ $user->role == $role ? 'selected' : '' }}>{{ ucfirst($role) }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>
        </div>

        <div>
            <label for="email_verified_at">Email Verified At:</label>
            <input type="datetime-local" id="email_verified_at" name="email_verified_at" value="{{ old('email_verified_at', $user->email_verified_at) }}">
        </div>

        <div>
            <label for="password">Password (Leave blank to keep current password):</label>
            <input type="password" id="password" name="password">
        </div>

        <div>
            <label for="password_confirmation">Confirm Password:</label>
            <input type="password" id="password_confirmation" name="password_confirmation">
        </div>

        <div>
            <label for="remember_token">Remember Token:</label>
            <input type="text" id="remember_token" name="remember_token" value="{{ old('remember_token', $user->remember_token) }}">
        </div>

        <div>
            <button type="submit">Update</button>
        </div>
    </form>

@endsection
