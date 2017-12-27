<?php
declare(strict_types=1);

namespace Glagol\Bridge\Lumen\Exceptions;

use Closure;
use function Glagol\SourceMap\has_source_map;
use function Glagol\SourceMap\load_map_from_generated_source;
use Glagol\SourceMap\Mapping;
use Glagol\SourceMap\MappingCollection;
use Illuminate\Support\Collection;
use Throwable;

function to_glagol_trace_from_exception(Throwable $exception): Collection
{
    return get_trace($exception)
        ->filter(only_having_source_maps())
        ->map(to_glagol_trace())
        ->filter()
        ->values();
}

function only_having_source_maps(): Closure
{
    return function (array $trace) {
        $filePath = array_get($trace, 'file');

        if (empty($filePath)) {
            return false;
        }

        return has_source_map($filePath, base_path());
    };
}

function to_glagol_trace(): Closure
{
    return function (array $trace) {
        $basePath = base_path();
        $mapFile = load_map_from_generated_source(array_get($trace, 'file'), $basePath);

        $sourceMap = $mapFile->sourceMap();

        $mapping = lookup_mapping($trace, $sourceMap->mappings());

        if (is_null($mapping)) {
            return null;
        }

        return [
            'file' => $mapping->getOriginalSource()->toPath(),
            'line' => $mapping->getOriginalLine(),
            'name' => $mapping->getName()
        ];
    };
}

/**
 * @param array $trace
 * @param MappingCollection $mappings
 * @return Mapping
 */
function lookup_mapping(array $trace, MappingCollection $mappings): ?Mapping
{
    $mappings = $mappings->filterByGeneratedLine($trace['line']);

    if (array_key_exists('function', $trace)) {
        $nameMappings = $mappings->filterByName($trace['function']);
        if ($nameMappings->isNotEmpty()) {
            $mappings = $nameMappings;
        }
    }

    return $mappings->first();
}

/**
 * @param Throwable $exception
 * @return Collection
 */
function get_trace(Throwable $exception): Collection
{
    $trace = $exception->getTrace();
    // we add the exception's line and file to the first trace
    array_unshift($trace, [
        'line' => $exception->getLine(),
        'file' => $exception->getFile(),
    ]);

    return collect($trace);
}
