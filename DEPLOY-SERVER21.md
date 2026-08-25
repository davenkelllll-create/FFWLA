# Die neue Website beim bestehenden Hoster (Serverdrome / server21) einrichten

## Wichtigste Erkenntnis vorweg

Es muss **nichts umgezogen** werden. `web.server21.eu` ist kein neuer Anbieter,
sondern der Server bei eurem bestehenden Hoster **Serverdrome**, auf dem eure
jetzige Website bereits läuft.

Per DNS überprüft:

| Hostname | IP-Adresse |
|---|---|
| `ff-langensendelbach.de` | 136.243.152.21 |
| `www.ff-langensendelbach.de` | 136.243.152.21 |
| `web.server21.eu` | 136.243.152.21 |
| `mail.server21.eu` | 136.243.152.21 |
| `serverdrome.de` (Firmenseite) | 116.202.246.200 |

Die Rückwärtsauflösung von 136.243.152.21 ergibt `web.server21.eu`. Beide
IP-Adressen liegen im Netz von Hetzner – Serverdrome betreibt also gemietete
Maschinen in einem deutschen Rechenzentrum, und `server21` ist der
Kundenserver, auf dem euer Webspace liegt. Das ist ein ganz üblicher Aufbau
für einen kleineren Hoster.

**Was daraus folgt:**

- Kein neuer Vertrag, keine DNS-Änderung, kein Domain-Transfer.
- Auf dem Server läuft **eure aktuelle Joomla-Seite** – die neue ersetzt sie.
- **Auch eure E-Mails laufen über diese Maschine** (`mail.server21.eu`).
  Beim Aufräumen darf ausschließlich der Website-Ordner angefasst werden.
- Zugangsdaten kommen aus dem **Serverdrome-Kundenbereich** oder von der
  Person, die den Vertrag betreut.

> **Was ich prüfen konnte und was nicht:** Die Zuordnung oben ist per DNS
> belegt. Den Server selbst und die Serverdrome-Website konnte ich aus meiner
> Umgebung heraus **nicht** aufrufen – Verwaltungsoberfläche, PHP-Version und
> Webserver-Software bleiben deshalb offen. Genau diese Punkte klärt der
> System-Check in Schritt 5, der direkt auf dem Server läuft.

> **Zur Einordnung:** In Hosting-Foren finden sich kritische Erfahrungsberichte
> zu Serverdrome – die stammen allerdings aus den Jahren 2008/2009 und sagen
> über den heutigen Betrieb wenig aus. Kein Grund zur Sorge, aber ein Grund,
> beim Support zügige Antworten zu erwarten und **eigene Backups** zu führen
> (siehe Schritt 7).

---

## 1. Zugangsdaten besorgen

Im **Serverdrome-Kundenbereich** anmelden (Zugang steht in der
Auftragsbestätigung; sonst beim Support anfragen). Ihr braucht:

1. **FTP- oder SFTP-Zugang** – Server, Benutzername, Passwort, Port
2. **Zugang zur Verwaltungsoberfläche**, falls vorhanden. Üblich sind
   Plesk (`https://web.server21.eu:8443`), ISPConfig (`:8080`) oder
   cPanel (`:2083`). Damit lassen sich PHP-Version und SSL selbst einstellen.
3. **Welcher Ordner ist das Web-Verzeichnis** der Domain? Je nach System heißt
   er `httpdocs`, `public_html`, `htdocs` oder `web`.

## 2. Diese drei Fragen unbedingt klären

Davon hängt ab, ob die Seite sicher läuft. Falls im Kundenbereich nicht
ersichtlich, genügt eine kurze Mail an den Serverdrome-Support:

> Guten Tag,
> für unseren Webspace zu **ff-langensendelbach.de** auf `web.server21.eu`
> stellen wir die Website auf eine neue PHP-Anwendung um. Bitte teilen Sie uns mit:
>
> 1. Welche **PHP-Version** ist eingestellt, und können wir auf **PHP 8.2**
>    wechseln (selbst oder durch Sie)?
> 2. Läuft der Webspace unter **Apache oder nginx**? Werden **`.htaccess`-Dateien
>    ausgewertet**? Falls nein: Wie können wir den Unterordner `data/`
>    zuverlässig gegen direkten Zugriff aus dem Web sperren?
> 3. Sind die PHP-Erweiterungen **GD** und **fileinfo** aktiv?
> 4. Ist für die Domain ein **SSL-Zertifikat** aktiv (Let's Encrypt)?
>
> Vielen Dank!

| Frage | Warum wichtig |
|---|---|
| **PHP 8.0 oder neuer?** | Die Seite nutzt moderne PHP-Syntax und startet sonst gar nicht. |
| **Apache oder nginx?** | Bei **nginx** werden `.htaccess`-Dateien **ignoriert** – dann wäre `data/secrets.json` mit dem Passwort-Hash öffentlich abrufbar. Der Ordner muss dann serverseitig gesperrt werden. |
| **GD und fileinfo aktiv?** | Ohne GD keine Bild-Vorschauen, ohne fileinfo keine sichere Upload-Prüfung. |

Der System-Check in Schritt 5 beantwortet alle drei auch selbst.

## 3. Erst in einem Unterordner testen – nicht die laufende Seite überschreiben

Die alte Seite ist noch online. Ladet die neue deshalb **zuerst parallel** hoch:

- **Variante A (empfohlen):** Unterordner, z. B. `<Web-Verzeichnis>/neu/`
  → erreichbar unter `https://ff-langensendelbach.de/neu/`
- **Variante B:** Subdomain wie `neu.ff-langensendelbach.de`, falls die
  Verwaltungsoberfläche das hergibt.

> ⚠️ **Bei Variante A** liegt die Seite nicht im Wurzelverzeichnis. Da alle
> internen Links absolut sind (`/nachrichten.php`), funktioniert der Testbetrieb
> im Unterordner **nur eingeschränkt** – Startseite und Adminbereich gehen,
> interne Links zeigen ins Leere. Zum echten Test taugt deshalb die Subdomain
> besser. Alternativ direkt Schritt 6 (Wurzelverzeichnis) gehen, nachdem ihr
> ein Backup gezogen habt.

**Vorher in jedem Fall: Backup der alten Seite.** Komplettes Web-Verzeichnis
per FTP herunterladen und dazu – falls Joomla eine Datenbank nutzt – über die
Verwaltungsoberfläche einen Datenbank-Export ziehen. Ohne Backup gibt es kein
Zurück.

## 4. Dateien hochladen

Aus dem GitHub-Repository (**Code → Download ZIP**) entpacken und hochladen:

```
*.php            alle Seiten inkl. index.php, wip.php, system-check.php
admin/           Verwaltungsbereich
includes/        Kernfunktionen
css/  js/  images/
data/            Inhalte (Nachrichten, Termine, Fahrzeuge, Geschichte …)
uploads/         Fotos und PDFs
favicon.svg  robots.txt
.htaccess        WICHTIG – siehe Hinweis
```

**Nicht hochladen:** `docs/`, `build-static.sh`, `.git/`, alle `*.md`.

> ⚠️ **Versteckte Dateien einblenden!** Es gibt **vier** `.htaccess`-Dateien:
> im Hauptordner, in `data/`, in `uploads/` und in `uploads/fahrzeuge/`.
> FTP-Programme blenden Dateien mit führendem Punkt standardmäßig aus.
> In FileZilla: *Server → Versteckte Dateien anzeigen erzwingen*.

Die Ordnerstruktur im Repository entspricht **1:1** der auf dem Server –
außer den genannten Ausnahmen ist es reines Kopieren.

## 5. System-Check laufen lassen

1. `https://<eure-adresse>/admin/` öffnen und anmelden.
2. `https://<eure-adresse>/system-check.php` aufrufen.

Das Skript prüft PHP-Version, GD, fileinfo, Webserver-Typ, Schreibrechte,
Datendateien und HTTPS – und nennt zu jedem Problem die Lösung.

**Den rot umrandeten Test unbedingt durchführen:** Er öffnet
`/data/secrets.json` in einem privaten Fenster.
Erscheint dort JSON-Text statt einer Fehlermeldung, ist das Admin-Passwort
öffentlich lesbar – dann **nicht online gehen**, sondern erst den
Administrator den Ordner sperren lassen.

**Nach bestandener Prüfung `system-check.php` vom Server löschen.**

## 6. Scharf schalten

Ist alles grün:

1. Backup der alten Seite noch einmal prüfen (Schritt 3).
2. Alte Joomla-Dateien aus dem Web-Verzeichnis entfernen –
   **nur den Website-Ordner**, keine Mail- oder Systemordner anfassen.
3. Die neuen Dateien ins Wurzelverzeichnis legen, sodass `index.php`
   direkt dort liegt.
4. Schreibrechte setzen, falls das Speichern im Admin scheitert:
   `data/`, `uploads/`, `uploads/galerie/`, `uploads/formulare/`,
   `uploads/fahrzeuge/` auf **755** (notfalls 775), Dateien darin auf **644**.

Die Website startet im **Aufbaumodus**: Besucher sehen die Baustellen-Seite mit
den Kontaktdaten, ihr seht angemeldet die komplette Seite mit einem gelben
Hinweisbalken. So könnt ihr in Ruhe alle Inhalte pflegen.

**Zum Schluss:**

1. **Admin-Passwort ändern** – Einstellungen → Admin-Passwort.
   Das bisherige stand im öffentlichen Repository und gilt als kompromittiert.
2. Telefonnummer, Fahrzeuge, Geschichte und Ansprechpartner pflegen
   (siehe `ADMIN-ANLEITUNG.md`).
3. **Einstellungen → Aufbaumodus ausschalten.** Ab jetzt ist die Seite öffentlich.

## 7. Danach

- Die Seite auf server21 ist ab sofort die **echte** Website.
  GitHub dient nur noch als Code-Backup; die GitHub-Pages-Vorschau könnt ihr
  abschalten.
- **Regelmäßig sichern:** `data/` und `uploads/` enthalten alle Inhalte.
  Ein gelegentlicher FTP-Download beider Ordner genügt als Backup –
  es gibt keine Datenbank.
- SSL muss aktiv sein (`https://`). Ohne Zertifikat wird das Admin-Passwort
  im Klartext übertragen.

---

*Falls ihr euch später doch für einen Wechsel entscheidet, beschreibt
`DEPLOY-STRATO.md` denselben Ablauf für ein Strato-Paket.*
