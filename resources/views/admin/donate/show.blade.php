@extends('layouts.admin')
@section('title', 'Invoice #' . $invoice->invoice_number)

@section('content')
<div>
    <div style="margin-bottom:1.2rem;">
        <a href="{{ route('admin.donate') }}" class="pw-adm-btn pw-adm-btn--ghost pw-adm-btn--sm">&larr; Kembali</a>
    </div>

    @if(session('success'))
    <div style="margin-bottom:1rem;padding:.8rem 1rem;background:rgba(80,200,100,.15);border:1px solid rgba(80,200,100,.4);border-radius:8px;color:#4caf50;font-size:.85rem;">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div style="margin-bottom:1rem;padding:.8rem 1rem;background:rgba(220,60,60,.15);border:1px solid rgba(220,60,60,.4);border-radius:8px;color:#e05252;font-size:.85rem;">
        {{ session('error') }}
    </div>
    @endif

    <div class="pw-adm-card">
        <div class="pw-adm-card__title">Detail Invoice</div>

        <table style="width:100%;border-collapse:collapse;font-size:.85rem;">
            <tr style="border-bottom:1px solid var(--pw-border,rgba(255,255,255,.08));">
                <td style="padding:.6rem 0;color:var(--pw-text-muted);width:160px;">Invoice #</td>
                <td style="padding:.6rem 0;font-family:monospace;font-size:.8rem;">{{ $invoice->invoice_number }}</td>
            </tr>
            @if($invoice->payhook_invoice_number)
            <tr style="border-bottom:1px solid var(--pw-border,rgba(255,255,255,.08));">
                <td style="padding:.6rem 0;color:var(--pw-text-muted);">PayHook Invoice</td>
                <td style="padding:.6rem 0;font-family:monospace;font-size:.8rem;color:var(--pw-text-muted);">{{ $invoice->payhook_invoice_number }}</td>
            </tr>
            @endif
            <tr style="border-bottom:1px solid var(--pw-border,rgba(255,255,255,.08));">
                <td style="padding:.6rem 0;color:var(--pw-text-muted);">User</td>
                <td style="padding:.6rem 0;">
                    @if($invoice->user)
                    <a href="{{ route('admin.members.show', $invoice->user->ID) }}" style="color:#b89d4f;">{{ $invoice->user->name }}</a>
                    @else
                        <span style="color:var(--pw-text-muted);">&mdash;</span>
                    @endif
                </td>
            </tr>
            <tr style="border-bottom:1px solid var(--pw-border,rgba(255,255,255,.08));">
                <td style="padding:.6rem 0;color:var(--pw-text-muted);">Gold Points</td>
                <td style="padding:.6rem 0;font-weight:700;color:#b89d4f;font-size:1.1rem;">{{ number_format($invoice->gold_amount) }} Gold Points</td>
            </tr>
            <tr style="border-bottom:1px solid var(--pw-border,rgba(255,255,255,.08));">
                <td style="padding:.6rem 0;color:var(--pw-text-muted);">Nominal</td>
                <td style="padding:.6rem 0;">Rp {{ number_format($invoice->unique_amount) }}</td>
            </tr>
            <tr style="border-bottom:1px solid var(--pw-border,rgba(255,255,255,.08));">
                <td style="padding:.6rem 0;color:var(--pw-text-muted);">Metode</td>
                <td style="padding:.6rem 0;text-transform:uppercase;">{{ $invoice->channel_type ?? '&mdash;' }}</td>
            </tr>
            <tr style="border-bottom:1px solid var(--pw-border,rgba(255,255,255,.08));">
                <td style="padding:.6rem 0;color:var(--pw-text-muted);">Status</td>
                <td style="padding:.6rem 0;">
                    @if($invoice->status === 'paid')
                        <span class="pw-badge pw-badge--success">Paid</span>
                    @elseif($invoice->status === 'pending')
                        <span class="pw-badge pw-badge--warning">Pending</span>
                    @elseif($invoice->status === 'expired')
                        <span class="pw-badge" style="background:rgba(249,115,22,.15);color:#fb923c;border:1px solid rgba(249,115,22,.3);">Waktu Habis</span>
                    @else
                        <span class="pw-badge pw-badge--danger">Gagal</span>
                    @endif
                </td>
            </tr>
            <tr style="border-bottom:1px solid var(--pw-border,rgba(255,255,255,.08));">
                <td style="padding:.6rem 0;color:var(--pw-text-muted);">Dibuat</td>
                <td style="padding:.6rem 0;">{{ $invoice->created_at->format('d M Y H:i:s') }}</td>
            </tr>
            @if($invoice->paid_at)
            <tr>
                <td style="padding:.6rem 0;color:var(--pw-text-muted);">Dibayar</td>
                <td style="padding:.6rem 0;">{{ \Carbon\Carbon::parse($invoice->paid_at)->format('d M Y H:i:s') }}</td>
            </tr>
            @endif
        </table>

        @if($invoice->notes)
        <div style="margin-top:1rem;padding:.8rem;background:var(--pw-bg-card,rgba(255,255,255,.04));border-radius:6px;">
            <div style="font-size:.72rem;color:var(--pw-text-muted);margin-bottom:.3rem;">CATATAN</div>
            <div style="font-size:.83rem;">{{ $invoice->notes }}</div>
        </div>
        @endif

        @if($invoice->status === 'pending')
        <div style="margin-top:1.5rem;padding-top:1.2rem;border-top:1px solid var(--pw-border,rgba(255,255,255,.08));display:flex;gap:.75rem;align-items:center;flex-wrap:wrap;">
            <div style="font-size:.78rem;color:var(--pw-text-muted);flex-shrink:0;">Aksi Manual:</div>

            <form method="POST" action="{{ route('admin.donate.approve', $invoice->id) }}"
                  data-confirm="Approve Invoice|Kredit {{ number_format($invoice->gold_amount) }} Gold Points ke {{ $invoice->user->name ?? 'user' }}?"
                  data-confirm-variant="success"
                  data-confirm-ok="Ya, Approve & Kredit Gold Points">
                @csrf
                <button type="submit" class="pw-adm-btn" style="background:#2e7d32;border-color:#2e7d32;color:#fff;">
                    &#10003; Approve &mdash; Kredit {{ number_format($invoice->gold_amount) }} Gold Points
                </button>
            </form>

            <form method="POST" action="{{ route('admin.donate.reject', $invoice->id) }}"
                  data-confirm="Tolak Invoice|Invoice {{ $invoice->invoice_number }} akan ditolak dan Gold Points tidak dikreditkan."
                  data-confirm-ok="Ya, Tolak">
                @csrf
                <button type="submit" class="pw-adm-btn" style="background:#c62828;border-color:#c62828;color:#fff;">
                    &#10007; Reject
                </button>
            </form>
        </div>
        @endif
    </div>
</div>
@endsection
