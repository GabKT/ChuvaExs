<?php

namespace Chuva\Php\WebScrapping;

use OpenSpout\Writer\Common\Creator\WriterEntityFactory;
use OpenSpout\Common\Entity\Row;
/**
 * Runner for the Webscrapping exercice.
 */
class Main {

  /**
   * Main runner, instantiates a Scrapper and runs.
   */
  public static function run(): void {
    $dom = new \DOMDocument('1.0', 'utf-8');
    $dom->loadHTMLFile(__DIR__ . '/../../assets/origin.html');

    $data = (new Scrapper())->scrap($dom);

    $writer = WriterEntityFactory::createXLSXWriter();
    $writer->openToFile(__DIR__ .'/../../assets/model.xlsx');

    
    foreach($data as $data){
      $cells = [];
      $cells[] = WriterEntityFactory::createCell((string)$data->id);
      $cells[] = WriterEntityFactory::createCell((string)$data->title);
      $cells[] = WriterEntityFactory::createCell((string)$data->type);
      foreach($data->authors as $author){
        $cells[] = WriterEntityFactory::createCell((string)$author->name);
        $cells[] = WriterEntityFactory::createCell((string)$author->institution);
      }
      $row = WriterEntityFactory::createRow($cells);
      $writer->addRow($row);
    }
    $writer->close();
  }
}
?>