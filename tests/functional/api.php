<?php

include 'includes/init.php';

$paths = [
    ['', 400, '{"error":"No service requested"}'],
    ['v1/', 400, '{"error":"Not enough parameters for this query."}'],
    ['v1/entity/central/?id=browser/chrome/browser/syncQuota.properties:collection.bookmarks.label', 200, '{"en-US":"Bookmarks","fr":"Marque-pages"}'],
    ['v1/entity/central/?id=browser/chrome/browser/IdontExist', 400, '{"error":"Entity not available"}'],
    ['v1/search/strings/central/en-US/fr/New%2Bbookmarks/', 200, '{"browser\/chrome\/browser\/places\/bookmarkProperties.properties:dialogTitleAddMulti":{"New Bookmarks":"Nouveaux marque-pages"}}'],
    ['v1/search/strings/central/en-US/fr/tralala/', 200, '[]'],
    ['v1/locales/central/', 200, '["ar","ast","cs","de","en-GB","en-US","eo","es-AR","es-CL","es-ES","es-MX","fa","fr","fy-NL","gl","he","hu","id","it","ja","ja-JP-mac","kk","ko","lt","lv","nb-NO","nl","nn-NO","pl","pt-BR","pt-PT","ru","sk","sl","sv-SE","th","tr","uk","vi","zh-CN","zh-TW"]'],
    ['v1/locales/iDontExist/', 400, '{"error":"The repo queried (iDontExist) doesn\'t exist."}'],
    ['v1/repositories/', 200, '["release","beta","aurora","central","firefox_ios","gaia_2_5","gaia","mozilla_org"]'],
    ['v1/repositories/', 200, '["release","beta","aurora","central","firefox_ios","gaia_2_5","gaia","mozilla_org"]'],
    ['v1/repositories/fr/', 200, '["aurora","beta","central","firefox_ios","gaia","gaia_2_5","mozilla_org","release"]'],
    ['v1/suggestions/central/en-US/fr/ar/?max_results=2', 200, '["Bookmark","Marque-page"]'],
    ['v1/suggestions/central/en-US/fr/ar/?max_results=10', 200, '["Bookmark","Bookmarks","New Bookmarks","Bookmark This Page","Marque-page","Marque-pages","Marquer cette page","Nouveaux marque-pages"]'],
    ['v1/suggestions/central/en-US/fr/ar/?max_results=0', 200, '["Bookmark","Bookmarks","New Bookmarks","Bookmark This Page","Marque-page","Marque-pages","Marquer cette page","Nouveaux marque-pages"]'],
    ['v1/suggestions/central/en-US/fr/ar/', 200, '["Bookmark","Bookmarks","New Bookmarks","Bookmark This Page","Marque-page","Marque-pages","Marquer cette page","Nouveaux marque-pages"]'],
    ['v1/suggestions/central/en-US/fr/bookmark/?max_results=2', 200, '["Bookmark","Bookmarks"]'],
    ['v1/tm/central/en-US/fr/Bookmark/?max_results=3&min_quality=80', 200, '[{"source":"Bookmark","target":"Marquer cette page","quality":100},{"source":"Bookmark","target":"Marque-page","quality":100},{"source":"Bookmarks","target":"Marque-pages","quality":88.89}]'],
    ['v1/tm/global/fr/en-US/Ouvrir/', 200, '[{"source":"Ouvrir dans le Finder","target":"Find in Finder","quality":28.57},{"source":"D\u00e9couvrez comment ouvrir une fen\u00eatre de navigation priv\u00e9e","target":"Learn how to open a private window","quality":8.77}]'],
    ['v1/transliterate/foo/bar/', 400, '{"error":"Wrong locale code"}'],
    ['v1/transliterate/sr-Cyrl/%D1%81%D1%80%D0%BF%D1%81%D0%BA%D0%B0/', 200, '["srpska"]'],
    ['v1/versions/', 200, '{"v1":"stable"}'],
];

$obj = new \pchevrel\Verif('Check API HTTP responses');
$obj
    ->setHost('localhost:8083')
    ->setPathPrefix('api/');

$check = function ($object, $paths) {
    foreach ($paths as $values) {
        list($path, $http_code, $content) = $values;
        $object
            ->setPath($path)
            ->fetchContent()
            ->hasResponseCode($http_code)
            ->isJson()
            ->isEqualTo($content);
    }
};

$check($obj, $paths);

$obj->report();

// Kill PHP dev server by killing all children processes of the bash process we opened in the background
exec('pkill -P ' . $pid);
die($obj->returnStatus());
