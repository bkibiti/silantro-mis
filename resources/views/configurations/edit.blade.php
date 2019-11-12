<div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="heading"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('configurations.update')}}" method="post"  enctype="multipart/form-data">
                @csrf()
                @method("POST")
                <div class="modal-body">
                    <div class="form-group row" id="formfields">
                        <label for="code" class="col-md-4 col-form-label text-md-left" id="label"></label>
                        <div class="col-md-8" id="formInput">
                        <span id="valid-msg" class="hide"></span>
                        <span id="error-msg" class="text text-danger"></span>
                        </div>
                    </div>
                    <input type="hidden" name="setting_id" id="id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>


