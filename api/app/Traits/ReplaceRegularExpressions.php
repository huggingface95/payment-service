<?php

namespace App\Traits;

use App\Exceptions\GraphqlException;

trait ReplaceRegularExpressions
{

    public function replaceStaticParams(string $subject, object $object, string $regexp): string
    {
        $d = json_decode(json_encode($object), true);
        return preg_replace_callback($regexp, function ($m) use ($d) {
            try {
                if (count($m) == 2) {
                    if (str_starts_with($m[1], 'config')) {
                        return config(str_replace('config.', '', $m[1]));
                    } else {
                        return $d[$m[1]];
                    }
                }
                return $m[0];
            } catch (\Throwable) {
                return $m[0];
            }
        }, $subject);
    }

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
                if (isset($m[1]) && count(($e = explode('.', $m[1]))) == 3) {
                    return $d[$e[0]][$e[1]][$e[2]];
                }
                if (isset($m[1]) && count(($e = explode('.', $m[1]))) == 2) {

                    return $d[$e[0]][$e[1]];
                }
                if (isset($d[$m[1]]) && !is_array($d[$m[1]])) {
                    return $d[$m[1]];
                }

                return $m[0];
            } catch (\Throwable) {
                $implodeData = collect($d)->map(function ($v, $k) {
                    return is_array($v)
                        ? collect($v)->keys()->crossJoin($k)->map(function ($v) {
                            return $v[1] . '.' . $v[0];
                        })
                        : $k;
                })->flatten(1)->implode(',');
                throw new GraphqlException('PARAMETER ' . $m[1] . ' NOT FOUND IN THIS (' . $implodeData . ')  LIST', 'not found', 403);
            }
        }, $content);
    }
}
