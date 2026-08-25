# Admin-Bereich – Was kann ich wo bearbeiten?

Anmeldung: `https://<deine-domain>/admin/` – Passwort siehe interne Notiz.
Passwort ändern: **Einstellungen → Admin-Passwort**.

---

## Übersicht

| Menüpunkt | Was damit gepflegt wird | Wirkt sich aus auf |
|---|---|---|
| **Dashboard** | Überblick, Schnellzugriff | – |
| **Nachrichten** | Berichte zu Einsätzen, Übungen, Veranstaltungen, Presse | Startseite, Nachrichten |
| **Termine** | Veranstaltungen und Übungen | Startseite, Kalender |
| **Bilder-Alben** | Fotos, die an Nachrichten angehängt werden | Nachrichten-Detailseiten |
| **Formulare** | PDF-Downloads (Mitgliedsantrag etc.) | Formulare |
| **Fahrzeuge** | Fuhrpark inkl. Fotos | Über uns |
| **Geschichte** | Zeitleiste und Gründungs-Kasten | Über uns |
| **Seiteninhalte** | Feste Texte der übrigen Seiten | Startseite, Jugendfeuerwehr, Bürgerecke, Links |
| **Einstellungen** | Kontaktdaten, Karte, Ansprechpartner, Social Media, Notfall-Banner, Passwort | alle Seiten |

---

## Fahrzeuge

**Admin → Fahrzeuge**

Pro Fahrzeug: Bezeichnung, Kategorie (farbiges Badge), Baujahr, Funkrufname,
Besatzung, Beschreibung und ein Foto.

- **Foto**: JPG, PNG, GIF oder WebP, max. 12 MB. Ein Vorschaubild wird
  automatisch erzeugt. Ohne Foto wird ein Symbol angezeigt – der Symbolname
  stammt von [icons.getbootstrap.com](https://icons.getbootstrap.com/)
  (ohne das Präfix `bi-`, z.B. `truck-front-fill`).
- **Reihenfolge**: über die Pfeiltasten in der Liste.
- Beim Löschen eines Fahrzeugs werden auch dessen Bilddateien entfernt.

## Geschichte

**Admin → Geschichte**

- **Hervorhebung**: der rote Kasten neben der Zeitleiste (Gründungsjahr,
  Zusatztext, Wahlspruch). Feld *Jahr* leeren blendet den Kasten aus.
- **Zeitleiste**: beliebig viele Einträge aus Jahr, Überschrift und Text.
  Die Reihenfolge im Formular ist die Reihenfolge auf der Seite.
  Leere Zeilen werden beim Speichern automatisch verworfen.

## Seiteninhalte

**Admin → Seiteninhalte** – vier Reiter, ein gemeinsamer Speichern-Knopf.

| Reiter | Inhalt |
|---|---|
| **Startseite** | Die Hero-Slides (Überzeile, Überschrift, Text, Bild, zwei Buttons) |
| **Jugendfeuerwehr** | Zahlen-Leiste, „Was machen wir?", „Wer kann mitmachen?", Kinderfeuerwehr-Texte und -Karten |
| **Bürgerecke** | Die 5 W im Notruf-Kasten und die Sicherheitstipps |
| **Links & Partner** | Übergeordnete Stellen, Behörden, Notrufnummern |

Hinweise:

- In den Feldern **Überschrift** (Hero) und den Texten der Jugendfeuerwehr
  sind HTML-Tags wie `<strong>` oder `<br>` erlaubt.
- **Symbol**-Felder erwarten einen Bootstrap-Icon-Namen ohne `bi-`.
- **Farb**-Felder erwarten einen Hex-Wert, z.B. `#CC0000`.
- Ein Hero-Slide ohne gültigen Bildpfad zeigt stattdessen den Farbverlauf
  mit Symbol – so bleibt die Seite auch ohne Foto ansehnlich.

## Ansprechpartner

**Admin → Einstellungen → Ansprechpartner**

Drei getrennte Gruppen: *Vorstandschaft & Wehrführung*, *Jugendfeuerwehr*,
*Kinderfeuerwehr*. Erscheinen auf „Über uns" und „Jugendfeuerwehr".
Ohne E-Mail-Adresse entfällt der Mail-Knopf bei der Person.

## Notfall-Hinweis

**Admin → Einstellungen → Notfall-Hinweis**

Blendet ein rotes Band über der Navigation auf **allen** Seiten ein –
gedacht für kurzfristige Warnungen (Unwetter, Straßensperrung).
Nach dem Ereignis wieder deaktivieren.

---

## Wichtig bei der statischen Vorschau

Die GitHub-Pages-Vorschau unter `docs/` ist eine **eingefrorene Kopie**.
Änderungen im Admin-Bereich erscheinen dort erst, wenn der Export neu
gebaut wird:

```bash
bash build-static.sh
git add docs/ && git commit -m "build: static pages update" && git push
```

Auf einem echten PHP-Hosting (z.B. Strato) entfällt dieser Schritt –
dort sind Änderungen sofort live.
