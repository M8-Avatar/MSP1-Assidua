<section>
    <header class="mb-4">
        <h2 style="font-size:1rem;font-weight:700;color:#1B3A4B">Informations du profil</h2>
        <p style="font-size:.875rem;color:#7A92A3">Mettez à jour votre nom et votre adresse email.</p>
    </header>

    @if ($errors->any())
        <div class="alert alert-danger py-2 mb-3" style="font-size:.875rem">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    @if (session('status') === 'profile-updated')
        <div class="alert alert-success py-2 mb-3" style="font-size:.875rem">Profil mis à jour.</div>
    @endif

    <form method="POST" action="{{ route('profile.update') }}">
        @csrf
        @method('PATCH')

        <div class="mb-3">
            <label class="form-label" style="font-size:.8125rem;font-weight:600;color:#1B3A4B">Nom</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}"
                   class="form-control @error('name') is-invalid @enderror" required autofocus>
            <x-input-error :messages="$errors->get('name')" />
        </div>

        <div class="mb-4">
            <label class="form-label" style="font-size:.8125rem;font-weight:600;color:#1B3A4B">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}"
                   class="form-control @error('email') is-invalid @enderror" required>
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <button type="submit" class="btn btn-primary" style="padding:.6rem 1.5rem">Enregistrer</button>
    </form>
</section>