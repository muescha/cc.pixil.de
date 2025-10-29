#!/usr/bin/env zsh
set -e

# Basisdaten
userid="123"
bildnamen=("mountain" "portrait" "city" "coffee" "car" "forest" "desk" "street" "beach" "cat")
bildIDs=(1015 1021 1035 1040 1052 1062 1074 1084 109 110)
sizes=("800/533" "500/750" "900/600" "600/600" "1000/667" "450/800" "1200/800" "600/900" "1000/562" "700/700")

# Basisverzeichnis für lokale Speicherung
basepath="$(pwd)"

echo "📸 Lade 10 Bilder von Picsum und erstelle Thumbnails (max 50 px)..."
echo "📂 Zielordner: $basepath"
echo

for i in {1..10}; do
  idx=$((i - 1))
  id=${bildIDs[$((idx + 1))]}
  size=${sizes[$((idx + 1))]}
  name=${bildnamen[$((idx + 1))]}

  imageFileName="${userid}${name}${i}.jpg"
  thumbFileName="${userid}${name}_${i}_t2.jpg"
  imagePath="${basepath}/${imageFileName}"
  thumbPath="${basepath}/${thumbFileName}"
  remoteUrl="https://picsum.photos/id/${id}/${size}"

  echo "──────────────────────────────────────────────"
  echo "🖼  Bildname: ${name}"
  echo "🌐 Quelle:  ${remoteUrl}"
  echo "💾 Ziel:    ${imagePath}"

  # Falls das Hauptbild schon existiert → überspringen
  if [[ -f "$imagePath" ]]; then
    echo "↪ ${imageFileName} existiert bereits – überspringe Download."
  else
    echo "→ Lade ${imageFileName}..."
    curl -s -L -A "Mozilla/5.0 (Macintosh; Intel Mac OS X)" \
         "${remoteUrl}" -o "${imagePath}"

    # Prüfen ob Datei wirklich geladen wurde (>100 Bytes)
    if [[ ! -s "$imagePath" || $(stat -f%z "$imagePath") -lt 100 ]]; then
      echo "❌ Fehler: ${imageFileName} ist kleiner als 100 Bytes oder leer."
      rm -f "$imagePath"
      echo "🚫 Abbruch – bitte später erneut versuchen."
      exit 1
    fi
    sleep 1
  fi

  # Thumbnail nur erzeugen, wenn es noch nicht existiert
  if [[ -f "$thumbPath" ]]; then
    echo "↪ ${thumbFileName} existiert bereits – überspringe Thumbnail."
  else
    echo "🪞 Erzeuge Thumbnail:"
    echo "   💾 Quelle: ${imagePath}"
    echo "   💾 Ziel:   ${thumbPath}"
    sips -Z 50 "$imagePath" --out "$thumbPath" >/dev/null
  fi
done

echo
echo "✅ Fertig! Alle Bilder und Thumbnails sind vollständig vorhanden."
echo "📁 Verzeichnis: $basepath"
