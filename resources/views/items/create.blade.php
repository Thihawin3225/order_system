@extends('layouts.app')

@section('title', 'New Item')

@section('content')
<div class="breadcrumb">
    <a href="{{ route('items.index') }}">Items</a> / New
</div>

<div class="page-header">
    <div class="page-title">
        <h1>Create Item</h1>
        <p>Add a new item to your catalog.</p>
    </div>
</div>

<div class="form-card">
    <form action="{{ route('items.store') }}" method="POST">
        @csrf
        <div class="form-field">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required>
            @error('name') <p style="color:red; font-size:12px; margin-top:4px;">{{ $message }}</p> @enderror
        </div>

        <div class="form-field">
            <label for="price">Price ($)</label>
            <input type="number" id="price" name="price" step="0.01" value="{{ old('price') }}" required>
            @error('price') <p style="color:red; font-size:12px; margin-top:4px;">{{ $message }}</p> @enderror
        </div>

        <div class="form-field">
            <label for="description">Description (Optional)</label>
            <textarea id="description" name="description" rows="4">{{ old('description') }}</textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-new">Save Item</button>
            <a href="{{ route('items.index') }}" class="btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection