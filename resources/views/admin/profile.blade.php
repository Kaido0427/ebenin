@extends('admin.layouts.app')

@section('title', 'Profil Admin | E-Benin')
@section('page_eyebrow', 'Compte')
@section('page_title', 'Profil admin')
@section('page_subtitle', 'Informations du compte, preferences et acces rapides du cockpit')
@section('search_placeholder', 'Rechercher une preference')

@section('content')
    @php($admin = auth('admin')->user())

    <section class="section-stack">
        <div class="profile-cover">
            <div class="profile-cover__overlay">
                <div class="profile-cover__crumb">/ Profil</div>
                <h2>Profil</h2>
            </div>
        </div>

        <div class="profile-identity profile-identity--floating">
            <div class="profile-identity__main">
                <div class="profile-identity__avatar">{{ strtoupper(substr($admin->name, 0, 2)) }}</div>
                <div class="profile-identity__copy">
                    <strong>{{ $admin->name }}</strong>
                    <span>{{ str_replace('_', ' ', $admin->role) }} / Operateur</span>
                    <small>{{ $admin->email }}</small>
                </div>
            </div>

            <div class="profile-identity__actions">
                <a href="{{ route('admin.dashboard') }}" class="ghost-btn">Dashboard</a>
                <a href="{{ route('admin.payments.index') }}" class="ghost-btn">Facturation</a>
                <a href="{{ route('admin.posts.index') }}" class="ghost-btn">Moderation</a>
            </div>
        </div>

        <div class="profile-tabbar">
            <span class="profile-tabbar__item is-active">Application</span>
            <span class="profile-tabbar__item">Compte</span>
            <span class="profile-tabbar__item">Acces</span>
        </div>

        <div class="profile-grid">
            <article class="profile-card">
                <div class="profile-card__header">
                    <div>
                        <h3>Parametres du compte</h3>
                        <p class="soft-muted">Mettre a jour l identite de connexion et la preference de theme.</p>
                    </div>
                </div>

                <div class="profile-highlights">
                    <div class="profile-highlights__item">
                        <span>Role</span>
                        <strong>{{ str_replace('_', ' ', $admin->role) }}</strong>
                    </div>
                    <div class="profile-highlights__item">
                        <span>Theme</span>
                        <strong>{{ $admin->preferred_theme }}</strong>
                    </div>
                    <div class="profile-highlights__item">
                        <span>Derniere connexion</span>
                        <strong>{{ optional($admin->last_login_at)->format('d/m H:i') ?: 'Jamais' }}</strong>
                    </div>
                </div>

                <form class="form-grid compact" method="POST" action="{{ route('admin.profile.update') }}">
                    @csrf
                    @method('PUT')
                    <div class="field">
                        <label>Nom</label>
                        <input type="text" name="name" value="{{ old('name', $admin->name) }}" required>
                    </div>
                    <div class="field">
                        <label>E-mail</label>
                        <input type="email" name="email" value="{{ old('email', $admin->email) }}" required>
                    </div>
                    <div class="field">
                        <label>Theme par defaut</label>
                        <select name="preferred_theme" data-theme-input required>
                            <option value="light" @selected(old('preferred_theme', $admin->preferred_theme) === 'light')>light</option>
                            <option value="dark" @selected(old('preferred_theme', $admin->preferred_theme) === 'dark')>dark</option>
                        </select>
                    </div>
                    <div class="field">
                        <label>Nouveau mot de passe</label>
                        <input type="password" name="password" placeholder="Laisser vide pour conserver l actuel">
                    </div>
                    <div class="field">
                        <label>Confirmation</label>
                        <input type="password" name="password_confirmation" placeholder="Repeter le nouveau mot de passe">
                    </div>
                    <div class="field field--full">
                        <button class="primary-btn" type="submit">Enregistrer les modifications</button>
                    </div>
                </form>
            </article>

            <div class="section-stack stack-reset-top">
                <article class="profile-card">
                    <div class="profile-card__header">
                        <div>
                            <h3>Informations de profil</h3>
                            <p class="soft-muted">Informations utiles pour situer ce profil dans l organisation.</p>
                        </div>
                    </div>

                    <div class="stack-list">
                        <div class="stack-item">
                            <div class="invoice-item__copy">
                                <strong>Role</strong>
                                <span>{{ str_replace('_', ' ', $admin->role) }}</span>
                            </div>
                            <span class="badge blue">admin</span>
                        </div>
                        <div class="stack-item">
                            <div class="invoice-item__copy">
                                <strong>Derniere connexion</strong>
                                <span>{{ optional($admin->last_login_at)->format('d/m/Y H:i') ?: 'Jamais' }}</span>
                            </div>
                            <span class="badge {{ $admin->last_login_at ? 'green' : 'orange' }}">
                                {{ $admin->last_login_at ? 'recent' : 'pending' }}
                            </span>
                        </div>
                        <div class="stack-item">
                            <div class="invoice-item__copy">
                                <strong>Theme enregistre</strong>
                                <span>{{ $admin->preferred_theme }}</span>
                            </div>
                            <span class="badge blue">{{ $admin->preferred_theme }}</span>
                        </div>
                    </div>
                </article>

                <article class="profile-card">
                    <div class="profile-card__header">
                        <div>
                            <h3>Acces rapides</h3>
                            <p class="soft-muted">Raccourcis vers les modules qui demandent le plus d attention.</p>
                        </div>
                    </div>

                    <div class="quick-links">
                        <a href="{{ route('admin.users.index') }}" class="quick-link">
                            <div class="quick-link__icon">U</div>
                            <div class="invoice-item__copy">
                                <strong>Utilisateurs</strong>
                                <span>Surveiller les auteurs, l activation et les blogs rattaches.</span>
                            </div>
                        </a>
                        <a href="{{ route('admin.subscriptions.index') }}" class="quick-link">
                            <div class="quick-link__icon">S</div>
                            <div class="invoice-item__copy">
                                <strong>Abonnements</strong>
                                <span>Traiter les echeances et eviter les ruptures de service.</span>
                            </div>
                        </a>
                        <a href="{{ route('admin.payments.index') }}" class="quick-link">
                            <div class="quick-link__icon">F</div>
                            <div class="invoice-item__copy">
                                <strong>Facturation</strong>
                                <span>Consulter les encaissements manuels et automatiques.</span>
                            </div>
                        </a>
                    </div>
                </article>

                <article class="profile-card">
                    <div class="profile-card__header">
                        <div>
                            <h3>Rythme du cockpit</h3>
                            <p class="soft-muted">Petite lecture visuelle de l environnement de travail.</p>
                        </div>
                    </div>

                    <div class="profile-pulse">
                        <div class="profile-pulse__bar" style="height: 82%;"></div>
                        <div class="profile-pulse__bar" style="height: 44%;"></div>
                        <div class="profile-pulse__bar" style="height: 68%;"></div>
                        <div class="profile-pulse__bar" style="height: 32%;"></div>
                        <div class="profile-pulse__bar" style="height: 76%;"></div>
                        <div class="profile-pulse__bar" style="height: 58%;"></div>
                    </div>
                </article>
            </div>
        </div>
    </section>
@endsection
