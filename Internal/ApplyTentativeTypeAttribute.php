<?php

declare(strict_types=1);

namespace Typhoon\PhpStormReflectionStubs\Internal;

use Typhoon\DeclarationId\AnonymousClassId;
use Typhoon\DeclarationId\AnonymousFunctionId;
use Typhoon\DeclarationId\NamedClassId;
use Typhoon\DeclarationId\NamedFunctionId;
use Typhoon\Reflection\Internal\Data;
use Typhoon\Reflection\Internal\Hook\ClassHook;
use Typhoon\Reflection\Internal\Hook\FunctionHook;
use Typhoon\Reflection\TyphoonReflector;
use Typhoon\TypedMap\TypedMap;

/**
 * @internal
 * @psalm-internal Typhoon\PhpStormReflectionStubs
 */
enum ApplyTentativeTypeAttribute implements FunctionHook, ClassHook
{
    case Instance;
    private const ATTRIBUTE = 'JetBrains\PhpStorm\Internal\TentativeType';

    public function priority(): int
    {
        return 900;
    }

    public function processFunction(NamedFunctionId|AnonymousFunctionId $id, TypedMap $data, TyphoonReflector $reflector): TypedMap
    {
        return self::processFunctionLike($data);
    }

    public function processClass(NamedClassId|AnonymousClassId $id, TypedMap $data, TyphoonReflector $reflector): TypedMap
    {
        return $data->with(Data::Methods, array_map(self::processFunctionLike(...), $data[Data::Methods]));
    }

    private static function processFunctionLike(TypedMap $data): TypedMap
    {
        $type = $data[Data::Type];

        if ($type->native === null || !self::hasTentativeAttribute($data[Data::Attributes])) {
            return $data;
        }

        return $data->with(Data::Type, $type->withTentative($type->native)->withNative(null));
    }

    /**
     * @param list<TypedMap> $attributes
     */
    private static function hasTentativeAttribute(array $attributes): bool
    {
        return array_any(
            $attributes,
            static fn(TypedMap $attribute): bool => $attribute[Data::AttributeClassName] === self::ATTRIBUTE,
        );
    }
}
