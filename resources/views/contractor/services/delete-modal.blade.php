<div class="modal fade" id="contractor-delete-modal" tabindex="-1" aria-labelledby="contractor-delete-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="post" id="contractor-delete-form">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h2 class="modal-title h5" id="contractor-delete-title">{{ __('labels.contractor_services_delete_title') }}</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('labels.contractor_services_delete_cancel') }}"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0" id="contractor-delete-text"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('labels.contractor_services_delete_cancel') }}</button>
                    <button type="submit" class="btn btn-danger">{{ __('labels.contractor_services_delete_confirm') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@once
    @push('scripts')
        <script>
            document.getElementById('contractor-delete-modal')?.addEventListener('show.bs.modal', function (event) {
                var origem = event.relatedTarget;
                if (!origem) {
                    return;
                }
                document.getElementById('contractor-delete-form').action = origem.getAttribute('data-delete-action');
                document.getElementById('contractor-delete-text').textContent = origem.getAttribute('data-delete-name');
            });
        </script>
    @endpush
@endonce
