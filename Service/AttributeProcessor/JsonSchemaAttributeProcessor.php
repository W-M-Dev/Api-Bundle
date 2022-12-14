<?php
/*
 * This file is part of the StfalconApiBundle.
 *
 * (c) Stfalcon LLC <stfalcon.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace StfalconStudio\ApiBundle\Service\AttributeProcessor;

use StfalconStudio\ApiBundle\Attribute\JsonSchema;
use StfalconStudio\ApiBundle\Exception\InvalidArgumentException;
use StfalconStudio\ApiBundle\Exception\RuntimeException;
use StfalconStudio\ApiBundle\Util\File\FileReader;

/**
 * JsonSchemaAttributeProcessor.
 */
class JsonSchemaAttributeProcessor
{
    private const JSON_FILE_EXTENSION = '.json';

    /** @var array */
    private array $cachedClasses = [];

    /**
     * @param DtoAttributeProcessor $dtoAttributeProcessor
     * @param FileReader            $fileReader
     * @param string                $jsonSchemaDir
     */
    public function __construct(private readonly DtoAttributeProcessor $dtoAttributeProcessor, private readonly FileReader $fileReader, private readonly string $jsonSchemaDir)
    {
    }

    /**
     * @param string $controllerClassName
     *
     * @return mixed
     */
    public function processAttributeForControllerClass(string $controllerClassName): mixed
    {
        $dtoClass = $this->dtoAttributeProcessor->processAttributeForClass($controllerClassName);

        return $this->processAttributeForDtoClass($dtoClass);
    }

    /**
     * @param string $dtoClassName
     *
     * @throws RuntimeException
     * @throws InvalidArgumentException
     *
     * @return mixed
     */
    public function processAttributeForDtoClass(string $dtoClassName): mixed
    {
        /** @var class-string<object> $dtoClassName */
        if (\array_key_exists($dtoClassName, $this->cachedClasses)) {
            return $this->cachedClasses[$dtoClassName];
        }

        $reflector = new \ReflectionClass($dtoClassName);
        $attributes = $reflector->getAttributes(JsonSchema::class, \ReflectionAttribute::IS_INSTANCEOF);

        if (\count($attributes) > 1) {
            throw new RuntimeException(\sprintf('Detected more than one JsonSchema attribute for class %s. Only one JsonSchema attribute allowed per class.', $dtoClassName));
        }
        if (1 !== \count($attributes)) {
            throw new RuntimeException(\sprintf('Missing JsonSchema attribute for class %s', $dtoClassName));
        }

        $jsonSchemaDirPath = \realpath($this->jsonSchemaDir);
        if (false === $jsonSchemaDirPath) {
            throw new RuntimeException(\sprintf('Directory for json Schema files "%s" is not found.', $this->jsonSchemaDir));
        }

        $jsonSchemaFilename = $this->getJsonSchemaFilename($attributes[0]->getArguments()['jsonSchemaName']);

        $path = $jsonSchemaDirPath.\DIRECTORY_SEPARATOR.$jsonSchemaFilename;
        $realPathToJsonSchemaFile = \realpath($path);
        if (false === $realPathToJsonSchemaFile) {
            throw new RuntimeException(\sprintf('Json Schema file "%s" is not found.', $path));
        }

        $jsonSchemaContent = $this->fileReader->getFileContents($realPathToJsonSchemaFile);
        if (false === $jsonSchemaContent) {
            throw new InvalidArgumentException(\sprintf('Cannot read content of file %s', $realPathToJsonSchemaFile));
        }

        $decodedSchema = \json_decode($jsonSchemaContent, true, 512, \JSON_THROW_ON_ERROR);

        $this->cachedClasses[$dtoClassName] = $decodedSchema;

        return $decodedSchema;
    }

    /**
     * @param string $jsonSchemaName
     *
     * @return string
     */
    private function getJsonSchemaFilename(string $jsonSchemaName): string
    {
        $result = $jsonSchemaName;

        if (self::JSON_FILE_EXTENSION !== \mb_substr($result, -5)) {
            $result .= self::JSON_FILE_EXTENSION;
        }

        return $result;
    }
}
