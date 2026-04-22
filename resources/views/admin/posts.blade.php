@extends('admin.layouts.app')

@section('title', 'Posts | Admin E-Benin')
@section('page_eyebrow', 'Tables')
@section('page_title', 'Posts')
@section('page_subtitle', 'Moderation, mise en avant et breaking news des contenus')
@section('search_placeholder', 'Rechercher un titre ou une structure')

@section('page_tabs')
    <div class="page-tabs">
        <a class="page-tab {{ request()->routeIs('admin.users.*') ? 'is-active' : '' }}" href="{{ route('admin.users.index') }}">
            <span>Auteurs</span>
        </a>
        <a class="page-tab {{ request()->routeIs('admin.blogs.*') ? 'is-active' : '' }}" href="{{ route('admin.blogs.index') }}">
            <span>Blogs</span>
        </a>
        <a class="page-tab {{ request()->routeIs('admin.posts.*') ? 'is-active' : '' }}" href="{{ route('admin.posts.index') }}">
            <span>Posts</span>
            <span class="page-tab__count">{{ number_format($postStats['total']) }}</span>
        </a>
    </div>
@endsection

@section('content')
    <section class="section-stack">
        <div class="stat-strip">
            <article class="stat-tile">
                <div class="stat-tile__head">
                    <div>
                        <span class="stat-tile__eyebrow">Total contenus</span>
                        <strong>{{ number_format($postStats['total']) }}</strong>
                        <p>Posts connus de la plateforme</p>
                    </div>
                    <div class="stat-tile__icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M5 5h14v14H5z" />
                            <path d="M8 9h8M8 12h8M8 15h5" />
                        </svg>
                    </div>
                </div>
            </article>
            <article class="stat-tile">
                <div class="stat-tile__head">
                    <div>
                        <span class="stat-tile__eyebrow">Publies</span>
                        <strong>{{ number_format($postStats['published']) }}</strong>
                        <p>Visibles cote public</p>
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
                        <span class="stat-tile__eyebrow">Caches</span>
                        <strong>{{ number_format($postStats['hidden']) }}</strong>
                        <p>En attente de reprise editoriale</p>
                    </div>
                    <div class="stat-tile__icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M3 12s3.5-6 9-6 9 6 9 6-3.5 6-9 6-9-6-9-6z" />
                            <path d="M3 3l18 18" />
                        </svg>
                    </div>
                </div>
            </article>
            <article class="stat-tile">
                <div class="stat-tile__head">
                    <div>
                        <span class="stat-tile__eyebrow">Breaking</span>
                        <strong>{{ number_format($postStats['breaking']) }}</strong>
                        <p>Alertes prioritaires actives</p>
                    </div>
                    <div class="stat-tile__icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M13 2L4 14h7l-1 8 9-12h-7z" />
                        </svg>
                    </div>
                </div>
            </article>
        </div>

        <section class="filter-card">
            <form class="filters" method="GET">
                <div class="field">
                    <label>Recherche</label>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Titre du post">
                </div>
                <div class="field">
                    <label>Statut</label>
                    <select name="status">
                        <option value="">Tous</option>
                        @foreach(['published' => 'Publie', 'hidden' => 'Cache', 'rejected' => 'Rejete'] as $value => $label)
                            <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label>Blog</label>
                    <select name="organization_id">
                        <option value="">Tous</option>
                        @foreach($organizations as $organization)
                            <option value="{{ $organization->id }}" @selected((string) request('organization_id') === (string) $organization->id)>{{ $organization->organization_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label>Featured</label>
                    <select name="featured">
                        <option value="">Tous</option>
                        <option value="1" @selected(request('featured') === '1')>Oui</option>
                        <option value="0" @selected(request('featured') === '0')>Non</option>
                    </select>
                </div>
                <div class="field">
                    <label>Breaking</label>
                    <select name="breaking">
                        <option value="">Tous</option>
                        <option value="1" @selected(request('breaking') === '1')>Oui</option>
                        <option value="0" @selected(request('breaking') === '0')>Non</option>
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
                    <h3>Tableau editorial</h3>
                    <p class="soft-muted">{{ $posts->total() }} contenus remontes avec leurs controles admin.</p>
                </div>
            </div>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Post</th>
                            <th>Blog</th>
                            <th>Etat</th>
                            <th>Edition admin</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($posts as $post)
                            <tr>
                                <td>
                                    <div class="table-person">
                                        <div class="table-avatar table-avatar--news">{{ strtoupper(substr($post->libelle, 0, 2)) }}</div>
                                        <div class="cell-title">
                                            <strong>{{ $post->libelle }}</strong>
                                            <span class="cell-meta">{{ optional($post->created_at)->format('d/m/Y H:i') }}</span>
                                            <span class="cell-meta">{{ $post->rubriques->pluck('name')->filter()->join(', ') ?: 'Sans rubrique' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="table-person table-person--compact">
                                        <div class="table-avatar">{{ strtoupper(substr($post->user->name ?? 'AU', 0, 2)) }}</div>
                                        <div class="cell-title">
                                            <strong>{{ $post->user->organization->organization_name ?? 'Sans blog' }}</strong>
                                            <span class="cell-meta">{{ $post->user->name ?? 'Auteur inconnu' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="status-stack">
                                        <span class="badge {{ $post->editorial_status === 'published' ? 'green' : ($post->editorial_status === 'hidden' ? 'orange' : 'red') }}">
                                            {{ $post->editorial_status }}
                                        </span>
                                        @if($post->featured)
                                            <span class="badge blue">featured</span>
                                        @endif
                                        @if($post->is_breaking)
                                            <span class="badge pink">breaking</span>
                                        @endif
                                    </div>
                                    @if($post->editorial_note)
                                        <div class="cell-meta meta-note">{{ $post->editorial_note }}</div>
                                    @endif
                                </td>
                                <td>
                                    <form class="form-grid compact" method="POST" action="{{ route('admin.posts.editorial', $post) }}">
                                        @csrf
                                        @method('PATCH')
                                        <div class="field">
                                            <label>Statut</label>
                                            <select name="editorial_status">
                                                @foreach(['published', 'hidden', 'rejected'] as $status)
                                                    <option value="{{ $status }}" @selected($post->editorial_status === $status)>{{ $status }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="field">
                                            <label>Featured</label>
                                            <select name="featured">
                                                <option value="0" @selected(!$post->featured)>Normal</option>
                                                <option value="1" @selected((bool) $post->featured)>Oui</option>
                                            </select>
                                        </div>
                                        <div class="field">
                                            <label>Breaking</label>
                                            <select name="is_breaking">
                                                <option value="0" @selected(!$post->is_breaking)>Non</option>
                                                <option value="1" @selected((bool) $post->is_breaking)>Oui</option>
                                            </select>
                                        </div>
                                        <div class="field field--full">
                                            <label>Note editoriale</label>
                                            <input type="text" name="editorial_note" value="{{ $post->editorial_note }}" placeholder="Motif ou contexte admin">
                                        </div>
                                        <div class="field field--full">
                                            <button class="primary-btn" type="submit">Enregistrer</button>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="empty-state">Aucun post a afficher.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="pagination-wrap">{{ $posts->links('vendor.pagination.bootstrap-4') }}</div>
        </section>
    </section>
@endsection
