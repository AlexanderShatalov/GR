<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if(!isset($arParams["CACHE_TIME"]))
    $arParams["CACHE_TIME"] = 3600000;

if(!isset($arParams["CACHE_TYPE"]))
    $arParams["CACHE_TYPE"] = 'A';

//старт зоны кеширования
//if($this->StartResultCache()){

use \Bitrix\Main\Context,
    Common\Utils\Connector;

if(!CModule::IncludeModule("iblock")){
    $this->abortResultCache();
    ShowError(GetMessage("IBLOCK_MODULE_NOT_INSTALLED"));
    return;
}

$Request = Context::getCurrent()->getRequest();
$Connector = new Connector();
$Connector->getMdaConnection('/siteapi/grain/');
$templatePage = '';

if($Request->isAjaxRequest() && $Request->get('action') == 'make_calc'){
    //  Ajax может быть чужим и прийти сюда по своим делам, поэтому ловим только конкретный
    $arResult['AJAX'] = 'Y';
    $templatePage = 'ajax';
    //  Две строчки выше на 99% не нужны, но пусть будет на всякий
    $postData = array();
    $resp = array();
    $prod = $Request->getPost('prod');
    $cat = $Request->getPost('category');
    $elev = $Request->getPost('elevator');
    $duration = $Request->getPost('duration');
    $volume = $Request->getPost('volume');
    if(!$prod || !$cat || !$elev || $duration < 3 || $duration > 90 || $volume <= 0){
        $resp['error'] = array('error' => true, 'error_text' => 'Переданы неправильные параметры');
        $resp = json_encode($resp, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        goto echo_resp;
    }
    $postData['product_code'] = implode('_', [$prod, $cat, $elev]);
    $postData['duration'] = $duration;
    $postData['volume'] = $volume;
    $postData['method'] = 'make_calc';
    $jsonData = json_encode($postData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    $headers = array('Content-type' => 'application/json');
    $Connector->addRawData($jsonData)->post()->header(0)->exec();
    $resp = $Connector->getResult();
    $error = $Connector->getError();
    if($error){
        //  Да-да, DRY и все дела... Потом переделаю
        $resp = array(
            'error' => array('error' => true, 'error_text' => 'Ошибка соединения с сервером')
        );
        $resp = json_encode($resp, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        goto echo_resp;
    }

    echo_resp:
    $APPLICATION->RestartBuffer();
    echo $resp;
    die();

    //  Разбираемся с данными, что пришли из калькулятора
}else{
    //  Это нормальная отрисовка страницы
    $requestData = array(
        'method' => 'get_data'
    );
    $pageData = $Connector->addData($requestData)->post(0)->header(0)->exec();
    $result = $pageData->getResult();
    $error = $pageData->getError();
    $result = json_decode($result, true);
    $arResult = array_merge($arResult, $result);
    $arAllElevators = array();
    foreach($arResult['ELEVATORS'] as $place => $arElevators){
        $arAllElevators = array_merge($arAllElevators, $arElevators);
    }
    $arResult['JSON_ELEVATORS'] = json_encode($arAllElevators, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
}






if(true){
    //$this->setResultCacheKeys(array());
    $this->includeComponentTemplate($templatePage);
}else{
    $this->abortResultCache();
}
//}
//  Конец зоны кеширования