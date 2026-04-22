@extends('admin.layouts.app')

@section('title', 'Utilisateurs | Admin E-Benin')
@section('page_eyebrow', 'Tables')
@section('page_title', 'Auteurs')
@section('page_subtitle', 'Gestion des comptes auteurs et suspension synchronisee avec leur structure')
@section('search_placeholder', 'Rechercher un auteur ou un e-mail')

@section('page_tabs')
    <div class="page-tabs">
        <a class="page-tab {{ request()->routeIs('admin.users.*') ? 'is-active' : '' }}" href="{{ route('admin.users.index') }}">
            <span>Auteurs</span>
            <span class="page-tab__count">{{ number_format($userStats['total']) }}</span>
        </a>
        <a class="page-tab {{ request()->routeIs('admin.blogs.*') ? 'is-active' : '' }}" href="{{ route('admin.blogs.index') }}">
            <span>Blogs</span>
        </a>
        <a class="page-tab {{ request()->routeIs('admin.posts.*') ? 'is-active' : '' }}" href="{{ route('admin.posts.index') }}">
            <span>Posts</span>
        </a>
    </div>
@endsection

@section('content')
    <section class="section-stack">
        <div class="stat-strip">
            <article class="stat-tile">
                <div class="stat-tile__head">
                    <div>
                        <span class="stat-tile__eyebrow">Total comptes</span>
                        <strong>{{ number_format($userStats['total']) }}</strong>
                        <p>Base auteurs globale</p>
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
                        <strong>{{ number_format($userStats['active']) }}</strong>
                        <p>Comptes operationnels</p>
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
                        <span class="stat-tile__eyebrow">Suspendus</span>
                        <strong>{{ number_format($userStats['inactive']) }}</strong>
                        <p>Acces actuellement bloques</p>
                    </div>
                    <div class="stat-tile__icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M18 6L6 18M6 6l12 12" />
                        </svg>
                    </div>
                </div>
            </article>
            <article class="stat-tile">
                <div class="stat-tile__head">
                    <div>
                        <span class="stat-tile__eyebrow">Rattaches a un blog</span>
                        <strong>{{ number_format($userStats['with_blog']) }}</strong>
                        <p>Comptes relies a une structure</p>
                    </div>
                    <div class="stat-tile__icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M4 19h16" />
                            <path d="M6 19V7l6-3 6 3v12" />
                        </svg>
                    </div>
                </div>
            </article>
        </div>

        <section class="filter-card">
            <form class="filters" method="GET">
                <div class="field">
                    <label>Recherche</label>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Nom, e-mail, telephone">
                </div>
                <div class="field">
                    <label>Statut</label>
                    <select name="status">
                        <option value="">Tous</option>
                        <option value="active" @selected(request('status') === 'active')>Actifs</option>
                        <option value="inactive" @selected(request('status') === 'inactive')>Suspendus</option>
                    </select>
                </div>
                <div class="field field--submit-end">
                    <button class="primary-btn" type="submit">Filtrer</button>
                </div>
            </form>
        </section>

        <section class="table-card">
            <div class="table-card__header">
                <div>
                    <h3>Tableau des auteurs</h3>
                    <p class="soft-muted">{{ $users->total() }} comptes trouves selon les filtres en cours.</p>
                </div>
            </div>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Auteur</th>
                            <th>Blog</th>
                            <th>Abonnement</th>
                            <th>Posts</th>
                            <th>Statut</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>
                                    <div class="table-person">
                                        <div class="table-avatar">{{ strtoupper(substr($user->name, 0, 2)) }}</div>
                                        <div class="cell-title">
                                            <strong>{{ $user->name }}</strong>
                                            <span class="cell-meta">{{ $user->email }}</span>
                                            <span class="cell-meta">{{ $user->phone ?: 'Telephone non renseigne' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="cell-title">
                                        <strong>{{ $user->organization->organization_name ?? 'Sans blog' }}</strong>
                                        <span class="cell-meta">{{ $user->organization->subdomain ?? 'Aucun sous-domaine' }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="cell-title">
                                        <strong>{{ optional($user->subscription_expiry)->format('d/m/Y') ?: 'Non configure' }}</strong>
                                        <span class="cell-meta">{{ $user->subscription_days_left ? $user->subscription_days_left . ' jours restants' : 'Abonnement inactif' }}</span>
                                    </div>
                                </td>
                                <td>{{ number_format($user->posts_count) }}</td>
                                <td>
                                    <div class="status-stack">
                                        <span class="badge {{ $user->is_active ? 'green' : 'red' }}">{{ $user->is_active ? 'Actif' : 'Suspendu' }}</span>
                                        @if($user->is_subscription_active)
                                            <span class="badge blue">Abonne</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <form class="inline-form" method="POST" action="{{ route('admin.users.toggle', $user) }}">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="reason" value="Action admin back-office">
                                        <button class="{{ $user->is_active ? 'danger-btn' : 'success-btn' }}" type="submit">
                                            {{ $user->is_active ? 'Suspendre' : 'Reactiver' }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="empty-state">Aucun utilisateur a afficher.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="pagination-wrap">{{ $users->links('vendor.pagination.bootstrap-4') }}</div>
        </section>
    </section>
@endsection
