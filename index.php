<?php
$uuid = trim($_GET['id'] ?? '', '/');
if (empty($uuid)) {
    $path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
    $uuid = $path;
}

$envLang = strtolower(trim(getenv('GIFT_LANG') ?: ''));
$host    = strtolower($_SERVER['HTTP_HOST'] ?? '');

$langs = [
  'regalo.tuned4u.com' => [
    'lang'             => 'es',
    'table'            => 'presentes_es',
    'html_lang'        => 'es',
    'page_title'       => 'Tuned4U — Tu Canción Personalizada',
    'error_title'      => 'Canción no encontrada',
    'error_body'       => 'Este enlace puede ser inválido o la canción aún no ha sido generada. Si crees que es un error, contáctanos.',
    'for_label'        => 'Una canción creada para',
    'generating_title' => 'Componiendo tu canción...',
    'generating_sub'   => 'Nuestra IA está creando algo único para ti. Esto generalmente tarda unos minutos.',
    'refresh_btn'      => 'Verificar de nuevo',
    'player_label'     => 'Tu canción personalizada',
    'download_btn'     => 'Descargar canción',
    'lyrics_label'     => 'Letra',
    'copy_btn'         => 'Copiar',
    'copy_done'        => 'Copiado ✓',
    'support_label'    => '¿Alguna pregunta sobre tu canción?',
    'support_email'    => 'contact@tuned4u.com',
    'footer_sub'       => 'Canciones creadas de historias reales · tuned4u.com',
    'doc_title_suffix' => '— Tuned4U',
    'status_ready'     => 'GERADO',
    'cta_label'        => 'Crear una canción para otra persona',
    'cta_url'          => 'https://pago.tuned4u.com',
    'sections'         => [
      'Intro'       => 'Intro',
      'Verse'       => 'Verso',
      'Verse 1'     => 'Verso 1',
      'Verse 2'     => 'Verso 2',
      'Pre-Chorus'  => 'Pre-Coro',
      'Chorus'      => 'Coro',
      'Final Chorus'=> 'Coro Final',
      'Bridge'      => 'Puente',
      'Outro'       => 'Outro',
    ],
    'chorus_keys' => ['Chorus','Final Chorus','Coro','Coro Final','Estribillo'],
  ],
  'gift.tuned4u.com' => [
    'lang'             => 'en',
    'table'            => 'tuned4u_gifts',
    'html_lang'        => 'en',
    'page_title'       => 'Tuned4U — Your Personal Song',
    'error_title'      => 'Song not found',
    'error_body'       => "This link may be invalid or the song hasn't been generated yet.",
    'for_label'        => 'A song created for',
    'generating_title' => 'Composing your song...',
    'generating_sub'   => 'Our AI is crafting something unique for you. This usually takes a few minutes.',
    'refresh_btn'      => 'Check again',
    'player_label'     => 'Your personalized song',
    'download_btn'     => 'Download song',
    'lyrics_label'     => 'Lyrics',
    'copy_btn'         => 'Copy',
    'copy_done'        => 'Copied ✓',
    'support_label'    => 'Any questions about your song?',
    'support_email'    => 'contact@tuned4u.com',
    'footer_sub'       => 'Songs created from real stories · tuned4u.com',
    'doc_title_suffix' => '— Tuned4U',
    'status_ready'     => 'GERADO',
    'cta_label'        => 'Create a song for someone else',
    'cta_url'          => 'https://tuned4u.com',
    'sections'         => [],
    'chorus_keys'      => ['Chorus','Final Chorus'],
  ],
];

$langs['es'] = $langs['regalo.tuned4u.com'];
$langs['en'] = $langs['gift.tuned4u.com'];

$cfg = $langs[$envLang] ?? $langs[$host] ?? $langs['gift.tuned4u.com'];

function jsStr($s) { return json_encode((string)$s, JSON_UNESCAPED_UNICODE); }
function jsArr($a) { return json_encode($a, JSON_UNESCAPED_UNICODE); }
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
      --gold: #F5A623;
      --gold-dim: rgba(245,166,35,0.18);
      --gold-glow: rgba(245,166,35,0.06);
      --bg: #0d0d0d;
      --card: #141414;
      --card2: #1a1a1a;
      --border: rgba(245,166,35,0.14);
      --text: #f0ead6;
      --muted: #7a7060;
      --chorus-bg: rgba(245,166,35,0.08);
      --chorus-border: rgba(245,166,35,0.35);
    }
    html, body { min-height: 100vh; background: var(--bg); color: var(--text); font-family: 'DM Sans', sans-serif; overflow-x: hidden; }
    body::before { content: ''; position: fixed; inset: 0; background: radial-gradient(ellipse 80% 50% at 50% 0%, rgba(245,166,35,0.07) 0%, transparent 65%); pointer-events: none; z-index: 0; }
    .page { position: relative; z-index: 1; }

    /* Loading */
    #loading { min-height: 100vh; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 20px; }
    .spinner { width: 32px; height: 32px; border: 1.5px solid rgba(245,166,35,0.2); border-top-color: var(--gold); border-radius: 50%; animation: spin 0.8s linear infinite; }
    @keyframes spin { to { transform: rotate(360deg); } }

    /* Error */
    #error { min-height: 100vh; display: none; flex-direction: column; align-items: center; justify-content: center; gap: 14px; text-align: center; padding: 32px; }
    #error h2 { font-family: 'Cormorant Garamond', serif; font-size: 2rem; color: var(--gold); }
    #error p { color: var(--muted); font-size: 0.9rem; line-height: 1.6; max-width: 340px; }
    #error a { color: var(--gold); font-size: 0.85rem; text-decoration: none; border-bottom: 1px solid rgba(245,166,35,0.3); padding-bottom: 2px; }

    /* Content */
    #content { display: none; min-height: 100vh; padding: 40px 20px 80px; }
    .container { max-width: 620px; margin: 0 auto; }

    /* Logo */
    .logo-wrap { text-align: center; margin-bottom: 36px; animation: fadeUp 0.7s ease both; }
    .logo-wrap img { height: 44px; width: auto; object-fit: contain; }

    /* Header */
    .header { text-align: center; margin-bottom: 36px; animation: fadeUp 0.7s 0.1s ease both; }
    .song-title { font-family: 'Cormorant Garamond', serif; font-size: clamp(2rem, 7vw, 3.4rem); font-weight: 300; line-height: 1.15; color: var(--text); margin-bottom: 10px; font-style: italic; }
    .song-for { font-size: 0.82rem; color: var(--muted); letter-spacing: 0.1em; text-transform: uppercase; }
    .song-for span { color: var(--gold); font-style: italic; font-family: 'Cormorant Garamond', serif; font-size: 0.95rem; text-transform: none; letter-spacing: 0; }

    /* Cover */
    .cover-wrap { margin: 0 auto 28px; width: min(260px, 72vw); aspect-ratio: 1; position: relative; animation: fadeUp 0.7s 0.15s ease both; }
    .cover-wrap::before { content: ''; position: absolute; inset: -1px; border-radius: 16px; background: linear-gradient(135deg, rgba(245,166,35,0.5), transparent 50%, rgba(245,166,35,0.2)); z-index: 0; }
    .cover-img { width: 100%; height: 100%; object-fit: cover; border-radius: 15px; position: relative; z-index: 1; display: block; }
    .cover-placeholder { width: 100%; height: 100%; border-radius: 15px; background: var(--card); display: flex; align-items: center; justify-content: center; position: relative; z-index: 1; font-size: 3.5rem; opacity: 0.25; }

    /* Generating */
    .generating-card { background: var(--card); border: 1px solid var(--border); border-radius: 20px; padding: 44px 28px; text-align: center; margin-bottom: 20px; animation: fadeUp 0.7s 0.2s ease both; }
    .pulse-ring { width: 72px; height: 72px; border-radius: 50%; border: 1px solid rgba(245,166,35,0.3); margin: 0 auto 20px; display: flex; align-items: center; justify-content: center; animation: pulse 2s ease-in-out infinite; font-size: 1.8rem; }
    @keyframes pulse { 0%,100%{transform:scale(1);opacity:1} 50%{transform:scale(1.05);opacity:0.7} }
    .generating-title { font-family: 'Cormorant Garamond', serif; font-size: 1.5rem; font-weight: 300; color: var(--text); margin-bottom: 8px; }
    .generating-sub { font-size: 0.83rem; color: var(--muted); line-height: 1.65; }
    .refresh-btn { display: inline-flex; align-items: center; gap: 7px; margin-top: 20px; padding: 9px 18px; border-radius: 8px; border: 1px solid var(--border); background: none; color: var(--gold); font-family: 'DM Sans', sans-serif; font-size: 0.78rem; cursor: pointer; transition: background 0.2s; }
    .refresh-btn:hover { background: rgba(245,166,35,0.08); }

    /* Custom Player */
    .player-card { background: var(--card); border: 1px solid var(--border); border-radius: 20px; padding: 24px 28px; margin-bottom: 20px; animation: fadeUp 0.7s 0.2s ease both; }
    .player-label { font-size: 0.68rem; letter-spacing: 0.2em; text-transform: uppercase; color: var(--muted); margin-bottom: 18px; }
    .player-main { display: flex; align-items: center; gap: 16px; margin-bottom: 16px; }
    .play-btn { width: 52px; height: 52px; border-radius: 50%; background: var(--gold); border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; flex-shrink: 0; transition: transform 0.15s, opacity 0.15s; }
    .play-btn:hover { opacity: 0.88; transform: scale(1.04); }
    .play-btn svg { width: 22px; height: 22px; fill: #0d0d0d; }
    .player-info { flex: 1; min-width: 0; }
    .player-song-name { font-family: 'Cormorant Garamond', serif; font-size: 1.05rem; font-weight: 400; color: var(--text); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-bottom: 2px; }
    .player-for-name { font-size: 0.75rem; color: var(--muted); }
    .progress-wrap { position: relative; height: 3px; background: rgba(245,166,35,0.15); border-radius: 2px; cursor: pointer; margin-bottom: 8px; }
    .progress-bar { height: 100%; background: var(--gold); border-radius: 2px; width: 0%; transition: width 0.1s linear; pointer-events: none; }
    .progress-thumb { position: absolute; top: 50%; width: 12px; height: 12px; background: var(--gold); border-radius: 50%; transform: translate(-50%, -50%); left: 0%; pointer-events: none; transition: left 0.1s linear; }
    .time-row { display: flex; justify-content: space-between; font-size: 0.7rem; color: var(--muted); margin-bottom: 14px; }
    .download-btn { display: flex; align-items: center; justify-content: center; gap: 7px; width: 100%; padding: 11px; border-radius: 10px; border: 1px solid rgba(245,166,35,0.28); background: rgba(245,166,35,0.05); color: var(--gold); font-family: 'DM Sans', sans-serif; font-size: 0.82rem; cursor: pointer; text-decoration: none; transition: background 0.2s; }
    .download-btn:hover { background: rgba(245,166,35,0.11); }
    .download-btn svg { width: 15px; height: 15px; stroke: var(--gold); fill: none; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }

    /* Divider */
    .divider { display: flex; align-items: center; gap: 14px; margin: 28px 0; animation: fadeUp 0.7s 0.28s ease both; }
    .divider::before, .divider::after { content: ''; flex: 1; height: 1px; background: var(--border); }
    .divider-icon { color: var(--gold); opacity: 0.4; font-size: 0.75rem; }

    /* Lyrics */
    .lyrics-card { background: var(--card); border: 1px solid var(--border); border-radius: 20px; padding: 28px; animation: fadeUp 0.7s 0.32s ease both; }
    .lyrics-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 22px; }
    .lyrics-label { font-size: 0.68rem; letter-spacing: 0.2em; text-transform: uppercase; color: var(--muted); }
    .copy-btn { font-size: 0.73rem; color: var(--gold); background: none; border: 1px solid rgba(245,166,35,0.25); border-radius: 6px; cursor: pointer; opacity: 0.85; transition: opacity 0.2s, background 0.2s; font-family: 'DM Sans', sans-serif; padding: 4px 10px; }
    .copy-btn:hover { opacity: 1; background: rgba(245,166,35,0.08); }
    .lyrics-section { margin-bottom: 22px; }
    .lyrics-section:last-child { margin-bottom: 0; }
    .lyrics-section-title { font-size: 0.65rem; letter-spacing: 0.18em; text-transform: uppercase; color: var(--gold); opacity: 0.55; margin-bottom: 7px; font-family: 'DM Sans', sans-serif; }
    .lyrics-body { white-space: pre-wrap; font-family: 'Cormorant Garamond', serif; font-size: 1.08rem; line-height: 1.95; color: rgba(240,234,214,0.82); text-align: center; }
    .lyrics-chorus { background: var(--chorus-bg); border-left: 3px solid var(--chorus-border); border-radius: 0 10px 10px 0; padding: 14px 18px; margin: 0 -4px; }
    .lyrics-chorus .lyrics-section-title { opacity: 1; color: var(--gold); }
    .lyrics-chorus .lyrics-body { color: var(--gold); opacity: 0.92; font-weight: 400; }

    /* CTA */
    .cta-card { background: var(--card2); border: 1px solid var(--border); border-radius: 16px; padding: 22px 24px; margin-top: 20px; text-align: center; animation: fadeUp 0.7s 0.38s ease both; }
    .cta-card p { font-size: 0.8rem; color: var(--muted); margin-bottom: 12px; }
    .cta-btn { display: inline-flex; align-items: center; gap: 7px; padding: 12px 24px; border-radius: 10px; background: var(--gold); color: #0d0d0d; font-family: 'DM Sans', sans-serif; font-size: 0.88rem; font-weight: 500; text-decoration: none; transition: opacity 0.2s; }
    .cta-btn:hover { opacity: 0.88; }

    /* Support */
    .support-row { display: flex; align-items: center; justify-content: center; gap: 8px; margin-top: 20px; padding: 16px; animation: fadeUp 0.7s 0.42s ease both; }
    .support-row p { font-size: 0.8rem; color: var(--muted); }
    .support-row a { color: var(--gold); text-decoration: none; font-size: 0.8rem; border-bottom: 1px solid rgba(245,166,35,0.3); padding-bottom: 1px; }

    /* Footer */
    .footer { text-align: center; margin-top: 48px; animation: fadeUp 0.7s 0.45s ease both; }
    .footer-brand { font-family: 'Cormorant Garamond', serif; font-size: 1.1rem; color: var(--gold); opacity: 0.4; letter-spacing: 0.2em; font-weight: 300; }
    .footer-sub { font-size: 0.7rem; color: var(--muted); margin-top: 5px; opacity: 0.7; }

    @keyframes fadeUp { from{opacity:0;transform:translateY(18px)} to{opacity:1;transform:translateY(0)} }
    @media(max-width:480px){ #content{padding:28px 14px 60px} .player-card,.lyrics-card,.generating-card{padding:20px 16px} .cover-wrap{width:min(220px,70vw)} }
  </style>
</head>
<body>
<div class="page">

  <div id="loading">
    <img src="https://qqxmdszwvwooqonmnvzf.supabase.co/storage/v1/object/public/assets/logo.tuned4u.nobackground.png" alt="Tuned4U" style="height:40px;width:auto;object-fit:contain;" />
    <div class="spinner"></div>
  </div>

  <div id="error">
    <img src="https://qqxmdszwvwooqonmnvzf.supabase.co/storage/v1/object/public/assets/logo.tuned4u.nobackground.png" alt="Tuned4U" style="height:40px;width:auto;margin-bottom:8px;" />
    <h2><?php echo htmlspecialchars($cfg['error_title']); ?></h2>
    <p><?php echo htmlspecialchars($cfg['error_body']); ?></p>
    <a href="mailto:<?php echo htmlspecialchars($cfg['support_email']); ?>"><?php echo htmlspecialchars($cfg['support_email']); ?></a>
  </div>

  <div id="content" class="page">
    <div class="container">

      <div class="logo-wrap">
        <img src="https://qqxmdszwvwooqonmnvzf.supabase.co/storage/v1/object/public/assets/logo.tuned4u.nobackground.png" alt="Tuned4U" />
      </div>

      <div class="header">
        <h1 class="song-title" id="titulo">—</h1>
        <p class="song-for"><?php echo htmlspecialchars($cfg['for_label']); ?> <span id="nome-display">—</span></p>
      </div>

      <div class="cover-wrap" id="cover-wrap">
        <div class="cover-placeholder">🎵</div>
      </div>

      <div class="generating-card" id="generating-card" style="display:none">
        <div class="pulse-ring">🎼</div>
        <h2 class="generating-title"><?php echo htmlspecialchars($cfg['generating_title']); ?></h2>
        <p class="generating-sub"><?php echo htmlspecialchars($cfg['generating_sub']); ?></p>
        <button class="refresh-btn" onclick="location.reload()">↻ <?php echo htmlspecialchars($cfg['refresh_btn']); ?></button>
      </div>

      <div class="player-card" id="player-card" style="display:none">
        <p class="player-label">♪ <?php echo htmlspecialchars($cfg['player_label']); ?></p>
        <div class="player-main">
          <button class="play-btn" id="play-btn" onclick="togglePlay()">
            <svg id="icon-play" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
            <svg id="icon-pause" viewBox="0 0 24 24" style="display:none"><path d="M6 4h4v16H6V4zm8 0h4v16h-4V4z"/></svg>
          </button>
          <div class="player-info">
            <div class="player-song-name" id="player-titulo">—</div>
            <div class="player-for-name" id="player-nome">—</div>
          </div>
        </div>
        <div class="progress-wrap" id="progress-wrap" onclick="seekAudio(event)">
          <div class="progress-bar" id="progress-bar"></div>
          <div class="progress-thumb" id="progress-thumb"></div>
        </div>
        <div class="time-row">
          <span id="time-cur">0:00</span>
          <span id="time-dur">—</span>
        </div>
        <a id="download-link" class="download-btn" download>
          <svg viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
          <?php echo htmlspecialchars($cfg['download_btn']); ?>
        </a>
        <audio id="audio-player" preload="metadata" style="display:none"></audio>
      </div>

      <div class="divider" id="lyrics-divider" style="display:none"><span class="divider-icon">✦</span></div>

      <div class="lyrics-card" id="lyrics-card" style="display:none">
        <div class="lyrics-header">
          <span class="lyrics-label"><?php echo htmlspecialchars($cfg['lyrics_label']); ?></span>
          <button class="copy-btn" id="copy-btn" onclick="copyLyrics()"><?php echo htmlspecialchars($cfg['copy_btn']); ?></button>
        </div>
        <div id="lyrics-content"></div>
      </div>

      <div class="cta-card" id="cta-card" style="display:none">
        <a class="cta-btn" href="<?php echo htmlspecialchars($cfg['cta_url']); ?>">
          🎵 <?php echo htmlspecialchars($cfg['cta_label']); ?>
        </a>
      </div>

      <div class="support-row" id="support-row" style="display:none">
        <p><?php echo htmlspecialchars($cfg['support_label']); ?> <a href="mailto:<?php echo htmlspecialchars($cfg['support_email']); ?>"><?php echo htmlspecialchars($cfg['support_email']); ?></a></p>
      </div>

      <div class="footer">
        <div class="footer-brand">Tuned4U</div>
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
  const sectionMap  = <?php echo jsArr($cfg['sections']); ?>;
  const chorusKeys  = <?php echo jsArr($cfg['chorus_keys']); ?>;

  const audio = document.getElementById('audio-player');

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
    document.getElementById('titulo').textContent       = gift.titulo || '♪';
    document.getElementById('nome-display').textContent = gift.nome   || '—';
    document.getElementById('player-titulo').textContent = gift.titulo || '♪';
    document.getElementById('player-nome').textContent   = gift.nome   || '—';

    if (gift.cover_url) {
      document.getElementById('cover-wrap').innerHTML =
        `<img class="cover-img" src="${gift.cover_url}" alt="portada"
          onerror="this.parentElement.innerHTML='<div class=cover-placeholder>🎵</div>'" />`;
    }

    if (gift.audio_url && gift.status === statusReady) {
      audio.src = gift.audio_url;
      const dl = document.getElementById('download-link');
      dl.href = gift.audio_url;
      dl.setAttribute('download', `${gift.titulo || 'song'}.mp3`);
      document.getElementById('player-card').style.display = 'block';
      document.getElementById('cta-card').style.display    = 'block';
      document.getElementById('support-row').style.display = 'flex';

      audio.addEventListener('timeupdate', updateProgress);
      audio.addEventListener('loadedmetadata', () => {
        document.getElementById('time-dur').textContent = fmtTime(audio.duration);
      });
      audio.addEventListener('ended', () => {
        document.getElementById('icon-play').style.display  = '';
        document.getElementById('icon-pause').style.display = 'none';
      });

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

  function togglePlay() {
    if (audio.paused) {
      audio.play();
      document.getElementById('icon-play').style.display  = 'none';
      document.getElementById('icon-pause').style.display = '';
    } else {
      audio.pause();
      document.getElementById('icon-play').style.display  = '';
      document.getElementById('icon-pause').style.display = 'none';
    }
  }

  function updateProgress() {
    if (!audio.duration) return;
    const pct = (audio.currentTime / audio.duration) * 100;
    document.getElementById('progress-bar').style.width  = pct + '%';
    document.getElementById('progress-thumb').style.left = pct + '%';
    document.getElementById('time-cur').textContent = fmtTime(audio.currentTime);
  }

  function seekAudio(e) {
    if (!audio.duration) return;
    const rect = e.currentTarget.getBoundingClientRect();
    const pct  = Math.max(0, Math.min(1, (e.clientX - rect.left) / rect.width));
    audio.currentTime = pct * audio.duration;
  }

  function fmtTime(s) {
    if (isNaN(s)) return '—';
    const m = Math.floor(s / 60);
    const ss = Math.floor(s % 60).toString().padStart(2, '0');
    return `${m}:${ss}`;
  }

  function formatLyrics(letra) {
    let text = letra.replace(/\*\*/g,'').replace(/🎵[^\n]*/g,'').replace(/💝[^\n]*/g,'').trim();
    const sections = text.split(/\[([^\]]+)\]/);
    if (sections.length <= 1)
      return `<div class="lyrics-section"><p class="lyrics-body">${escHtml(text)}</p></div>`;
    let html = '';
    for (let i = 1; i < sections.length; i += 2) {
      const rawKey = sections[i].trim();
      const body   = (sections[i + 1] || '').trim();
      if (!body) continue;
      const label   = sectionMap[rawKey] || rawKey;
      const isChorus = chorusKeys.some(k => rawKey === k || label === k);
      const cls = isChorus ? 'lyrics-section lyrics-chorus' : 'lyrics-section';
      html += `<div class="${cls}">
        <div class="lyrics-section-title">${escHtml(label)}</div>
        <p class="lyrics-body">${escHtml(body)}</p>
      </div>`;
    }
    return html || `<p class="lyrics-body">${escHtml(text)}</p>`;
  }

  function escHtml(str) {
    return str.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
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
