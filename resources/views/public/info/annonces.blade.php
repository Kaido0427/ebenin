@extends('public.layouts.app')
@section('title', 'Publier une annonce | E-Benin')
@section('meta_description', 'Publiez votre annonce sur E-Benin — emploi, immobilier, véhicules, services et 15 catégories. 10 000 FCFA par annonce.')

@push('head')
<style>
/* ── Hero ── */
.ia-hero {
    background: linear-gradient(135deg, #003f7f 0%, #e8191e 100%);
    color: #fff; padding: 72px 0 56px; text-align: center; position: relative; overflow: hidden;
}
.ia-hero::before {
    content: ''; position: absolute; inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}
.ia-hero__badge { display: inline-block; background: rgba(255,255,255,.15); border: 1px solid rgba(255,255,255,.3); color: #fff; font-size: .78rem; font-weight: 700; letter-spacing: .06em; text-transform: uppercase; padding: 5px 14px; border-radius: 20px; margin-bottom: 20px; }
.ia-hero h1 { font-size: 2.6rem; font-weight: 800; margin: 0 0 16px; line-height: 1.15; position: relative; }
.ia-hero__sub { font-size: 1.05rem; opacity: .88; max-width: 580px; margin: 0 auto 32px; line-height: 1.7; position: relative; }
.ia-hero__actions { display: flex; gap: 14px; justify-content: center; flex-wrap: wrap; position: relative; }
.ia-hero__actions .btn-white { background: #fff; color: var(--primary); font-weight: 700; padding: 13px 28px; border-radius: 6px; text-decoration: none; font-size: .97rem; transition: all .2s; }
.ia-hero__actions .btn-white:hover { background: #fff3f3; transform: translateY(-2px); }
.ia-hero__actions .btn-ghost { border: 2px solid rgba(255,255,255,.6); color: #fff; font-weight: 600; padding: 12px 26px; border-radius: 6px; text-decoration: none; font-size: .97rem; transition: all .2s; }
.ia-hero__actions .btn-ghost:hover { background: rgba(255,255,255,.1); transform: translateY(-2px); }
/* ── Stats ── */
.ia-stats { display: grid; grid-template-columns: repeat(3,1fr); gap: 1px; background: var(--border); border: 1px solid var(--border); border-radius: var(--radius); overflow: hidden; margin: -28px 0 0; position: relative; z-index: 10; }
.ia-stat { background: var(--white); padding: 24px 20px; text-align: center; }
.ia-stat__val { font-size: 1.8rem; font-weight: 800; color: var(--primary); line-height: 1; }
.ia-stat__lbl { font-size: .78rem; color: var(--muted); margin-top: 5px; }
/* ── Sections ── */
.ia-section { padding: 64px 0; }
.ia-section + .ia-section { border-top: 1px solid var(--border); }
.ia-section__head { text-align: center; margin-bottom: 40px; }
.ia-section__title { font-size: 1.7rem; font-weight: 800; color: var(--dark); margin-bottom: 10px; }
.ia-section__desc { font-size: .95rem; color: var(--mid); max-width: 560px; margin: 0 auto; line-height: 1.7; }
/* ── Categories ── */
.ia-cats { display: grid; grid-template-columns: repeat(5, 1fr); gap: 12px; }
@media (max-width:900px) { .ia-cats { grid-template-columns: repeat(3,1fr); } }
@media (max-width:560px) { .ia-cats { grid-template-columns: repeat(2,1fr); } }
.ia-cat { display: flex; flex-direction: column; align-items: center; gap: 10px; padding: 20px 14px; background: var(--white); border: 1px solid var(--border); border-radius: 10px; text-align: center; cursor: default; transition: all .2s; }
.ia-cat:hover { border-color: var(--primary); box-shadow: 0 4px 16px rgba(0,63,127,.1); transform: translateY(-2px); }
.ia-cat__icon { font-size: 1.7rem; }
.ia-cat__label { font-size: .8rem; font-weight: 600; color: var(--dark); line-height: 1.3; }
/* ── Steps ── */
.ia-steps { display: grid; grid-template-columns: repeat(4,1fr); gap: 20px; position: relative; }
@media (max-width:900px) { .ia-steps { grid-template-columns: repeat(2,1fr); } }
@media (max-width:560px) { .ia-steps { grid-template-columns: 1fr; } }
.ia-steps::before { content:''; position:absolute; top:28px; left:calc(12.5% + 14px); right:calc(12.5% + 14px); height:2px; background: linear-gradient(90deg, var(--primary), #e8191e); z-index:0; }
@media (max-width:900px) { .ia-steps::before { display:none; } }
.ia-step { background: var(--white); border: 1px solid var(--border); border-radius: 10px; padding: 24px 20px; text-align: center; position: relative; z-index:1; }
.ia-step__num { width: 44px; height: 44px; background: var(--primary); color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 1rem; margin: 0 auto 16px; border: 3px solid var(--white); box-shadow: 0 0 0 2px var(--primary); }
.ia-step__title { font-size: .93rem; font-weight: 700; color: var(--dark); margin-bottom: 8px; }
.ia-step__text { font-size: .82rem; color: var(--mid); line-height: 1.6; margin: 0; }
/* ── Pricing ── */
.ia-pricing { max-width: 480px; margin: 0 auto; background: var(--white); border: 2px solid var(--accent, #e8191e); border-radius: 16px; overflow: hidden; }
.ia-pricing__head { background: var(--accent, #e8191e); color: #fff; padding: 28px 32px; text-align: center; }
.ia-pricing__badge { font-size: .73rem; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; opacity: .85; margin-bottom: 8px; }
.ia-pricing__price { font-size: 2.8rem; font-weight: 800; line-height: 1; }
.ia-pricing__period { font-size: .9rem; opacity: .9; margin-top: 6px; }
.ia-pricing__body { padding: 28px 32px; }
.ia-pricing__list { list-style: none; padding: 0; margin: 0 0 24px; display: flex; flex-direction: column; gap: 12px; }
.ia-pricing__list li { display: flex; align-items: center; gap: 10px; font-size: .9rem; color: var(--mid); }
.ia-pricing__list li::before { content: '✓'; width: 22px; height: 22px; background: #fff3f3; color: #c62828; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: .75rem; flex-shrink: 0; }
/* ── CTA ── */
.ia-cta { background: linear-gradient(135deg, #003f7f 0%, #e8191e 100%); color: #fff; padding: 64px 0; text-align: center; }
.ia-cta h2 { font-size: 2rem; font-weight: 800; margin-bottom: 12px; }
.ia-cta p { opacity: .88; max-width: 500px; margin: 0 auto 32px; line-height: 1.7; }
</style>
@endpush

@section('content')

{{-- Hero --}}
<div class="ia-hero">
    <div class="container">
        <div class="ia-hero__badge">📋 Petites annonces</div>
        <h1>Publiez votre annonce sur E-Benin</h1>
        <p class="ia-hero__sub">Le marché des annonces du Bénin. Emploi, immobilier, véhicules, services et bien plus — touchez des milliers de Béninois chaque jour.</p>
        <div class="ia-hero__actions">
            <a href="{{ route('advertiser.register') }}" class="btn-white">Publier une annonce →</a>
            <a href="{{ request()->getSchemeAndHttpHost() }}/annonces" class="btn-ghost">Voir les annonces</a>
        </div>
    </div>
</div>

<div class="container">

    {{-- Stats --}}
    <div class="ia-stats">
        <div class="ia-stat">
            <div class="ia-stat__val">10 000</div>
            <div class="ia-stat__lbl">FCFA par annonce</div>
        </div>
        <div class="ia-stat">
            <div class="ia-stat__val">15</div>
            <div class="ia-stat__lbl">catégories</div>
        </div>
        <div class="ia-stat">
            <div class="ia-stat__val">0</div>
            <div class="ia-stat__lbl">abonnement requis</div>
        </div>
    </div>

    {{-- Categories --}}
    <section class="ia-section">
        <div class="ia-section__head">
            <div class="ia-section__title">15 catégories disponibles</div>
            <p class="ia-section__desc">Publiez dans la catégorie qui correspond le mieux à votre annonce.</p>
        </div>
        <div class="ia-cats">
            <div class="ia-cat"><span class="ia-cat__icon">🚗</span><span class="ia-cat__label">Véhicules</span></div>
            <div class="ia-cat"><span class="ia-cat__icon">🏠</span><span class="ia-cat__label">Immobilier</span></div>
            <div class="ia-cat"><span class="ia-cat__icon">💼</span><span class="ia-cat__label">Emploi</span></div>
            <div class="ia-cat"><span class="ia-cat__icon">🔧</span><span class="ia-cat__label">Services</span></div>
            <div class="ia-cat"><span class="ia-cat__icon">📱</span><span class="ia-cat__label">Multimédia / Électronique</span></div>
            <div class="ia-cat"><span class="ia-cat__icon">🛋️</span><span class="ia-cat__label">Maison / Mobilier</span></div>
            <div class="ia-cat"><span class="ia-cat__icon">👗</span><span class="ia-cat__label">Mode / Habillement</span></div>
            <div class="ia-cat"><span class="ia-cat__icon">⚽</span><span class="ia-cat__label">Loisirs / Sport</span></div>
            <div class="ia-cat"><span class="ia-cat__icon">🥗</span><span class="ia-cat__label">Alimentation</span></div>
            <div class="ia-cat"><span class="ia-cat__icon">🐾</span><span class="ia-cat__label">Animaux</span></div>
            <div class="ia-cat"><span class="ia-cat__icon">👶</span><span class="ia-cat__label">Enfants / Bébé</span></div>
            <div class="ia-cat"><span class="ia-cat__icon">🔨</span><span class="ia-cat__label">Matériaux / Bricolage</span></div>
            <div class="ia-cat"><span class="ia-cat__icon">🌾</span><span class="ia-cat__label">Agriculture / Élevage</span></div>
            <div class="ia-cat"><span class="ia-cat__icon">🎉</span><span class="ia-cat__label">Évènements</span></div>
            <div class="ia-cat"><span class="ia-cat__icon">📦</span><span class="ia-cat__label">Autres</span></div>
        </div>
    </section>

    {{-- Steps --}}
    <section class="ia-section" style="background:var(--bg);margin:0 -9999px;padding:64px 9999px;">
        <div class="ia-section__head">
            <div class="ia-section__title">Publier en 4 étapes</div>
            <p class="ia-section__desc">Simple, rapide, votre annonce en ligne en quelques minutes.</p>
        </div>
        <div class="ia-steps">
            <div class="ia-step">
                <div class="ia-step__num">1</div>
                <div class="ia-step__title">Créez un compte annonceur</div>
                <p class="ia-step__text">Inscription gratuite. Nom, email, numéro de téléphone. Aucune carte requise à l'inscription.</p>
            </div>
            <div class="ia-step">
                <div class="ia-step__num">2</div>
                <div class="ia-step__title">Rédigez votre annonce</div>
                <p class="ia-step__text">Titre, description, catégorie, photos, prix, localisation et vos coordonnées de contact.</p>
            </div>
            <div class="ia-step">
                <div class="ia-step__num">3</div>
                <div class="ia-step__title">Payez en ligne</div>
                <p class="ia-step__text">10 000 FCFA par annonce. Paiement sécurisé via mobile money (MTN, Moov) ou carte bancaire.</p>
            </div>
            <div class="ia-step">
                <div class="ia-step__num">4</div>
                <div class="ia-step__title">Votre annonce est en ligne</div>
                <p class="ia-step__text">Immédiatement visible sur la page annonces et la page d'accueil d'E-Benin.</p>
            </div>
        </div>
    </section>

    {{-- Pricing --}}
    <section class="ia-section">
        <div class="ia-section__head">
            <div class="ia-section__title">Tarif clair, sans surprise</div>
            <p class="ia-section__desc">Vous ne payez que ce que vous publiez. Pas d'abonnement, pas d'engagement.</p>
        </div>
        <div class="ia-pricing">
            <div class="ia-pricing__head">
                <div class="ia-pricing__badge">Par annonce</div>
                <div class="ia-pricing__price">10 000 FCFA</div>
                <div class="ia-pricing__period">une seule fois · aucun abonnement</div>
            </div>
            <div class="ia-pricing__body">
                <ul class="ia-pricing__list">
                    <li>Annonce visible jusqu'à suppression</li>
                    <li>Photos incluses (plusieurs images)</li>
                    <li>Coordonnées de contact affichées</li>
                    <li>Mise en avant sur la page d'accueil</li>
                    <li>Paiement mobile money ou carte</li>
                    <li>Gestion depuis votre dashboard</li>
                </ul>
                <a href="{{ route('advertiser.register') }}" class="btn btn--primary" style="width:100%;justify-content:center;font-size:.97rem;padding:14px;background:var(--accent,#e8191e);border-color:var(--accent,#e8191e);">Créer un compte annonceur →</a>
                <p style="text-align:center;font-size:.78rem;color:var(--muted);margin-top:12px;">Inscription gratuite · paiement uniquement à la publication.</p>
            </div>
        </div>
    </section>
</div>

{{-- CTA final --}}
<div class="ia-cta">
    <div class="container">
        <h2>Prêt à trouver vos premiers clients ?</h2>
        <p>Publiez votre première annonce sur E-Benin et touchez des milliers de Béninois dès aujourd'hui.</p>
        <div style="display:flex;gap:14px;justify-content:center;flex-wrap:wrap;">
            <a href="{{ route('advertiser.register') }}" class="btn-white" style="background:#fff;color:#e8191e;font-weight:700;padding:13px 28px;border-radius:6px;text-decoration:none;font-size:.97rem;">Créer un compte annonceur</a>
            <a href="{{ request()->getSchemeAndHttpHost() }}/annonces" class="btn-ghost" style="border:2px solid rgba(255,255,255,.6);color:#fff;font-weight:600;padding:12px 26px;border-radius:6px;text-decoration:none;font-size:.97rem;">Voir toutes les annonces</a>
        </div>
    </div>
</div>

@endsection
