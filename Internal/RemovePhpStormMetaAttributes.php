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
enum RemovePhpStormMetaAttributes implements ClassHook, FunctionHook
{
    case Instance;
    private const ATTRIBUTE_PREFIX = 'JetBrains\\';

    public function priority(): int
    {
        return 850;
    }

    public function processClass(NamedClassId|AnonymousClassId $id, TypedMap $data, TyphoonReflector $reflector): TypedMap
    {
        return self::removeAttributes($data)
            ->with(Data::Constants, array_map(self::removeAttributes(...), $data[Data::Constants]))
            ->with(Data::Properties, array_map(self::removeAttributes(...), $data[Data::Properties]))
            ->with(Data::Methods, array_map(self::processFunctionLike(...), $data[Data::Methods]));
    }

    public function processFunction(NamedFunctionId|AnonymousFunctionId $id, TypedMap $data, TyphoonReflector $reflector): TypedMap
    {
        return self::processFunctionLike($data);
    }

    private static function processFunctionLike(TypedMap $data): TypedMap
    {
        return self::removeAttributes($data)
            ->with(Data::Parameters, array_map(self::removeAttributes(...), $data[Data::Parameters]));
    }

    private static function removeAttributes(TypedMap $data): TypedMap
    {
        return $data->with(Data::Attributes, array_values(array_filter(
            $data[Data::Attributes],
            static fn(TypedMap $attribute): bool => !str_starts_with($attribute[Data::AttributeClassName], self::ATTRIBUTE_PREFIX),
        )));
    }
}
