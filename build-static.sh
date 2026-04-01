#!/bin/bash
# ============================================================
# build-static.sh – Statische GitHub Pages Version bauen
# Ausführen nach PHP-Änderungen: bash build-static.sh
# ============================================================

set -e
SCRIPT_DIR="$(cd "$(dirname "$0")" && pwd)"
DOCS_DIR="$SCRIPT_DIR/docs"
PORT=8181

echo "→ PHP-Server starten..."
php -S 127.0.0.1:$PORT -t "$SCRIPT_DIR" > /tmp/fw-build.log 2>&1 &
PHP_PID=$!
sleep 2

# Sicherstellen, dass der Server läuft
if ! curl -s -o /dev/null -w "%{http_code}" http://127.0.0.1:$PORT/index.php | grep -q "200"; then
  echo "✗ PHP-Server konnte nicht gestartet werden"
  kill $PHP_PID 2>/dev/null; exit 1
fi

echo "→ docs/ Verzeichnis vorbereiten..."
rm -rf "$DOCS_DIR"
mkdir -p "$DOCS_DIR"

echo "→ Seiten rendern..."
PAGES=(
  "index.php:index.html"
  "nachrichten.php:nachrichten.html"
  "kalender.php:kalender.html"
  "galerie.php:galerie.html"
  "jugendfeuerwehr.php:jugendfeuerwehr.html"
  "formulare.php:formulare.html"
  "ueber-uns.php:ueber-uns.html"
  "links.php:links.html"
  "kontakt.php:kontakt.html"
  "impressum.php:impressum.html"
  "datenschutz.php:datenschutz.html"
  "404.php:404.html"
)
for entry in "${PAGES[@]}"; do
  src="${entry%%:*}"
  dst="${entry##*:}"
  curl -s "http://127.0.0.1:$PORT/$src" -o "$DOCS_DIR/$dst"
  echo "  ✓ $dst"
done

echo "→ Assets kopieren..."
cp -r "$SCRIPT_DIR/css"     "$DOCS_DIR/"
cp -r "$SCRIPT_DIR/js"      "$DOCS_DIR/"
cp -r "$SCRIPT_DIR/data"    "$DOCS_DIR/"
cp -r "$SCRIPT_DIR/uploads" "$DOCS_DIR/" 2>/dev/null || mkdir -p "$DOCS_DIR/uploads/galerie" "$DOCS_DIR/uploads/formulare"
touch "$DOCS_DIR/.nojekyll"

echo "→ Links anpassen (.php → .html)..."
PHP_LINKS=(
  "index.php" "nachrichten.php" "nachrichten-detail.php"
  "kalender.php" "galerie.php" "galerie-detail.php"
  "jugendfeuerwehr.php" "formulare.php" "ueber-uns.php"
  "links.php" "kontakt.php" "impressum.php" "datenschutz.php"
  "404.php"
)
for f in "$DOCS_DIR"/*.html; do
  # Absolute /page.php → relative page.html
  for page in "${PHP_LINKS[@]}"; do
    base="${page%.php}"
    sed -i "s|href=\"/$page\"|href=\"${base}.html\"|g" "$f"
    sed -i "s|href=\"/$page?|href=\"${base}.html?|g" "$f"
  done
  # href="/" → index.html
  sed -i 's|href="/"|href="index.html"|g' "$f"
  # Absolute asset paths → relative
  sed -i 's|href="/css/|href="css/|g; s|href="/js/|href="js/|g' "$f"
  sed -i 's|src="/js/|src="js/|g;  s|src="/css/|src="css/|g' "$f"
done

echo "→ PHP-Server stoppen..."
kill $PHP_PID 2>/dev/null

echo ""
echo "✅ Build fertig! docs/ ist aktuell."
echo "   Git commit nicht vergessen:"
echo "   git add docs/ && git commit -m 'build: static pages update'"
