#!/bin/bash
# ============================================================
# build-static.sh – Statische GitHub Pages Version bauen
# Ausführen nach PHP-Änderungen: bash build-static.sh
#
# Wichtig: data/ wird NICHT exportiert (enthält u.a. Zugangsdaten);
# die Inhalte sind beim Rendern bereits ins HTML eingebacken.
# ============================================================

set -e
SCRIPT_DIR="$(cd "$(dirname "$0")" && pwd)"
DOCS_DIR="$SCRIPT_DIR/docs"
PORT=8181

# Optionale absolute Basis-URL für sitemap.xml (z.B. https://user.github.io/repo).
# Leer lassen => es wird keine sitemap.xml erzeugt (statt einer mit falscher Domain).
BASE_URL=""

echo "→ PHP-Server starten..."
php -S 127.0.0.1:$PORT -t "$SCRIPT_DIR" > /tmp/fw-build.log 2>&1 &
PHP_PID=$!
# Auf Bereitschaft warten
for i in 1 2 3 4 5 6 7 8 9 10; do
  sleep 1
  if curl -s -o /dev/null "http://127.0.0.1:$PORT/index.php"; then break; fi
done
if ! curl -s -o /dev/null -w "%{http_code}" "http://127.0.0.1:$PORT/index.php" | grep -q "200"; then
  echo "✗ PHP-Server konnte nicht gestartet werden"; cat /tmp/fw-build.log; kill $PHP_PID 2>/dev/null; exit 1
fi

echo "→ docs/ Verzeichnis vorbereiten..."
rm -rf "$DOCS_DIR"
mkdir -p "$DOCS_DIR"

echo "→ Statische Seiten rendern..."
PAGES=(
  "wip.php:index.html"
  "index.php:home.html"
  "nachrichten.php:nachrichten.html"
  "kalender.php:kalender.html"
  "jugendfeuerwehr.php:jugendfeuerwehr.html"
  "formulare.php:formulare.html"
  "ueber-uns.php:ueber-uns.html"
  "buergerecke.php:buergerecke.html"
  "links.php:links.html"
  "kontakt.php:kontakt.html"
  "impressum.php:impressum.html"
  "datenschutz.php:datenschutz.html"
  "404.php:404.html"
)
for entry in "${PAGES[@]}"; do
  src="${entry%%:*}"; dst="${entry##*:}"
  curl -s "http://127.0.0.1:$PORT/$src" -o "$DOCS_DIR/$dst"
  echo "  ✓ $dst"
done

# ---- Detailseiten je Datensatz rendern (sonst 404 im statischen Export) ----
echo "→ Detailseiten rendern..."
NEWS_IDS=$(php -r '$a=json_decode(file_get_contents("data/nachrichten.json"),true)?:[];foreach($a as $i){echo $i["id"],"\n";}')
for nid in $NEWS_IDS; do
  [ -z "$nid" ] && continue
  curl -s "http://127.0.0.1:$PORT/nachrichten-detail.php?id=$nid" -o "$DOCS_DIR/nachrichten-detail-$nid.html"
  echo "  ✓ nachrichten-detail-$nid.html"
done

echo "→ Assets kopieren..."
cp -r "$SCRIPT_DIR/css" "$DOCS_DIR/"
cp -r "$SCRIPT_DIR/js"  "$DOCS_DIR/"
cp -r "$SCRIPT_DIR/images" "$DOCS_DIR/" 2>/dev/null || true
cp -r "$SCRIPT_DIR/uploads" "$DOCS_DIR/" 2>/dev/null || mkdir -p "$DOCS_DIR/uploads/formulare"
cp "$SCRIPT_DIR/favicon.svg" "$DOCS_DIR/" 2>/dev/null || true
# WICHTIG: data/ NICHT kopieren – enthält Zugangsdaten und wird nicht clientseitig gelesen.
rm -rf "$DOCS_DIR/data"
# Keine ausführbaren PHP-Reste im statischen Export
rm -f "$DOCS_DIR/uploads/.htaccess"
touch "$DOCS_DIR/.nojekyll"

echo "→ Links anpassen..."
# 1) Detail-Links je ID auf die generierten Einzeldateien umbiegen (vor dem generischen Rewrite)
for nid in $NEWS_IDS; do
  [ -z "$nid" ] && continue
  for f in "$DOCS_DIR"/*.html; do
    sed -i "s|/nachrichten-detail\.php?id=$nid|nachrichten-detail-$nid.html|g" "$f"
  done
done

# 2) Generischer .php → .html Rewrite
PHP_LINKS=(
  "index.php" "nachrichten.php" "kalender.php"
  "jugendfeuerwehr.php" "formulare.php" "ueber-uns.php" "buergerecke.php"
  "links.php" "kontakt.php" "impressum.php" "datenschutz.php" "404.php"
)
for f in "$DOCS_DIR"/*.html; do
  for page in "${PHP_LINKS[@]}"; do
    base="${page%.php}"
    sed -i "s|href=\"/$page\"|href=\"${base}.html\"|g" "$f"
    sed -i "s|href=\"/$page?|href=\"${base}.html?|g" "$f"
  done
  sed -i 's|href="/"|href="home.html"|g' "$f"
  # Asset-Pfade relativ machen
  sed -i 's|href="/css/|href="css/|g; s|href="/js/|href="js/|g' "$f"
  sed -i 's|src="/js/|src="js/|g;  s|src="/css/|src="css/|g' "$f"
  sed -i 's|href="/favicon.svg"|href="favicon.svg"|g' "$f"
  sed -i 's|href="/images/|href="images/|g; s|src="/images/|src="images/|g; s|content="/images/|content="images/|g' "$f"
  # Inline-Style-Hintergrundbilder (z.B. Hero-Carousel: style="background-image:url('/images/...')")
  # werden von den href=/src=-Regeln oben NICHT erfasst – eigene Regel dafür.
  sed -i -E "s#url\((['\"]?)/(images|css|js|uploads)/#url(\1\2/#g" "$f"
done

# 3) Cookie-Banner verlinkt die Datenschutzseite – im statischen Export auf .html
if [ -f "$DOCS_DIR/js/cookie-banner.js" ]; then
  sed -i 's|/datenschutz.php|datenschutz.html|g' "$DOCS_DIR/js/cookie-banner.js"
fi

echo "→ robots.txt erzeugen..."
cat > "$DOCS_DIR/robots.txt" <<TXT
User-agent: *
Allow: /
TXT
[ -n "$BASE_URL" ] && echo "Sitemap: ${BASE_URL%/}/sitemap.xml" >> "$DOCS_DIR/robots.txt"

if [ -n "$BASE_URL" ]; then
  echo "→ sitemap.xml erzeugen (BASE_URL=$BASE_URL)..."
  {
    echo '<?xml version="1.0" encoding="UTF-8"?>'
    echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'
    for f in "$DOCS_DIR"/*.html; do
      name="$(basename "$f")"
      [ "$name" = "404.html" ] && continue
      echo "  <url><loc>${BASE_URL%/}/$name</loc></url>"
    done
    echo '</urlset>'
  } > "$DOCS_DIR/sitemap.xml"
else
  echo "  (BASE_URL leer – keine sitemap.xml erzeugt)"
fi

echo "→ PHP-Server stoppen..."
kill $PHP_PID 2>/dev/null

echo ""
echo "✅ Build fertig! docs/ ist aktuell."
echo "   git add docs/ && git commit -m 'build: static pages update'"
