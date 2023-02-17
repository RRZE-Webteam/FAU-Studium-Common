<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Application\JsonSerializer;

use JsonException;

final class JsonSerializer
{
    /**
     *
     * @param string $string
     * @return object
     *
     * phpcs:disable NeutronStandard.Functions.DisallowCallUserFunc.CallUserFunc
     * @throws JsonException
     */
    public function deserialize(string $string): object
    {
        /**
         * @var string $className
         * @var array $objectData
         *
         * @psalm-suppress MixedAssignment
         * @psalm-suppress MixedArrayAccess
         */
        [$className, $objectData] = json_decode($string, true, 512, JSON_THROW_ON_ERROR);

        if (!in_array(JsonSerializable::class, (array) class_implements($className), true)) {
            throw CouldNotDeserialize::becauseClassDoesNotImplementInterface($className);
        }

        /**
         * @var object $object
         */
        $object = call_user_func([$className, JsonSerializable::FROM_ARRAY_METHOD], $objectData);

        if (!$object instanceof $className) {
            throw CouldNotDeserialize::becauseWrongClassInstantiated($object, $className);
        }

        return $object;
    }

    /**
     * @throws JsonException
     */
    public function serialize(JsonSerializable $jsonSerializable): string
    {
        return json_encode([
            get_class($jsonSerializable),
            $jsonSerializable,
        ], JSON_THROW_ON_ERROR);
    }
}
