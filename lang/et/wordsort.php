<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License.
//
// @package    mod_wordsort
// @copyright 2026 Reelika Pihl
// @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later

defined('MOODLE_INTERNAL') || die();

// Plugin specific information.

$string['pluginname'] = 'Word Sort';
$string['modulename'] = 'Word Sort';
$string['modulenameplural'] = 'Word Sort tegevused';
$string['pluginadministration'] = 'Word Sort administreerimine';
$string['activitysetup'] = 'Word Sort seadistamine';
$string['wordsort:viewreports'] = 'Vaata Word Sort raporteid';
$string['exportwords'] = 'Eksport';
$string['importwords'] = 'Import';
$string['csvfile'] = 'CSV fail';

// Activity settings.
$string['activityoptions'] = 'Tegevuse valikud';
$string['shufflewords'] = 'Sega sõnad';
$string['moveup'] = 'Liiguta üles';
$string['movedown'] = 'Liiguta alla';
$string['start'] = 'Alusta';
$string['teachertools'] = 'Õpetaja tööriistad';
$string['backtoreport'] = 'Tagasi raportisse';

// Word
$string['word'] = 'Sõna';
$string['words'] = 'Sõnad';
$string['addword'] = 'Lisa sõna';
$string['wordsaved'] = 'Sõna edukalt salvestatud.';
$string['managewords'] = 'Halda sõnu';
$string['deleteword'] = 'Kustuta sõna';
$string['confirmdeleteword'] = 'Kas oled kindel, et soovid sõna "{$a}" kustutada?';
$string['worddeleted'] = 'Sõna kustutatud.';
$string['bulkaddwords'] = 'Massiliselt lisamine';
$string['bulkwordsadded'] = 'Lisatud {$a} sõna.';

// Attempts
$string['attempt'] = 'Katse';
$string['maxattempts'] = 'Maksimaalne katsete arv';
$string['attemptsettings'] = 'Katsete seadistamine';
$string['attempts'] = 'Katsed';
$string['bestattempt'] = 'Parim katse';
$string['viewattempts'] = 'Vaata katseid';
$string['attemptsreport'] = 'Õpilaste katsete raport';

// Feedback
$string['feedbackmode'] = 'Tagasiside režiim';
$string['feedbackeachmove'] = 'Iga liikumise järel';
$string['feedbacksubmit'] = 'Pärast esitamist';
$string['feedbacknone'] = 'Tagasisidet pole';

// Categories
$string['categoryleft'] = 'Vasak kategooria';
$string['categoryright'] = 'Parem kategooria';
$string['errorcategoryleftrequired'] = 'Vasak kategooria on nõutav.';
$string['errorcategoryrightrequired'] = 'Parem kategooria on nõutav.';
$string['errorcategoriesequal'] = 'Vasak ja parem kategooria peavad olema erinevad.';
$string['category'] = 'Kategooria';
$string['categories'] = 'Kategooriad';
$string['leftcategory'] = 'Vasak kategooria';
$string['rightcategory'] = 'Parem kategooria';
$string['correctcategory'] = 'Õige kategooria';

// Timing.
$string['timingsettings'] = 'Aja seadistamine';
$string['timingmode'] = 'Aja režiim';
$string['notimer'] = 'Taimer puudub';
$string['countdown'] = 'Aja tagasiloendus';
$string['stopwatch'] = 'Stoppkell';
$string['timevalue'] = 'Aja limiit / Sihtaeg (sekundites)';
$string['timelimitlabel'] = 'Aja limiit';

// Results and review.
$string['finished'] = 'Lõpetatud';
$string['tryagain'] = 'Proovi uuesti';
$string['submit'] = 'Lõpeta';
$string['score'] = 'Tulemus';
$string['submissionsummary'] = 'Soorituse kokkuvõte';
$string['bestscore'] = 'Parim tulemus';
$string['youranswer'] = 'Sinu vastus';
$string['correctanswer'] = 'Õige vastus';
$string['status'] = 'Olek';
$string['statusinprogress'] = 'Pooleli';
$string['statusabandoned'] = 'Hüljatud';
$string['student'] = 'Õpilane';
$string['percentage'] = 'Protsent';
$string['submitted'] = 'Esitatud';
$string['statussubmitted'] = 'Esitatud';
$string['review'] = 'Ülevaade';
$string['viewanswers'] = 'Vaata vastuseid';
$string['attemptreviewtitle'] = 'Katse {$a}';
$string['attemptreview'] = 'Katse ülevaade';
$string['studentanswer'] = 'Õpilase vastus';
$string['result'] = 'Tulemus';
$string['correct'] = 'Õige';
$string['incorrect'] = 'Vale';
$string['passed'] = 'Läbitud';
$string['notpassed'] = 'Ei läbinud';
$string['details'] = 'Detailid';
$string['grade'] = 'Hinne';

// Status messages.
$string['nowordsadded'] = 'Sõnu pole veel lisatud.';
$string['previewactivity'] = 'Tegevuse eelvaade';
$string['previewactivitydesc'] = 'Vaata tegevuse eelvaadet täpselt nii, nagu seda näevad sinu õpilased.';
$string['activitysetupdesc'] = 'See tegevus ei ole veel õpilaste jaoks valmis. Lõpeta järgmised seadistamise sammud allpool.';
$string['categoriesconfigured'] = 'Seadistatud kategooriad';
$string['wordsnotadded'] = 'Sõnu pole veel lisatud. Palun lisa sõnad, et õpilased saaksid seda tegevust teha.';
$string['activitysetupnext'] = 'Järgmine samm: lisa vähemalt üks sõna.';
$string['nextstep'] = 'Järgmine samm';
$string['addfirstword'] = 'Lisa oma esimene sõna, et alustada tegevuse loomist.';
$string['invalidcategory'] = 'CSV-failist leiti kehtetu kategooria "{$a}".';
$string['invalidcsv'] = 'Kehtetu CSV fail.';
$string['cannotreadcsv'] = 'Üles laaditud CSV-faili ei saanud lugeda.';
$string['importsuccess'] = 'Sõnad edukalt imporditud.';
$string['importsummary'] = 'Imporditud {$a->imported} sõna. Vahele jäetud {$a->skipped} duplikaati.';
$string['nomoreattempts'] = 'Oled kasutanud ära kõik saadaolevad katsed.';
$string['categorymismatch'] = 'Import ebaõnnestus. CSV kategooriad ei kattu selle tegevuse jaoks seadistatud kategooriatega.';
$string['activitysubmitted'] = 'Sinu tulemused on saadetud õpetajale. Head päeva!';
$string['attemptsused'] = 'Oled proovinud: {$a->used}/{$a->max} korda.';

//Privaatsus

$string['privacy:metadata:wordsort_attempts'] = 'Word Sort salvestab õpilase katsete andmeid, sealhulgas tulemusi, vastuseid ja ajakasutuse andmeid.';
$string['privacy:metadata:wordsort_attempts:userid'] = 'Kasutaja ID, kes katse sooritas.';
$string['privacy:metadata:wordsort_attempts:attempt'] = 'Katse number.';
$string['privacy:metadata:wordsort_attempts:score'] = 'Katsel saavutatud punktisumma.';
$string['privacy:metadata:wordsort_attempts:totalwords'] = 'Katses olevate sõnade koguarv.';
$string['privacy:metadata:wordsort_attempts:percentage'] = 'Katsel saavutatud protsentuaalne tulemus.';
$string['privacy:metadata:wordsort_attempts:timeused'] = 'Katse sooritamiseks kasutatud aeg.';
$string['privacy:metadata:wordsort_attempts:timecreated'] = 'Katse loomise aeg.';
$string['privacy:metadata:wordsort_attempts:answers'] = 'Katse käigus esitatud vastused.';
$string['privacy:metadata:wordsort_attempts:status'] = 'Katse olek.';
$string['privacy:metadata:wordsort_attempts:finalsubmission'] = 'Näitab, kas katse esitati lõplikult.';
$string['privacy:attempts'] = 'Word Sort katsed';

// End of file.