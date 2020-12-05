#!/usr/bin/php
<?php

class lang_config{

}

function loadCSV(string $file_path)
{
    $file = fopen($file_path, "r");
    $list = [];
    while ($data = fgetcsv($file)) {
        $list[]=$data;
    }
    fclose($file);
    return $list;
}

function getInput(string $input): array
{
    return json_decode($input, true);
}

function getParsedData(array $raw_data, array $title): array
{
    $parsed_data = [];
    foreach ($raw_data as $key => $value) {
        $key = $value[0];
        foreach ($value as $index => $content) {
            if ($index == 0) {
                continue;
            }
            $lang = $title[$index];
            if ($value[$index]) {
                $parsed_data[$key][$lang] = $value[$index];
            }
        }
    }
    return $parsed_data;
}

function solve($data, $dic, $lang_list)
{
    $res = new lang_config();
    foreach ($lang_list as $lang) {
        $res->{$lang} = replace($data, $dic, $lang);
    }
    return $res;
}

function replace($item, $dic, $lang)
{   
    foreach ($item as $key => $config) {
        if (is_array($config)) {
            $item[$key] = replace($config, $dic, $lang);
        } else {
            $item[$key] = $dic[$config][$lang] ?? $config;
        }
    }
    return $item;
}

function main(): void
{
    $raw_data = loadCSV('input.csv');
    $lang_list = ['en', 'de', 'fr', 'ru', 'ko', 'ja', 'es', 'tr', 'it', 'ar', 'th', 'pt'];
    $title = array_merge([''], $lang_list);
    array_shift($raw_data);

    $parsed_data = getParsedData($raw_data, $title);
    // print_r($parsed_data);
    $input = getInput('"a":1');
    // print_r($input);
    $res = solve($input, $parsed_data, $lang_list);
    // print_r($res);
    $res = json_encode($res, JSON_UNESCAPED_UNICODE);
    // print_r($res);
    $my_file = fopen('result.json', 'w');
    fwrite($my_file, $res);
    fclose($my_file);
}

main();