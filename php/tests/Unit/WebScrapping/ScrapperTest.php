<?php

namespace Chuva\Tests\Unit\WebScrapping\WebScrapping;

use Chuva\Php\WebScrapping\Scrapper;
use PHPUnit\Framework\TestCase;

/**
 * Tests requirements for Scrapper.
 */
class ScrapperTest extends TestCase {

  /**
   * Tests scrap count.
   */
  public function testScrapSignature() {
    $dom = new \DOMDocument('1.0', 'utf-8');
    $dom->loadHTML('
    <a href="https://proceedings.science/proceedings/100227/_papers/137459" class="paper-card p-lg bd-gradient-left">
    <h4 class="my-xs paper-title">INVERSE LAPLACE TRANSFORM FOR SIGNAL ANALYSIS OF LOW FIELD NUCLEAR MAGNETIC RESONANCE</h4>
    <div class="authors">
    <span title="Departamento de Química  / Instituto de Ciências Exatas  / Universidade Federal de Minas Gerais">Tiago   Bueno de Moraes;</span></div>
    <div>
    <div class="tags mr-sm">Poster Presentation</div>
    <div class="tags flex-row mr-sm"><div class="expand"></div>
    <div class="volume-info">137459</div>
    </div></div></a>
    ');
    // @$dom->loadHTML('<html><body><h4>Generic Title</h4><p>Chove Chuva</p></body></html>');

    $scrapper = new Scrapper();
    $query = "//a[@class='paper-card p-lg bd-gradient-left']//h4 | 
              //div[@class='authors']| 
              //a[@class='paper-card p-lg bd-gradient-left']//div//div[@class='tags mr-sm'] | 
              //div[@class='volume-info']";
    $array = $scrapper->scrap($dom, $query);
    $results = '';
    foreach ($array as $obj) {
      $results .= "$obj";
    }
    $this->assertEquals('137459, INVERSE LAPLACE TRANSFORM FOR SIGNAL ANALYSIS OF LOW FIELD NUCLEAR MAGNETIC RESONANCE, Poster Presentation, Tiago   Bueno de Moraes; Departamento de QuÃ­mica  / Instituto de CiÃªncias Exatas  / Universidade Federal de Minas Gerais;', $results);
  }

}
