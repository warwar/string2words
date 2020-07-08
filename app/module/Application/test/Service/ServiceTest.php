<?php


namespace ApplicationTest\Service;


use Application\Service\String2Words;
use Laminas\Test\PHPUnit\Controller\AbstractControllerTestCase;

class ServiceTest extends AbstractControllerTestCase
{
    public function testString2Words()
    {
        $String2Words = new String2Words;
        $a = $String2Words('Русский язык');
        $this->assertArrayHasKey('Русский', $a);
        $this->assertArrayHasKey('язык', $a);
        $this->assertCount(2, $a);

        $a = $String2Words('10 year\'s \'Особое\'');
        $this->assertArrayHasKey('year\'s', $a);
        $this->assertArrayHasKey('10', $a);
        $this->assertArrayHasKey('Особое', $a);
        $this->assertArrayNotHasKey("'Особое'", $a);
        $this->assertCount(3, $a);

        $a = $String2Words('слово-через-дефисы "слово"');
        $this->assertArrayHasKey('слово-через-дефисы', $a);
        $this->assertArrayHasKey('слово', $a);
        $this->assertArrayNotHasKey('"слово"', $a);
        $this->assertCount(2, $a);

        $str = "\nРусский язык\r\n слово-через-дефисы \"слово\" с Цифрой1\n z Victor 10 year's 'old\t 'Особое'\n incoming\n";
        $a = $String2Words($str);
        $this->assertCount(13, $a);
        $this->assertArrayHasKey('Цифрой1', $a);
        $this->assertArrayHasKey('слово-через-дефисы', $a);

        $a = $String2Words($str, ['words' => "/[-a-zа-я'\"]+/ui"]);
        $this->assertCount(12, $a);
        $this->assertArrayNotHasKey('Цифрой1', $a);
        $this->assertArrayNotHasKey('10', $a);
        $this->assertArrayHasKey('Цифрой', $a);

        $a = $String2Words($str, ['words' => "/[a-zа-я0-9'\"]+/ui"]);
        $this->assertCount(14, $a);
        $this->assertArrayNotHasKey('слово-через-дефисы', $a);
        $this->assertArrayHasKey('через', $a);
        $this->assertArrayHasKey('дефисы', $a);
        $this->assertArrayHasKey('year\'s', $a);
    }

    public function testString2WordsErrorEmptyWords()
    {
        $this->expectError();
        $String2Words = new String2Words;
        $this->assertNull($String2Words('test', ['words' => ""]));
    }

    public function testString2WordsErrorEmptyClearChars()
    {
        $this->expectError();
        $String2Words = new String2Words;
        $this->assertNull($String2Words('test', ['clean_chars' => ""]));
    }
}