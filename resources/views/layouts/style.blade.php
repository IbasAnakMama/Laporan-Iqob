<style>
:root{--bg:#f1f4f2;--ink:#18231f;--mut:#5d6b65;--line:#d9e0dc;--card:#fff;--gold:#b8892b;--th:#f6f8f7;--inp:#fff;--inl:#c5cfca;--out:#fff;--p:#0e5a4b;--p2:#0a4338}
body.role-musrif{--p:#27325f;--p2:#1b2447}
body.role-admin{--p:#14322a;--p2:#0b201b}
*{box-sizing:border-box}
body{--link:var(--p);margin:0;font:15px/1.55 "Segoe UI",system-ui,sans-serif;background:var(--bg);color:var(--ink)}
a{color:var(--link)}
.side{position:fixed;inset:0 auto 0 0;width:250px;background:var(--p2);color:#fff;display:flex;flex-direction:column;padding:22px 14px;z-index:20}
.brand{font:700 20px Georgia,serif;padding:0 10px 20px;border-bottom:1px solid rgba(255,255,255,.15)}
.brand small{display:block;font:400 12px "Segoe UI",sans-serif;color:#e6c97a;margin-top:2px}
.side nav{display:flex;flex-direction:column;gap:2px;margin-top:16px;flex:1}
.side nav a{color:#dfe9e5;text-decoration:none;padding:10px 12px;border-radius:8px}
.side nav a:hover{background:rgba(255,255,255,.08)}
.side nav a.on{background:#fff;color:var(--p2);font-weight:600}
.logout{width:100%;background:none;border:1px solid rgba(255,255,255,.3);color:#fff;padding:9px;border-radius:8px;cursor:pointer}
.main{margin-left:250px;padding:28px 34px;max-width:1200px}
.nav-toggle,.burger{display:none}
h1{font:700 26px Georgia,serif;margin:0 0 4px}h2{font-size:17px;margin:0 0 12px}
.sub{color:var(--mut);margin:0 0 22px}
.card{background:var(--card);border:1px solid var(--line);border-radius:12px;padding:20px;margin-bottom:20px}
.hero{background:var(--p);color:#fff;border-radius:14px;padding:26px 28px;margin-bottom:20px}
.hero h1{color:#fff}.hero .meta{opacity:.85;display:flex;gap:18px;flex-wrap:wrap;margin-top:4px}
.quote{margin-top:20px;padding-top:18px;border-top:1px solid rgba(255,255,255,.25);font:italic 19px/1.5 Georgia,serif;max-width:760px}
.quote cite{display:block;font:600 13px "Segoe UI",sans-serif;font-style:normal;color:#ecd18a;margin-top:6px}
.stats{display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:14px;margin-bottom:20px}
.stat{background:var(--card);border:1px solid var(--line);border-radius:12px;padding:16px}
.stat b{display:block;font-size:30px;font-family:Georgia,serif;color:var(--link)}.stat span{color:var(--mut);font-size:13px}
table{width:100%;border-collapse:collapse;font-size:14px}
th,td{text-align:left;padding:10px 12px;border-bottom:1px solid var(--line);vertical-align:top}
th{background:var(--th);font-weight:600}
.wrap{overflow-x:auto}
.badge{display:inline-block;padding:2px 10px;border-radius:99px;font-size:12px;font-weight:600}
.b-gold{background:#f7ecd0;color:#7a5a12}.b-blue{background:#dde8f8;color:#1f4a8a}.b-green{background:#d8efe3;color:#136a3e}.b-red{background:#f9dcdc;color:#a02323}
label{display:block;font-weight:600;margin:12px 0 4px;font-size:14px}
input,select,textarea{width:100%;padding:10px 12px;border:1px solid var(--inl);border-radius:8px;font:inherit;background:var(--inp);color:var(--ink)}
input:focus,select:focus,textarea:focus,button:focus-visible,a:focus-visible{outline:2px solid var(--gold);outline-offset:1px}
.btn{display:inline-block;background:var(--p);color:#fff;border:0;padding:10px 18px;border-radius:8px;font:600 14px inherit;cursor:pointer;text-decoration:none}
.btn.sm{padding:5px 12px;font-size:13px}.btn.red{background:#a02323}.btn.out{background:var(--out);color:var(--link);border:1px solid var(--link)}
.row{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:14px}
.flash{padding:12px 16px;border-radius:8px;margin-bottom:16px}.flash.ok{background:#d8efe3;color:#0f5a33}.flash.err{background:#f9dcdc;color:#8e1f1f}
.santri-list{display:grid;grid-template-columns:repeat(auto-fill,minmax(230px,1fr));gap:8px;margin-top:6px}
.santri-list label{font-weight:400;display:flex;gap:8px;align-items:center;margin:0;padding:8px 10px;border:1px solid var(--line);border-radius:8px}
.santri-list input{width:auto}
.empty{color:var(--mut);padding:18px;text-align:center}
.muted{color:var(--mut);font-size:13px}.inline{display:inline}
@media(max-width:860px){
 .side{transform:translateX(-100%);transition:transform .2s}
 .nav-toggle:checked~.side{transform:none}
 .burger{display:block;position:fixed;top:10px;left:10px;z-index:30;background:var(--p2);color:#fff;border-radius:8px;padding:6px 12px;font-size:20px;cursor:pointer}
 .main{margin-left:0;padding:62px 16px 24px}
}
@media(prefers-reduced-motion:reduce){.side{transition:none}}
html[data-theme=dark]{color-scheme:dark;--bg:#0e1512;--ink:#e3ece8;--mut:#9db0a7;--line:#26352e;--card:#15201b;--th:#1b2923;--inp:#101914;--inl:#33463d;--out:#15201b}
html[data-theme=dark] body{--link:#7fd1b4}
html[data-theme=dark] body.role-musrif{--link:#a9b8f5}
html[data-theme=dark] .b-gold{background:#3a2f12;color:#f0d68a}
html[data-theme=dark] .b-blue{background:#16294a;color:#a9c6f5}
html[data-theme=dark] .b-green{background:#12301f;color:#9fe0bb}
html[data-theme=dark] .b-red{background:#3a1717;color:#f3b0b0}
html[data-theme=dark] .flash.ok{background:#12301f;color:#9fe0bb}
html[data-theme=dark] .flash.err{background:#3a1717;color:#f3b0b0}
html[data-theme=dark] .side nav a.on{background:#e3ece8}
.tema{width:100%;background:rgba(255,255,255,.1);border:0;color:#fff;padding:9px;border-radius:8px;cursor:pointer;margin-bottom:8px}
.av{border-radius:50%;object-fit:cover;display:inline-flex;align-items:center;justify-content:center;background:#e6c97a;color:#2b2208;font-weight:700;flex:none;vertical-align:middle}
.chip{display:flex;gap:10px;align-items:center;color:#fff;text-decoration:none;padding:12px 10px;border-bottom:1px solid rgba(255,255,255,.15);font-size:13px;line-height:1.3}
.chip span{overflow:hidden;text-overflow:ellipsis}
.prof{display:flex;gap:22px;align-items:center;flex-wrap:wrap}
.kamar-form{display:flex;gap:6px}.kamar-form input{width:110px}.kamar-form select{width:190px}
</style>
