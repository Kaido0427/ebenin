@php
    $host = request()->getHost();
    $baseDomain = str_contains($host, 'e-benin.bj') ? 'e-benin.bj' : 'e-benin.com';
    $siteRoot = 'https://' . $baseDomain;
    $navItems = collect();
    $footerRubriques = collect();
@endphp

@extends('public.layouts.app')

@section('title', 'Politique de confidentialité | E-Benin')
@section('meta_description', "Consultez la politique de confidentialité d'E-Benin sur la collecte, l'utilisation et la protection des données personnelles.")
@section('canonical', $siteRoot . '/politique')

@section('content')
    <div class="page-hero page-hero--dark">
        <div class="container">
            <div class="page-hero__eyebrow">E-Benin</div>
            <h1 class="page-hero__title">Politique de confidentialité</h1>
            <p class="page-hero__text">La présente politique explique comment E-Benin et SAVPLUS CONSEIL collectent, utilisent et protègent vos données personnelles.</p>
            <div class="page-hero__meta">Dernière mise à jour : 21 avril 2026</div>
        </div>
    </div>

    <main>
        <div class="policy-layout">
            <div class="policy-section">
                <h2><span>1</span> Qui sommes-nous ?</h2>
                <p>E-Benin est un portail d'information multi-auteurs opéré avec l'appui technique de SAVPLUS CONSEIL. La plateforme permet à des blogueurs, rédactions et journalistes de publier leurs contenus sur leurs espaces dédiés et sur le portail principal.</p>
            </div>

            <div class="policy-section">
                <h2><span>2</span> Données collectées</h2>
                <ul>
                    <li>Données d'inscription des blogueurs : nom, e-mail, téléphone, mot de passe chiffré, informations d'organisation.</li>
                    <li>Données éditoriales : articles publiés, images, vidéos, commentaires et métadonnées liées à la publication.</li>
                    <li>Données techniques : adresse IP, journaux de connexion, informations de session et statistiques d'usage.</li>
                </ul>
            </div>

            <div class="policy-section">
                <h2><span>3</span> Finalités du traitement</h2>
                <ul>
                    <li>Créer et administrer votre espace blogueur.</li>
                    <li>Publier, afficher et modérer les contenus sur le réseau E-Benin.</li>
                    <li>Assurer la sécurité de la plateforme et prévenir les usages frauduleux.</li>
                    <li>Vous contacter en cas de support, d'abonnement ou d'information importante.</li>
                </ul>
            </div>

            <div class="policy-section">
                <h2><span>4</span> Partage des données</h2>
                <p>Vos données ne sont pas revendues. Elles peuvent être partagées uniquement avec des prestataires techniques nécessaires au fonctionnement du service ou avec les autorités compétentes dans le cadre légal applicable.</p>
            </div>

            <div class="policy-section">
                <h2><span>5</span> Durée de conservation</h2>
                <ul>
                    <li>Les données de compte sont conservées pendant la durée d'activité du compte puis selon les obligations légales applicables.</li>
                    <li>Les contenus publiés restent visibles tant qu'ils ne sont pas supprimés par leur auteur ou par la modération.</li>
                    <li>Les logs techniques sont conservés pour la sécurité et la maintenance pendant une durée limitée.</li>
                </ul>
            </div>

            <div class="policy-section">
                <h2><span>6</span> Vos droits</h2>
                <ul>
                    <li>Droit d'accès à vos données personnelles.</li>
                    <li>Droit de rectification des informations inexactes.</li>
                    <li>Droit de suppression dans les cas prévus par la loi.</li>
                    <li>Droit d'opposition ou de limitation pour certains traitements.</li>
                </ul>
            </div>

            <div class="policy-section">
                <h2><span>7</span> Cookies et session</h2>
                <p>E-Benin utilise des cookies techniques de session pour permettre l'authentification, la sécurité CSRF et le bon fonctionnement des espaces connectés. Des cookies d'analyse peuvent être ajoutés pour améliorer le service.</p>
            </div>

            <div class="policy-section">
                <h2><span>8</span> Contact</h2>
                <p>Pour toute question relative à vos données ou à la plateforme, vous pouvez écrire à <a href="mailto:contact@savplus.net">contact@savplus.net</a> ou appeler le <a href="tel:+22920213759">(+229) 20 21 37 59</a>.</p>
            </div>

            <div style="background:var(--white);border-radius:var(--radius-lg);padding:28px;text-align:center;box-shadow:var(--shadow);margin-top:24px">
                <h3 style="font-family:var(--font-serif);font-size:1.3rem;margin-bottom:8px">Besoin d'une précision ?</h3>
                <p style="color:var(--muted);font-size:.88rem;margin-bottom:16px">Notre équipe peut vous aider à comprendre le traitement de vos données ou l'utilisation de votre compte blogueur.</p>
                <a href="mailto:contact@savplus.net" class="btn btn--primary">Contacter le support</a>
            </div>
        </div>
    </main>
@endsection
