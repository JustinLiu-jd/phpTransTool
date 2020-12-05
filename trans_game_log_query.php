#!/usr/bin/php
<?php

class csv2KeyValuePair {

    protected $fileName;

    protected $items;

    protected $title;

    public function __construct(string $fileName = '')
    {
        $this->fileName = $fileName;
        $this->loadCSV();
        $this->formatItems($this->items);
    }

    public function getFileName()
    {
        return $this->fileName;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getItems()
    {
        return $this->items;
    }

    protected function loadCSV(string $file_path = null)
    {
        $file_path = $file_path ?? $this->fileName;

        $file = fopen($file_path, "r");
        $list = [];
        while ($data = fgetcsv($file)) {
            $list[]=$data;
        }
        fclose($file);

        $this->items = $list;
    }

    protected function formatItems($list = null)
    {
        $items = $input ?? $this->items;
        $this->title = $items[0];
        array_shift($items);
        $this->items = $items;
    }

    
    /**
     * 处理成数组中 键值对 的形式, 并输出到文件中.
     * 
     * @param int keyColumn 键所在的列下标
     * @param int valueColumn 值所在的列下标
     * @param string outputFileName 输出文件名
     * @param int lineFrom 从哪一行开始(以 0 为开始的下标)
     * @param int|null lineEnd 到哪一行结束(以 0 为开始的下标)
     */
    public function outputAsArray($keyColumn, $valueColumn, $outputFileName = 'output.php', $lineFrom = 0, $lineEnd = null)
    {
        $outputFile = fopen($outputFileName, 'w');
        fwrite($outputFile, '<?php'.PHP_EOL.PHP_EOL);
        fwrite($outputFile, 'return ['.PHP_EOL);

        $line = $lineFrom;
        while ($curr = $this->items[$line++]) {
            if ($lineEnd && $line > $lineEnd) {
                break;
            }
            $key = addslashes($curr[$keyColumn]);
            $value = addslashes($curr[$valueColumn]);
            fwrite($outputFile, "    '{$key}' => '{$value}',".PHP_EOL);
        }


        fwrite($outputFile, '];'.PHP_EOL);
        fclose($outputFile);
    }

}

function main(): void
{
    define('PHP_EOL', '\n');

    $service = new csv2KeyValuePair('input.csv');
    $service->outputAsArray(0, 0, 'output.php', 25);

    print_r($service->getTitle());
}

main();