@extends('admin.layouts.app')

@section('title', 'Annonces | Admin E-Benin')
@section('page_eyebrow', 'Market')
@section('page_title', 'Annonces')
@section('page_subtitle', 'Moderation des annonces et suivi des paiements annonceurs')
@section('search_placeholder', 'Rechercher une annonce ou un annonceur')

@section('page_tabs')
    <div class="page-tabs">
        <a class="page-tab {{ request()->routeIs('admin.annonces.*') ? 'is-active' : '' }}" href="{{ url('/admin/annonces') }}">
            <span>Annonces</span>
            <span class="page-tab__count">{{ number_format($annonceStats['total']) }}</span>
        </a>
        <a class="page-tab {{ request()->routeIs('admin.necrologies.*') ? 'is-active' : '' }}" href="{{ url('/admin/necrologies') }}">
            <span>Necrologies</span>
        </a>
    </div>
@endsection

@section('content')
    <section class="section-stack">
        <div class="stat-strip">
            <article class="stat-tile"><div class="stat-tile__head"><div><span class="stat-tile__eyebrow">Total</span><strong>{{ number_format($annonceStats['total']) }}</strong><p>Annonces en base</p></div></div></article>
            <article class="stat-tile"><div class="stat-tile__head"><div><span class="stat-tile__eyebrow">Actives</span><strong>{{ number_format($annonceStats['active']) }}</strong><p>Visibles publiquement</p></div></div></article>
            <article class="stat-tile"><div class="stat-tile__head"><div><span class="stat-tile__eyebrow">En attente</span><strong>{{ number_format($annonceStats['pending']) }}</strong><p>A valider</p></div></div></article>
            <article class="stat-tile"><div class="stat-tile__head"><div><span class="stat-tile__eyebrow">Payees</span><strong>{{ number_format($annonceStats['paid']) }}</strong><p>Paiement annonce valide</p></div></div></article>
        </div>

        <section class="filter-card">
            <form class="filters" method="GET">
                <div class="field"><label>Recherche</label><input type="text" name="q" value="{{ request('q') }}" placeholder="Titre ou annonceur"></div>
                <div class="field">
                    <label>Statut publication</label>
                    <select name="status">
                        <option value="">Tous</option>
                        @foreach(['pending', 'active', 'rejected'] as $status)
                            <option value="{{ $status }}" @selected(request('status') === $status)>{{ $status }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label>Statut paiement</label>
                    <select name="payment_status">
                        <option value="">Tous</option>
                        @foreach(['pending', 'paid', 'failed'] as $paymentStatus)
                            <option value="{{ $paymentStatus }}" @selected(request('payment_status') === $paymentStatus)>{{ $paymentStatus }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field field--submit-end"><button class="primary-btn" type="submit">Filtrer</button></div>
            </form>
        </section>

        <section class="table-card">
            <div class="table-card__header">
                <div><h3>Tableau des annonces</h3><p class="soft-muted">{{ $annonces->total() }} annonce(s) dans cette vue.</p></div>
            </div>
            <div class="table-wrap">
                <table>
                    <thead><tr><th>Annonce</th><th>Annonceur</th><th>Paiement</th><th>Moderation</th></tr></thead>
                    <tbody>
                        @forelse($annonces as $annonce)
                            <tr>
                                <td>
                                    <div class="cell-title">
                                        <strong>{{ $annonce->title }}</strong>
                                        <span class="cell-meta">{{ $annonce->category_label ?? $annonce->category }}</span>
                                        <span class="cell-meta">{{ optional($annonce->created_at)->format('d/m/Y H:i') }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="cell-title">
                                        <strong>{{ $annonce->advertiser->company_name ?? $annonce->advertiser->name ?? 'Annonceur' }}</strong>
                                        <span class="cell-meta">{{ $annonce->advertiser->email ?? 'n/a' }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge {{ $annonce->payment_status === 'paid' ? 'green' : ($annonce->payment_status === 'pending' ? 'orange' : 'red') }}">{{ $annonce->payment_status }}</span>
                                    <div class="cell-meta">{{ number_format($annonce->price ?? \App\Models\Annonce::PRICE_PER_ANNONCE, 0, ',', ' ') }} F</div>
                                </td>
                                <td>
                                    <form class="inline-form" method="POST" action="{{ url('/admin/annonces/' . $annonce->id . '/status') }}">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status">
                                            @foreach(['pending', 'active', 'rejected'] as $status)
                                                <option value="{{ $status }}" @selected($annonce->status === $status)>{{ $status }}</option>
                                            @endforeach
                                        </select>
                                        <button class="primary-btn" type="submit">Appliquer</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="empty-state">Aucune annonce.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="pagination-wrap">{{ $annonces->links('vendor.pagination.bootstrap-4') }}</div>
        </section>
    </section>
@endsection

