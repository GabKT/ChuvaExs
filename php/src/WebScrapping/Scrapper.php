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

    foreach($dataList as $data){

      if($data->nodeName === 'h4'){
        $title = $data->nodeValue;
      }
      $authors = [];
      if ($data->nodeName === 'div' && $data->getAttribute('class')==='authors') {
        $spans = $data->getElementsByTagName('span');
        foreach($spans as $span){
          $dep = $span->getAttribute('title');
          $name = $span->nodeValue;
          $author = new Person($dep, $name);
          $authors[]=$author;
        }
      }
      if($data->nodeName === 'div' && $data->getAttribute('class')==='tags mr-sm'){
        $type = $data->nodeValue;
      } 
      if($data->nodeName === 'div' && $data->getAttribute('class')==='volume-info'){
        $id = $data->nodeValue;
      } 
      $paper = new Paper($id, $title, $type, $authors);
      $flag = 0;
      foreach($allDatas as $data){
        if($paper->title == $data->title){
          $flag = 1;
        }
      }
      if($flag != 1 && !empty($authors)){
        $allDatas[] = $paper;
      }
    }
    return $allDatas;
  }

}
?>