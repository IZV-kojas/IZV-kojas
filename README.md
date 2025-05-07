# IZV Koju Saite

## Projekta apraksts
Šis ir kopmītņu mājaslapas projekts, kas paredzēts visiem kopmītņu iedzīvotājiem, IZV skolēniem un citiem ieinteresētiem. Mājaslapa kalpos kā informatīvs un interaktīvs resurss, kur varēs uzzināt par kopmītņu dzīvi, vēsturi un aktuālajiem notikumiem.

## Funkcionalitāte
- **Kopmītņu arhīvs** – noteikumi, interesanti fakti
- **Kopmītņu receptes** – iedzīvotāju iecienītākās receptes
- **Iedzīvotāju profili** – informācija par kopmītņu iedzīvotājiem
- **Saziņas platforma "Koju čats"** – iespēja dalīties pieredzē un diskutēt
- **Pasākumu kalendārs** – gaidāmie notikumi un aktivitātes
- **Aprēķinu sistēma** – rīks pasākumu budžeta plānošanai un organizēšanai
- u.c.


## Tīmekļa vietne
Pieejams: <https://izvkojas.com>

## Projekta dokumentācija
Pieejams: <https://docs.google.com/document/d/14bUOHrkMKudyzdfrV4mDcCssey2H8GfTp_Lxx6dOhro/edit?usp=sharing>

## Instrukcija kā palaist projektu lokāli

1. Lejupielādēt un ieinstalēt XAMPP no: <https://www.apachefriends.org>
2. Lejupielādēt projektu no GitHub: <https://github.com/IZV-kojas/IZV-kojas>

(Lejupielādēt ZIP failu vai izmantot komandu:

“git clone <https://github.com/IZV-kojas/IZV-kojas.git”>)

3. Atarhivēt vai atvērt projekta mapi
4. Iekopēt projekta failus (plugins, themes, uploads utt.) uz:

_C:\\xampp\\htdocs\\izv-kojas_

5. Atvērt XAMPP Control Panel
6. Startēt Apache un MySQL serverus
7. Atvērt pārlūkprogrammu un doties uz:

<http://localhost/phpmyadmin>

8. Izveidot jaunu datubāzi ar nosaukumu: _izv_kojas_
9. Pārliecināties, ka projektā atrodas fails _wp-config.php_

Ja faila nav, nokopēt _wp-config-sample.php_ un pārsaukt par _wp-config.php_

10. Failā wp-config.php iestatīt:

DB_NAME: _izv_kojas_

DB_USER: _root_

DB_PASSWORD: "" (tukšs)

11. Atvērt pārlūkā saiti:

_<http://localhost/izv-kojas>_

12. Veikt WordPress uzstādīšanu, ja nepieciešams
13. Projekts ir palaists lokāli

