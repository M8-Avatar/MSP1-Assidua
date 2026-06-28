<section>
    <header class="mb-4">
        <h2 style="font-size:1rem;font-weight:700;color:#1B3A4B">Modifier le mot de passe</h2>
        <p style="font-size:.875rem;color:#7A92A3">Utilisez un mot de passe long et aléatoire.</p>
    </header>

    @if (session('status') === 'password-updated')
        <div class="alert alert-success py-2 mb-3" style="font-size:.875rem">Mot de passe modifié.</div>
    @endif

    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label" style="font-size:.8125rem;font-weight:600;color:#1B3A4B">Mot de passe actuel</label>
            <input type="password" name="current_password"
                   class="form-control @error('current_password', 'updatePassword') is-invalid @enderror"
                   autocomplete="current-password">
            <x-input-error :messages="$errors->updatePassword->get('current_password')" />
        </div>

        <div class="mb-3">
            <label class="form-label" style="font-size:.8125rem;font-weight:600;color:#1B3A4B">Nouveau mot de passe</label>
            <input type="password" name="password"
                   class="form-control @error('password', 'updatePassword') is-invalid @enderror"
                   autocomplete="new-password">
            <x-input-error :messages="$errors->updatePassword->get('password')" />
        </div>

        <div class="mb-4">
            <label class="form-label" style="font-size:.8125rem;font-weight:600;color:#1B3A4B">Confirmer</label>
            <input type="password" name="password_confirmation"
                   class="form-control" autocomplete="new-password">
        </div>

        <button type="submit" class="btn btn-primary" style="padding:.6rem 1.5rem">Enregistrer</button>
    </form>
</section>