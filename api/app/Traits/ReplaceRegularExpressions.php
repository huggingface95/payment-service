<?php

namespace App\Traits;

use App\Exceptions\GraphqlException;

trait ReplaceRegularExpressions
{

    /**
     * @throws GraphqlException
     */
    public function replaceObjectData(string $content, object $object, string $regexp): string
    {
        return $this->replaceData($content, json_decode(json_encode($object), true), $regexp);
    }

    /**
     * @throws GraphqlException
     */
    private function replaceData(string $content, array $d, string $regexp): string
    {
        return preg_replace_callback(
         $regexp, function ($m) use ($d) {
            try {
                if (isset($m[1]) && count(($e = explode('.', $m[1]))) > 1) {
                    return $d[$e[0]][$e[1]];
                }
                if (isset($d[$m[1]]) && is_string($d[$m[1]])) {
                    return $d[$m[1]];
                }

                return $m[0];
            }
            catch (\Throwable){
                throw new GraphqlException("PARAMETER ".$m[1]." NOT FOUND IN THIS (".implode(',', array_keys($d)).")  LIST", 'not found', 403);
            }

        }, $content);
    }
}
