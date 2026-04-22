@extends('admin.layouts.app')

@section('title', 'Admins | Admin E-Benin')
@section('page_eyebrow', 'Administration')
@section('page_title', 'Comptes admin')
@section('page_subtitle', 'Gestion des profils back-office et des roles operateurs')
@section('search_placeholder', 'Rechercher un admin ou un role')

@section('content')
    <section class="section-stack">
        <div class="stat-strip">
            <article class="stat-tile">
                <div class="stat-tile__head">
                    <div>
                        <span class="stat-tile__eyebrow">Total comptes</span>
                        <strong>{{ number_format($adminStats['total']) }}</strong>
                        <p>Acces back-office</p>
                    </div>
                    <div class="stat-tile__icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M20 21a8 8 0 0 0-16 0" />
                            <circle cx="12" cy="8" r="4" />
                        </svg>
                    </div>
                </div>
            </article>
            <article class="stat-tile">
                <div class="stat-tile__head">
                    <div>
                        <span class="stat-tile__eyebrow">Actifs</span>
                        <strong>{{ number_format($adminStats['active']) }}</strong>
                        <p>Comptes disponibles</p>
                    </div>
                    <div class="stat-tile__icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M5 12l4 4L19 6" />
                        </svg>
                    </div>
                </div>
            </article>
            <article class="stat-tile">
                <div class="stat-tile__head">
                    <div>
                        <span class="stat-tile__eyebrow">Super admins</span>
                        <strong>{{ number_format($adminStats['super_admins']) }}</strong>
                        <p>Profils a privileges etendus</p>
                    </div>
                    <div class="stat-tile__icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M12 3l7 4v5c0 5-3.5 8-7 9-3.5-1-7-4-7-9V7z" />
                        </svg>
                    </div>
                </div>
            </article>
            <article class="stat-tile">
                <div class="stat-tile__head">
                    <div>
                        <span class="stat-tile__eyebrow">Connexions recentes</span>
                        <strong>{{ number_format($adminStats['connected_recently']) }}</strong>
                        <p>Sur les 7 derniers jours</p>
                    </div>
                    <div class="stat-tile__icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M12 8v5l3 3" />
                            <circle cx="12" cy="12" r="9" />
                        </svg>
                    </div>
                </div>
            </article>
        </div>

        <section class="filter-card">
            <form class="filters" method="GET">
                <div class="field">
                    <label>Recherche</label>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Nom, e-mail, role">
                </div>
                <div class="field">
                    <label>Role</label>
                    <select name="role">
                        <option value="">Tous</option>
                        @foreach(['super_admin', 'editorial_admin', 'billing_support'] as $role)
                            <option value="{{ $role }}" @selected(request('role') === $role)>{{ $role }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label>Statut</label>
                    <select name="status">
                        <option value="">Tous</option>
                        <option value="active" @selected(request('status') === 'active')>Actifs</option>
                        <option value="inactive" @selected(request('status') === 'inactive')>Inactifs</option>
                    </select>
                </div>
                <div class="field field--submit-end">
                    <button class="primary-btn" type="submit">Filtrer</button>
                </div>
            </form>
        </section>

        <div class="admin-two-col">
            <section class="billing-card">
                <div class="billing-card__header">
                    <div>
                        <h3>Creer un admin</h3>
                        <p class="soft-muted">Ajouter un profil back-office sans exposer d inscription publique.</p>
                    </div>
                </div>

                <form class="form-grid compact" method="POST" action="{{ route('admin.admins.store') }}">
                    @csrf
                    <div class="field">
                        <label>Nom</label>
                        <input type="text" name="name" required>
                    </div>
                    <div class="field">
                        <label>E-mail</label>
                        <input type="email" name="email" required>
                    </div>
                    <div class="field">
                        <label>Role</label>
                        <select name="role" required>
                            <option value="super_admin">super_admin</option>
                            <option value="editorial_admin">editorial_admin</option>
                            <option value="billing_support">billing_support</option>
                        </select>
                    </div>
                    <div class="field field--full">
                        <label>Mot de passe</label>
                        <input type="password" name="password" required>
                    </div>
                    <div class="field field--full">
                        <button class="primary-btn" type="submit">Creer le compte admin</button>
                    </div>
                </form>
            </section>

            <section class="table-card">
                <div class="table-card__header">
                    <div>
                        <h3>Equipe admin</h3>
                        <p class="soft-muted">{{ $admins->total() }} profil(s) dans cette vue.</p>
                    </div>
                </div>

                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Admin</th>
                                <th>Role</th>
                                <th>Theme</th>
                                <th>Derniere connexion</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($admins as $admin)
                                <tr>
                                    <td>
                                        <div class="cell-title">
                                            <strong>{{ $admin->name }}</strong>
                                            <span class="cell-meta">{{ $admin->email }}</span>
                                        </div>
                                    </td>
                                    <td>{{ $admin->role }}</td>
                                    <td>{{ $admin->preferred_theme }}</td>
                                    <td>{{ optional($admin->last_login_at)->format('d/m/Y H:i') ?: 'Jamais' }}</td>
                                    <td>
                                        <form method="POST" action="{{ route('admin.admins.toggle', $admin) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button class="{{ $admin->is_active ? 'danger-btn' : 'success-btn' }}" type="submit">
                                                {{ $admin->is_active ? 'Desactiver' : 'Reactiver' }}
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="empty-state">Aucun admin.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="pagination-wrap">{{ $admins->links('vendor.pagination.bootstrap-4') }}</div>
            </section>
        </div>
    </section>
@endsection
