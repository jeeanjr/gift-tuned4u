<?php
$uuid = trim($_GET['id'] ?? '', '/');
if (empty($uuid)) {
    $path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
    $uuid = $path;
}

// Roteamento: variável de ambiente GIFT_LANG (EasyPanel) > HTTP_HOST > fallback EN
// No EasyPanel: Environment Variables → GIFT_LANG = es  (para regalo.tuned4u.com)
//                                      GIFT_LANG = en  (para gift.tuned4u.com)
$envLang = strtolower(trim(getenv('GIFT_LANG') ?: ''));
$host    = strtolower($_SERVER['HTTP_HOST'] ?? '');

$langs = [

  'regalo.tuned4u.com' => [
    'lang'             => 'es',
    'table'            => 'presentes_es',
    'html_lang'        => 'es',
    'brand'            => 'Tuned4U',
    'page_title'       => 'Tuned4U — Tu Canción Personalizada',
    'error_title'      => 'Canción no encontrada',
    'error_body'       => 'Este enlace puede ser inválido o la canción aún no ha sido generada. Si crees que es un error, contáctanos.',
    'for_label'        => 'Una canción creada para',
    'generating_title' => 'Componiendo tu canción...',
    'generating_sub'   => 'Nuestra IA está creando algo único para ti.<br>Esto generalmente tarda unos minutos.',
    'refresh_btn'      => '↻ Verificar de nuevo',
    'player_label'     => '♪ Tu canción personalizada',
    'download_btn'     => '↓ Descargar canción',
    'lyrics_label'     => 'Letra',
    'copy_btn'         => 'Copiar ↗',
    'copy_done'        => 'Copiado ✓',
    'support_label'    => '¿Alguna pregunta sobre tu canción?',
    'support_email'    => 'contact@tuned4u.com',
    'footer_sub'       => 'Canciones creadas de historias reales · tuned4u.com',
    'doc_title_suffix' => '— Tuned4U',
    'status_ready'     => 'GERADO',
  ],

  'gift.tuned4u.com' => [
    'lang'             => 'en',
    'table'            => 'tuned4u_gifts',
    'html_lang'        => 'en',
    'brand'            => 'Tuned4U',
    'page_title'       => 'Tuned4U — Your Personal Song',
    'error_title'      => 'Song not found',
    'error_body'       => "This link may be invalid or the song hasn't been generated yet. If you think this is an error, contact us.",
    'for_label'        => 'A song created for',
    'generating_title' => 'Composing your song...',
    'generating_sub'   => 'Our AI is crafting something unique for you.<br>This usually takes a few minutes.',
    'refresh_btn'      => '↻ Check again',
    'player_label'     => '♪ Your personalized song',
    'download_btn'     => '↓ Download song',
    'lyrics_label'     => 'Lyrics',
    'copy_btn'         => 'Copy ↗',
    'copy_done'        => 'Copied ✓',
    'support_label'    => 'Any questions about your song?',
    'support_email'    => 'contact@tuned4u.com',
    'footer_sub'       => 'Songs created from real stories · tuned4u.com',
    'doc_title_suffix' => '— Tuned4U',
    'status_ready'     => 'GERADO',
  ],

];

// Aliases curtos para GIFT_LANG=es / GIFT_LANG=en
$langs['es'] = $langs['regalo.tuned4u.com'];
$langs['en'] = $langs['gift.tuned4u.com'];

// Seleção: env var > domínio > fallback EN
$cfg = $langs[$envLang] ?? $langs[$host] ?? $langs['gift.tuned4u.com'];

function jsStr($s) { return json_encode((string)$s, JSON_UNESCAPED_UNICODE); }
?>
<!DOCTYPE html>
<html lang="<?php echo htmlspecialchars($cfg['html_lang']); ?>">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?php echo htmlspecialchars($cfg['page_title']); ?></title>
  <meta name="robots" content="noindex, nofollow" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet" />
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --gold:       #F5A623;
      --gold-soft:  rgba(245,166,35,0.15);
      --gold-glow:  rgba(245,166,35,0.06);
      --bg:         #0d0d0d;
      --card:       #141414;
      --border:     rgba(245,166,35,0.15);
      --text:       #f0ead6;
      --muted:      #7a7060;
    }

    html, body {
      min-height: 100vh;
      background: var(--bg);
      color: var(--text);
      font-family: 'DM Sans', sans-serif;
      overflow-x: hidden;
    }

    body::before {
      content: '';
      position: fixed;
      inset: 0;
      background:
        radial-gradient(ellipse 80% 60% at 20% 10%, var(--gold-glow) 0%, transparent 60%),
        radial-gradient(ellipse 60% 80% at 80% 90%, rgba(245,166,35,0.04) 0%, transparent 60%);
      pointer-events: none;
      z-index: 0;
    }

    .page { position: relative; z-index: 1; }

    /* ── Loading ── */
    #loading {
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      gap: 24px;
    }
    .logo-mark {
      font-family: 'Cormorant Garamond', serif;
      font-size: 2rem;
      color: var(--gold);
      letter-spacing: 0.1em;
      font-weight: 300;
    }
    .spinner {
      width: 36px;
      height: 36px;
      border: 1.5px solid rgba(245,166,35,0.2);
      border-top-color: var(--gold);
      border-radius: 50%;
      animation: spin 0.8s linear infinite;
    }
    @keyframes spin { to { transform: rotate(360deg); } }

    /* ── Error ── */
    #error {
      min-height: 100vh;
      display: none;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      gap: 16px;
      text-align: center;
      padding: 32px;
    }
    #error h2 {
      font-family: 'Cormorant Garamond', serif;
      font-size: 2rem;
      color: var(--gold);
    }
    #error p  { color: var(--muted); font-size: 0.9rem; line-height: 1.6; max-width: 360px; }
    #error a  {
      color: var(--gold);
      font-size: 0.85rem;
      text-decoration: none;
      border-bottom: 1px solid rgba(245,166,35,0.3);
      padding-bottom: 2px;
    }

    /* ── Content ── */
    #content { display: none; min-height: 100vh; padding: 48px 24px 80px; }
    .container { max-width: 680px; margin: 0 auto; }

    /* ── Header ── */
    .header {
      text-align: center;
      margin-bottom: 48px;
      animation: fadeUp 0.8s ease both;
    }
    .brand {
      font-family: 'Cormorant Garamond', serif;
      font-size: 1rem;
      letter-spacing: 0.3em;
      text-transform: uppercase;
      color: var(--gold);
      font-weight: 400;
      margin-bottom: 28px;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 12px;
    }
    .brand::before, .brand::after {
      content: '';
      width: 40px;
      height: 1px;
      background: var(--gold);
      opacity: 0.4;
    }
    .song-title {
      font-family: 'Cormorant Garamond', serif;
      font-size: clamp(2rem, 6vw, 3.6rem);
      font-weight: 300;
      line-height: 1.15;
      color: var(--text);
      margin-bottom: 12px;
      font-style: italic;
    }
    .song-for {
      font-size: 0.85rem;
      color: var(--muted);
      letter-spacing: 0.08em;
      text-transform: uppercase;
    }
    .song-for span {
      color: var(--gold);
      font-style: italic;
      font-family: 'Cormorant Garamond', serif;
      font-size: 1rem;
      text-transform: none;
      letter-spacing: 0;
    }

    /* ── Cover ── */
    .cover-wrap {
      margin: 0 auto 40px;
      width: min(300px, 80vw);
      aspect-ratio: 1;
      position: relative;
      animation: fadeUp 0.8s 0.15s ease both;
    }
    .cover-wrap::before {
      content: '';
      position: absolute;
      inset: -1px;
      border-radius: 16px;
      background: linear-gradient(135deg, rgba(245,166,35,0.4), transparent 50%, rgba(245,166,35,0.15));
      z-index: 0;
    }
    .cover-img {
      width: 100%; height: 100%;
      object-fit: cover;
      border-radius: 15px;
      position: relative; z-index: 1;
      display: block;
    }
    .cover-placeholder {
      width: 100%; height: 100%;
      border-radius: 15px;
      background: var(--card);
      display: flex; align-items: center; justify-content: center;
      position: relative; z-index: 1;
      font-size: 4rem; opacity: 0.3;
    }

    /* ── Generating ── */
    .generating-card {
      background: var(--card);
      border: 1px solid var(--border);
      border-radius: 20px;
      padding: 48px 32px;
      text-align: center;
      margin-bottom: 24px;
      animation: fadeUp 0.8s 0.2s ease both;
    }
    .pulse-ring {
      width: 80px; height: 80px;
      border-radius: 50%;
      border: 1px solid rgba(245,166,35,0.3);
      margin: 0 auto 24px;
      display: flex; align-items: center; justify-content: center;
      animation: pulse 2s ease-in-out infinite;
      font-size: 2rem;
    }
    @keyframes pulse {
      0%, 100% { transform: scale(1); opacity: 1; }
      50%       { transform: scale(1.05); opacity: 0.7; }
    }
    .generating-title {
      font-family: 'Cormorant Garamond', serif;
      font-size: 1.6rem; font-weight: 300;
      color: var(--text); margin-bottom: 10px;
    }
    .generating-sub { font-size: 0.85rem; color: var(--muted); line-height: 1.7; }
    .refresh-btn {
      display: inline-flex; align-items: center; gap: 8px;
      margin-top: 24px; padding: 10px 20px;
      border-radius: 8px; border: 1px solid var(--border);
      background: none; color: var(--gold);
      font-family: 'DM Sans', sans-serif; font-size: 0.8rem;
      cursor: pointer; transition: background 0.2s;
    }
    .refresh-btn:hover { background: rgba(245,166,35,0.08); }

    /* ── Player ── */
    .player-card {
      background: var(--card);
      border: 1px solid var(--border);
      border-radius: 20px;
      padding: 32px; margin-bottom: 24px;
      animation: fadeUp 0.8s 0.25s ease both;
    }
    .player-label {
      font-size: 0.7rem; letter-spacing: 0.2em;
      text-transform: uppercase; color: var(--muted); margin-bottom: 16px;
    }
    audio { width: 100%; height: 44px; outline: none; border-radius: 8px; }
    .download-btn {
      display: flex; align-items: center; justify-content: center; gap: 8px;
      width: 100%; margin-top: 16px; padding: 12px;
      border-radius: 10px; border: 1px solid rgba(245,166,35,0.3);
      background: rgba(245,166,35,0.05); color: var(--gold);
      font-family: 'DM Sans', sans-serif; font-size: 0.85rem;
      cursor: pointer; text-decoration: none; transition: background 0.2s;
    }
    .download-btn:hover { background: rgba(245,166,35,0.12); }

    /* ── Divider ── */
    .divider {
      display: flex; align-items: center; gap: 16px;
      margin: 32px 0;
      animation: fadeUp 0.8s 0.3s ease both;
    }
    .divider::before, .divider::after {
      content: ''; flex: 1; height: 1px; background: var(--border);
    }
    .divider-icon { color: var(--gold); opacity: 0.4; font-size: 0.8rem; }

    /* ── Lyrics ── */
    .lyrics-card {
      background: var(--card);
      border: 1px solid var(--border);
      border-radius: 20px;
      padding: 32px;
      animation: fadeUp 0.8s 0.35s ease both;
    }
    .lyrics-header {
      display: flex; align-items: center; justify-content: space-between;
      margin-bottom: 24px;
    }
    .lyrics-label { font-size: 0.7rem; letter-spacing: 0.2em; text-transform: uppercase; color: var(--muted); }
    .copy-btn {
      font-size: 0.75rem; color: var(--gold);
      background: none; border: none; cursor: pointer;
      opacity: 0.7; transition: opacity 0.2s;
      font-family: 'DM Sans', sans-serif;
    }
    .copy-btn:hover { opacity: 1; }
    .lyrics-section { margin-bottom: 20px; }
    .lyrics-section-title {
      font-size: 0.7rem; letter-spacing: 0.15em; text-transform: uppercase;
      color: var(--gold); opacity: 0.6; margin-bottom: 8px;
      font-family: 'DM Sans', sans-serif;
    }
    .lyrics-body {
      white-space: pre-wrap;
      font-family: 'Cormorant Garamond', serif;
      font-size: 1.05rem; line-height: 1.9;
      color: rgba(240,234,214,0.85);
    }

    /* ── Support ── */
    .support-card {
      background: var(--card);
      border: 1px solid var(--border);
      border-radius: 16px;
      padding: 24px 28px; margin-top: 24px;
      display: flex; align-items: center; gap: 16px;
      animation: fadeUp 0.8s 0.4s ease both;
    }
    .support-icon { font-size: 1.4rem; flex-shrink: 0; opacity: 0.8; }
    .support-text { flex: 1; }
    .support-text p { font-size: 0.82rem; color: var(--muted); line-height: 1.5; }
    .support-text a {
      color: var(--gold); text-decoration: none; font-size: 0.82rem;
      border-bottom: 1px solid rgba(245,166,35,0.3); padding-bottom: 1px;
      transition: opacity 0.2s;
    }
    .support-text a:hover { opacity: 0.8; }

    /* ── Footer ── */
    .footer {
      text-align: center; margin-top: 56px;
      animation: fadeUp 0.8s 0.45s ease both;
    }
    .footer-brand {
      font-family: 'Cormorant Garamond', serif;
      font-size: 1.2rem; color: var(--gold);
      opacity: 0.5; letter-spacing: 0.2em; font-weight: 300;
    }
    .footer-sub { font-size: 0.72rem; color: var(--muted); margin-top: 6px; }

    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(20px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    @media (max-width: 480px) {
      #content { padding: 32px 16px 60px; }
      .player-card, .lyrics-card, .generating-card { padding: 24px 20px; }
      .support-card { flex-direction: column; text-align: center; }
    }
  </style>
</head>
<body>
<div class="page">

  <div id="loading">
    <div class="logo-mark"><?php echo htmlspecialchars($cfg['brand']); ?></div>
    <div class="spinner"></div>
  </div>

  <div id="error">
    <div class="logo-mark"><?php echo htmlspecialchars($cfg['brand']); ?></div>
    <h2><?php echo htmlspecialchars($cfg['error_title']); ?></h2>
    <p><?php echo htmlspecialchars($cfg['error_body']); ?></p>
    <a href="mailto:<?php echo htmlspecialchars($cfg['support_email']); ?>">
      <?php echo htmlspecialchars($cfg['support_email']); ?>
    </a>
  </div>

  <div id="content" class="page">
    <div class="container">

      <div class="header">
        <div class="brand"><?php echo htmlspecialchars($cfg['brand']); ?></div>
        <h1 class="song-title" id="titulo">—</h1>
        <p class="song-for">
          <?php echo htmlspecialchars($cfg['for_label']); ?>
          <span id="nome-display">—</span>
        </p>
      </div>

      <div class="cover-wrap" id="cover-wrap">
        <div class="cover-placeholder">🎵</div>
      </div>

      <div class="generating-card" id="generating-card" style="display:none">
        <div class="pulse-ring">🎼</div>
        <h2 class="generating-title"><?php echo htmlspecialchars($cfg['generating_title']); ?></h2>
        <p class="generating-sub"><?php echo $cfg['generating_sub']; ?></p>
        <button class="refresh-btn" onclick="location.reload()">
          <?php echo htmlspecialchars($cfg['refresh_btn']); ?>
        </button>
      </div>

      <div class="player-card" id="player-card" style="display:none">
        <p class="player-label"><?php echo htmlspecialchars($cfg['player_label']); ?></p>
        <audio id="audio-player" controls preload="metadata"></audio>
        <a id="download-link" class="download-btn" download>
          <?php echo htmlspecialchars($cfg['download_btn']); ?>
        </a>
      </div>

      <div class="divider" id="lyrics-divider" style="display:none">
        <span class="divider-icon">✦</span>
      </div>

      <div class="lyrics-card" id="lyrics-card" style="display:none">
        <div class="lyrics-header">
          <span class="lyrics-label"><?php echo htmlspecialchars($cfg['lyrics_label']); ?></span>
          <button class="copy-btn" id="copy-btn" onclick="copyLyrics()">
            <?php echo htmlspecialchars($cfg['copy_btn']); ?>
          </button>
        </div>
        <div id="lyrics-content"></div>
      </div>

      <div class="support-card" id="support-card" style="display:none">
        <div class="support-icon">💬</div>
        <div class="support-text">
          <p><?php echo htmlspecialchars($cfg['support_label']); ?></p>
          <a href="mailto:<?php echo htmlspecialchars($cfg['support_email']); ?>">
            <?php echo htmlspecialchars($cfg['support_email']); ?>
          </a>
        </div>
      </div>

      <div class="footer">
        <div class="footer-brand"><?php echo htmlspecialchars($cfg['brand']); ?></div>
        <p class="footer-sub"><?php echo htmlspecialchars($cfg['footer_sub']); ?></p>
      </div>

    </div>
  </div>
</div>

<script>
  const SUPABASE_URL  = 'https://qqxmdszwvwooqonmnvzf.supabase.co';
  const SUPABASE_ANON = 'sb_publishable_NDbDHWxxykBQ2FqMRgMDZg_O-mir9hv';

  const uuid        = <?php echo jsStr($uuid); ?>;
  const table       = <?php echo jsStr($cfg['table']); ?>;
  const statusReady = <?php echo jsStr($cfg['status_ready']); ?>;
  const copyDone    = <?php echo jsStr($cfg['copy_done']); ?>;
  const copyLabel   = <?php echo jsStr($cfg['copy_btn']); ?>;
  const titleSuffix = <?php echo jsStr($cfg['doc_title_suffix']); ?>;

  async function load() {
    if (!uuid) { showError(); return; }
    try {
      const res = await fetch(
        `${SUPABASE_URL}/rest/v1/${table}?uuid=eq.${encodeURIComponent(uuid)}&select=*&limit=1`,
        { headers: { apikey: SUPABASE_ANON, Authorization: `Bearer ${SUPABASE_ANON}` } }
      );
      if (!res.ok) { showError(); return; }
      const data = await res.json();
      if (!data || data.length === 0) { showError(); return; }
      render(data[0]);
    } catch(e) { showError(); }
  }

  function render(gift) {
    document.getElementById('loading').style.display = 'none';
    document.title = `${gift.titulo || '♪'} ${titleSuffix}`;
    document.getElementById('titulo').textContent      = gift.titulo || '♪';
    document.getElementById('nome-display').textContent = gift.nome  || '—';

    if (gift.cover_url) {
      document.getElementById('cover-wrap').innerHTML =
        `<img class="cover-img" src="${gift.cover_url}" alt="cover"
          onerror="this.parentElement.innerHTML='<div class=cover-placeholder>🎵</div>'" />`;
    }

    if (gift.audio_url && gift.status === statusReady) {
      const audio = document.getElementById('audio-player');
      audio.src = gift.audio_url;

      const dl = document.getElementById('download-link');
      dl.href = gift.audio_url;
      dl.setAttribute('download', `${gift.titulo || 'song'}.mp3`);

      document.getElementById('player-card').style.display  = 'block';
      document.getElementById('support-card').style.display = 'flex';

      if (gift.letra) {
        document.getElementById('lyrics-divider').style.display = 'flex';
        document.getElementById('lyrics-card').style.display    = 'block';
        document.getElementById('lyrics-content').innerHTML     = formatLyrics(gift.letra);
        window._lyricsRaw = gift.letra;
      }
    } else {
      document.getElementById('generating-card').style.display = 'block';
    }

    document.getElementById('content').style.display = 'block';
  }

  function formatLyrics(letra) {
    let text = letra
      .replace(/\*\*/g, '')
      .replace(/🎵[^\n]*/g, '')
      .replace(/💝[^\n]*/g, '')
      .trim();

    const sections = text.split(/\[([^\]]+)\]/);
    if (sections.length <= 1)
      return `<div class="lyrics-section"><p class="lyrics-body">${escHtml(text)}</p></div>`;

    let html = '';
    for (let i = 1; i < sections.length; i += 2) {
      const body = (sections[i + 1] || '').trim();
      if (!body) continue;
      html += `<div class="lyrics-section">
        <div class="lyrics-section-title">${escHtml(sections[i])}</div>
        <p class="lyrics-body">${escHtml(body)}</p>
      </div>`;
    }
    return html || `<p class="lyrics-body">${escHtml(text)}</p>`;
  }

  function escHtml(str) {
    return str
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;');
  }

  function copyLyrics() {
    const text = window._lyricsRaw || document.getElementById('lyrics-content').innerText;
    navigator.clipboard.writeText(text).then(() => {
      const btn = document.getElementById('copy-btn');
      btn.textContent = copyDone;
      setTimeout(() => btn.textContent = copyLabel, 2000);
    });
  }

  function showError() {
    document.getElementById('loading').style.display = 'none';
    document.getElementById('error').style.display   = 'flex';
  }

  load();
</script>
</body>
</html>
