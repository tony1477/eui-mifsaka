<?php
class RunningTelegramTesCommand extends CConsoleCommand
{


$url = "https://mifsaka.com/agemlive/order/reportorder/downpdf?lro=80&company=1&sloc=&materialgroup=&customer=&sales=&spvid=&product=&salesarea=&startdate=03/01/2020&enddate=03/03/2020&per=1";
$ch = curl_init($url);
curl_exec($ch);
curl_close($ch);


}
