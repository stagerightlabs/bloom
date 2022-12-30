<?php

declare(strict_types=1);
require __DIR__ . '/../vendor/autoload.php';

use PHPStan\PhpDocParser\Ast\PhpDoc\ParamTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTextNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\ReturnTagValueNode;
use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\PhpDocParser\Parser\ConstExprParser;
use PHPStan\PhpDocParser\Parser\PhpDocParser;
use PHPStan\PhpDocParser\Parser\TokenIterator;
use PHPStan\PhpDocParser\Parser\TypeParser;
use StageRightLabs\Bloom\ServiceFactory;
use StageRightLabs\Bloom\Utility\Text;

/**
 * Generate markdown documentation for service classes using  phpdoc-parser.
 */
foreach (ServiceFactory::getRegistry() as $key => $service) {

    // Prepare to extract doc block details from the service class via reflection
    $class = new \ReflectionClass($service);

    // Ensure that this class should have a documentation file
    if (classShouldBeExcluded($class)) {
        continue;
    }

    // Prepare a markdown buffer to hold the generated content
    $markdown = '';

    // Write the page heading to markdown
    $h1 = camelCaseToTitleString(maybePluralize($key));
    $markdown .= "# {$h1} \n\n";

    // Write the page content to markdown
    if ($class->getDocComment()) {
        // Parse the class documentation string
        $documentation = parseDocumentationString($class->getDocComment());

        // Write the text content to markdown
        foreach (getDocumentationText($documentation) as $text) {
            $markdown .= "{$text}\n\n";
        }

        // Write reference links to markdown
        $links = $documentation->getTagsByName('@see');
        if (!empty($links)) {
            $markdown .= "Further Reading:\n\n";
            foreach ($links as $link) {
                $markdown .= "- [{$link->value}]({$link->value})\n";
            }
        }

        $markdown .= "\n";
    }

    // write out the details for each method in the class
    foreach ($class->getMethods() as $method) {
        // Skip any methods that are flagged for exclusion
        if (methodShouldBeExcluded($method)) {
            continue;
        }

        // Parse the method documentation
        $documentation = parseDocumentationString($method->getDocComment());

        // Write the text content to markdown
        $markdown .= "## {$method->getName()}()\n\n";
        foreach (getDocumentationText($documentation) as $text) {
            $markdown .= "{$text}\n\n";
        }

        // Write the list of parameters to markdown
        $parameters = $documentation->getParamTagValues();
        if (!empty($parameters)) {
            $markdown .= "### Parameters\n\n";
            $markdown .= writeParametersTable($parameters);
            $markdown .= "\n";
        }

        // Write the return value to markdown
        $returns = $documentation->getReturnTagValues();
        if (!empty($returns)) {
            $markdown .= "### Return Type\n\n";
            foreach ($returns as $return) {
                $markdown .= formatTypeString($return) . "\n";
            }
        }

        // Write reference links to markdown
        $links = $documentation->getTagsByName('@see');
        if (!empty($links)) {
            $markdown .= "\n### Further Reading:\n\n";
            foreach ($links as $link) {
                $markdown .= "- [{$link->value}]({$link->value})\n";
            }
        }

        $markdown .= "\n";
    }

    $markdown .= "###### This page was dynamically generated from the {$class->getShortName()} source code.\n\n";

    // save the file.
    $filename = Text::snakeCase($key) . '.md';
    $path = __DIR__ . '/' . $filename;
    file_put_contents($path, $markdown);
    echo "Wrote {$path}\n";
}

/**
 * Parse a documentation string with the PHPStan phpdoc-parser.
 *
 * @param string $documentation
 * @return PhpDocNode
 */
function parseDocumentationString(string $documentation): PhpDocNode
{
    $lexer = new Lexer();
    $constExprParser = new ConstExprParser();
    $typeParser = new TypeParser($constExprParser);

    return (new PhpDocParser($typeParser, $constExprParser))
        ->parse(new TokenIterator($lexer->tokenize($documentation)));
}

/**
 * Should this class be excluded from the documentation?
 *
 * @param ReflectionClass $reflectionClass
 * @return bool
 */
function classShouldBeExcluded(ReflectionClass $reflectionClass): bool
{
    $ignored = [
        'HorizonService',
    ];

    return in_array($reflectionClass->getShortName(), $ignored);
}

/**
 * Should this method be excluded from the documentation?
 *
 * @param ReflectionMethod $method
 * @return bool
 */
function methodShouldBeExcluded(ReflectionMethod $method): bool
{
    $ignored = [
        '__construct'
    ];

    if (in_array($method->getName(), $ignored)) {
        return true;
    }

    return !$method->isPublic();
}

/**
 * Convert a camel case string into a human readable title case string.
 *
 * @param string $str
 * @return string
 */
function camelCaseToTitleString(string $str): string
{
    return ucwords(preg_replace('/(?<!\ )[A-Z]/', ' $0', $str));
}

/**
 * Pluralize a title string, unless it shouldn't be pluralized.
 *
 * @param string $str
 * @return string
 */
function maybePluralize(string $str): string
{
    $ignore = [
        'friendbot',
        'horizon',
    ];

    if (!in_array($str, $ignore)) {
        $str .= 's';
    }

    return $str;
}

/**
 * Extract the text nodes from a parsed doc block.
 *
 * @param PhpDocNode $node
 * @return PhpDocTextNode[]
 */
function getDocumentationText(PhpDocNode $node): array
{
    return array_filter($node->children, function ($child) {
        return $child instanceof PhpDocTextNode
            && !empty($child->text);
    });
}

/**
 * Convert an array of parameter nodes into a markdown table.
 *
 * @param array $parameters
 * @return string
 */
function writeParametersTable(array $parameters): string
{
    $includeNotes = doParametersContainNotes($parameters);

    // Header
    $table = "| Name | Type ";
    if ($includeNotes) {
        $table .= '| Notes ';
    }
    $table .= "|\n";

    // Separator
    $table .= "| ---- | ---- ";
    if ($includeNotes) {
        $table .= '| ---- ';
    }
    $table .= "|\n";

    // Rows
    foreach ($parameters as $parameterNode) {
        // Name
        $table .= "| {$parameterNode->parameterName}";

        // Type
        $table .= "| " . formatTypeString($parameterNode) . " ";

        // Notes
        if (!empty($parameterNode->description) && $includeNotes) {
            $table .= "| {$parameterNode->description} ";
        }
        $table .= "|\n";
    }

    return $table;
}

/**
 * Does an array of parameters contain any 'note' content?
 *
 * @param array $parameters
 * @return bool
 */
function doParametersContainNotes(array $parameters): bool
{
    if (empty($parameters)) {
        return false;
    }

    foreach ($parameters as $parameterNode) {
        if (!empty($parameterNode->description)) {
            return true;
        }
    }

    return false;
}

/**
 * Adjust the formatting of a parameter type string.
 *
 * @param ParamTagValueNode|ReturnTagValueNode $node
 * @return string
 */
function formatTypeString(ParamTagValueNode|ReturnTagValueNode $node): string
{
    // Convert separation pipes to commas
    $type = str_replace(' | ', ',', $node->type->__toString());

    // Remove parenthesis
    $type = str_replace(['(', ')'], '', $type);

    return "`" . $type . "`";
}
