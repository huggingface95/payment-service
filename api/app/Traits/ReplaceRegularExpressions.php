<?php

namespace App\Traits;

trait ReplaceRegularExpressions
{
    public function replaceObjectData(string $content, object $object, string $regexp): string
    {
        return $this->replaceData($content, json_decode(json_encode($object), true), $regexp);
    }

    private function replaceData(string $content, array $d, string $regexp): string
    {
        return preg_replace_callback($regexp, function ($m) use ($d) {
            if (isset($m[1]) && count(($e = explode('.', $m[1]))) > 1) {
                return $d[$e[0]][$e[1]];
            }
            if (isset($d[$m[1]]) && is_string($d[$m[1]])){
                return $d[$m[1]];
            }
            return $m[0];
        }, $content);
    }
}
