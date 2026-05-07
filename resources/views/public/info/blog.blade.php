@extends('public.layouts.app')
@section('title', 'Créez votre blog | E-Benin')
@section('meta_description', 'Rejoignez le réseau des rédactions et blogueurs du Bénin. Votre propre sous-domaine, dashboard complet, visibilité nationale. Gratuit 90 jours.')

@push('head')
<style>
/* ── Hero ── */
.ib-hero {
    background: linear-gradient(135deg, #003f7f 0%, #0057b3 60%, #1a73e8 100%);
    color: #fff; padding: 72px 0 56px; text-align: center; position: relative; overflow: hidden;
}
.ib-hero::before {
    content: ''; position: absolute; inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}
.ib-hero__badge { display: inline-block; background: rgba(255,255,255,.15); border: 1px solid rgba(255,255,255,.3); color: #fff; font-size: .78rem; font-weight: 700; letter-spacing: .06em; text-transform: uppercase; padding: 5px 14px; border-radius: 20px; margin-bottom: 20px; }
.ib-hero h1 { font-size: 2.6rem; font-weight: 800; margin: 0 0 16px; line-height: 1.15; position: relative; }
.ib-hero__sub { font-size: 1.05rem; opacity: .88; max-width: 580px; margin: 0 auto 32px; line-height: 1.7; position: relative; }
.ib-hero__actions { display: flex; gap: 14px; justify-content: center; flex-wrap: wrap; position: relative; }
.ib-hero__actions .btn-white { background: #fff; color: var(--primary); font-weight: 700; padding: 13px 28px; border-radius: 6px; text-decoration: none; font-size: .97rem; transition: all .2s; }
.ib-hero__actions .btn-white:hover { background: #e8f0fe; transform: translateY(-2px); }
.ib-hero__actions .btn-ghost { border: 2px solid rgba(255,255,255,.6); color: #fff; font-weight: 600; padding: 12px 26px; border-radius: 6px; text-decoration: none; font-size: .97rem; transition: all .2s; }
.ib-hero__actions .btn-ghost:hover { background: rgba(255,255,255,.1); transform: translateY(-2px); }
/* ── Stats ── */
.ib-stats { display: grid; grid-template-columns: repeat(3,1fr); gap: 1px; background: var(--border); border: 1px solid var(--border); border-radius: var(--radius); overflow: hidden; margin: -28px 0 0; position: relative; z-index: 10; }
.ib-stat { background: var(--white); padding: 24px 20px; text-align: center; }
.ib-stat__val { font-size: 1.8rem; font-weight: 800; color: var(--primary); line-height: 1; }
.ib-stat__lbl { font-size: .78rem; color: var(--muted); margin-top: 5px; }
/* ── Sections ── */
.ib-section { padding: 64px 0; }
.ib-section + .ib-section { border-top: 1px solid var(--border); }
.ib-section__head { text-align: center; margin-bottom: 40px; }
.ib-section__title { font-size: 1.7rem; font-weight: 800; color: var(--dark); margin-bottom: 10px; }
.ib-section__desc { font-size: .95rem; color: var(--mid); max-width: 560px; margin: 0 auto; line-height: 1.7; }
/* ── Features grid ── */
.ib-features { display: grid; grid-template-columns: repeat(3,1fr); gap: 20px; }
@media (max-width:900px) { .ib-features { grid-template-columns: repeat(2,1fr); } }
@media (max-width:560px) { .ib-features { grid-template-columns: 1fr; } }
.ib-feat { background: var(--white); border: 1px solid var(--border); border-radius: 10px; padding: 28px 24px; transition: all .2s; }
.ib-feat:hover { border-color: var(--primary); box-shadow: 0 4px 20px rgba(0,63,127,.1); transform: translateY(-3px); }
.ib-feat__icon { width: 48px; height: 48px; background: #e8f0fe; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; margin-bottom: 16px; }
.ib-feat__title { font-size: 1rem; font-weight: 700; color: var(--dark); margin-bottom: 8px; }
.ib-feat__text { font-size: .86rem; color: var(--mid); line-height: 1.7; margin: 0; }
/* ── Steps ── */
.ib-steps { display: grid; grid-template-columns: repeat(4,1fr); gap: 20px; position: relative; }
@media (max-width:900px) { .ib-steps { grid-template-columns: repeat(2,1fr); } }
@media (max-width:560px) { .ib-steps { grid-template-columns: 1fr; } }
.ib-steps::before { content:''; position:absolute; top:28px; left:calc(12.5% + 14px); right:calc(12.5% + 14px); height:2px; background: linear-gradient(90deg, var(--primary), #1a73e8); z-index:0; }
@media (max-width:900px) { .ib-steps::before { display:none; } }
.ib-step { background: var(--white); border: 1px solid var(--border); border-radius: 10px; padding: 24px 20px; text-align: center; position: relative; z-index:1; }
.ib-step__num { width: 44px; height: 44px; background: var(--primary); color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 1rem; margin: 0 auto 16px; border: 3px solid var(--white); box-shadow: 0 0 0 2px var(--primary); }
.ib-step__title { font-size: .93rem; font-weight: 700; color: var(--dark); margin-bottom: 8px; }
.ib-step__text { font-size: .82rem; color: var(--mid); line-height: 1.6; margin: 0; }
/* ── Pricing ── */
.ib-pricing { max-width: 480px; margin: 0 auto; background: var(--white); border: 2px solid var(--primary); border-radius: 16px; overflow: hidden; }
.ib-pricing__head { background: var(--primary); color: #fff; padding: 28px 32px; text-align: center; }
.ib-pricing__badge { font-size: .73rem; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; opacity: .8; margin-bottom: 8px; }
.ib-pricing__price { font-size: 3.2rem; font-weight: 800; line-height: 1; }
.ib-pricing__period { font-size: .9rem; opacity: .85; margin-top: 6px; }
.ib-pricing__body { padding: 28px 32px; }
.ib-pricing__list { list-style: none; padding: 0; margin: 0 0 24px; display: flex; flex-direction: column; gap: 12px; }
.ib-pricing__list li { display: flex; align-items: center; gap: 10px; font-size: .9rem; color: var(--mid); }
.ib-pricing__list li::before { content: '✓'; width: 22px; height: 22px; background: #e8f5e9; color: #2e7d32; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: .75rem; flex-shrink: 0; }
/* ── CTA ── */
.ib-cta { background: linear-gradient(135deg, #003f7f 0%, #0057b3 100%); color: #fff; padding: 64px 0; text-align: center; }
.ib-cta h2 { font-size: 2rem; font-weight: 800; margin-bottom: 12px; }
.ib-cta p { opacity: .88; max-width: 500px; margin: 0 auto 32px; line-height: 1.7; }
</style>
@endpush

@section('content')

{{-- Hero --}}
<div class="ib-hero">
    <div class="container">
        <div class="ib-hero__badge">📰 Réseau de blogs</div>
        <h1>Créez votre blog sur E-Benin</h1>
        <p class="ib-hero__sub">Rejoignez les rédactions et blogueurs qui font l'information au Bénin. Votre propre adresse, votre propre audience — sans compétences techniques.</p>
        <div class="ib-hero__actions">
            <a href="{{ route('userRegister') }}" class="btn-white">Commencer gratuitement →</a>
            <a href="{{ route('bloger.login') }}" class="btn-ghost">Se connecter</a>
        </div>
    </div>
</div>

<div class="container">

    {{-- Stats --}}
    <div class="ib-stats">
        <div class="ib-stat">
            <div class="ib-stat__val">Gratuit</div>
            <div class="ib-stat__lbl">pour commencer</div>
        </div>
        <div class="ib-stat">
            <div class="ib-stat__val">90 j</div>
            <div class="ib-stat__lbl">d'essai inclus</div>
        </div>
        <div class="ib-stat">
            <div class="ib-stat__val">∞</div>
            <div class="ib-stat__lbl">articles publiables</div>
        </div>
    </div>

    {{-- Features --}}
    <section class="ib-section">
        <div class="ib-section__head">
            <div class="ib-section__title">Tout ce dont vous avez besoin</div>
            <p class="ib-section__desc">Une plateforme complète pour créer, publier et développer votre présence éditoriale au Bénin.</p>
        </div>
        <div class="ib-features">
            <div class="ib-feat">
                <div class="ib-feat__icon">🌐</div>
                <div class="ib-feat__title">Votre propre sous-domaine</div>
                <p class="ib-feat__text">Adresse personnalisée <strong>votreorg.e-benin.com</strong> — professionnel, mémorisable, partageable sur vos réseaux.</p>
            </div>
            <div class="ib-feat">
                <div class="ib-feat__icon">📊</div>
                <div class="ib-feat__title">Dashboard complet</div>
                <p class="ib-feat__text">Publiez des articles, gérez vos rubriques, ajoutez des images et vidéos — tout depuis un espace unique.</p>
            </div>
            <div class="ib-feat">
                <div class="ib-feat__icon">🌍</div>
                <div class="ib-feat__title">Visibilité nationale</div>
                <p class="ib-feat__text">Vos contenus apparaissent sur le portail E-Benin, lu par des milliers de Béninois chaque jour.</p>
            </div>
            <div class="ib-feat">
                <div class="ib-feat__icon">🎨</div>
                <div class="ib-feat__title">Design professionnel</div>
                <p class="ib-feat__text">Front moderne avec votre logo et vos couleurs, optimisé mobile et réseaux sociaux. Aucune compétence technique requise.</p>
            </div>
            <div class="ib-feat">
                <div class="ib-feat__icon">💬</div>
                <div class="ib-feat__title">Commentaires & audience</div>
                <p class="ib-feat__text">Vos lecteurs interagissent avec vos articles. Construisez une communauté engagée autour de vos contenus.</p>
            </div>
            <div class="ib-feat">
                <div class="ib-feat__icon">📱</div>
                <div class="ib-feat__title">Réseaux sociaux intégrés</div>
                <p class="ib-feat__text">Facebook, Twitter, Instagram, WhatsApp — vos liens sont visibles directement sur votre page de blog.</p>
            </div>
        </div>
    </section>

    {{-- Steps --}}
    <section class="ib-section" style="background:var(--bg);margin:0 -9999px;padding:64px 9999px;">
        <div class="ib-section__head">
            <div class="ib-section__title">Lancez-vous en 4 étapes</div>
            <p class="ib-section__desc">Simple, rapide, sans compétences techniques.</p>
        </div>
        <div class="ib-steps">
            <div class="ib-step">
                <div class="ib-step__num">1</div>
                <div class="ib-step__title">Créez votre compte</div>
                <p class="ib-step__text">Nom, organisation, email. Inscription gratuite en 2 minutes, sans carte bancaire.</p>
            </div>
            <div class="ib-step">
                <div class="ib-step__num">2</div>
                <div class="ib-step__title">Personnalisez votre blog</div>
                <p class="ib-step__text">Logo, biographie, réseaux sociaux, rubriques éditoriales — votre identité en ligne.</p>
            </div>
            <div class="ib-step">
                <div class="ib-step__num">3</div>
                <div class="ib-step__title">Publiez vos articles</div>
                <p class="ib-step__text">Rédigez, ajoutez des médias, publiez. Vos articles sont immédiatement en ligne.</p>
            </div>
            <div class="ib-step">
                <div class="ib-step__num">4</div>
                <div class="ib-step__title">Développez votre audience</div>
                <p class="ib-step__text">Profitez de la visibilité E-Benin et abonnez-vous pour continuer après l'essai.</p>
            </div>
        </div>
    </section>

    {{-- Pricing --}}
    <section class="ib-section">
        <div class="ib-section__head">
            <div class="ib-section__title">Tarif simple et transparent</div>
            <p class="ib-section__desc">Commencez gratuitement, continuez avec un abonnement accessible.</p>
        </div>
        <div class="ib-pricing">
            <div class="ib-pricing__head">
                <div class="ib-pricing__badge">Offre de lancement</div>
                <div class="ib-pricing__price">Gratuit</div>
                <div class="ib-pricing__period">90 jours d'essai · aucune carte requise</div>
            </div>
            <div class="ib-pricing__body">
                <ul class="ib-pricing__list">
                    <li>Sous-domaine personnalisé inclus</li>
                    <li>Articles illimités pendant l'essai</li>
                    <li>Accès complet au dashboard</li>
                    <li>Logo, biographie, réseaux sociaux</li>
                    <li>Visibilité sur le portail E-Benin</li>
                    <li>Support par e-mail</li>
                </ul>
                <a href="{{ route('userRegister') }}" class="btn btn--primary" style="width:100%;justify-content:center;font-size:.97rem;padding:14px;">Créer mon blog gratuitement →</a>
                <p style="text-align:center;font-size:.78rem;color:var(--muted);margin-top:12px;">Après l'essai, un abonnement mensuel est proposé pour continuer.</p>
            </div>
        </div>
    </section>
</div>

{{-- CTA final --}}
<div class="ib-cta">
    <div class="container">
        <h2>Prêt à lancer votre média ?</h2>
        <p>Rejoignez les rédactions et blogueurs qui font l'information au Bénin. Inscription gratuite, sans engagement.</p>
        <div style="display:flex;gap:14px;justify-content:center;flex-wrap:wrap;">
            <a href="{{ route('userRegister') }}" class="btn-white" style="background:#fff;color:var(--primary);font-weight:700;padding:13px 28px;border-radius:6px;text-decoration:none;font-size:.97rem;">S'inscrire gratuitement</a>
            <a href="{{ route('bloger.login') }}" class="btn-ghost" style="border:2px solid rgba(255,255,255,.6);color:#fff;font-weight:600;padding:12px 26px;border-radius:6px;text-decoration:none;font-size:.97rem;">Se connecter</a>
        </div>
    </div>
</div>

@endsection
