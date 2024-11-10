@extends('layouts.app')
@section('content')
<div class="container mx-auto mt-10">
    <h1 class="text-2xl font-semibold mb-6">Edit Record</h1>
    @if($errors->any())
        <div class="bg-red-500 text-white p-4 rounded mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                 <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('records.update', $record->id) }}" method="POST" class="bg-white shadow-md rounded p-6">
         @csrf
         @method('PATCH')
        <div class="mb-4">
            <label for="name" class="form-label text-gray-700">Name</label>
            <input type="text" name="name" id="name" class="form-control"value="{{ $record->name }}" required>
        </div>

        <div class="mb-4">
            <label for="description" class="form-label text-gray-700">Description</label>
            <textarea name="description" id="description" class="form-control">{{ $record->description }}</textarea>
        </div>

        <div class="mb-4">
            <label for="status" class="form-control">Status</label>
            <select name="status" id="status" class="w-full px-4 py-2 border rounded">
                <option value="Active" {{ $record->status == 'Active' ? 'selected' : '' }}>Active</option>
                <option value="Inactive" {{ $record->status == 'Inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Save Changes</button>
    </form>

</div>
@endsection
