@extends('admin.layouts.app')

@section('title', 'Abonnements | Admin E-Benin')
@section('page_eyebrow', 'Facturation')
@section('page_title', 'Abonnements')
@section('page_subtitle', 'Suivi des echeances et renouvellement rapide des structures')
@section('search_placeholder', 'Rechercher un blog ou une echeance')

@section('page_tabs')
    <div class="page-tabs">
        <a class="page-tab {{ request()->routeIs('admin.payments.*') ? 'is-active' : '' }}" href="{{ route('admin.payments.index') }}">
            <span>Paiements</span>
        </a>
        <a class="page-tab {{ request()->routeIs('admin.subscriptions.*') ? 'is-active' : '' }}" href="{{ route('admin.subscriptions.index') }}">
            <span>Abonnements</span>
            <span class="page-tab__count">{{ number_format($subscriptionStats['total']) }}</span>
        </a>
    </div>
@endsection

@section('content')
    @php($spotlightSubscriptions = collect($subscriptions->items())->take(5))

    <section class="section-stack">
        <div class="stat-strip">
            <article class="stat-tile">
                <div class="stat-tile__head">
                    <div>
                        <span class="stat-tile__eyebrow">Total abonnements</span>
                        <strong>{{ number_format($subscriptionStats['total']) }}</strong>
                        <p>Structures suivies</p>
                    </div>
                    <div class="stat-tile__icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M12 8v5l3 3" />
                            <circle cx="12" cy="12" r="9" />
                        </svg>
                    </div>
                </div>
            </article>
            <article class="stat-tile">
                <div class="stat-tile__head">
                    <div>
                        <span class="stat-tile__eyebrow">Actifs</span>
                        <strong>{{ number_format($subscriptionStats['active']) }}</strong>
                        <p>Couverture actuellement valide</p>
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
                        <span class="stat-tile__eyebrow">Echeances proches</span>
                        <strong>{{ number_format($subscriptionStats['expiring']) }}</strong>
                        <p>A traiter dans les 7 jours</p>
                    </div>
                    <div class="stat-tile__icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M12 8v5l3 3" />
                            <circle cx="12" cy="12" r="9" />
                        </svg>
                    </div>
                </div>
            </article>
            <article class="stat-tile">
                <div class="stat-tile__head">
                    <div>
                        <span class="stat-tile__eyebrow">Expires</span>
                        <strong>{{ number_format($subscriptionStats['expired']) }}</strong>
                        <p>Structures hors couverture</p>
                    </div>
                    <div class="stat-tile__icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M18 6L6 18M6 6l12 12" />
                        </svg>
                    </div>
                </div>
            </article>
        </div>

        <div class="split-grid">
            <article class="billing-card">
                <div class="billing-card__header">
                    <div>
                        <h3>Renouvellement pilote</h3>
                        <p class="soft-muted">
                            Ce panneau sert a prolonger rapidement une structure. Chaque renouvellement cree aussi
                            une transaction de trace cote admin afin de garder un historique coherent.
                        </p>
                    </div>
                </div>

                <div class="stack-list">
                    <div class="stack-item">
                        <div class="invoice-item__copy">
                            <strong>Cycle standard</strong>
                            <span>La base actuelle repose sur un plan `Blog Standard` avec renouvellement mensuel.</span>
                        </div>
                        <span class="badge blue">1 mois</span>
                    </div>
                    <div class="stack-item">
                        <div class="invoice-item__copy">
                            <strong>Trace legacy</strong>
                            <span>Le renouvellement met a jour la nouvelle table d abonnement et synchronise les champs historiques utilisateur.</span>
                        </div>
                        <span class="badge orange">sync</span>
                    </div>
                    <div class="stack-item">
                        <div class="invoice-item__copy">
                            <strong>Priorite du jour</strong>
                            <span>{{ number_format($subscriptionStats['expiring']) }} structure(s) doivent etre traitees rapidement.</span>
                        </div>
                        <span class="badge {{ $subscriptionStats['expiring'] > 0 ? 'red' : 'green' }}">
                            {{ $subscriptionStats['expiring'] > 0 ? 'urgent' : 'stable' }}
                        </span>
                    </div>
                </div>
            </article>

            <article class="billing-card">
                <div class="billing-card__header">
                    <div>
                        <h3>Structures a regarder</h3>
                        <p class="soft-muted">Echantillon des prochaines echeances visibles sur cette page.</p>
                    </div>
                </div>

                <div class="stack-list">
                    @forelse($spotlightSubscriptions as $subscription)
                        <div class="stack-item">
                            <div class="invoice-item__copy">
                                <strong>{{ $subscription->organization->organization_name ?? 'Organisation' }}</strong>
                                <span>{{ optional($subscription->expires_at)->format('d/m/Y H:i') ?: 'Sans date' }}</span>
                                <span>{{ $subscription->plan_name }}</span>
                            </div>
                            <span class="badge {{ $subscription->days_left > 7 ? 'green' : ($subscription->days_left > 0 ? 'orange' : 'red') }}">
                                {{ $subscription->days_left > 0 ? $subscription->days_left . ' jours' : 'Expire' }}
                            </span>
                        </div>
                    @empty
                        <div class="empty-state">Aucun abonnement a mettre en avant.</div>
                    @endforelse
                </div>
            </article>
        </div>

        <section class="table-card">
            <div class="table-card__header">
                <div>
                    <h3>Tableau des abonnements</h3>
                    <p class="soft-muted">{{ $subscriptions->total() }} ligne(s) accessibles dans cette vue.</p>
                </div>
            </div>

            <section class="filter-card">
                <form class="filters" method="GET">
                    <div class="field">
                        <label>Recherche</label>
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Blog, e-mail, plan">
                    </div>
                    <div class="field">
                        <label>Statut</label>
                        <select name="status">
                            <option value="">Tous</option>
                            @foreach(['active', 'paused', 'expired', 'cancelled'] as $status)
                                <option value="{{ $status }}" @selected(request('status') === $status)>{{ $status }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field field--submit-end">
                        <button class="primary-btn" type="submit">Filtrer</button>
                    </div>
                </form>
            </section>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Blog</th>
                            <th>Plan</th>
                            <th>Expiration</th>
                            <th>Statut</th>
                            <th>Renouveler</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($subscriptions as $subscription)
                            <tr>
                                <td>{{ $subscription->organization->organization_name ?? 'Organisation' }}</td>
                                <td>{{ $subscription->plan_name }}</td>
                                <td>{{ optional($subscription->expires_at)->format('d/m/Y H:i') }}</td>
                                <td>
                                    <span class="badge {{ $subscription->days_left > 7 ? 'green' : ($subscription->days_left > 0 ? 'orange' : 'red') }}">
                                        {{ $subscription->days_left > 0 ? $subscription->days_left . ' jours' : 'Expire' }}
                                    </span>
                                </td>
                                <td>
                                    <form class="inline-form" method="POST" action="{{ route('admin.subscriptions.renew', $subscription->organization) }}">
                                        @csrf
                                        <input type="number" name="months_awarded" min="1" max="24" value="1" placeholder="Mois">
                                        <input type="number" name="amount" min="0" step="0.01" placeholder="Montant">
                                        <input type="hidden" name="notes" value="Renouvellement rapide back-office">
                                        <button class="primary-btn" type="submit">Prolonger</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="empty-state">Aucun abonnement.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="pagination-wrap">{{ $subscriptions->links('vendor.pagination.bootstrap-4') }}</div>
        </section>
    </section>
@endsection
