<div class="modal fade" id="createTypeModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Create Modal</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="ajaxForm">
            <div class="form-group">
                <label for="type_title">Type title</label>
                <input id="type_title" class="form-control create-input" type="text" name="type_title" />
                <span class="invalid-feedback input_type_title">
                </span>
            </div>
            <div class="form-group">
                <label for="type_description">Type Description</label>
                <input id="type_description" class="form-control create-input" type="text" name="type_description" />
                <span class="invalid-feedback input_type_description">
                </span>
            </div>
        </div> 
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            {{-- <button id="close-type-create-modal" type="button" class="btn btn-secondary">Close with Javascript</button> --}}
            <button id="submit-ajax-form" type="button" class="btn btn-primary">Save changes</button>
        </div>
      </div>
    </div>
  </div>
  
  <div class="modal fade" id="editTypeModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Edit Modal</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="ajaxForm">
            <input type="hidden" id="edit_type_id" name="type_id" />
            <div class="form-group">
                <label for="type_title">Type title</label>
                <input id="edit_type_title" class="form-control" type="text" name="type_title" />
            </div>
            <div class="form-group">
                <label for="type_description">Type Description</label>
                <input id="edit_type_description" class="form-control" type="text" name="type_description" />
            </div>
        </div> 
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
           
            <button id="update-type" type="button" class="btn btn-primary">Update</button>
        </div>
      </div>
    </div>
  </div>
  
  <div class="modal fade" id="showTypeModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Show type</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="show-type-id">
            </div>  
            <div class="show-type-title">
            </div>
            <div class="show-type-description">
            </div>    
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  
