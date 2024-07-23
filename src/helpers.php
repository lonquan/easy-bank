<?php
declare(strict_types=1);

if (!function_exists('snake2camel')) {
    function snake2camel(string $string): string
    {
        return str_replace(' ', '', ucwords(str_replace('_', ' ', $string)));
    }
}


if (!function_exists('throw_if')) {
    function throw_if(mixed $condition, Throwable|string $exception = 'RuntimeException', ...$parameters): mixed
    {
        if ($condition) {
            if (is_string($exception) && class_exists($exception)) {
                $exception = new $exception(...$parameters);
            }

            throw is_string($exception) ? new RuntimeException($exception) : $exception;
        }

        return $condition;
    }
}

if (!function_exists('throw_unless')) {
    function throw_unless(mixed $condition, Throwable|string $exception = 'RuntimeException', ...$parameters): mixed
    {
        throw_if(!$condition, $exception, ...$parameters);

        return $condition;
    }
}