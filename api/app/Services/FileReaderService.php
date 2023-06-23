<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Shuchkin\SimpleXLS;


class FileReaderService extends AbstractService
{

    const EXTENSION_XLS = 'xls';
    const EXTENSION_XML = 'xml';
    const EXTENSION_CSV = 'csv';
    const EXTENSION_TXT = 'txt';

    public function getFileContent(UploadedFile $file, array $columns = []): array
    {
        if (self::EXTENSION_XML == $file->extension()) {
            return $this->parseXmlContent($file->getContent(), $columns);
        } else if (self::EXTENSION_XLS == $file->extension()) {
            return $this->parseXlsContent($file->getContent(), $columns);
        } else if (self::EXTENSION_CSV == $file->extension() || self::EXTENSION_TXT == $file->extension()) {
            return $this->parseCsvContent($file->getContent(), $columns);
        }
        return [];
    }

    protected function parseXmlContent(string $content, array $columns = []): array
    {
        $xml = simplexml_load_string($content);
        $json = json_encode($xml);
        $xml = json_decode($json, true);
        if (!empty($columns)) {
            $optimized = [];
            $this->recursiveSearch($xml, $optimized, $columns, self::EXTENSION_XML);
            $xml = $optimized;
        }

        return $xml;
    }

    protected function parseCsvContent(string $content, array $columns = []): array
    {
        $csv = array_map('str_getcsv', str_getcsv($content, "\n"));
        array_walk($csv, function (&$a) use ($csv) {
            $a = array_combine($csv[0], $a);
        });
        array_shift($csv);
        if (!empty($columns)) {
            $optimized = [];
            $this->recursiveSearch($csv, $optimized, $columns, self::EXTENSION_CSV);
            $csv = $optimized;
        }

        return $csv;
    }


    protected function parseXlsContent(string $content, array $columns = []): array
    {
        $xls = SimpleXLS::parseData($content)->rows();
        array_walk($xls, function (&$a) use ($xls) {
            $a = array_combine($xls[0], $a);
        });
        array_shift($xls);
        if (!empty($columns)) {
            $optimized = [];
            $this->recursiveSearch($xls, $optimized, $columns, self::EXTENSION_XLS);
            $xls = $optimized;
        }

        return $xls;
    }

    private function recursiveSearch($array, &$result, $rows, $type): void
    {
        foreach ($array as $value) {
            if (in_array($type, [self::EXTENSION_XML, self::EXTENSION_CSV, self::EXTENSION_TXT, self::EXTENSION_XLS]) && is_array($value)) {
                if (!count(array_diff(array_keys($rows), array_keys($value)))) {
                    $manipulatedData = [];
                    foreach ($rows as $k => $v) {
                        $explode = explode(':', $v);
                        if (count($explode) == 3) {
                            $manipulatedData[$explode[0]] = (new $explode[1]())->query()->where($explode[2], '=', $value[$k])->first()?->id;
                        } else {
                            $manipulatedData[$explode[0]] = $value[$k];
                        }
                    }
                    $result[] = $manipulatedData;
                }
                $this->recursiveSearch($value, $result, $rows, $type);
            }
        }
    }
}
