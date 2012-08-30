<?php

$colege = array();
$colege['data'] = array(
    array("name" => "Harvard", "key" => 16777217, "subtext" => "Cambridge, Massachusetts"),
    array("name" => "Hawaii", "key" => 16777412, "subtext" => "Honolulu, Hawaii"),
    array("name" => "Hartford", "key" => 16777445, "subtext" => "West Hartford, Connecticut"),
    array(
        "name" => "Harding",
        "key" => 16777927,
        "subtext" => "Searcy, Arkansas"
    ),
    array(
        "name" => "Hampton",
        "key" => 16777627,
        "subtext" => "Hampton, Virginia"
    ),
    array(
        "name" => "Harper",
        "key" => 16779183,
        "subtext" => "Palatine, Illinois"
    ),
    array(
        "name" => "Hawai`i Pacific University",
        "key" => 16777611,
        "subtext" => "Honolulu, Hawaii"
    ),
    array(
        "name" => "Hamilton College",
        "key" => 16777262,
        "subtext" => "Clinton, New York"
    )
);
//echo json_encode($arr);
$str = '{
  "data": [
    {
      "name": "Dubai", 
      "key": 218104176, 
      "subtext": "United Arab Emirates"
    }, 
    {
      "name": "Dublin", 
      "key": 219110906, 
      "subtext": "Ireland"
    }, 
    {
      "name": "Dubna, Russia", 
      "key": 220068414, 
      "subtext": "Moskva, Russia"
    }, 
    {
      "name": "Dubuque, IA", 
      "key": 220548728, 
      "subtext": "IA, United States"
    }, 
    {
      "name": "Dubno, Ukraine", 
      "key": 220471793, 
      "subtext": "Rivnens\'ka Oblast\', Ukraine"
    }, 
    {
      "name": "Dubrajpur, India", 
      "key": 219128039, 
      "subtext": "West Bengal, India"
    }, 
    {
      "name": "Dublin, OH", 
      "key": 220602982, 
      "subtext": "OH, United States"
    }, 
    {
      "name": "Dublin, CA", 
      "key": 220522764, 
      "subtext": "CA, United States"
    }
  ]
}';
$city = json_decode(utf8_encode($str));
//echo (json_encode($arr2));

?>
