@extends('layouts.app')
@section('content')
<div class="container mx-auto mt-10">
    <div class="flex justify-between mb-6">
        <h1 class="text-2xl font-semibold">Records</h1>
        <a href="{{ route('records.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded">
            Add New Record
        </a>
    </div>

    @if(session('status'))
        <div class="bg-green-500 text-white p-4 rounded mb-4">{{ session('status') }}</div>
    @endif

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
                    <td class="py-2 px-4">{{ $record['name'] }}</td>
                    <td class="py-2 px-4">{{ $record['description'] }}</td>
                    <td class="py-2 px-4">{{ $record['status'] }}</td>
                    <td class="py-2 px-4">{{ $record['created_at'] }}</td>
                    <td class="py-2 px-4 flex space-x-2">
                        <a href="{{ route('records.edit', $record['id']) }}" class="bg-yellow-500 text-white px-2 py-1 rounded">Edit</a>
                        <button onclick="showDeleteModal('{{ $record['id'] }}')" 
                            class="bg-red-500 text-white px-2 py-1 rounded">Delete</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $records->links() }}
    </div>
</div>

<div id="delete-modal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden justify-center items-center">
    <div class="bg-white p-6 rounded shadow-lg text-center">
        <h2 class="text-xl font-semibold mb-4">Are you sure?</h2>
        <p class="text-gray-700 mb-6">This action cannot be undone.</p>
        
        <button id="confirm-delete" class="bg-red-500 text-white px-4 py-2 rounded mr-2">Yes, Delete</button>
        <button type="button" onclick="hideDeleteModal()" class="bg-gray-300 px-4 py-2 rounded">Cancel</button>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    let deleteRecordId;

    function showDeleteModal(recordId) {
        deleteRecordId = recordId;
        $('#delete-modal').removeClass('hidden');
    }

    function hideDeleteModal() {
        $('#delete-modal').addClass('hidden');
        deleteRecordId = null;
    }

    $('#confirm-delete').on('click', function() {
        if (deleteRecordId) {
            $.ajax({
                url: `/records/${deleteRecordId}`,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $(`#record-${deleteRecordId}`).remove();
                    hideDeleteModal();
                    alert(response.message || 'Record deleted successfully');
                },
                error: function(xhr) {
                    alert(xhr.responseJSON.message || 'An error occurred while deleting the record.');
                }
            });
        }
    });
</script>
@endsection
