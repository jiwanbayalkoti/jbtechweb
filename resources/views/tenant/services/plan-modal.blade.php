<div class="modal fade" id="servicePlanModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title mb-0">Plans for <span id="servicePlanTitle"></span></h5>
                    <small class="text-muted">Create packages customers can buy or subscribe to.</small>
                </div>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-7">
                        <table class="table table-sm table-striped" id="servicePlanTable">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Price</th>
                                    <th>Cycle</th>
                                    <th>Status</th>
                                    <th width="90">Actions</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                    <div class="col-lg-5">
                        <h6 class="plan-form-title">Add Plan</h6>
                        <form id="servicePlanForm" method="POST">
                            @csrf
                            <div class="form-group">
                                <label>Plan Name *</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Description</label>
                                <textarea name="description" class="form-control" rows="2"></textarea>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Price *</label>
                                    <input type="number" name="price" class="form-control" min="0" step="0.01" value="0" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Billing Cycle *</label>
                                    <select name="billing_cycle" class="form-control" required>
                                        <option value="one_time">One Time</option>
                                        <option value="monthly">Monthly</option>
                                        <option value="yearly">Yearly</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Features <small class="text-muted">(one per line)</small></label>
                                <textarea name="features" class="form-control" rows="4" placeholder="Feature one&#10;Feature two"></textarea>
                            </div>
                            <div class="form-row align-items-end">
                                <div class="form-group col-md-6">
                                    <label>Sort Order</label>
                                    <input type="number" name="sort_order" class="form-control" value="0" min="0">
                                </div>
                                <div class="form-group col-md-6">
                                    <div class="custom-control custom-checkbox mb-2">
                                        <input type="checkbox" name="is_active" value="1" class="custom-control-input" id="servicePlanActive" checked>
                                        <label class="custom-control-label" for="servicePlanActive">Active</label>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Save Plan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
