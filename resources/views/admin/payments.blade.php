@extends('admin.layouts.app')

@section('title', 'Paiements | Admin E-Benin')
@section('page_eyebrow', 'Facturation')
@section('page_title', 'Paiements')
@section('page_subtitle', 'Suivi des encaissements Kkiapay et des paiements saisis manuellement')
@section('search_placeholder', 'Rechercher un paiement ou une reference')

@section('page_tabs')
    <div class="page-tabs">
        <a class="page-tab {{ request()->routeIs('admin.payments.*') ? 'is-active' : '' }}" href="{{ route('admin.payments.index') }}">
            <span>Paiements</span>
            <span class="page-tab__count">{{ number_format($paymentStats['transactions_total']) }}</span>
        </a>
        <a class="page-tab {{ request()->routeIs('admin.subscriptions.*') ? 'is-active' : '' }}" href="{{ route('admin.subscriptions.index') }}">
            <span>Abonnements</span>
            <span class="page-tab__count">{{ number_format($subscriptionHealth['active']) }}</span>
        </a>
    </div>
@endsection

@section('content')
    <section class="section-stack">
        <div class="billing-grid">
            <article class="billing-hero">
                <div class="billing-hero__eyebrow">Facturation E-Benin</div>
                <div class="billing-hero__layout">
                    <div>
                        <h2>{{ number_format($paymentStats['paid_total'], 0, ',', ' ') }} F</h2>
                        <p>
                            Vue consolidee des encaissements. Les operations manuelles, les retours Kkiapay et l'etat des
                            abonnements sont reunis dans un seul poste de controle.
                        </p>
                    </div>

                    <div class="billing-visual-card">
                        <div class="billing-visual-card__chip"></div>
                        <div class="billing-visual-card__signal">
                            <span></span><span></span><span></span>
                        </div>
                        <div class="billing-visual-card__number">4562 1122 4594 7852</div>
                        <div class="billing-visual-card__meta">
                            <div>
                                <span>Titulaire</span>
                                <strong>E-Benin Admin</strong>
                            </div>
                            <div>
                                <span>Expire</span>
                                <strong>12/28</strong>
                            </div>
                        </div>
                        <div class="billing-visual-card__brand">
                            <span></span>
                            <span></span>
                        </div>
                    </div>
                </div>

                <div class="billing-totals">
                    <div class="billing-total">
                        <span>Kkiapay</span>
                        <strong>{{ number_format($paymentStats['auto_total'], 0, ',', ' ') }} F</strong>
                    </div>
                    <div class="billing-total">
                        <span>Manuel</span>
                        <strong>{{ number_format($paymentStats['manual_total'], 0, ',', ' ') }} F</strong>
                    </div>
                    <div class="billing-total">
                        <span>Abonnements actifs</span>
                        <strong>{{ number_format($subscriptionHealth['active']) }}</strong>
                    </div>
                    <div class="billing-total">
                        <span>Echeances proches</span>
                        <strong>{{ number_format($subscriptionHealth['expiring']) }}</strong>
                    </div>
                </div>
            </article>

            <article class="billing-card billing-card--mini">
                <div class="stat-tile__icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M12 1v22M17 5.5a4 4 0 0 0-4-2.5H10a3 3 0 0 0 0 6h4a3 3 0 1 1 0 6h-3a4 4 0 0 1-4-2.5" />
                    </svg>
                </div>
                <span class="stat-tile__eyebrow">Encaisse auto</span>
                <strong>{{ number_format($paymentStats['auto_total'], 0, ',', ' ') }} F</strong>
                <p>Kkiapay et callbacks historises</p>
            </article>

            <article class="billing-card billing-card--mini">
                <div class="stat-tile__icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <rect x="3" y="6" width="18" height="12" rx="2" />
                        <path d="M3 10h18" />
                    </svg>
                </div>
                <span class="stat-tile__eyebrow">Encaisse manuel</span>
                <strong>{{ number_format($paymentStats['manual_total'], 0, ',', ' ') }} F</strong>
                <p>Reglements saisis par l equipe</p>
            </article>

            <article class="billing-card">
                <div class="billing-card__header">
                    <div>
                        <h3>Factures recentes</h3>
                        <p class="soft-muted">Derniers mouvements remontes au backoffice.</p>
                    </div>
                </div>

                <div class="invoice-list">
                    @forelse($recentTransactions as $transaction)
                        <div class="invoice-item">
                            <div class="invoice-item__copy">
                                <strong>{{ $transaction->organization->organization_name ?? 'Organisation' }}</strong>
                                <span>{{ $transaction->reference ?? $transaction->token ?? 'Sans reference' }}</span>
                                <span>{{ optional($transaction->paid_at ?? $transaction->created_at)->format('d/m/Y H:i') }}</span>
                            </div>
                            <div>
                                <div class="invoice-item__amount">{{ number_format($transaction->amount, 0, ',', ' ') }} F</div>
                                <span class="badge {{ $transaction->status === 'paid' ? 'green' : ($transaction->status === 'pending' ? 'orange' : 'red') }}">
                                    {{ $transaction->status }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">Aucune transaction recente.</div>
                    @endforelse
                </div>
            </article>
        </div>

        <div class="split-grid">
            <article class="billing-card">
                <div class="billing-card__header">
                    <div>
                        <h3>Ajouter un paiement manuel</h3>
                        <p class="soft-muted">Enregistrer un reglement, attribuer des mois et tracer l action admin.</p>
                    </div>
                </div>

                <form class="form-grid compact" method="POST" action="{{ route('admin.payments.manual') }}">
                    @csrf
                    <div class="field">
                        <label>Blog</label>
                        <select name="organization_id" required>
                            @foreach($organizations as $organization)
                                <option value="{{ $organization->id }}">{{ $organization->organization_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field">
                        <label>Montant</label>
                        <input type="number" name="amount" min="0" step="0.01" required>
                    </div>
                    <div class="field">
                        <label>Mois accordes</label>
                        <input type="number" name="months_awarded" min="1" max="24" value="1" required>
                    </div>
                    <div class="field">
                        <label>Statut</label>
                        <select name="status" required>
                            <option value="paid">paid</option>
                            <option value="pending">pending</option>
                            <option value="failed">failed</option>
                        </select>
                    </div>
                    <div class="field">
                        <label>Methode</label>
                        <input type="text" name="payment_method" value="manual" required>
                    </div>
                    <div class="field">
                        <label>Reference</label>
                        <input type="text" name="reference" placeholder="Facture ou ticket">
                    </div>
                    <div class="field field--full">
                        <label>Notes</label>
                        <textarea name="notes" placeholder="Contexte ou commentaire interne"></textarea>
                    </div>
                    <div class="field field--full">
                        <button class="primary-btn" type="submit">Enregistrer le paiement</button>
                    </div>
                </form>
            </article>

            <article class="billing-card">
                <div class="billing-card__header">
                    <div>
                        <h3>Sante de la facturation</h3>
                        <p class="soft-muted">Etat rapide des paiements en attente et des abonnements a traiter.</p>
                    </div>
                </div>

                <div class="stack-list">
                    <div class="stack-item">
                        <div class="invoice-item__copy">
                            <strong>Paiements en attente</strong>
                            <span>{{ number_format($paymentStats['pending_total']) }} operation(s) a relancer</span>
                        </div>
                        <span class="badge orange">pending</span>
                    </div>
                    <div class="stack-item">
                        <div class="invoice-item__copy">
                            <strong>Paiements echoues</strong>
                            <span>{{ number_format($paymentStats['failed_total']) }} operation(s) a verifier</span>
                        </div>
                        <span class="badge red">failed</span>
                    </div>
                    <div class="stack-item">
                        <div class="invoice-item__copy">
                            <strong>Abonnements actifs</strong>
                            <span>{{ number_format($subscriptionHealth['active']) }} structures couvertes actuellement</span>
                        </div>
                        <span class="badge green">active</span>
                    </div>
                    <div class="stack-item">
                        <div class="invoice-item__copy">
                            <strong>Echeances proches</strong>
                            <span>{{ number_format($subscriptionHealth['expiring']) }} structure(s) a renouveler rapidement</span>
                        </div>
                        <span class="badge orange">due soon</span>
                    </div>
                    <div class="stack-item">
                        <div class="invoice-item__copy">
                            <strong>Abonnements expires</strong>
                            <span>{{ number_format($subscriptionHealth['expired']) }} structure(s) hors couverture</span>
                        </div>
                        <span class="badge red">expired</span>
                    </div>
                </div>

                <div class="billing-callout">
                    <strong>Lecture rapide</strong>
                    <p>La logique metier reste identique. Cette couche sert uniquement a rendre le suivi plus lisible et plus proche du style Soft UI.</p>
                </div>
            </article>
        </div>

        <section class="filter-card">
            <form class="filters" method="GET">
                <div class="field">
                    <label>Recherche</label>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Reference, token, blog">
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
                    <label>Statut</label>
                    <select name="status">
                        <option value="">Tous</option>
                        @foreach(['paid', 'pending', 'failed'] as $status)
                            <option value="{{ $status }}" @selected(request('status') === $status)>{{ $status }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label>Source</label>
                    <select name="source">
                        <option value="">Toutes</option>
                        @foreach(['kkiapay', 'manual'] as $source)
                            <option value="{{ $source }}" @selected(request('source') === $source)>{{ $source }}</option>
                        @endforeach
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
                    <h3>Historique des paiements</h3>
                    <p class="soft-muted">{{ $transactions->total() }} transaction(s) dans cette vue.</p>
                </div>
            </div>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Blog</th>
                            <th>Montant</th>
                            <th>Source</th>
                            <th>Statut</th>
                            <th>Reference</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $transaction)
                            <tr>
                                <td>
                                    <div class="table-person table-person--compact">
                                        <div class="table-avatar table-avatar--brand">{{ strtoupper(substr($transaction->organization->organization_name ?? 'OR', 0, 2)) }}</div>
                                        <div class="cell-title">
                                            <strong>{{ $transaction->organization->organization_name ?? 'Organisation' }}</strong>
                                            <span class="cell-meta">{{ $transaction->admin->name ?? 'Systeme' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ number_format($transaction->amount, 0, ',', ' ') }} F</td>
                                <td>{{ $transaction->source }}</td>
                                <td>
                                    <span class="badge {{ $transaction->status === 'paid' ? 'green' : ($transaction->status === 'pending' ? 'orange' : 'red') }}">
                                        {{ $transaction->status }}
                                    </span>
                                </td>
                                <td>{{ $transaction->reference ?? $transaction->token }}</td>
                                <td>{{ optional($transaction->paid_at ?? $transaction->created_at)->format('d/m/Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="empty-state">Aucune transaction.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="pagination-wrap">{{ $transactions->links('vendor.pagination.bootstrap-4') }}</div>
        </section>
    </section>
@endsection
