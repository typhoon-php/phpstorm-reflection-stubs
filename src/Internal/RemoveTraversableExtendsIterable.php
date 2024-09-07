<?php

declare(strict_types=1);

namespace Typhoon\PhpStormReflectionStubs\Internal;

use Typhoon\DeclarationId\AnonymousClassId;
use Typhoon\DeclarationId\NamedClassId;
use Typhoon\Reflection\Internal\Data;
use Typhoon\Reflection\Internal\Hook\ClassHook;
use Typhoon\Reflection\TyphoonReflector;
use Typhoon\TypedMap\TypedMap;

/**
 * @internal
 * @psalm-internal Typhoon\PhpStormReflectionStubs
 */
enum RemoveTraversableExtendsIterable implements ClassHook
{
    case Instance;

    public function priority(): int
    {
        return 1000;
    }

    public function processClass(NamedClassId|AnonymousClassId $id, TypedMap $data, TyphoonReflector $reflector): TypedMap
    {
        // https://github.com/JetBrains/phpstorm-stubs/pull/1528
        if ($id->name === \Traversable::class) {
            return $data->without(Data::UnresolvedInterfaces);
        }

        return $data;
    }
}
