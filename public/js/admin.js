/**
 * JB Tech Nepal - Admin JS
 * Modal forms, confirm/alert dialogs
 */

const JBAdmin = {
    modal: function(modalId) {
        return $('#' + modalId);
    },

    showModal: function(modalId, options = {}) {
        const $modal = this.modal(modalId);
        if ($modal.length) {
            $modal.modal('show');
        }
    },

    hideModal: function(modalId) {
        const $modal = this.modal(modalId);
        if ($modal.length) {
            $modal.modal('hide');
        }
    },

    confirm: function(options) {
        const defaults = {
            title: 'Confirm',
            message: 'Are you sure?',
            confirmText: 'Yes',
            cancelText: 'Cancel',
            confirmClass: 'btn-primary',
            onConfirm: function() {},
            onCancel: function() {}
        };
        const opts = $.extend({}, defaults, options);
        const $modal = $('#confirmModal');
        if ($modal.length === 0) {
            const html = `
                <div class="modal fade" id="confirmModal" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="confirmModalTitle"></h5>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body" id="confirmModalBody"></div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="confirmModalCancel"></button>
                                <button type="button" class="btn" id="confirmModalConfirm"></button>
                            </div>
                        </div>
                    </div>
                </div>`;
            $('body').append(html);
        }
        $('#confirmModalTitle').text(opts.title);
        $('#confirmModalBody').html(opts.message);
        $('#confirmModalCancel').text(opts.cancelText);
        $('#confirmModalConfirm').text(opts.confirmText).removeClass().addClass('btn ' + opts.confirmClass);
        $('#confirmModalConfirm').off('click').on('click', function() {
            $('#confirmModal').modal('hide');
            opts.onConfirm();
        });
        $('#confirmModal').modal('show');
    },

    alert: function(options) {
        const defaults = {
            title: 'Notice',
            message: '',
            type: 'info',
            okText: 'OK',
            onOk: function() {}
        };
        const opts = $.extend({}, defaults, options);
        const typeClass = { success: 'btn-success', danger: 'btn-danger', warning: 'btn-warning', info: 'btn-info' }[opts.type] || 'btn-primary';
        const $modal = $('#alertModal');
        if ($modal.length === 0) {
            const html = `
                <div class="modal fade" id="alertModal" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="alertModalTitle"></h5>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body" id="alertModalBody"></div>
                            <div class="modal-footer">
                                <button type="button" class="btn" id="alertModalOk"></button>
                            </div>
                        </div>
                    </div>
                </div>`;
            $('body').append(html);
        }
        $('#alertModalTitle').text(opts.title);
        $('#alertModalBody').html(opts.message);
        $('#alertModalOk').text(opts.okText).removeClass().addClass('btn ' + typeClass);
        $('#alertModalOk').off('click').on('click', function() {
            $('#alertModal').modal('hide');
            opts.onOk();
        });
        $('#alertModal').modal('show');
    },

    slugify: function(str) {
        if (!str) return '';
        return String(str).toLowerCase().trim()
            .replace(/\s+/g, '-')
            .replace(/[^\w\-]+/g, '')
            .replace(/\-\-+/g, '-')
            .replace(/^-+/, '')
            .replace(/-+$/, '');
    },

    autoSlugFromTitle: function(titleSelector, slugSelector) {
        const self = this;
        $(document).on('input keyup', titleSelector, function() {
            const slug = self.slugify($(this).val());
            const $slug = $(slugSelector);
            if (!$slug.data('manual')) $slug.val(slug);
        });
        $(document).on('input', slugSelector, function() {
            $(this).data('manual', $(this).val().length > 0);
        });
    }
};

window.JBAdmin = JBAdmin;
