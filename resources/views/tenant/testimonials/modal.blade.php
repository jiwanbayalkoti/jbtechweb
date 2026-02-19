<div class="modal fade" id="testimonialModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="testimonialForm" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Testimonial</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Client Name *</label>
                                <input type="text" name="client_name" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Client Title</label>
                                <input type="text" name="client_title" class="form-control" placeholder="CEO, Manager">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Company</label>
                        <input type="text" name="client_company" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Testimonial Content *</label>
                        <textarea name="content" class="form-control rich-editor" rows="4" required></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Rating (1-5)</label>
                                <input type="number" name="rating" class="form-control" value="5" min="1" max="5">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Sort Order</label>
                                <input type="number" name="sort_order" class="form-control" value="0">
                            </div>
                        </div>
                    </div>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" name="is_active" value="1" class="custom-control-input" id="tActive" checked>
                        <label class="custom-control-label" for="tActive">Active</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
