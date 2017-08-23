<?php
declare(strict_types=1);
namespace GlagolTest\Bridge\Lumen\Entity;

use Glagol\Bridge\Lumen\Entity\JsonSerializeTrait;
use JsonSerializable;
use PHPUnit\Framework\TestCase;

class JsonSerializeTraitTest extends TestCase
{
    public function testShouldSerializeEntity()
    {
        $entity = new class implements JsonSerializable {
            use JsonSerializeTrait;
            private $id = 123;
            private $row;
            private $rows;
            function __construct()
            {
                $this->row = new class implements JsonSerializable {
                    use JsonSerializeTrait;
                    private $name = "bla";
                };
                $this->rows = [
                    new class implements JsonSerializable {
                        use JsonSerializeTrait;
                        private $name = "bla2";
                    }
                ];
            }
        };

        $this->assertEquals(['id' => 123, 'row' => ['name' => 'bla'], 'rows' => [
            ['name' => 'bla2']
        ]], json_decode(json_encode($entity), true));
    }
}
