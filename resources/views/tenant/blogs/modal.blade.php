<div class="modal fade" id="blogModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="blogForm" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Blog Post</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Title *</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Slug (leave blank for auto)</label>
                        <input type="text" name="slug" class="form-control" placeholder="url-friendly-slug">
                    </div>
                    <div class="form-group">
                        <label>Content</label>
                        <textarea name="content" class="form-control rich-editor" rows="8"></textarea>
                    </div>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" name="is_published" value="1" class="custom-control-input" id="blogPublished">
                        <label class="custom-control-label" for="blogPublished">Published</label>
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
