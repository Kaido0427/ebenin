@extends('public.layouts.app')
@section('title', 'En savoir plus sur les blogs | E-Benin')
@section('meta_description', 'Tout ce qu\'il faut savoir pour créer votre blog sur E-Benin : fonctionnalités, avantages, tarifs et comment démarrer.')

@push('head')
<style>
.info-hero { background: linear-gradient(135deg, var(--primary) 0%, #0057b3 100%); color: #fff; padding: 60px 0; text-align: center; }
.info-hero h1 { font-size: 2.2rem; font-weight: 800; margin-bottom: 12px; }
.info-hero p { font-size: 1rem; opacity: .88; max-width: 600px; margin: 0 auto; }
.info-section { padding: 56px 0; }
.info-section + .info-section { border-top: 1px solid var(--border); }
.info-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 24px; margin-top: 32px; }
.info-card { background: var(--white); border: 1px solid var(--border); border-radius: var(--radius); padding: 28px 24px; }
.info-card__icon { font-size: 2rem; margin-bottom: 12px; }
.info-card__title { font-size: 1rem; font-weight: 700; color: var(--dark); margin-bottom: 8px; }
.info-card__text { font-size: .88rem; color: var(--mid); line-height: 1.7; }
.info-pricing { background: var(--bg); border-radius: var(--radius); padding: 36px; text-align: center; margin-top: 32px; border: 2px solid var(--primary); }
.info-pricing__price { font-size: 3rem; font-weight: 800; color: var(--primary); }
.info-pricing__label { font-size: .93rem; color: var(--muted); margin-bottom: 20px; }
.info-steps { counter-reset: steps; margin-top: 32px; display: flex; flex-direction: column; gap: 20px; }
.info-step { display: flex; gap: 20px; align-items: flex-start; }
.info-step__num { counter-increment: steps; background: var(--primary); color: #fff; width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; flex-shrink: 0; font-size: .93rem; }
.info-step__body h3 { font-size: .97rem; font-weight: 700; margin-bottom: 4px; color: var(--dark); }
.info-step__body p { font-size: .85rem; color: var(--mid); margin: 0; line-height: 1.6; }
.info-cta { text-align: center; padding: 48px 0; }
.info-cta h2 { font-size: 1.6rem; font-weight: 800; margin-bottom: 12px; }
.info-cta p { color: var(--mid); margin-bottom: 24px; }
</style>
@endpush

@section('content')
<div class="info-hero">
    <div class="container">
        <h1>Créez votre blog sur E-Benin</h1>
        <p>Rejoignez le réseau des rédactions et blogueurs du Bénin. Publiez vos articles, construisez votre audience et développez votre présence éditoriale.</p>
    </div>
</div>

<div class="container">
    <section class="info-section">
        <h2 class="section-title" style="margin-bottom:0">Pourquoi choisir E-Benin ?</h2>
        <div class="info-grid">
            <div class="info-card">
                <div class="info-card__icon">📰</div>
                <div class="info-card__title">Votre propre sous-domaine</div>
                <div class="info-card__text">Votre blog dispose d'une adresse personnalisée : <strong>votrenomorg.e-benin.com</strong> — professionnel et mémorisable.</div>
            </div>
            <div class="info-card">
                <div class="info-card__icon">📊</div>
                <div class="info-card__title">Dashboard complet</div>
                <div class="info-card__text">Publiez des articles, ajoutez des images/vidéos, gérez vos rubriques et suivez l'activité de votre blog depuis un seul espace.</div>
            </div>
            <div class="info-card">
                <div class="info-card__icon">🌍</div>
                <div class="info-card__title">Visibilité nationale</div>
                <div class="info-card__text">Vos articles apparaissent sur le portail principal E-Benin, accessible par des milliers de lecteurs béninois chaque jour.</div>
            </div>
            <div class="info-card">
                <div class="info-card__icon">🎨</div>
                <div class="info-card__title">Design professionnel</div>
                <div class="info-card__text">Front moderne avec votre logo, vos couleurs. Optimisé pour le mobile et les réseaux sociaux.</div>
            </div>
            <div class="info-card">
                <div class="info-card__icon">💬</div>
                <div class="info-card__title">Commentaires & engagement</div>
                <div class="info-card__text">Vos lecteurs peuvent commenter vos articles. Créez une communauté autour de votre contenu.</div>
            </div>
            <div class="info-card">
                <div class="info-card__icon">🔗</div>
                <div class="info-card__title">Réseaux sociaux intégrés</div>
                <div class="info-card__text">Ajoutez vos liens Facebook, Twitter, Instagram, WhatsApp directement sur votre blog.</div>
            </div>
        </div>
    </section>

    <section class="info-section">
        <h2 class="section-title" style="margin-bottom:0">Comment démarrer ?</h2>
        <div class="info-steps">
            <div class="info-step">
                <div class="info-step__num">1</div>
                <div class="info-step__body">
                    <h3>Créez votre compte</h3>
                    <p>Renseignez vos informations personnelles et le nom de votre organisation. Inscription gratuite, sans carte bancaire.</p>
                </div>
            </div>
            <div class="info-step">
                <div class="info-step__num">2</div>
                <div class="info-step__body">
                    <h3>Personnalisez votre blog</h3>
                    <p>Ajoutez votre logo, une biographie, vos réseaux sociaux et définissez vos rubriques éditoriales.</p>
                </div>
            </div>
            <div class="info-step">
                <div class="info-step__num">3</div>
                <div class="info-step__body">
                    <h3>Publiez vos premiers articles</h3>
                    <p>Depuis votre dashboard, rédigez et publiez vos articles avec images et vidéos. Ils sont immédiatement visibles.</p>
                </div>
            </div>
            <div class="info-step">
                <div class="info-step__num">4</div>
                <div class="info-step__body">
                    <h3>Abonnez-vous pour continuer</h3>
                    <p>Après votre essai, un abonnement mensuel vous permet de continuer à publier et accéder à toutes les fonctionnalités.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="info-section">
        <h2 class="section-title" style="margin-bottom:0">Tarifs</h2>
        <div class="info-pricing">
            <div class="info-pricing__price">Gratuit</div>
            <div class="info-pricing__label">pour démarrer · 90 jours d'essai inclus</div>
            <p style="color:var(--mid);font-size:.9rem;margin-bottom:20px;">Après la période d'essai, un abonnement est requis pour continuer à publier.</p>
            <a href="{{ route('userRegister') }}" class="btn btn--primary" style="font-size:1rem;padding:14px 32px;">Créer mon blog gratuitement →</a>
        </div>
    </section>

    <section class="info-cta">
        <h2>Prêt à lancer votre média ?</h2>
        <p>Rejoignez les rédactions et blogueurs qui font l'information au Bénin.</p>
        <div style="display:flex;gap:14px;justify-content:center;flex-wrap:wrap;">
            <a href="{{ route('userRegister') }}" class="btn btn--primary" style="font-size:.97rem;padding:13px 28px;">S'inscrire gratuitement</a>
            <a href="{{ route('bloger.login') }}" class="btn btn--outline" style="font-size:.97rem;padding:13px 28px;">Se connecter</a>
        </div>
    </section>
</div>
@endsection
