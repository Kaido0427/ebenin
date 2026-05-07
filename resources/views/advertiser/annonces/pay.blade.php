@extends('advertiser.layouts.app')
@section('title', 'Payer pour publier | E-Benin Annonces')

@push('head')
<style>
.pay-wrap { min-height: 70vh; display: flex; align-items: center; justify-content: center; padding: 40px 0; }
.pay-card { background: var(--white); border: 1px solid var(--border); border-radius: var(--radius); padding: 40px 36px; max-width: 480px; width: 100%; text-align: center; box-shadow: var(--shadow); }
.pay-icon { font-size: 2.8rem; margin-bottom: 16px; }
.pay-title { font-size: 1.4rem; font-weight: 800; color: var(--dark); margin-bottom: 8px; }
.pay-sub { font-size: .93rem; color: var(--mid); margin-bottom: 28px; line-height: 1.6; }
.pay-amount { background: rgba(0,63,127,.06); border-radius: 10px; padding: 16px; margin-bottom: 28px; }
.pay-amount__value { font-size: 2.4rem; font-weight: 800; color: var(--primary); }
.pay-amount__label { font-size: .83rem; color: var(--muted); }
.pay-annonce-title { font-size: .88rem; color: var(--mid); background: var(--bg); border-radius: 8px; padding: 10px 14px; margin-bottom: 24px; font-style: italic; }
</style>
@endpush

@section('content')
<div class="pay-wrap">
    <div class="container">
        <div class="pay-card" style="margin:0 auto;">
            <div class="pay-icon">💳</div>
            <div class="pay-title">Publier votre annonce</div>
            <div class="pay-sub">Votre annonce est prête. Un paiement unique est requis pour la mettre en ligne.</div>

            <div class="pay-annonce-title">« {{ $annonce->title }} »</div>

            <div class="pay-amount">
                <div class="pay-amount__value">10 000 FCFA</div>
                <div class="pay-amount__label">paiement unique · annonce active jusqu'à suppression</div>
            </div>

            <button
                id="pay-btn"
                class="btn btn--primary"
                style="width:100%;justify-content:center;font-size:1rem;padding:14px;"
                onclick="payAnnonce()">
                Payer 10 000 FCFA et publier
            </button>

            <p style="margin-top:16px;font-size:.78rem;color:var(--muted);">
                Paiement sécurisé via Kkiapay (Mobile Money, carte bancaire)
            </p>

            <a href="{{ route('advertiser.dashboard') }}" style="display:block;margin-top:14px;font-size:.82rem;color:var(--muted);">
                ← Retour au dashboard (annonce en attente)
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.kkiapay.me/k.js"></script>
<script>
function payAnnonce() {
    openKkiapayWidget({
        amount: 10000,
        api_key: 'cb876650e192fdf79d12342d023a6f4ebe257de4',
        sandbox: false,
        name: '{{ auth("advertiser")->user()->name ?? "" }}',
        phone: '{{ auth("advertiser")->user()->phone ?? "" }}',
        email: '{{ auth("advertiser")->user()->email ?? "" }}',
        data: 'annonce_id:{{ $annonce->id }}',
    });
}

addSuccessListener(function(response) {
    fetch('{{ route("advertiser.annonces.payment.callback", $annonce) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ transactionId: response.transactionId })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            window.location.href = '{{ route("advertiser.dashboard") }}';
        }
    });
});
</script>
@endpush
