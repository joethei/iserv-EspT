# Elternsprechtagterminvereinbarung für IServ
[![Build Status](https://teamcity.joethei.xyz/app/rest/builds/buildType:(id:Studium_Programming_DigiHelferElternsprechtag)/statusIcon)](https://teamcity.joethei.xyz/viewType.html?buildTypeId=Studium_Programming_DigiHelferElternsprechtag&guest=1)

Entstanden als Projektarbeit im Rahmen des [DigiHelfer](https://www.hs-emden-leer.de/studierende/fachbereiche/technik/projekte/neo-mint/digihelfer) Projekts

## Dokumentation
[Projektarbeit](https://pdf.joethei.space/Abgaben/Projektarbeit/index.pdf)

## Hinzufügen des Repositories

```
wget -O public.key https://joethei.space/index.php/s/LTPCNbcmX2gPXX6/download
apt-key add public.key
echo "deb https://nexus.joethei.xyz/repository/apt stable main" > /etc/apt/sources.list.d/digihelfer.list
apt update
```

Kann nun in IServ unter `Verwaltung > System > Pakete` gefunden werden