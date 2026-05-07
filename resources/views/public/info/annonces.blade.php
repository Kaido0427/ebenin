@extends('public.layouts.app')
@section('title', 'En savoir plus sur les annonces | E-Benin')
@section('meta_description', 'Publiez vos annonces sur E-Benin : emploi, immobilier, véhicules, services et bien plus. 10 000 FCFA par annonce.')

@push('head')
<style>
.info-hero { background: linear-gradient(135deg, var(--primary) 0%, #0057b3 100%); color: #fff; padding: 60px 0; text-align: center; }
.info-hero h1 { font-size: 2.2rem; font-weight: 800; margin-bottom: 12px; }
.info-hero p { font-size: 1rem; opacity: .88; max-width: 600px; margin: 0 auto; }
.info-section { padding: 56px 0; }
.info-section + .info-section { border-top: 1px solid var(--border); }
.info-cats { display: grid; grid-template-columns: repeat(auto-fill, minmax(170px, 1fr)); gap: 12px; margin-top: 24px; }
.info-cat { display: flex; align-items: center; gap: 10px; padding: 14px; background: var(--white); border: 1px solid var(--border); border-radius: var(--radius); font-size: .88rem; font-weight: 600; color: var(--dark); text-decoration: none; transition: all .2s; }
.info-cat:hover { border-color: var(--primary); color: var(--primary); transform: translateY(-1px); }
.info-cat__icon { font-size: 1.3rem; }
.info-pricing { background: var(--bg); border-radius: var(--radius); padding: 36px; text-align: center; margin-top: 32px; border: 2px solid var(--primary); }
.info-pricing__price { font-size: 3.2rem; font-weight: 800; color: var(--primary); }
.info-pricing__label { font-size: .93rem; color: var(--muted); margin-bottom: 20px; }
.info-steps { counter-reset: steps; margin-top: 32px; display: flex; flex-direction: column; gap: 20px; }
.info-step { display: flex; gap: 20px; align-items: flex-start; }
.info-step__num { counter-increment: steps; background: var(--primary); color: #fff; width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; flex-shrink: 0; font-size: .93rem; }
.info-step__body h3 { font-size: .97rem; font-weight: 700; margin-bottom: 4px; color: var(--dark); }
.info-step__body p { font-size: .85rem; color: var(--mid); margin: 0; line-height: 1.6; }
.info-cta { text-align: center; padding: 48px 0; }
</style>
@endpush

@section('content')
<div class="info-hero">
    <div class="container">
        <h1>Publiez une annonce sur E-Benin</h1>
        <p>Le marché des annonces du Bénin. Emploi, immobilier, véhicules, services et bien plus — touchez des milliers de Béninois chaque jour.</p>
    </div>
</div>

<div class="container">
    <section class="info-section">
        <h2 class="section-title" style="margin-bottom:0">Toutes les catégories disponibles</h2>
        <div class="info-cats">
            <div class="info-cat"><span class="info-cat__icon">🚗</span> Véhicules</div>
            <div class="info-cat"><span class="info-cat__icon">🏠</span> Immobilier</div>
            <div class="info-cat"><span class="info-cat__icon">💼</span> Emploi</div>
            <div class="info-cat"><span class="info-cat__icon">🔧</span> Services</div>
            <div class="info-cat"><span class="info-cat__icon">📱</span> Multimédia / Électronique</div>
            <div class="info-cat"><span class="info-cat__icon">🛋️</span> Maison / Mobilier</div>
            <div class="info-cat"><span class="info-cat__icon">👗</span> Mode / Habillement</div>
            <div class="info-cat"><span class="info-cat__icon">⚽</span> Loisirs / Sport</div>
            <div class="info-cat"><span class="info-cat__icon">🥗</span> Alimentation</div>
            <div class="info-cat"><span class="info-cat__icon">🐾</span> Animaux</div>
            <div class="info-cat"><span class="info-cat__icon">👶</span> Enfants / Bébé</div>
            <div class="info-cat"><span class="info-cat__icon">🔨</span> Matériaux / Bricolage</div>
            <div class="info-cat"><span class="info-cat__icon">🌾</span> Agriculture / Élevage</div>
            <div class="info-cat"><span class="info-cat__icon">🎉</span> Évènements</div>
            <div class="info-cat"><span class="info-cat__icon">📦</span> Autres</div>
        </div>
    </section>

    <section class="info-section">
        <h2 class="section-title" style="margin-bottom:0">Comment publier une annonce ?</h2>
        <div class="info-steps">
            <div class="info-step">
                <div class="info-step__num">1</div>
                <div class="info-step__body">
                    <h3>Créez votre compte annonceur</h3>
                    <p>Inscription gratuite. Renseignez vos informations et le nom de votre entreprise.</p>
                </div>
            </div>
            <div class="info-step">
                <div class="info-step__num">2</div>
                <div class="info-step__body">
                    <h3>Rédigez votre annonce</h3>
                    <p>Titre, description, photos, prix, localisation et coordonnées de contact.</p>
                </div>
            </div>
            <div class="info-step">
                <div class="info-step__num">3</div>
                <div class="info-step__body">
                    <h3>Payez par annonce</h3>
                    <p>10 000 FCFA par annonce publiée. Paiement sécurisé via mobile money ou carte bancaire.</p>
                </div>
            </div>
            <div class="info-step">
                <div class="info-step__num">4</div>
                <div class="info-step__body">
                    <h3>Votre annonce est en ligne</h3>
                    <p>Immédiatement visible sur la page annonces d'E-Benin et sur la page d'accueil.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="info-section">
        <h2 class="section-title" style="margin-bottom:0">Tarif</h2>
        <div class="info-pricing">
            <div class="info-pricing__price">10 000 FCFA</div>
            <div class="info-pricing__label">par annonce publiée · aucun abonnement</div>
            <p style="color:var(--mid);font-size:.9rem;margin-bottom:20px;">
                Vous ne payez que ce que vous publiez. Pas d'engagement, pas d'abonnement mensuel.<br>
                L'annonce reste en ligne jusqu'à ce que vous la supprimiez.
            </p>
            <a href="{{ route('advertiser.register') }}" class="btn btn--primary" style="font-size:1rem;padding:14px 32px;">Créer un compte annonceur →</a>
        </div>
    </section>

    <section class="info-cta">
        <h2>Prêt à publier votre première annonce ?</h2>
        <p>Rejoignez les annonceurs qui trouvent leurs clients sur E-Benin.</p>
        <div style="display:flex;gap:14px;justify-content:center;flex-wrap:wrap;">
            <a href="{{ route('advertiser.register') }}" class="btn btn--primary" style="font-size:.97rem;padding:13px 28px;">Créer un compte annonceur</a>
            <a href="{{ route('advertiser.login') }}" class="btn btn--outline" style="font-size:.97rem;padding:13px 28px;">Se connecter</a>
        </div>
    </section>
</div>
@endsection
