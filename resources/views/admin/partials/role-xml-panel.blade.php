{{-- Shared Role XML form (CodeMirror + save) — set $formAction, $charName, $roleXml, $roleXmlError, $pwadminRolexmlUrl --}}
<div class="pw-adm-card" style="margin-bottom:.8rem;">
    <div class="pw-adm-card__title" style="display:flex;align-items:center;gap:.5rem;flex-wrap:wrap;">
        <span>Role XML</span>
        <span style="font-size:.72rem;font-weight:500;color:var(--pw-text-muted);">— {{ $charName }}</span>
    </div>
    <p style="font-size:.68rem;color:var(--pw-text-muted);margin-bottom:.5rem;">
        Sumber: <code>XmlRole</code> di Tomcat (sama seperti <a href="{{ $pwadminRolexmlUrl ?? '#' }}" target="_blank" rel="noopener">rolexml.jsp</a>).
        Edit hati-hati — XML invalid bisa merusak data karakter. Tema <strong>Dark / Light</strong> panel mengganti tema editor di bawah.
    </p>
    @if(!empty($roleXmlError))
    <p style="font-size:.68rem;color:#f87171;margin-bottom:.5rem;">{{ $roleXmlError }} Salin <code>deploy/pwadmin/api_rolexml_xml.jsp</code> (dan <code>api_rolexml_save.jsp</code> untuk simpan) ke <code>webapps/pwAdmin/</code>.</p>
    @endif
    @if(!empty($roleXml))
    <form id="rl-form-role-xml" method="POST" action="{{ $formAction }}">
        @csrf
        <div id="rl-cm-xml" class="rl-code-mirror" data-label="XML"></div>
        <input type="hidden" name="xml" id="rl-xml-hidden" value="">
        <div style="text-align:center;margin-top:1rem;display:flex;flex-wrap:wrap;gap:.6rem;justify-content:center;align-items:center;">
            <button type="submit" class="pw-adm-btn rl-btn-save-xml"
                style="padding:.55rem 1.8rem;font-size:.8rem;font-weight:700;letter-spacing:.02em;
                background:linear-gradient(180deg,#9a7b28 0%,#7a6120 100%);
                color:#fff;border:1px solid rgba(0,0,0,.2);border-radius:8px;cursor:pointer;
                box-shadow:0 1px 0 rgba(255,255,255,.2) inset,0 2px 8px rgba(0,0,0,.2);
                text-shadow:0 1px 1px rgba(0,0,0,.35);">
                <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24" style="vertical-align:middle;margin-right:.35rem;opacity:1;"><path d="M17 3H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V7l-4-4z"/></svg>
                Simpan Role XML
            </button>
        </div>
    </form>
    @elseif(empty($roleXmlError))
    <p style="font-size:.68rem;color:var(--pw-text-muted);">Tidak ada isi XML dari Tomcat. Periksa <code>api_rolexml_xml.jsp</code>.</p>
    @endif
</div>
