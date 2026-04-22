@extends('admin.layouts.app')

@section('title', 'Dashboard Admin | E-Benin')
@section('page_eyebrow', 'Cockpit')
@section('page_title', 'Tableau de bord')
@section('page_subtitle', 'Vision centrale sur les comptes, les blogs, les contenus et les revenus')

@section('content')
    @php
        $lineWidth = 520;
        $lineHeight = 220;
        $pointCount = max(1, $monthlyRevenue->count() - 1);
        $revenueLinePoints = $monthlyRevenue->values()->map(function ($item, $index) use ($lineWidth, $lineHeight, $pointCount, $maxRevenue) {
            $x = $pointCount > 0 ? round(($index / $pointCount) * $lineWidth, 2) : 0;
            $y = round($lineHeight - (($item['value'] / $maxRevenue) * ($lineHeight - 26)) - 12, 2);

            return $x . ',' . $y;
        })->implode(' ');
        $revenueAreaPoints = '0,' . $lineHeight . ' ' . $revenueLinePoints . ' ' . $lineWidth . ',' . $lineHeight;
    @endphp

    <section class="section-stack">
        <div class="metrics-grid">
            <article class="metric-card">
                <div class="metric-card__head">
                    <div>
                        <span class="metric-card__eyebrow">Utilisateurs</span>
                        <strong>{{ number_format($kpis['users_total']) }}</strong>
                        <p>{{ number_format($kpis['users_active']) }} comptes actifs</p>
                    </div>
                    <div class="metric-card__icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M20 21a8 8 0 0 0-16 0" />
                            <circle cx="12" cy="8" r="4" />
                        </svg>
                    </div>
                </div>
                <div class="metric-card__trend {{ $kpiChanges['users_total']['direction'] === 'up' ? 'is-up' : 'is-down' }}">
                    <span>{{ $kpiChanges['users_total']['direction'] === 'up' ? '+' : '-' }}{{ $kpiChanges['users_total']['percent'] }}%</span>
                    <span>vs mois precedent</span>
                </div>
            </article>

            <article class="metric-card">
                <div class="metric-card__head">
                    <div>
                        <span class="metric-card__eyebrow">Blogs</span>
                        <strong>{{ number_format($kpis['blogs_total']) }}</strong>
                        <p>{{ number_format($kpis['blogs_active']) }} blogs visibles</p>
                    </div>
                    <div class="metric-card__icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M4 19h16" />
                            <path d="M6 19V7l6-3 6 3v12" />
                            <path d="M9 10h6M9 13h6" />
                        </svg>
                    </div>
                </div>
                <div class="metric-card__trend {{ $kpiChanges['blogs_total']['direction'] === 'up' ? 'is-up' : 'is-down' }}">
                    <span>{{ $kpiChanges['blogs_total']['direction'] === 'up' ? '+' : '-' }}{{ $kpiChanges['blogs_total']['percent'] }}%</span>
                    <span>nouvelles structures</span>
                </div>
            </article>

            <article class="metric-card">
                <div class="metric-card__head">
                    <div>
                        <span class="metric-card__eyebrow">Production</span>
                        <strong>{{ number_format($kpis['posts_total']) }}</strong>
                        <p>{{ number_format($kpis['posts_hidden']) }} contenus a surveiller</p>
                    </div>
                    <div class="metric-card__icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M5 5h14v14H5z" />
                            <path d="M8 9h8M8 12h8M8 15h5" />
                        </svg>
                    </div>
                </div>
                <div class="metric-card__trend {{ $kpiChanges['posts_total']['direction'] === 'up' ? 'is-up' : 'is-down' }}">
                    <span>{{ $kpiChanges['posts_total']['direction'] === 'up' ? '+' : '-' }}{{ $kpiChanges['posts_total']['percent'] }}%</span>
                    <span>volume editorial</span>
                </div>
            </article>

            <article class="metric-card">
                <div class="metric-card__head">
                    <div>
                        <span class="metric-card__eyebrow">Revenus encaisses</span>
                        <strong>{{ number_format($kpis['payments_revenue'], 0, ',', ' ') }} F</strong>
                        <p>{{ number_format($kpis['payments_total']) }} transactions historisees</p>
                    </div>
                    <div class="metric-card__icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M12 1v22M17 5.5a4 4 0 0 0-4-2.5H10a3 3 0 0 0 0 6h4a3 3 0 1 1 0 6h-3a4 4 0 0 1-4-2.5" />
                        </svg>
                    </div>
                </div>
                <div class="metric-card__trend {{ $kpiChanges['payments_revenue']['direction'] === 'up' ? 'is-up' : 'is-down' }}">
                    <span>{{ $kpiChanges['payments_revenue']['direction'] === 'up' ? '+' : '-' }}{{ $kpiChanges['payments_revenue']['percent'] }}%</span>
                    <span>recette mensuelle</span>
                </div>
            </article>
        </div>

        <div class="dashboard-hero">
            <article class="hero-card hero-card--story">
                <div class="hero-card__eyebrow">Pilotage E-Benin</div>
                <h2>Un cockpit editorial, commercial et operationnel.</h2>
                <p>
                    Le backoffice centralise la vie du reseau: suivi des blogueurs, activite des structures,
                    moderation des contenus et encaissement des abonnements. L'objectif est d'aller vite sans perdre
                    la visibilite sur ce qui merite une intervention humaine.
                </p>

                <div class="hero-card__actions">
                    <a href="{{ route('admin.posts.index') }}" class="primary-btn">Ouvrir la moderation</a>
                    <a href="{{ route('admin.payments.index') }}" class="ghost-btn">Voir la facturation</a>
                </div>

                <div class="hero-insights">
                    @foreach($heroInsights as $insight)
                        <div class="insight-chip">
                            <span>{{ $insight['label'] }}</span>
                            <strong>{{ $insight['value'] }}</strong>
                            <small>{{ $insight['meta'] }}</small>
                        </div>
                    @endforeach
                </div>
            </article>

            <article class="hero-card hero-card--panel">
                <h3>Activite immediate</h3>
                <p class="soft-subtitle">Les derniers encaissements et publications pour garder le tempo de la plateforme.</p>

                <div class="mini-feed">
                    @forelse($latestTransactions->take(3) as $transaction)
                        <div class="mini-feed__item">
                            <div>
                                <strong>{{ $transaction->organization->organization_name ?? 'Organisation' }}</strong>
                                <span>{{ strtoupper($transaction->source ?? 'n/a') }} • {{ optional($transaction->paid_at ?? $transaction->created_at)->format('d/m/Y H:i') }}</span>
                            </div>
                            <span class="badge {{ $transaction->status === 'paid' ? 'green' : ($transaction->status === 'pending' ? 'orange' : 'red') }}">
                                {{ number_format($transaction->amount, 0, ',', ' ') }} F
                            </span>
                        </div>
                    @empty
                        <div class="empty-state">Aucun paiement recent a afficher.</div>
                    @endforelse

                    @forelse($latestPosts->take(2) as $post)
                        <div class="mini-feed__item">
                            <div>
                                <strong>{{ $post->libelle }}</strong>
                                <span>{{ $post->user->organization->organization_name ?? 'Sans blog' }}</span>
                            </div>
                            <span class="badge blue">{{ optional($post->created_at)->diffForHumans() }}</span>
                        </div>
                    @empty
                    @endforelse
                </div>
            </article>
        </div>

        <div class="chart-grid chart-grid--dashboard">
            <article class="chart-card chart-card--dark">
                <div class="chart-card__header">
                    <div>
                        <h3>Activite du reseau</h3>
                        <p class="soft-muted">Volume de publications observe sur les 6 derniers mois.</p>
                    </div>
                    <span class="badge blue">{{ number_format($kpis['posts_total']) }} posts</span>
                </div>

                <div class="bar-chart">
                    @foreach($monthlyRevenue as $item)
                        <div class="bar-chart__item">
                            <div class="bar-chart__value">{{ number_format($monthlyPosts[$loop->index]['value'] ?? 0) }}</div>
                            <div class="bar-chart__bar bar-chart__bar--soft" style="height: {{ max(12, round((($monthlyPosts[$loop->index]['value'] ?? 0) / $maxPosts) * 100)) }}%;"></div>
                            <div class="bar-chart__label">{{ $item['label'] }}</div>
                        </div>
                    @endforeach
                </div>

                <div class="mini-kpis">
                    <div class="mini-kpis__item">
                        <span>Utilisateurs</span>
                        <strong>{{ number_format($kpis['users_total']) }}</strong>
                    </div>
                    <div class="mini-kpis__item">
                        <span>Blogs</span>
                        <strong>{{ number_format($kpis['blogs_total']) }}</strong>
                    </div>
                    <div class="mini-kpis__item">
                        <span>Revenus</span>
                        <strong>{{ number_format($kpis['payments_revenue'], 0, ',', ' ') }} F</strong>
                    </div>
                    <div class="mini-kpis__item">
                        <span>Alerte</span>
                        <strong>{{ number_format($kpis['expiring_soon']) }}</strong>
                    </div>
                </div>
            </article>

            <article class="chart-card chart-card--line">
                <div class="chart-card__header">
                    <div>
                        <h3>Apercu des revenus</h3>
                        <p class="soft-muted">{{ $kpis['expiring_soon'] }} abonnements arrivent a echeance sous 7 jours.</p>
                    </div>
                    <span class="badge orange">{{ $kpiChanges['payments_revenue']['percent'] }}% d evolution</span>
                </div>

                <div class="line-chart">
                    <svg viewBox="0 0 {{ $lineWidth }} {{ $lineHeight }}" preserveAspectRatio="none" aria-hidden="true">
                        <defs>
                            <linearGradient id="revenueAreaFill" x1="0" y1="0" x2="0" y2="1">
                                <stop offset="0%" stop-color="#56b9ff" stop-opacity="0.28" />
                                <stop offset="100%" stop-color="#56b9ff" stop-opacity="0.03" />
                            </linearGradient>
                            <linearGradient id="revenueLineStroke" x1="0" y1="0" x2="1" y2="0">
                                <stop offset="0%" stop-color="#56b9ff" />
                                <stop offset="100%" stop-color="#003f7f" />
                            </linearGradient>
                        </defs>
                        @foreach(range(0, 4) as $gridRow)
                            <line x1="0" y1="{{ 20 + ($gridRow * 45) }}" x2="{{ $lineWidth }}" y2="{{ 20 + ($gridRow * 45) }}"></line>
                        @endforeach
                        <polygon points="{{ $revenueAreaPoints }}"></polygon>
                        <polyline points="{{ $revenueLinePoints }}"></polyline>
                        @foreach($monthlyRevenue as $item)
                            @php
                                $x = $pointCount > 0 ? round(($loop->index / $pointCount) * $lineWidth, 2) : 0;
                                $y = round($lineHeight - (($item['value'] / $maxRevenue) * ($lineHeight - 26)) - 12, 2);
                            @endphp
                            <circle cx="{{ $x }}" cy="{{ $y }}" r="5"></circle>
                        @endforeach
                    </svg>
                </div>

                <div class="line-chart__labels">
                    @foreach($monthlyRevenue as $item)
                        <span>{{ $item['label'] }}</span>
                    @endforeach
                </div>
            </article>
        </div>

        <div class="dashboard-bottom">
            <div class="section-stack stack-reset-top">
                <article class="watch-card">
                    <h3>Abonnements a surveiller</h3>
                    <p class="soft-subtitle">Les organisations qui doivent etre traitees rapidement avant rupture de service.</p>

                    <div class="watch-stack">
                        @forelse($expiringSubscriptions as $subscription)
                            <div class="watch-item">
                                <div class="watch-item__icon">{{ strtoupper(substr($subscription->organization->organization_name ?? 'B', 0, 1)) }}</div>
                                <div class="watch-item__copy">
                                    <strong>{{ $subscription->organization->organization_name ?? 'Blog' }}</strong>
                                    <span>{{ optional($subscription->expires_at)->format('d/m/Y H:i') ?: 'Sans date' }}</span>
                                </div>
                                <span class="badge {{ $subscription->days_left > 7 ? 'green' : ($subscription->days_left > 0 ? 'orange' : 'red') }}">
                                    {{ $subscription->days_left > 0 ? $subscription->days_left . ' jours' : 'Expire' }}
                                </span>
                            </div>
                        @empty
                            <div class="empty-state">Aucun abonnement sensible pour le moment.</div>
                        @endforelse
                    </div>
                </article>

                <article class="watch-card">
                    <h3>Moderation en attente</h3>
                    <p class="soft-subtitle">Les derniers contenus caches ou rejetes pour reprise editoriale rapide.</p>

                    <div class="watch-stack">
                        @forelse($flaggedPosts as $post)
                            <div class="watch-item">
                                <div class="watch-item__icon">{{ strtoupper(substr($post->libelle, 0, 1)) }}</div>
                                <div class="watch-item__copy">
                                    <strong>{{ $post->libelle }}</strong>
                                    <span>{{ $post->user->organization->organization_name ?? 'Sans blog' }}</span>
                                </div>
                                <span class="badge {{ $post->editorial_status === 'hidden' ? 'orange' : 'red' }}">
                                    {{ $post->editorial_status }}
                                </span>
                            </div>
                        @empty
                            <div class="empty-state">Aucun contenu signale actuellement.</div>
                        @endforelse
                    </div>
                </article>
            </div>

            <article class="activity-card">
                <h3>Activite recente</h3>
                <p class="soft-subtitle">Chronologie simplifiee des paiements, contenus et alertes d'echeance.</p>

                <div class="activity-list">
                    @forelse($activityFeed as $item)
                        <div class="activity-item">
                            <div class="activity-item__icon">{{ strtoupper(substr($item['type'], 0, 1)) }}</div>
                            <div class="activity-item__copy">
                                <strong>{{ $item['title'] }}</strong>
                                <span>{{ $item['meta'] }}</span>
                            </div>
                            <div class="activity-item__time">{{ $item['time'] }}</div>
                        </div>
                    @empty
                        <div class="empty-state">Aucune activite recente a remonter.</div>
                    @endforelse
                </div>
            </article>
        </div>
    </section>
@endsection
