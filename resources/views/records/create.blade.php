@extends('layouts.app')
@section('content')
<div class="container mx-auto mt-10">
    <h1 class="text-2xl font-semibold mb-6">Create New Record</h1>

    @if ($errors->any())
        <div class="bg-red-500 text-white p-4 rounded mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('records.store') }}" method="POST" class="bg-white shadow-md rounded p-6">
        @csrf
        <div class="mb-4">
            <label for="name" class="form-label text-gray-700">Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
        </div>
        
        <div class="mb-4">
            <label for="description" class="form-label text-gray-700">Description</label>
            <textarea name="description" id="description" class="form-control">{{ old('description') }}</textarea>
        </div>
        
        <div class="mb-4">
            <label for="status" class="form-label text-gray-700">Status</label>
            <select name="status" id="status" class="form-select">
                <option value="Todo" {{ old('status') == 'Todo' ? 'selected' : '' }}>Todo</option>
                <option value="Done" {{ old('status') == 'Done' ? 'selected' : '' }}>Done</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Save Record</button>
    </form>

</div>
@endsection
