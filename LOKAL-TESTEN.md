# Website lokal testen (vor dem Umzug zu Strato)

Du kannst die komplette Seite inklusive Adminbereich auf deinem eigenen PC
laufen lassen, dort in Ruhe Inhalte pflegen und das Ganze später **als
fertiges Paket** zu Strato hochladen. Alle Inhalte liegen als Dateien
(`data/`, `uploads/`) – sie wandern beim Upload einfach mit.

---

## Variante A: XAMPP (empfohlen, einfachste Variante mit Oberfläche)

XAMPP bringt alles mit (Apache + PHP) und verhält sich fast wie das spätere
Strato-Hosting (auch die `.htaccess`-Dateien greifen).

1. **XAMPP herunterladen & installieren:** https://www.apachefriends.org
   (Windows oder macOS, PHP 8.x ist enthalten).
2. **XAMPP Control Panel** öffnen → bei **Apache** auf **Start**.
3. **Projektdateien holen:** auf GitHub **Code → Download ZIP**, entpacken.
4. Einen Ordner anlegen und die Projektdateien hineinkopieren:
   - Windows: `C:\xampp\htdocs\ffwla\`
   - macOS:   `/Applications/XAMPP/htdocs/ffwla/`
   So, dass `index.php` direkt in diesem `ffwla`-Ordner liegt.
   (Den Ordner `docs/` sowie `build-static.sh` brauchst du
   zum Testen **nicht** – kannst sie aber drin lassen, stören nicht.)
5. Im Browser öffnen:
   - Website: **http://localhost/ffwla/**
   - Admin:   **http://localhost/ffwla/admin/**  (Passwort: `feuerwehr2024`)
6. Jetzt nach Herzenslust ausprobieren: Nachrichten/Termine anlegen, Fotos &
   PDFs hochladen, unter **Einstellungen** Kontaktdaten/Karte/Passwort ändern.

> Tipp: Ändere lokal ruhig schon das **Admin-Passwort** und trage die echten
> Inhalte ein. Beim späteren Upload ist alles direkt dabei.

---

## Variante B: PHP-Built-in-Server (für etwas Geübtere, ohne XAMPP)

Wenn PHP 8 installiert ist, genügt im Projektordner ein Befehl:

```bash
php -S localhost:8000
```

Dann im Browser **http://localhost:8000/** bzw. **/admin/** öffnen.
(Hinweis: Dieser Mini-Server ignoriert `.htaccess` – fürs reine Ausprobieren
egal. macOS hat seit neueren Versionen kein PHP mehr vorinstalliert; dann
`brew install php` oder einfach Variante A / MAMP nutzen.)

---

## Anschließend „das Gesamtpaket weiterleiten" (Upload zu Strato)

Das Schöne an der Flat-File-Lösung: **Was du lokal eingibst, ist sofort Teil
des Ordners.** Zum Veröffentlichen lädst du denselben Ordner hoch:

1. Bei Strato (oder anderem PHP-Hosting) einloggen, FTP-Zugang holen.
2. Den **Inhalt deines `ffwla`-Ordners** per FTP/File-Manager ins
   Web-Hauptverzeichnis hochladen – **inklusive** deiner lokal gepflegten
   `data/` und `uploads/`. Damit sind alle Inhalte sofort online.
3. Nicht hochladen: `docs/`, `build-static.sh`, `.git/`.
4. Versteckte `.htaccess`-Dateien mitnehmen (siehe `DEPLOY-STRATO.md`).

Details zum Strato-Upload stehen in **`DEPLOY-STRATO.md`**.

---

## Wichtige Hinweise
- **Kein Datenverlust beim Umzug:** deine lokal eingegebenen Nachrichten,
  Termine, Fotos und Einstellungen liegen in `data/` und `uploads/` und werden
  einfach mit hochgeladen.
- **Passwort:** das Standardpasswort `feuerwehr2024` bitte spätestens vor dem
  Online-Gang ändern (Admin → Einstellungen).
- **PHP-Version:** XAMPP/Strato sollten PHP **8.0+** verwenden.
