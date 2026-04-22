@extends('admin.layouts.app')

@section('title', 'Blogs | Admin E-Benin')
@section('page_eyebrow', 'Tables')
@section('page_title', 'Blogs')
@section('page_subtitle', 'Activation, visibilite publique et etat d abonnement des structures')
@section('search_placeholder', 'Rechercher un blog ou un sous-domaine')

@section('page_tabs')
    <div class="page-tabs">
        <a class="page-tab {{ request()->routeIs('admin.users.*') ? 'is-active' : '' }}" href="{{ route('admin.users.index') }}">
            <span>Auteurs</span>
        </a>
        <a class="page-tab {{ request()->routeIs('admin.blogs.*') ? 'is-active' : '' }}" href="{{ route('admin.blogs.index') }}">
            <span>Blogs</span>
            <span class="page-tab__count">{{ number_format($blogStats['total']) }}</span>
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
                        <span class="stat-tile__eyebrow">Structures</span>
                        <strong>{{ number_format($blogStats['total']) }}</strong>
                        <p>Blogs enregistres</p>
                    </div>
                    <div class="stat-tile__icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M4 19h16" />
                            <path d="M6 19V7l6-3 6 3v12" />
                        </svg>
                    </div>
                </div>
            </article>
            <article class="stat-tile">
                <div class="stat-tile__head">
                    <div>
                        <span class="stat-tile__eyebrow">Actifs</span>
                        <strong>{{ number_format($blogStats['active']) }}</strong>
                        <p>Blogs actuellement operationnels</p>
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
                        <span class="stat-tile__eyebrow">Publics</span>
                        <strong>{{ number_format($blogStats['public']) }}</strong>
                        <p>Visibles par les lecteurs</p>
                    </div>
                    <div class="stat-tile__icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <circle cx="12" cy="12" r="8" />
                            <path d="M2 12h20M12 2a15 15 0 0 1 0 20M12 2a15 15 0 0 0 0 20" />
                        </svg>
                    </div>
                </div>
            </article>
            <article class="stat-tile">
                <div class="stat-tile__head">
                    <div>
                        <span class="stat-tile__eyebrow">A risque</span>
                        <strong>{{ number_format($blogStats['expiring']) }}</strong>
                        <p>Echeances sous 7 jours</p>
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
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Nom, e-mail, sous-domaine">
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
                    <h3>Tableau des blogs</h3>
                    <p class="soft-muted">{{ $blogs->total() }} structures disponibles dans cette vue.</p>
                </div>
            </div>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Blog</th>
                            <th>Proprietaires</th>
                            <th>Transactions</th>
                            <th>Abonnement</th>
                            <th>Visibilite</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($blogs as $blog)
                            <tr>
                                <td>
                                    <div class="table-person">
                                        <div class="table-avatar table-avatar--brand">{{ strtoupper(substr($blog->organization_name, 0, 2)) }}</div>
                                        <div class="cell-title">
                                            <strong>{{ $blog->organization_name }}</strong>
                                            <span class="cell-meta">{{ $blog->organization_email }}</span>
                                            <span class="cell-meta">{{ $blog->subdomain ?: 'Sans sous-domaine' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ number_format($blog->owner_users_count) }}</td>
                                <td>{{ number_format($blog->transactions_count) }}</td>
                                <td>
                                    @if($blog->subscription)
                                        <div class="cell-title">
                                            <strong>{{ optional($blog->subscription->expires_at)->format('d/m/Y') ?: 'Sans date' }}</strong>
                                            <span class="cell-meta">{{ $blog->subscription->days_left > 0 ? $blog->subscription->days_left . ' jours restants' : 'Expire' }}</span>
                                        </div>
                                    @else
                                        <span class="badge orange">Non configure</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="status-stack">
                                        <span class="badge {{ $blog->is_active ? 'green' : 'red' }}">{{ $blog->is_active ? 'Actif' : 'Suspendu' }}</span>
                                        <span class="badge {{ $blog->is_publicly_visible ? 'blue' : 'orange' }}">{{ $blog->is_publicly_visible ? 'Public' : 'Masque' }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="row-actions">
                                        <form method="POST" action="{{ route('admin.blogs.toggle', $blog) }}">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="reason" value="Action admin blog">
                                            <button class="{{ $blog->is_active ? 'danger-btn' : 'success-btn' }}" type="submit">
                                                {{ $blog->is_active ? 'Suspendre' : 'Reactiver' }}
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.blogs.visibility', $blog) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button class="ghost-btn" type="submit">
                                                {{ $blog->is_publicly_visible ? 'Masquer' : 'Rendre public' }}
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="empty-state">Aucun blog a afficher.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="pagination-wrap">{{ $blogs->links('vendor.pagination.bootstrap-4') }}</div>
        </section>
    </section>
@endsection
