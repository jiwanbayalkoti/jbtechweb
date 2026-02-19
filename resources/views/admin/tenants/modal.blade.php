<div class="modal fade" id="tenantModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="tenantForm" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Tenant</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Company Name *</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Company Email *</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Phone</label>
                                <input type="text" name="phone" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6" id="planFields">
                            <div class="form-group">
                                <label>Plan *</label>
                                <select name="plan_id" class="form-control" required>
                                    @foreach($plans ?? [] as $plan)
                                    <option value="{{ $plan->id }}">{{ $plan->name }} - ${{ $plan->monthly_price }}/mo</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Address</label>
                        <textarea name="address" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="form-group d-none" id="tenantActiveField">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" name="is_active" value="1" class="custom-control-input" id="tenantIsActive">
                            <label class="custom-control-label" for="tenantIsActive">Active</label>
                        </div>
                    </div>
                    <hr>
                    <h6>Admin User</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Admin Name *</label>
                                <input type="text" name="admin_name" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Admin Email *</label>
                                <input type="email" name="admin_email" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Password *</label>
                                <input type="password" name="admin_password" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Confirm Password *</label>
                                <input type="password" name="admin_password_confirmation" class="form-control" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Tenant</button>
                </div>
            </form>
        </div>
    </div>
</div>
