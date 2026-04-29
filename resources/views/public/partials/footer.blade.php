@php
    use Illuminate\Support\Str;

    $host = request()->getHost();
    $baseDomain = str_contains($host, 'e-benin.bj') ? 'e-benin.bj' : 'e-benin.com';
    $siteRoot = 'https://' . $baseDomain;
    $isSubdomain = !in_array($host, ['e-benin.com', 'www.e-benin.com', 'e-benin.bj', 'www.e-benin.bj'], true)
                   && count(explode('.', $host)) > 2;
    $organization = $isSubdomain ? ($organization ?? null) : null;
    $footerRubriques = collect($footerRubriques ?? ($navItems ?? []))->filter()->take(6);
    $footerOrganizations = collect($footerOrgs ?? [])->filter()->take(6);
    $policyUrl = $siteRoot . '/politique';
    $registerUrl = $siteRoot . '/bloger/register';
    $logoPath = $brandLogoPath ?? ($organization && filled($organization->organization_logo) ? $organization->organization_logo : 'images/ebenins.png');
    $logoUrl = Str::startsWith((string) $logoPath, ['http://', 'https://']) ? $logoPath : asset(ltrim((string) $logoPath, '/'));

    $categoryUrl = function ($rubrique) use ($organization, $baseDomain) {
        if (!$rubrique || !filled($rubrique->id ?? null)) {
            return '#';
        }

        return $organization
            ? "https://{$organization->subdomain}.{$baseDomain}/category/{$rubrique->id}"
            : "https://{$baseDomain}/categories/{$rubrique->id}";
    };

    $socialLinks = collect($socials ?? [])->filter(function ($social) {
        return filled($social->url ?? null);
    })->take(4);
@endphp

<footer class="footer">
    <div class="container">
        <div class="footer__grid">
            <div class="footer__brand">
                <a href="{{ $homeUrl ?? $siteRoot }}" class="logo">
                    <img src="{{ $logoUrl }}" alt="{{ $organization->organization_name ?? 'E-Benin' }}" class="logo__img--light">
                </a>
                <p class="footer__desc">
                    {{ $organization?->organization_name ? "Blog de {$organization->organization_name}, membre du réseau E-Benin." : "E-Benin fédère les blogs d'actualité, les reportages et les rubriques thématiques autour du Bénin." }}
                </p>
                <div class="footer__social">
                    @forelse ($socialLinks as $social)
                        <a href="{{ $social->url }}" target="_blank" rel="noopener noreferrer">
                            {{ strtoupper(Str::substr($social->social->nom ?? 'R', 0, 1)) }}
                        </a>
                    @empty
                        <a href="mailto:contact@savplus.net">M</a>
                        <a href="https://wa.me/22969416666" target="_blank" rel="noopener noreferrer">W</a>
                    @endforelse
                </div>
            </div>

            <div>
                <div class="footer__col-title">Rubriques</div>
                <div class="footer__links">
                    @foreach ($footerRubriques as $rubrique)
                        <a href="{{ $categoryUrl($rubrique) }}">{{ $rubrique->name }}</a>
                    @endforeach
                </div>
            </div>

            <div>
                <div class="footer__col-title">Réseau</div>
                <div class="footer__links">
                    <a href="{{ $siteRoot }}">Portail principal</a>
                    <a href="{{ $registerUrl }}">Créer un blog</a>
                    <a href="{{ $policyUrl }}">Politique de confidentialité</a>
                    @foreach ($footerOrganizations as $footerOrg)
                        @if (filled($footerOrg->subdomain ?? null))
                            <a href="https://{{ $footerOrg->subdomain }}.{{ $baseDomain }}/blog">{{ $footerOrg->organization_name }}</a>
                        @endif
                    @endforeach
                </div>
            </div>

            <div>
                <div class="footer__col-title">Contact</div>
                <div class="footer__links">
                    <a href="mailto:contact@savplus.net">contact@savplus.net</a>
                    <a href="tel:+22920213759">(+229) 20 21 37 59</a>
                    <a href="tel:+22969416666">(+229) 69 41 66 66</a>
                    <a href="https://savplus.net" target="_blank" rel="noopener noreferrer">SAVPLUS CONSEIL</a>
                </div>
            </div>
        </div>

        <div class="footer__bottom">
            <span>&copy; {{ now()->year }} E-Benin. Tous droits réservés.</span>
            <div class="footer__bottom-links">
                <a href="{{ $policyUrl }}">Confidentialité</a>
                <a href="mailto:contact@savplus.net">Contact</a>
            </div>
        </div>
    </div>
</footer>
