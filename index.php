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
    'tribute_label'    => 'Un tributo especial para',
    'love_from'        => 'Con amor, de',
    'error_title'      => 'Canción no encontrada',
    'error_body'       => 'Este enlace puede ser inválido o la canción aún no ha sido generada.',
    'generating_title' => 'Componiendo tu canción...',
    'generating_sub'   => 'Nuestra IA está creando algo único. Esto tarda unos minutos.',
    'refresh_btn'      => 'Verificar de nuevo',
    'lyrics_label'     => 'Letra de la canción',
    'love_suffix'      => 'con amor ❤️',
    'download_title'   => 'Descarga tu regalo',
    'download_sub'     => 'Todos los archivos de tu tributo',
    'dl_music'         => 'Música',
    'dl_photo'         => 'Portada',
    'share_whatsapp'   => 'Compartir por WhatsApp',
    'cta_title'        => 'Crea una canción para alguien especial',
    'cta_sub'          => 'Convierte tu historia en una canción personalizada en minutos.',
    'cta_btn'          => 'Crear mi canción',
    'cta_url'          => 'https://chat.digitalagencia.store/es?utm_source=regalo&utm_medium=pagina_presente&utm_campaign=recompra&utm_content=cta_btn',
    'footer_sub'       => 'Convirtiendo momentos en melodías inolvidables',
    'support_label'    => '¿Preguntas?',
    'support_email'    => 'contact@tuned4u.com',
    'status_ready'     => 'GERADO',
    'copy_btn'         => 'Copiar',
    'copy_done'        => 'Copiado ✓',
    'sections' => [
      'Intro'=>'Intro','Verse'=>'Verso','Verse 1'=>'Verso 1','Verse 2'=>'Verso 2',
      'Pre-Chorus'=>'Pre-Coro','Chorus'=>'Coro','Final Chorus'=>'Coro Final',
      'Bridge'=>'Puente','Outro'=>'Outro',
    ],
    'chorus_keys' => ['Chorus','Final Chorus','Coro','Coro Final','Estribillo'],
    'share_msg_tpl' => '🎵 Escucha "{title}" — ¡un tributo especial para {honoree}! Hecho con amor por Tuned4U ❤️\n\n{url}',
  ],
  'gift.tuned4u.com' => [
    'lang'             => 'en',
    'table'            => 'tuned4u_gifts',
    'html_lang'        => 'en',
    'page_title'       => 'Tuned4U — Your Personal Song',
    'tribute_label'    => 'A special tribute for',
    'love_from'        => 'With love, from',
    'error_title'      => 'Song not found',
    'error_body'       => "This link may be invalid or the song hasn't been generated yet.",
    'generating_title' => 'Composing your song...',
    'generating_sub'   => 'Our AI is crafting something unique. This usually takes a few minutes.',
    'refresh_btn'      => 'Check again',
    'lyrics_label'     => 'Song Lyrics',
    'love_suffix'      => 'with love ❤️',
    'download_title'   => 'Download your gift',
    'download_sub'     => 'All files from your tribute',
    'dl_music'         => 'Music',
    'dl_photo'         => 'Photo',
    'share_whatsapp'   => 'Share on WhatsApp',
    'cta_title'        => 'Create a song for someone you love',
    'cta_sub'          => 'Turn your story into an exclusive personalized song in minutes.',
    'cta_btn'          => 'Create my song',
    'cta_url'          => 'https://tuned4u.com',
    'footer_sub'       => 'Turning moments into unforgettable melodies',
    'support_label'    => 'Questions?',
    'support_email'    => 'contact@tuned4u.com',
    'status_ready'     => 'GERADO',
    'copy_btn'         => 'Copy',
    'copy_done'        => 'Copied ✓',
    'sections'         => [],
    'chorus_keys'      => ['Chorus','Final Chorus'],
    'share_msg_tpl'    => '🎵 Listen to "{title}" — a special tribute for {honoree}! Made with love by Tuned4U ❤️\n\n{url}',
  ],
];
$langs['es'] = $langs['regalo.tuned4u.com'];
$langs['en'] = $langs['gift.tuned4u.com'];
$cfg = $langs[$envLang] ?? $langs[$host] ?? $langs['gift.tuned4u.com'];
function jsStr($s){return json_encode((string)$s,JSON_UNESCAPED_UNICODE);}
function jsArr($a){return json_encode($a,JSON_UNESCAPED_UNICODE);}
?>
<!DOCTYPE html>
<html lang="<?php echo htmlspecialchars($cfg['html_lang']); ?>">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width,initial-scale=1.0"/>
<title><?php echo htmlspecialchars($cfg['page_title']); ?></title>
<meta name="robots" content="noindex,nofollow"/>
<link rel="preconnect" href="https://fonts.googleapis.com"/>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;1,400;1,600&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet"/>
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{
  --gold:#C9A84C;
  --gold2:#E8C97A;
  --gold-grad:linear-gradient(135deg,#C9A84C,#E8C97A);
  --bg:#0f0e0c;
  --bg2:#161410;
  --card:#1C1A16;
  --card2:#221f19;
  --border:rgba(201,168,76,0.18);
  --border2:rgba(201,168,76,0.32);
  --text:#F5F0E8;
  --text2:#B8A880;
  --muted:#6B6050;
  --radius:16px;
  --radius-sm:10px;
}
html,body{min-height:100vh;background:var(--bg);color:var(--text);font-family:'Inter',sans-serif;overflow-x:hidden}
body::before{content:'';position:fixed;inset:0;background:radial-gradient(ellipse 70% 40% at 50% 0%,rgba(201,168,76,0.07) 0%,transparent 60%);pointer-events:none;z-index:0}
.page{position:relative;z-index:1;max-width:480px;margin:0 auto;padding:32px 16px 80px}
img{display:block}

/* Loading / Error */
.center-screen{min-height:100vh;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:16px;padding:32px;text-align:center}
.spinner{width:32px;height:32px;border:1.5px solid rgba(201,168,76,0.2);border-top-color:var(--gold);border-radius:50%;animation:spin .8s linear infinite}
@keyframes spin{to{transform:rotate(360deg)}}
.error-title{font-family:'Cormorant Garamond',serif;font-size:1.8rem;background:var(--gold-grad);-webkit-background-clip:text;-webkit-text-fill-color:transparent}
.error-p{font-size:.85rem;color:var(--text2);line-height:1.6;max-width:300px}
.error-a{color:var(--gold);font-size:.82rem;text-decoration:none;border-bottom:1px solid rgba(201,168,76,.3);padding-bottom:2px}

/* Fade animation */
@keyframes fadeUp{from{opacity:0;transform:translateY(16px)}to{opacity:1;transform:translateY(0)}}
.fade1{animation:fadeUp .6s ease both}
.fade2{animation:fadeUp .6s .12s ease both}
.fade3{animation:fadeUp .6s .22s ease both}
.fade4{animation:fadeUp .6s .32s ease both}
.fade5{animation:fadeUp .6s .42s ease both}
.fade6{animation:fadeUp .6s .52s ease both}

/* Header */
.header-section{text-align:center;margin-bottom:28px}
.header-icon{display:flex;align-items:center;justify-content:center;gap:4px;margin-bottom:14px}
.icon-circle{width:40px;height:40px;border-radius:50%;background:rgba(201,168,76,.12);display:flex;align-items:center;justify-content:center;font-size:1.1rem}
.tribute-label{font-size:.65rem;letter-spacing:.3em;text-transform:uppercase;color:var(--gold);margin-bottom:8px}
.honoree-name{font-family:'Cormorant Garamond',serif;font-size:clamp(2.4rem,9vw,3.6rem);font-weight:600;background:var(--gold-grad);-webkit-background-clip:text;-webkit-text-fill-color:transparent;line-height:1.1;margin-bottom:8px}
.love-from{font-size:.82rem;color:var(--text2)}
.love-from strong{color:var(--text);font-weight:500}

/* Cover + Player card */
.player-card{background:rgba(28,26,22,.8);border:1px solid var(--border);border-radius:20px;overflow:hidden;margin-bottom:20px;backdrop-filter:blur(12px)}
.cover-area{position:relative;max-width:280px;margin:24px auto 0;border-radius:14px;overflow:hidden}
.cover-area img{width:100%;object-fit:cover;border-radius:14px;display:block}
.cover-overlay{position:absolute;inset:0;background:linear-gradient(to top,rgba(15,14,12,.92) 0%,rgba(15,14,12,.15) 50%,transparent 100%);border-radius:14px}
.cover-text{position:absolute;bottom:14px;left:16px;right:16px}
.cover-title{font-family:'Cormorant Garamond',serif;font-size:1.15rem;font-weight:600;color:var(--text);line-height:1.2;margin-bottom:2px}
.cover-style{font-size:.72rem;color:var(--text2)}
.cover-placeholder{width:100%;aspect-ratio:1;background:var(--card2);border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:3rem;opacity:.2}
.player-controls{padding:20px 20px 20px}
.progress-row{display:flex;align-items:center;gap:10px;margin-bottom:12px}
.time-label{font-size:.68rem;color:var(--muted);min-width:32px;font-variant-numeric:tabular-nums}
.time-label.right{text-align:right}
.progress-wrap{flex:1;height:4px;background:rgba(201,168,76,.15);border-radius:2px;cursor:pointer;position:relative}
.progress-fill{height:100%;background:var(--gold-grad);border-radius:2px;width:0%;pointer-events:none}
.btn-row{display:flex;align-items:center;justify-content:center}
.play-btn{width:56px;height:56px;border-radius:50%;background:var(--gold-grad);border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:transform .15s,opacity .15s;box-shadow:0 0 24px rgba(201,168,76,.25)}
.play-btn:hover{opacity:.88;transform:scale(1.05)}
.play-btn svg{width:24px;height:24px;fill:#0f0e0c}

/* Generating */
.generating-card{background:var(--card);border:1px solid var(--border);border-radius:20px;padding:40px 24px;text-align:center;margin-bottom:20px}
.pulse-ring{width:64px;height:64px;border-radius:50%;border:1px solid rgba(201,168,76,.3);margin:0 auto 18px;display:flex;align-items:center;justify-content:center;animation:pulse 2s ease-in-out infinite;font-size:1.6rem}
@keyframes pulse{0%,100%{transform:scale(1);opacity:1}50%{transform:scale(1.06);opacity:.7}}
.gen-title{font-family:'Cormorant Garamond',serif;font-size:1.45rem;font-weight:400;color:var(--text);margin-bottom:8px}
.gen-sub{font-size:.82rem;color:var(--text2);line-height:1.6}
.refresh-btn{display:inline-flex;align-items:center;gap:6px;margin-top:18px;padding:8px 16px;border-radius:8px;border:1px solid var(--border);background:none;color:var(--gold);font-size:.75rem;cursor:pointer;transition:background .2s;font-family:'Inter',sans-serif}
.refresh-btn:hover{background:rgba(201,168,76,.08)}

/* Download row inside player */
.dl-row{display:flex;gap:10px;margin-top:14px}
.dl-btn{flex:1;display:flex;align-items:center;justify-content:center;gap:6px;padding:10px;border-radius:var(--radius-sm);border:1px solid var(--border);background:rgba(201,168,76,.05);color:var(--gold);font-size:.75rem;text-decoration:none;transition:background .2s;cursor:pointer}
.dl-btn:hover{background:rgba(201,168,76,.12)}
.dl-btn svg{width:14px;height:14px;stroke:var(--gold);fill:none;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;flex-shrink:0}
.dl-btn.disabled{opacity:.35;pointer-events:none}

/* Lyrics */
.lyrics-card{border:1px solid rgba(201,168,76,.22);border-radius:20px;overflow:hidden;margin-bottom:20px;position:relative}
.lyrics-card::before{content:'';position:absolute;inset:-1px;border-radius:21px;background:var(--gold-grad);opacity:.08;pointer-events:none;z-index:0}
.lyrics-inner{position:relative;z-index:1}
.lyrics-header-band{background:var(--gold-grad);padding:16px 24px;text-align:center}
.lyrics-header-label{font-size:.62rem;letter-spacing:.3em;text-transform:uppercase;color:rgba(15,14,12,.65);margin-bottom:4px}
.lyrics-header-title{font-family:'Cormorant Garamond',serif;font-size:1.15rem;font-weight:600;color:#0f0e0c}
.lyrics-sep{display:flex;align-items:center;justify-content:center;gap:8px;padding:12px 0;border-bottom:1px solid var(--border)}
.lyrics-sep span{font-size:.75rem;color:rgba(201,168,76,.5)}
.lyrics-sep::before,.lyrics-sep::after{content:'';width:32px;height:1px;background:rgba(201,168,76,.25)}
.lyrics-body-wrap{padding:20px 24px 16px;background:var(--card)}
.lyrics-section{margin-bottom:18px}
.lyrics-section:last-child{margin-bottom:0}
.ls-label{font-size:.62rem;letter-spacing:.18em;text-transform:uppercase;color:var(--muted);margin-bottom:6px;font-weight:500}
.ls-body{white-space:pre-wrap;font-family:'Cormorant Garamond',serif;font-size:1.05rem;line-height:1.9;color:rgba(245,240,232,.78);text-align:center}
.lyrics-section.is-chorus .ls-label{color:var(--gold)}
.lyrics-section.is-chorus .ls-body{color:var(--gold2);font-weight:500;padding-left:14px;border-left:2px solid rgba(201,168,76,.4)}
.lyrics-footer{display:flex;align-items:center;justify-content:space-between;padding:12px 20px;border-top:1px solid var(--border);background:var(--card)}
.lyrics-footer-brand{font-size:.72rem;font-weight:600;background:var(--gold-grad);-webkit-background-clip:text;-webkit-text-fill-color:transparent}
.lyrics-footer-for{font-size:.7rem;color:var(--text2);font-style:italic}
.copy-btn-wrap{display:flex;justify-content:center;padding:10px 0 14px;background:var(--card)}
.copy-btn{font-size:.73rem;color:var(--gold);background:none;border:1px solid rgba(201,168,76,.25);border-radius:6px;cursor:pointer;padding:4px 12px;font-family:'Inter',sans-serif;transition:background .2s}
.copy-btn:hover{background:rgba(201,168,76,.08)}

/* WhatsApp */
.wa-btn{display:flex;align-items:center;justify-content:center;gap:8px;width:100%;padding:14px;border-radius:var(--radius);background:#25D366;color:#fff;font-size:.9rem;font-weight:600;border:none;cursor:pointer;transition:opacity .2s;text-decoration:none;margin-bottom:20px}
.wa-btn:hover{opacity:.88}
.wa-btn svg{width:18px;height:18px;fill:#fff;flex-shrink:0}

/* CTA */
.cta-card{background:var(--card);border:1px solid var(--border);border-radius:20px;padding:24px;text-align:center;margin-bottom:20px}
.cta-icon-circle{width:48px;height:48px;border-radius:50%;background:rgba(201,168,76,.1);display:flex;align-items:center;justify-content:center;margin:0 auto 14px;font-size:1.3rem}
.cta-title{font-family:'Cormorant Garamond',serif;font-size:1.15rem;font-weight:600;color:var(--text);margin-bottom:6px}
.cta-sub{font-size:.78rem;color:var(--text2);line-height:1.55;margin-bottom:16px}
.cta-btn{display:flex;align-items:center;justify-content:center;gap:8px;width:100%;padding:13px;border-radius:var(--radius-sm);background:var(--gold-grad);color:#0f0e0c;font-weight:600;font-size:.88rem;text-decoration:none;border:none;cursor:pointer;transition:opacity .2s}
.cta-btn:hover{opacity:.88}

/* Support */
.support-line{text-align:center;padding:4px 0 16px;font-size:.76rem;color:var(--text2)}
.support-line a{color:var(--gold);text-decoration:none;border-bottom:1px solid rgba(201,168,76,.3);padding-bottom:1px}

/* Footer */
.footer{text-align:center;padding:8px 0 16px}
.footer img{height:36px;width:auto;margin:0 auto 8px}
.footer-sub{font-size:.68rem;color:var(--muted)}

@media(max-width:400px){.page{padding:24px 12px 60px}.cover-area{max-width:240px}}
</style>
</head>
<body>

<div id="loading" class="center-screen">
  <img src="https://qqxmdszwvwooqonmnvzf.supabase.co/storage/v1/object/public/assets/logo.tuned4u.nobackground.png" alt="Tuned4U" style="height:38px;width:auto"/>
  <div class="spinner"></div>
</div>

<div id="error" class="center-screen" style="display:none">
  <img src="https://qqxmdszwvwooqonmnvzf.supabase.co/storage/v1/object/public/assets/logo.tuned4u.nobackground.png" alt="Tuned4U" style="height:38px;width:auto"/>
  <h2 class="error-title"><?php echo htmlspecialchars($cfg['error_title']); ?></h2>
  <p class="error-p"><?php echo htmlspecialchars($cfg['error_body']); ?></p>
  <a class="error-a" href="mailto:<?php echo htmlspecialchars($cfg['support_email']); ?>"><?php echo htmlspecialchars($cfg['support_email']); ?></a>
</div>

<div id="content" class="page" style="display:none">

  <!-- Header -->
  <div class="header-section fade1">
    <div class="header-icon">
      <div class="icon-circle">🎁</div>
      <div class="icon-circle" style="width:24px;height:24px;font-size:.7rem;margin-left:-8px;margin-top:-16px;background:rgba(201,168,76,.18)">❤️</div>
    </div>
    <p class="tribute-label"><?php echo htmlspecialchars($cfg['tribute_label']); ?></p>
    <h1 class="honoree-name" id="honoree-name">—</h1>
    <p class="love-from"><?php echo htmlspecialchars($cfg['love_from']); ?> <strong id="client-name">—</strong></p>
  </div>

  <!-- Generating state -->
  <div class="generating-card fade2" id="generating-card" style="display:none">
    <div class="pulse-ring">🎼</div>
    <h2 class="gen-title"><?php echo htmlspecialchars($cfg['generating_title']); ?></h2>
    <p class="gen-sub"><?php echo htmlspecialchars($cfg['generating_sub']); ?></p>
    <button class="refresh-btn" onclick="location.reload()">↻ <?php echo htmlspecialchars($cfg['refresh_btn']); ?></button>
  </div>

  <!-- Player card -->
  <div class="player-card fade2" id="player-card" style="display:none">
    <div class="cover-area" id="cover-area">
      <div class="cover-placeholder" id="cover-placeholder">🎵</div>
    </div>
    <div class="player-controls">
      <div class="progress-row">
        <span class="time-label" id="time-cur">0:00</span>
        <div class="progress-wrap" id="progress-wrap" onclick="seekAudio(event)">
          <div class="progress-fill" id="progress-fill"></div>
        </div>
        <span class="time-label right" id="time-dur">—</span>
      </div>
      <div class="btn-row">
        <button class="play-btn" id="play-btn" onclick="togglePlay()">
          <svg id="icon-play" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
          <svg id="icon-pause" viewBox="0 0 24 24" style="display:none"><path d="M6 4h4v16H6V4zm8 0h4v16h-4V4z"/></svg>
        </button>
      </div>
      <div class="dl-row">
        <a id="dl-music" class="dl-btn disabled" download>
          <svg viewBox="0 0 24 24"><path d="M9 18V5l12-2v13"/><circle cx="6" cy="18" r="3"/><circle cx="18" cy="16" r="3"/></svg>
          <?php echo htmlspecialchars($cfg['dl_music']); ?>
        </a>
        <a id="dl-photo" class="dl-btn disabled" download>
          <svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
          <?php echo htmlspecialchars($cfg['dl_photo']); ?>
        </a>
      </div>
    </div>
    <audio id="audio-player" preload="metadata" style="display:none"></audio>
  </div>

  <!-- Lyrics -->
  <div class="lyrics-card fade3" id="lyrics-card" style="display:none">
    <div class="lyrics-inner">
      <div class="lyrics-header-band">
        <div class="lyrics-header-label"><?php echo htmlspecialchars($cfg['lyrics_label']); ?></div>
        <div class="lyrics-header-title" id="lyrics-titulo">—</div>
      </div>
      <div class="lyrics-sep"><span>♪ ♫ ♪</span></div>
      <div class="lyrics-body-wrap" id="lyrics-content"></div>
      <div class="copy-btn-wrap">
        <button class="copy-btn" id="copy-btn" onclick="copyLyrics()"><?php echo htmlspecialchars($cfg['copy_btn']); ?></button>
      </div>
      <div class="lyrics-footer">
        <span class="lyrics-footer-brand">Tuned4U</span>
        <span class="lyrics-footer-for" id="lyrics-footer-for">—</span>
      </div>
    </div>
  </div>

  <!-- WhatsApp -->
  <a class="wa-btn fade4" id="wa-btn" href="#" target="_blank" style="display:none">
    <svg viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M11.999 2C6.477 2 2 6.477 2 12c0 1.89.525 3.66 1.438 5.168L2.009 22l4.973-1.403A9.954 9.954 0 0012 22c5.523 0 10-4.477 10-10S17.522 2 11.999 2z"/></svg>
    <?php echo htmlspecialchars($cfg['share_whatsapp']); ?>
  </a>

  <!-- CTA -->
  <div class="cta-card fade5" id="cta-card" style="display:none">
    <div class="cta-icon-circle">✨</div>
    <h3 class="cta-title"><?php echo htmlspecialchars($cfg['cta_title']); ?></h3>
    <p class="cta-sub"><?php echo htmlspecialchars($cfg['cta_sub']); ?></p>
    <a class="cta-btn" href="<?php echo htmlspecialchars($cfg['cta_url']); ?>" target="_blank">
      ✨ <?php echo htmlspecialchars($cfg['cta_btn']); ?>
    </a>
  </div>

  <!-- Support -->
  <p class="support-line fade5" id="support-line" style="display:none">
    <?php echo htmlspecialchars($cfg['support_label']); ?>
    <a href="mailto:<?php echo htmlspecialchars($cfg['support_email']); ?>"><?php echo htmlspecialchars($cfg['support_email']); ?></a>
  </p>

  <!-- Footer -->
  <div class="footer fade6">
    <img src="https://qqxmdszwvwooqonmnvzf.supabase.co/storage/v1/object/public/assets/logo.tuned4u.nobackground.png" alt="Tuned4U"/>
    <p class="footer-sub"><?php echo htmlspecialchars($cfg['footer_sub']); ?></p>
  </div>

</div>

<script>
const SUPABASE_URL  = 'https://qqxmdszwvwooqonmnvzf.supabase.co';
const SUPABASE_ANON = 'sb_publishable_NDbDHWxxykBQ2FqMRgMDZg_O-mir9hv';
const uuid         = <?php echo jsStr($uuid); ?>;
const table        = <?php echo jsStr($cfg['table']); ?>;
const statusReady  = <?php echo jsStr($cfg['status_ready']); ?>;
const copyDone     = <?php echo jsStr($cfg['copy_done']); ?>;
const copyLabel    = <?php echo jsStr($cfg['copy_btn']); ?>;
const sectionMap   = <?php echo jsArr($cfg['sections']); ?>;
const chorusKeys   = <?php echo jsArr($cfg['chorus_keys']); ?>;
const shareTpl     = <?php echo jsStr($cfg['share_msg_tpl']); ?>;
const loveLabel    = <?php echo jsStr($cfg['love_suffix']); ?>;

const audio = document.getElementById('audio-player');

async function load(){
  if(!uuid){showError();return;}
  try{
    const res = await fetch(`${SUPABASE_URL}/rest/v1/${table}?uuid=eq.${encodeURIComponent(uuid)}&select=*&limit=1`,
      {headers:{apikey:SUPABASE_ANON,Authorization:`Bearer ${SUPABASE_ANON}`}});
    if(!res.ok){showError();return;}
    const data = await res.json();
    if(!data||data.length===0){showError();return;}
    render(data[0]);
  }catch(e){showError();}
}

function render(g){
  document.getElementById('loading').style.display='none';
  document.getElementById('honoree-name').textContent = g.nome||'—';
  document.getElementById('client-name').textContent  = g.nome||'—';
  document.getElementById('lyrics-titulo').textContent = g.titulo||'—';
  document.getElementById('lyrics-footer-for').textContent = `${g.nome||'—'}, ${loveLabel}`;

  if(g.cover_url){
    document.getElementById('cover-area').innerHTML=
      `<img src="${g.cover_url}" alt="cover" onerror="this.parentElement.innerHTML='<div class=cover-placeholder>🎵</div>'"/>
       <div class="cover-overlay"></div>
       <div class="cover-text">
         <div class="cover-title">${escHtml(g.titulo||'')}</div>
       </div>`;
  }

  if(g.audio_url && g.status===statusReady){
    audio.src = g.audio_url;
    const dlM = document.getElementById('dl-music');
    dlM.href = g.audio_url;
    dlM.setAttribute('download',(g.titulo||'song')+'.mp3');
    dlM.classList.remove('disabled');
    if(g.cover_url){
      const dlP = document.getElementById('dl-photo');
      dlP.href = g.cover_url;
      dlP.setAttribute('download',(g.titulo||'cover')+'.jpg');
      dlP.classList.remove('disabled');
    }
    audio.addEventListener('timeupdate',updateProgress);
    audio.addEventListener('loadedmetadata',()=>{
      document.getElementById('time-dur').textContent=fmt(audio.duration);
    });
    audio.addEventListener('ended',()=>{
      document.getElementById('icon-play').style.display='';
      document.getElementById('icon-pause').style.display='none';
    });
    document.getElementById('player-card').style.display='block';

    // WhatsApp share
    const msg = shareTpl
      .replace('{title}',g.titulo||'')
      .replace('{honoree}',g.nome||'')
      .replace('{url}',window.location.href);
    document.getElementById('wa-btn').href='https://wa.me/?text='+encodeURIComponent(msg);
    document.getElementById('wa-btn').style.display='flex';

    document.getElementById('cta-card').style.display='block';
    document.getElementById('support-line').style.display='block';

    if(g.letra){
      document.getElementById('lyrics-card').style.display='block';
      document.getElementById('lyrics-content').innerHTML=formatLyrics(g.letra);
      window._lyricsRaw=g.letra;
    }
  }else{
    document.getElementById('generating-card').style.display='block';
  }
  document.getElementById('content').style.display='block';
}

function togglePlay(){
  if(audio.paused){
    audio.play();
    document.getElementById('icon-play').style.display='none';
    document.getElementById('icon-pause').style.display='';
  }else{
    audio.pause();
    document.getElementById('icon-play').style.display='';
    document.getElementById('icon-pause').style.display='none';
  }
}

function updateProgress(){
  if(!audio.duration)return;
  const p=(audio.currentTime/audio.duration)*100;
  document.getElementById('progress-fill').style.width=p+'%';
  document.getElementById('time-cur').textContent=fmt(audio.currentTime);
}

function seekAudio(e){
  if(!audio.duration)return;
  const r=e.currentTarget.getBoundingClientRect();
  audio.currentTime=Math.max(0,Math.min(1,(e.clientX-r.left)/r.width))*audio.duration;
}

function fmt(s){
  if(isNaN(s))return'—';
  return Math.floor(s/60)+':'+String(Math.floor(s%60)).padStart(2,'0');
}

function formatLyrics(letra){
  let text=letra.replace(/\*\*/g,'').replace(/🎵[^\n]*/g,'').replace(/💝[^\n]*/g,'').trim();
  const secs=text.split(/\[([^\]]+)\]/);
  if(secs.length<=1)return`<div class="lyrics-section"><p class="ls-body">${escHtml(text)}</p></div>`;
  let html='';
  for(let i=1;i<secs.length;i+=2){
    const rawKey=secs[i].trim();
    const body=(secs[i+1]||'').trim();
    if(!body)continue;
    const label=sectionMap[rawKey]||rawKey;
    const isChorus=chorusKeys.some(k=>rawKey===k||label===k);
    html+=`<div class="lyrics-section${isChorus?' is-chorus':''}">
      <div class="ls-label">${escHtml(label)}</div>
      <p class="ls-body">${escHtml(body)}</p>
    </div>`;
  }
  return html||`<p class="ls-body">${escHtml(text)}</p>`;
}

function escHtml(s){
  return s.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

function copyLyrics(){
  const t=window._lyricsRaw||document.getElementById('lyrics-content').innerText;
  navigator.clipboard.writeText(t).then(()=>{
    const b=document.getElementById('copy-btn');
    b.textContent=copyDone;
    setTimeout(()=>b.textContent=copyLabel,2000);
  });
}

function showError(){
  document.getElementById('loading').style.display='none';
  document.getElementById('error').style.display='flex';
}

load();
</script>
</body>
</html>
