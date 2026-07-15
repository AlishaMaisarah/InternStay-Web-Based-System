<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Background Illustration — Preview</title>
<style>
  :root{
    /* token system */
    --violet-deep:  #4a2a80;
    --violet:       #6f42c1;
    --violet-soft:  #8b5cf6;
    --gold-deep:    #b9791f;
    --gold:         #d9a441;
    --gold-soft:    #f0c675;
    --teal-deep:    #1f7a6d;
    --teal:         #2f9e8f;
    --teal-soft:    #5fc9ba;
    --paper:        #faf8f5;
    --ink:          #2a2338;
  }

  *{box-sizing:border-box;}
  html,body{margin:0;padding:0;}
  body{
    min-height:100vh;
    background:
      radial-gradient(ellipse 90% 60% at 15% 0%, #f3edff 0%, transparent 60%),
      radial-gradient(ellipse 70% 50% at 100% 100%, #fff6e8 0%, transparent 55%),
      var(--paper);
    font-family: -apple-system, "Segoe UI", sans-serif;
    color: var(--ink);
    overflow-x:hidden;
  }


  .bg-scene{
    position:fixed;
    inset:0;
    z-index:0;
    pointer-events:none;
    user-select:none;
    overflow:hidden;
  }

  /* soft atmospheric color blobs, sit behind everything for depth */
  .blob{
    position:absolute;
    border-radius:50%;
    filter: blur(60px);
    opacity:.35;
  }
  .blob--1{ width:420px; height:420px; top:-8%; left:-6%;
    background: radial-gradient(circle at 35% 35%, var(--violet-soft), transparent 70%); }
  .blob--2{ width:380px; height:380px; bottom:-10%; right:-8%;
    background: radial-gradient(circle at 60% 60%, var(--gold-soft), transparent 70%); }
  .blob--3{ width:300px; height:300px; top:38%; left:46%;
    background: radial-gradient(circle at 50% 50%, var(--teal-soft), transparent 72%); }

  .bg-illustration{ position:absolute; }

  @keyframes drift{
    0%,100%{ transform: translate(0,0) rotate(var(--r,0deg)); }
    50%{ transform: translate(6px,-10px) rotate(calc(var(--r,0deg) + 2deg)); }
  }
  .float{ animation: drift 14s ease-in-out infinite; animation-delay: var(--d,0s); }

  @media (prefers-reduced-motion: reduce){
    .float{ animation:none; }
  }
</style>
</head>
<body>

<div class="bg-scene" aria-hidden="true">

  <!-- atmosphere -->
  <div class="blob blob--1"></div>
  <div class="blob blob--2"></div>
  <div class="blob blob--3"></div>

  <svg width="0" height="0" style="position:absolute">
    <defs>
      <linearGradient id="gViolet" x1="0" y1="0" x2="1" y2="1">
        <stop offset="0%" stop-color="var(--violet-soft)"/>
        <stop offset="100%" stop-color="var(--violet-deep)"/>
      </linearGradient>
      <linearGradient id="gGold" x1="0" y1="0" x2="1" y2="1">
        <stop offset="0%" stop-color="var(--gold-soft)"/>
        <stop offset="100%" stop-color="var(--gold-deep)"/>
      </linearGradient>
      <linearGradient id="gTeal" x1="0" y1="0" x2="1" y2="1">
        <stop offset="0%" stop-color="var(--teal-soft)"/>
        <stop offset="100%" stop-color="var(--teal-deep)"/>
      </linearGradient>
      <linearGradient id="gRoute" x1="0" y1="0" x2="1" y2="0">
        <stop offset="0%"  stop-color="var(--violet)"  stop-opacity="0.5"/>
        <stop offset="50%" stop-color="var(--gold)"     stop-opacity="0.5"/>
        <stop offset="100%" stop-color="var(--teal)"    stop-opacity="0.5"/>
      </linearGradient>
    </defs>
  </svg>

  <!-- the signature element: one continuous journey line strung across the
       whole page, connecting every icon into a single narrative instead of
       scattering unrelated glyphs -->
  <svg class="bg-illustration" style="top:0; left:0; width:100%; height:100%; opacity:0.5;"
       viewBox="0 0 1440 1200" preserveAspectRatio="none" fill="none">
    <path d="M120 160
             C 300 60, 420 260, 560 210
             S 760 90, 900 220
             S 1040 480, 880 560
             S 560 620, 480 780
             S 620 980, 420 1040"
          stroke="url(#gRoute)" stroke-width="3.5" stroke-dasharray="2 14"
          stroke-linecap="round"/>
    <circle cx="120" cy="160"  r="6" fill="var(--violet)" opacity="0.55"/>
    <circle cx="900" cy="220"  r="6" fill="var(--gold)"   opacity="0.55"/>
    <circle cx="880" cy="560"  r="6" fill="var(--teal)"   opacity="0.55"/>
    <circle cx="420" cy="1040" r="6" fill="var(--violet)" opacity="0.55"/>
  </svg>

  <!-- Compass — journey start, top-left -->
  <svg class="bg-illustration float" style="width:130px; height:130px; top:9%; left:5%; --r:-8deg; --d:0s; opacity:0.65; filter: drop-shadow(0 8px 16px rgba(74,42,128,0.15));"
       viewBox="0 0 100 100" fill="url(#gViolet)">
    <path d="M50 5C25.1 5 5 25.1 5 50s20.1 45 45 45 45-20.1 45-45S74.9 5 50 5zm0 82c-20.4 0-37-16.6-37-37s16.6-37 37-37 37 16.6 37 37-16.6 37-37 37z"/>
    <path d="M50 25c-1.7 0-3 1.3-3 3v13.6L36.3 36.3c-1.2-1.2-3.1-1.2-4.2 0s-1.2 3.1 0 4.2L42.9 50l-9.2 9.2c-1.2 1.2-1.2 3.1 0 4.2.6.6 1.4.9 2.1.9s1.5-.3 2.1-.9L50 56.4V70c0 1.7 1.3 3 3 3s3-1.3 3-3V56.4l10.8 10.8c.6.6 1.4.9 2.1.9s1.5-.3 2.1-.9c1.2-1.2 1.2-3.1 0-4.2L57.1 50l9.2-9.2c1.2-1.2 1.2-3.1 0-4.2s-3.1-1.2-4.2 0L50 41.6V28c0-1.7-1.3-3-3-3z"/>
  </svg>

  <!-- Location pin — the destination being searched for -->
  <svg class="bg-illustration float" style="width:78px; height:78px; top:15%; left:36%; --r:6deg; --d:1.2s; opacity:0.6; filter: drop-shadow(0 6px 12px rgba(185,121,31,0.18));"
       viewBox="0 0 100 100" fill="url(#gGold)">
    <path d="M50 2C31.2 2 16 17.2 16 36c0 23.3 30.6 59.9 31.9 61.5.6.7 1.5 1.1 2.4 1.1.9 0 1.8-.4 2.4-1.1C54.1 95.9 84 59.3 84 36 84 17.2 68.8 2 50 2zm0 50c-8.8 0-16-7.2-16-16s7.2-16 16-16 16 7.2 16 16-7.2 16-16 16z"/>
  </svg>

  <!-- Graduation cap — study leg of the journey -->
  <svg class="bg-illustration float" style="width:190px; height:190px; top:10%; right:6%; --r:-5deg; --d:2.1s; opacity:0.38; filter: drop-shadow(0 10px 20px rgba(31,122,109,0.15));"
       viewBox="0 0 100 100" fill="url(#gTeal)">
    <path d="M50 15L5 35l45 20 37-16.4V65c0 2.2 1.8 4 4 4s4-1.8 4-4V35.4L50 15zm0 53.6L18 54.4V62c0 8.8 14.3 16 32 16s32-7.2 32-16v-7.6L50 68.6z"/>
  </svg>

  <!-- Briefcase — career leg, mid right, larger anchor -->
  <svg class="bg-illustration float" style="width:150px; height:150px; bottom:13%; left:3%; --r:7deg; --d:0.6s; opacity:0.6; filter: drop-shadow(0 8px 18px rgba(74,42,128,0.16));"
       viewBox="0 0 100 100" fill="url(#gGold)">
    <path d="M85 30H70v-8c0-4.4-3.6-8-8-8H38c-4.4 0-8 3.6-8 8v8H15c-5.5 0-10 4.5-10 10v40c0 5.5 4.5 10 10 10h70c5.5 0 10-4.5 10-10V40c0-5.5-4.5-10-10-10zM36 22c0-1.1.9-2 2-2h24c1.1 0 2 .9 2 2v8H36v-8zm53 58c0 2.2-1.8 4-4 4H15c-2.2 0-4-1.8-4-4V48h78v32zm0-38H11v-2c0-2.2 1.8-4 4-4h70c2.2 0 4 1.8 4 4v2z"/>
  </svg>

  <!-- small satellite compass, distant echo, bottom-left, faint for depth -->
  <svg class="bg-illustration float" style="width:75px; height:75px; top:44%; right:8%; --r:12deg; --d:1.8s; opacity:0.80;"
       viewBox="0 0 100 100" fill="url(#gGold)">
    <path d="M50 2C23.5 2 2 23.5 2 50s21.5 48 48 48 48-21.5 48-48S76.5 2 50 2zm0 88c-22.1 0-40-17.9-40-40s17.9-40 40-40 40 17.9 40 40-17.9 40-40 40zm12-40L50 26 38 50l12 12 12-12z"/>
  </svg>

  <!-- House — settling down, bottom center-left, warm anchor -->
  <svg class="bg-illustration float" style="width:150px; height:150px; bottom:3%; left:33%; --r:-6deg; --d:0.9s; opacity:0.65; filter: drop-shadow(0 8px 16px rgba(185,121,31,0.18));"
       viewBox="0 0 24 24" fill="url(#gTeal)">
    <path d="M12 3L2 12h3v8h6v-5h2v5h6v-8h3L12 3z"/>
  </svg>

  <!-- Building — the city, bottom right, large but soft -->
  <svg class="bg-illustration float" style="width:150px; height:150px; bottom:2%; right:3%; --r:4deg; --d:1.5s; opacity:0.42;"
       viewBox="0 0 100 100" fill="url(#gViolet)">
    <path d="M40 2h45v96H5c2.2 0 4-1.8 4-4V35c0-2.2 1.8-4 4-4h27V2zm37 84V10H48v76h29zm-18-58h8v8h-8v-8zm0 20h8v8h-8v-8zm0 20h8v8h-8v-8zm-22-2h8v8h-8v-8zm0-20h8v8h-8v-8zm-20 40h8v8H17v-8zm0-20h8v8H17v-8z"/>
  </svg>

</div>

</body>
</html>