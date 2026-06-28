<section>
    <header class="mb-4">
        <h2 style="font-size:1rem;font-weight:700;color:#E53935">Supprimer le compte</h2>
        <p style="font-size:.875rem;color:#7A92A3">
            Une fois supprimé, toutes les données seront définitivement effacées.
        </p>
    </header>

    <button type="button" class="btn btn-danger btn-sm"
            data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
        Supprimer mon compte
    </button>

    {{-- Bootstrap 5 modal (no Alpine) --}}
    <div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title" style="font-size:1rem;font-weight:700;color:#1B3A4B">
                        Confirmer la suppression
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p style="font-size:.875rem;color:#64788A">
                        Êtes-vous sûr ? Cette action est irréversible. Entrez votre mot de passe pour confirmer.
                    </p>
                    <form id="deleteAccountForm" method="POST" action="{{ route('profile.destroy') }}">
                        @csrf
                        @method('DELETE')
                        <div class="mb-3">
                            <label class="form-label" style="font-size:.8125rem;font-weight:600;color:#1B3A4B">
                                Mot de passe
                            </label>
                            <input type="password" name="password"
                                   class="form-control @error('password', 'userDeletion') is-invalid @enderror"
                                   placeholder="••••••••" required>
                            <x-input-error :messages="$errors->userDeletion->get('password')" />
                        </div>
                    </form>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">
                        Annuler
                    </button>
                    <button type="submit" form="deleteAccountForm" class="btn btn-danger btn-sm">
                        Supprimer définitivement
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>