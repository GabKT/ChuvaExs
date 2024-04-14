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

    foreach($dataList as $data){
      if($data->nodeName === 'h4'){
          $title = $data->nodeValue;
      }
        
      if ($data->nodeName === 'div' && $data->getAttribute('class')==='authors') {
          $spans = $data->getElementsByTagName('span');
          foreach($spans as $span){
            $dep = $span->getAttribute('title');
            $name = $span->nodeValue;
            $author = new Person($name, $dep);
            $authors[]=$author;
          }
      }
      if($data->nodeName === 'div' && $data->getAttribute('class')==='tags mr-sm'){
          $type = $data->nodeValue;
      } 
      if($data->nodeName === 'div' && $data->getAttribute('class')==='volume-info'){
          $id = $data->nodeValue;
      } 
      $count++;
      if($count == 4){
        $paper = new Paper($id, $title, $type, $authors);
        $allDatas[] = $paper;
        $count=0;
        $authors = [];
      }
    }
    //var_dump($allDatas);
    return $allDatas;
    /*capturar dados dom e sair preenchendo as variaveis por verificaçao -> gerou varios colaterais como dados fora de ordem ou com dados vazios, cheguei a implementar soluçoes 
    para esses problemas mas sempre apareciam mais foi ai que eu parei tudo pra ter uma outra estrategia ja que os dados estavam sendo coletados corretamente entao o erro nao era
    a captura e sim como eu lidava com eles foi ai que eu comecei a debugar variavel por variavel e funçao por funçao e percebi que os dados que eu precisava vinham de 4 em 4
    como eu defini na query do xpath e um simples contador ja resolvia meu problema */
  }
}
?>