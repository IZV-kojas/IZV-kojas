# IZV Koju Saite

## Projekta apraksts
Šis ir **kopmītņu mājaslapas projekts**, kas paredzēts visiem kopmītņu iedzīvotājiem, IZV skolēniem un citiem interesentiem. Mājaslapa kalpos kā **informatīvs un interaktīvs resurss**, kur varēs uzzināt par kopmītņu dzīvi, vēsturi un aktuālajiem notikumiem.

## Funkcionalitāte
- **Informācija par kopmītņu dzīvi** – jaunumi, noteikumi, interesanti fakti
- **Kopmītņu receptes** (WIP) – iedzīvotāju iecienītākās receptes
- **Vēstures sadaļa** (WIP) – kopmītņu izcelsme un attīstība
- **Iedzīvotāju profili** – informācija par kopmītņu iedzīvotājiem
- **Saziņas platforma "Koju Blogs"** – iespēja dalīties pieredzē un diskutēt
- **Pasākumu kalendārs** (WIP) – gaidāmie notikumi un aktivitātes
- **Aprēķinu sistēma** (WIP) – rīks pasākumu budžeta plānošanai un organizēšanai


## Tīmekļa vietne
Pieejams: https://izvkojas.com

## Projekta dokumentācija
Pieejams: https://docs.google.com/document/d/1AjGb9h3W9fvTpXkpwW2cugXtutvXcjyy/edit?usp=sharing&ouid=115377672219018467340&rtpof=true&sd=true

**Instrukcija kā palaist projektu lokāli**

1. Lejupielādēt un ieinstalēt XAMPP no: <https://www.apachefriends.org>
2. Lejupielādēt projektu no GitHub: <https://github.com/IZV-kojas/IZV-kojas>

(Lejupielādēt ZIP failu vai izmantot komandu:

“git clone <https://github.com/IZV-kojas/IZV-kojas.git”>)

1. Atarhivēt vai atvērt projekta mapi
2. Iekopēt projekta failus (plugins, themes, uploads utt.) uz:

_C:\\xampp\\htdocs\\izv-kojas_

1. Atvērt XAMPP Control Panel
2. Startēt Apache un MySQL serverus
3. Atvērt pārlūkprogrammu un doties uz:

<http://localhost/phpmyadmin>

1. Izveidot jaunu datubāzi ar nosaukumu: _izv_kojas_
2. Pārliecināties, ka projektā atrodas fails _wp-config.php_

Ja faila nav, nokopēt _wp-config-sample.php_ un pārsaukt par _wp-config.php_

1. Failā wp-config.php iestatīt:

DB_NAME: _izv_kojas_

DB_USER: _root_

DB_PASSWORD: "" (tukšs)

1. Atvērt pārlūkā saiti:

_<http://localhost/izv-kojas>_

1. Veikt WordPress uzstādīšanu, ja nepieciešams
2. Projekts ir palaists lokāli

