<?php

namespace Chuva\Php\WebScrapping;

use Chuva\Php\WebScrapping\Entity\Paper;
use Chuva\Php\WebScrapping\Entity\Person;

/**
 * Does the scrapping of a webpage.
 */
class Scrapper {

  /**
   * Loads paper information from the HTML and returns the array with the data.
   */
  public function scrap(\DOMDocument $dom): array {
    $xpath = new \DOMXPath($dom);
    $dataList = $xpath->query(
      "//a[@class='paper-card p-lg bd-gradient-left']//h4 | 
       //div[@class='authors']| 
      //a[@class='paper-card p-lg bd-gradient-left']//div//div[@class='tags mr-sm'] | 
      //div[@class='volume-info']");
    $allDatas = [];
    $count = 0; 
    foreach ($dataList as $data) {
      if ($data->nodeName === 'h4'){
          $title = $data->nodeValue;
    }
    if ($data->nodeName === 'div' && $data->getAttribute('class') === 'authors') {
          $spans = $data->getElementsByTagName('span');
          foreach ($spans as $span) {
            $dep = $span->getAttribute('title');
            $name = $span->nodeValue;
            $author = new Person($name, $dep);
            $authors[] = $author;
          }
    }
    if ($data->nodeName === 'div' && $data->getAttribute('class') === 'tags mr-sm') {
          $type = $data->nodeValue;
    }
    if($data->nodeName === 'div' && $data->getAttribute('class') === 'volume-info'){
          $id = $data->nodeValue;
    }
    $count++;
    if($count == 4){
        $paper = new Paper($id, $title, $type, $authors);
        $allDatas[] = $paper;
        $count=0; // Reset count.
        $authors = []; // Reset array.
    }
    }
    return $allDatas;
  }

}
