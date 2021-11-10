<?

// это уже продуктивная структура. в которой мы пытаемся получить код авторизации
// на основании redresh token


require_once 'config.php';
require_once 'amowh.php';
// require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

$amoApi = new \amowh\Amo(CLIENT_ID, CLIENT_SECRET, CLIENT_REDIRECT_URI, SUBDOMAIN);
// echo "<pre>" . print_r($amoApi, 1) . "</pre>";


var_dump(
    $amoApi->checkTokensIsCorrect()
);die();


echo "<pre>" . print_r(
    $amoApi->findCatalogElementByArtnumber(21206),
1) . "</pre>";
die();

// echo var_dump($amoApi->checkTokensIsCorrect());

$resList = $amoApi->getCatalogList([
    'catalog_id' => CATALOG_ID,
    'page' => 1
]);


echo "<pre>" . print_r($resList['_embedded'], 1) . "</pre>";
die();

foreach ($resList['_embedded']['items'] as $key => $value) {
    echo "<pre>" . print_r($value, 1) . "</pre>";
}

die();

for ($i=0; $i < 101 ; $i++) { 
    // попытаемся создать один товар 
    $resAdd = $amoApi->addCatalogElement([
        [
            'catalog_id' => CATALOG_ID,
            'name' => 'Тестовый товар из API ' . $i,
        ],
        // [
        //     'catalog_id' => CATALOG_ID,
        //     'name' => 'Тестовый товар из API 22',
        // ]
    ]);
}



// echo "<pre>" . print_r($resAdd, 1) . "</pre>";


/*


Array
(
    [token_type] => Bearer
    [expires_in] => 86400
    [access_token] => eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjczZjhmN2E2N2U5YmZlMzdiM2QxZTY2ODRhMGViYTAzMDhmMTg4MjZjYTQyNjVmMGFmMTFlNDFjYmIyYTgzMDBjYWZjZDFlMjdlMTFjM2M4In0.eyJhdWQiOiJiZDRhYzAwYi02OWFmLTQwNDItOGJiNy0zNGYyNzc0NjNmZjMiLCJqdGkiOiI3M2Y4ZjdhNjdlOWJmZTM3YjNkMWU2Njg0YTBlYmEwMzA4ZjE4ODI2Y2E0MjY1ZjBhZjExZTQxY2JiMmE4MzAwY2FmY2QxZTI3ZTExYzNjOCIsImlhdCI6MTYwNjY1Njk2OCwibmJmIjoxNjA2NjU2OTY4LCJleHAiOjE2MDY3NDMzNjgsInN1YiI6IjY0MjY0NTQiLCJhY2NvdW50X2lkIjoyOTA5NDk1NSwic2NvcGVzIjpbInB1c2hfbm90aWZpY2F0aW9ucyIsImNybSIsIm5vdGlmaWNhdGlvbnMiXX0.de6ZMt5Wu6SCu3477O8a8nD6uR8koahbLdvtcLxlK0D6XdCfHuVl8I2lz_BtXugK3xbA01fw-sGG_EP4ROGWoLQlvX_XLga4rILVpiY2wcTy83f_VV1IMWEWQ9Arn00u9mh1mZo7HXmtMddjF-n6WFVJgUR7-WdLRpCJ5VBSLewyR5RnYBQhJlhRSCj5CUUN_jkrBR7S6t_BKkCDfC0Fv74G9gNRjDTC3T_5yD7gGMTmaEjds9nDUm8lBWtytqbAPkLl4iQqnWZv6x0tYM3QzHZDuzChof8MqMsaM-ma9C4WTWpXD4J7iPx4N1D5xnBAkw9G3VQuyfIs4L5Xicl6OQ
    [refresh_token] => def502003c3a11faf2d3aebf630f40d018ec9a57e78aae643fa6e52daf25ca0886e2c9c2d97e5447a884baaff402e2a88ec94d965c47b1a61ca21c49fb2c64e227fa37c1ed54d7325b01e0483805b27ed089fb65109a0c7f24b7831d8000172087082d8ef43024efa3d5d2e9a696ef14b0a33c7196bf5ad9fbe3e2a873630353487fc07d690122630ded23020f2154ca7c8cb2de6247432be6a3b297a17345847ec1680bfec7afb84c51e83613ec941f6c3bca288e34a10761c8ca2a844e8b041b2b4c9fffdb7a9497b2f8b5e6f9092ce9e5b55a014a1cf378c28519301db75fc578d7c5dcd8fbb41a9b27f0c7673eb0f9d9fd317417c6ac2ab953d486fedd783cf1ca54936159299b8f9d0fd989de7ef93f96349e16d469865926798a723491742e720aa845f860e2706758f69c48094a0300a18a6200754d0f48c0117eed5c0ba28b130c135146386cb7da5ea0c2b1ba50ab89402a201fb7a6baa641430592c645b312ca96f4b114f82acd82301cda2289f12ca225de4330e1f98c93345eb33035fb1383b2980910518d0ea7eb1abf3f8e072e4ae706a13cad8fc1721152b935aa2c26a099c05eab6e97d4c7504f74d7b9ad4bc25e86cc9566d92021943070d34f2b121d7e8caebd5d
)


*/


?>