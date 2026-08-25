# Umzug der Website zu Strato (PHP-Webhosting)

Diese Website ist ein einfaches PHP-Projekt **ohne Datenbank** – alle Inhalte
liegen als Dateien (`data/*.json`) und Uploads (`uploads/`). Dadurch ist der
Umzug unkompliziert: Dateien hochladen, PHP-Version einstellen, fertig.

---

## 1. Passendes Strato-Paket
- Ein **Webhosting-/PowerWeb-Paket mit PHP 8.x** genügt (z. B. „PowerWeb").
- **Keine** MySQL-Datenbank nötig.
- **SSL** (kostenloses Let's-Encrypt-Zertifikat) im Strato-Paket **aktivieren** →
  die Seite läuft dann über `https://` (wichtig auch für den sicheren Admin-Login).

## 2. PHP-Version einstellen
Im Strato-Kundenbereich unter **„PHP-Version"** mindestens **PHP 8.0** wählen
(empfohlen: 8.2 oder 8.3). Diese Erweiterungen sollten aktiv sein (sind sie
bei Strato standardmäßig): **GD** (Bild-Vorschauen) und **fileinfo** (Upload-Prüfung).

## 3. Dateien hochladen
**Diese Ordner/Dateien gehören auf den Server** (= die PHP-Seite):

```
*.php            (alle Seiten: index.php, wip.php, nachrichten.php, …)
admin/           (Verwaltungsbereich)
includes/        (Kernfunktionen)
css/  js/  images/
data/            (Inhalte – Nachrichten, Termine, Fahrzeuge, Geschichte …)
uploads/         (Fotos, PDFs)
favicon.svg  robots.txt
.htaccess        (WICHTIG – siehe Hinweis unten)
```

**NICHT hochladen** (nur für Entwicklung/Backup):
```
docs/            (statische GitHub-Pages-Vorschau – auf dem PHP-Server überflüssig)
build-static.sh  .git/   *.md
```

Die Ordnerstruktur im Repository entspricht **1:1** der Struktur auf dem
Server – bis auf die vier oben genannten Ausnahmen ist es reines
Kopieren, es muss nichts umsortiert oder umbenannt werden.

**So kommst du an die Dateien:** auf GitHub auf **Code → Download ZIP**, das
Archiv entpacken und den Inhalt (ohne die oben genannten Ausnahmen) hochladen.

**Hochladen** per:
- Strato **File-Manager** (im Kundenbereich) **oder**
- **FTP/SFTP-Programm** (z. B. FileZilla) mit den FTP-Zugangsdaten aus dem
  Strato-Kundenbereich.

Lade die Dateien so hoch, dass **`index.php` direkt im Web-Hauptverzeichnis**
liegt (dort, wohin deine Domain zeigt – bei Strato meist der oberste Ordner
des Webspace).

> ⚠️ **Versteckte Dateien anzeigen!** Die `.htaccess`-Dateien beginnen mit einem
> Punkt und werden von FTP-Programmen oft ausgeblendet. In FileZilla:
> *Server → Versteckte Dateien anzeigen erzwingen*. Es gibt vier davon:
> im Hauptordner, in `data/`, in `uploads/` und in `uploads/fahrzeuge/` –
> alle müssen mit hoch.

## 4. Schreibrechte für data/ und uploads/
Der Adminbereich **schreibt** in `data/` und `uploads/`. Bei Strato läuft PHP
unter demselben Benutzer wie die Dateien – meist funktioniert es direkt.
Falls Speichern im Admin fehlschlägt, setze per FTP die Rechte (CHMOD):
- Ordner `data/`, `uploads/`, `uploads/galerie/`, `uploads/formulare/`,
  `uploads/fahrzeuge/` → **755**
- Dateien darin → **644**

(Falls weiterhin nötig: Ordner **775**.)

## 5. Erste Schritte nach dem Upload

Die Website startet im **Aufbaumodus**: Besucher sehen die Baustellen-Seite
mit den Kontaktdaten, Sie selbst sehen nach dem Login die komplette Website.
So können Sie in Ruhe alles vorbereiten, bevor die Seite öffentlich geht.

1. Domain im Browser öffnen → die **Baustellen-Seite** muss erscheinen.
2. `https://deine-domain.de/admin/` öffnen → einloggen.
3. **Sofort das Passwort ändern:** Einstellungen → „Admin-Passwort ändern".
4. Inhalte pflegen: Telefonnummer, Fahrzeuge, Geschichte, Ansprechpartner …
   (Angemeldet sehen Sie die echte Website mit einem gelben Hinweisbalken.)
5. Wenn alles passt: **Einstellungen → Aufbaumodus** ausschalten.
   Ab diesem Moment ist die Website öffentlich.

> Den Aufbaumodus können Sie jederzeit wieder einschalten – z. B. für
> größere Umbauten.

## 6. Sicherheit
- Das Admin-Passwort liegt **nur** in `data/secrets.json` und wird durch die
  `.htaccess` (`<Files "*.json"> Require all denied`) vor direktem Abruf im
  Browser geschützt. Deshalb ist es so wichtig, dass die `.htaccess`-Dateien
  mit hochgeladen werden.
- Nach dem Umzug ist **die Strato-Seite die „echte" Website**. GitHub dient nur
  noch als Code-Backup. Die GitHub-Pages-Vorschau kannst du abschalten.
- **Passwort nach dem Umzug ändern** (das Standardpasswort war öffentlich bekannt).

## 7. Domain
- Domain bei Strato dazu gebucht → im Kundenbereich der Webspace zuweisen.
- Domain liegt woanders → dort die DNS-Einträge auf den Strato-Server zeigen
  lassen (Strato zeigt die nötigen Werte an).
