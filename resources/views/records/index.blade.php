@extends('layouts.app')
@section('content')
<div class="container mx-auto mt-10">
    <div class="flex justify-between mb-6">
        <h1 class="text-2xl font-semibold">Records</h1>
        <a href="{{ route('records.create') }}" class="btn btn-success">
            Add New Record
        </a>
    </div>
    
    <div class="flex justify-between mb-6">
    @if(\Session::get('success'))
    <div style="background-color: #d1e7dd; color: #0f5132; border: 1px solid #badbcc; padding: 1rem; border-radius: 0.25rem; margin-bottom: 1rem;">
        <strong>Success!</strong> {{ session('success') }}
    </div> 
    @endif
    </div>

    <table class="w-full bg-white shadow-md rounded mb-4">
        <thead>
            <tr class="bg-gray-200">
                <th class="py-2 px-4">ID</th>
                <th class="py-2 px-4">Name</th>
                <th class="py-2 px-4">Description</th>
                <th class="py-2 px-4">Status</th>
                <th class="py-2 px-4">Created At</th>
                <th class="py-2 px-4">Actions</th>
            </tr>
        </thead>
        <tbody id="record-list">
            @foreach($records as $record)
            <tr id="record-{{ $record['id'] }}">
                <td class="py-2 px-4">{{ $record['id'] }}</td>
                <td class="py-2 px-4">{{ $record['fields']['Name'] ?? 'N/A' }}</td>
                <td class="py-2 px-4">{{ $record['fields']['Description'] ?? 'N/A' }}</td>
                <td class="py-2 px-4">{{ $record['fields']['Status'] ?? 'N/A' }}</td>
                <td class="py-2 px-4">{{ $record['createdTime'] }}</td>
                <td class="py-2 px-4 flex space-x-2">
                    <a href="{{ route('records.edit', $record['id']) }}" class="btn btn-primary rounded">Edit</a>
                    <button 
                        class="btn btn-danger"
                        data-toggle="modal"
                        data-target="#modal_{{ $record['id']}}">
                         Delete
                    </button>
                     @include('records.modals.delete_modal')
                </td>
            </tr>
            @endforeach
         </tbody>
    </table>
</div>
@endsection
@section('scripts')
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
@endsection
