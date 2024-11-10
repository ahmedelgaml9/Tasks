<div class="modal fade" id="modal_{{ $record->id}}" tabindex="-1" role="dialog"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-title-{{ $record->id  }}">Delete </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                   <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you  sure to delete this
            </div>
            <div class="modal-footer">
                <form action="{{route('records.destroy', $record->id )}}"  method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-danger">
                        Confirm
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>