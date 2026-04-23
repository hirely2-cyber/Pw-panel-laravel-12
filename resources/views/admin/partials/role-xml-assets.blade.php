@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/codemirror.min.css" crossorigin>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/theme/dracula.min.css" crossorigin>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/theme/eclipse.min.css" crossorigin>
<style>
    .rl-code-mirror .CodeMirror {
        min-height: 220px; height: 58vh; max-height: 75vh; border-radius: 7px; font-size: 12.5px;
        border: 1px solid var(--pw-border, rgba(255,255,255,.12));
    }
    .rl-code-mirror .CodeMirror-gutters { border-radius: 7px 0 0 7px; }
    .rl-code-mirror .CodeMirror-focused { outline: 2px solid rgba(200, 151, 42, 0.35); outline-offset: 0; }
    :root[data-theme="dark"] .rl-code-mirror .cm-s-dracula.CodeMirror { background: #1e1e2a; border-color: rgba(200, 151, 42, 0.2); }
    :root[data-theme="light"] .rl-code-mirror .cm-s-eclipse.CodeMirror { background: #f8fafc; border-color: rgba(0,0,0,.12); }
    .rl-btn-save-xml { transition: filter .12s, box-shadow .12s; }
    .rl-btn-save-xml:hover { filter: brightness(1.08); }
    .rl-btn-save-xml:active { filter: brightness(0.95); }
</style>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/codemirror.min.js" crossorigin></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/xml/xml.min.js" crossorigin></script>
<script>
(function () {
  function editorTheme() {
    return document.documentElement.getAttribute('data-theme') === 'light' ? 'eclipse' : 'dracula';
  }
  function init() {
    var elXml = document.getElementById('rl-cm-xml');
    if (!elXml) return;
    if (typeof CodeMirror === 'undefined') {
      elXml.textContent = 'Gagal memuat editor (CodeMirror). Periksa koneksi ke CDN, atau cek konsol browser (F12).';
      return;
    }
    var raw = {!! \Illuminate\Support\Js::from($roleXml ?? '') !!};
    window.rlXmlEditor = CodeMirror(elXml, {
      value: raw,
      mode: 'application/xml',
      readOnly: false,
      lineNumbers: true,
      lineWrapping: true,
      theme: editorTheme(),
      tabSize: 2,
      indentUnit: 2,
    });
    function syncTheme() {
      if (!window.rlXmlEditor) return;
      window.rlXmlEditor.setOption('theme', editorTheme());
      window.rlXmlEditor.refresh();
    }
    new MutationObserver(syncTheme).observe(document.documentElement, { attributes: true, attributeFilter: ['data-theme'] });
    var form = document.getElementById('rl-form-role-xml');
    if (form) {
      form.addEventListener('submit', function () {
        var hid = document.getElementById('rl-xml-hidden');
        if (hid && window.rlXmlEditor) hid.value = window.rlXmlEditor.getValue();
      });
    }
  }
  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init);
  else init();
})();
</script>
@endpush
