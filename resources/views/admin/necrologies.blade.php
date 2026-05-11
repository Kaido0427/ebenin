@extends('admin.layouts.app')

@section('title', 'Necrologies | Admin E-Benin')
@section('page_eyebrow', 'Market')
@section('page_title', 'Necrologies')
@section('page_subtitle', 'Moderation des necrologies annonceurs')
@section('search_placeholder', 'Rechercher un defunt ou un annonceur')

@section('page_tabs')
    <div class="page-tabs">
        <a class="page-tab {{ request()->routeIs('admin.annonces.*') ? 'is-active' : '' }}" href="{{ url('/admin/annonces') }}">
            <span>Annonces</span>
        </a>
        <a class="page-tab {{ request()->routeIs('admin.necrologies.*') ? 'is-active' : '' }}" href="{{ url('/admin/necrologies') }}">
            <span>Necrologies</span>
            <span class="page-tab__count">{{ number_format($necrologieStats['total']) }}</span>
        </a>
    </div>
@endsection

@section('content')
    <section class="section-stack">
        <div class="stat-strip">
            <article class="stat-tile"><div class="stat-tile__head"><div><span class="stat-tile__eyebrow">Total</span><strong>{{ number_format($necrologieStats['total']) }}</strong><p>Necrologies en base</p></div></div></article>
            <article class="stat-tile"><div class="stat-tile__head"><div><span class="stat-tile__eyebrow">Actives</span><strong>{{ number_format($necrologieStats['active']) }}</strong><p>Visibles publiquement</p></div></div></article>
            <article class="stat-tile"><div class="stat-tile__head"><div><span class="stat-tile__eyebrow">En attente</span><strong>{{ number_format($necrologieStats['pending']) }}</strong><p>A valider</p></div></div></article>
            <article class="stat-tile"><div class="stat-tile__head"><div><span class="stat-tile__eyebrow">Rejetees</span><strong>{{ number_format($necrologieStats['rejected']) }}</strong><p>Moderation negative</p></div></div></article>
        </div>

        <section class="filter-card">
            <form class="filters" method="GET">
                <div class="field"><label>Recherche</label><input type="text" name="q" value="{{ request('q') }}" placeholder="Nom du defunt ou annonceur"></div>
                <div class="field">
                    <label>Statut</label>
                    <select name="status">
                        <option value="">Tous</option>
                        @foreach(['pending', 'active', 'rejected'] as $status)
                            <option value="{{ $status }}" @selected(request('status') === $status)>{{ $status }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field field--submit-end"><button class="primary-btn" type="submit">Filtrer</button></div>
            </form>
        </section>

        <section class="table-card">
            <div class="table-card__header">
                <div><h3>Tableau des necrologies</h3><p class="soft-muted">{{ $necrologies->total() }} necrologie(s) dans cette vue.</p></div>
            </div>
            <div class="table-wrap">
                <table>
                    <thead><tr><th>Defunt</th><th>Annonceur</th><th>Periode</th><th>Moderation</th></tr></thead>
                    <tbody>
                        @forelse($necrologies as $necrologie)
                            <tr>
                                <td>
                                    <div class="cell-title">
                                        <strong>{{ $necrologie->nom_defunt }}</strong>
                                        <span class="cell-meta">{{ \Illuminate\Support\Str::limit(strip_tags((string) $necrologie->message), 90) }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="cell-title">
                                        <strong>{{ $necrologie->advertiser->company_name ?? $necrologie->advertiser->name ?? 'Annonceur' }}</strong>
                                        <span class="cell-meta">{{ $necrologie->advertiser->email ?? 'n/a' }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="cell-title">
                                        <strong>{{ optional($necrologie->date_naissance)->format('d/m/Y') ?: 'n/a' }} - {{ optional($necrologie->date_deces)->format('d/m/Y') ?: 'n/a' }}</strong>
                                        <span class="badge {{ $necrologie->status === 'active' ? 'green' : ($necrologie->status === 'pending' ? 'orange' : 'red') }}">{{ $necrologie->status }}</span>
                                    </div>
                                </td>
                                <td>
                                    <form class="inline-form" method="POST" action="{{ url('/admin/necrologies/' . $necrologie->id . '/status') }}">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status">
                                            @foreach(['pending', 'active', 'rejected'] as $status)
                                                <option value="{{ $status }}" @selected($necrologie->status === $status)>{{ $status }}</option>
                                            @endforeach
                                        </select>
                                        <button class="primary-btn" type="submit">Appliquer</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="empty-state">Aucune necrologie.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="pagination-wrap">{{ $necrologies->links('vendor.pagination.bootstrap-4') }}</div>
        </section>
    </section>
@endsection

