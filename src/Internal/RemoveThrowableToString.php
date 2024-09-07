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
enum RemoveThrowableToString implements ClassHook
{
    case Instance;

    public function priority(): int
    {
        return 1000;
    }

    public function processClass(NamedClassId|AnonymousClassId $id, TypedMap $data, TyphoonReflector $reflector): TypedMap
    {
        // todo issue
        if ($id->name === \Throwable::class) {
            $methods = $data[Data::Methods];
            unset($methods['__toString']);

            return $data->with(Data::Methods, $methods);
        }

        return $data;
    }
}
