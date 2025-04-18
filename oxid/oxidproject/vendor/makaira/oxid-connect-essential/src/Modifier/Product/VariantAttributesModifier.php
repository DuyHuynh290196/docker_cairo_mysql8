<?php

namespace Makaira\OxidConnectEssential\Modifier\Product;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\Exception;
use Doctrine\DBAL\Driver\Result;
use Doctrine\DBAL\Exception as DBALException;
use Makaira\OxidConnectEssential\Modifier;
use Makaira\OxidConnectEssential\Type;
use Makaira\OxidConnectEssential\Type\Product\Product;
use Makaira\OxidConnectEssential\Exception as ConnectException;
use Makaira\OxidConnectEssential\Utils\ModuleSettingsProvider;
use Makaira\OxidConnectEssential\Utils\TableTranslator;
use OxidEsales\Eshop\Core\Exception\SystemComponentException;
use OxidEsales\Eshop\Core\Model\BaseModel;
use OxidEsales\Eshop\Core\UtilsObject;

/**
 * Class AttributeModifier
 *
 * @package Makaira\OxidConnectEssential\Type\ProductRepository
 * @SuppressWarnings(PHPMD.ShortVariable)
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class VariantAttributesModifier extends Modifier
{
    public string $selectVariantNameQuery = '
                        SELECT
                            oxvarname
                        FROM
                            oxarticles
                        WHERE
                            oxid = :productId
                        ';

    public string $selectVariantDataQuery = '
                        SELECT
                            oxid as `id`,
                            oxvarselect as `value`
                        FROM
                            oxarticles
                        WHERE
                            oxparentid = :productId
                            AND {{activeSnippet}}
                        ';

    public string $selectVariantAttributesQuery = '
                        SELECT
                            oxattribute.oxid as `id`,
                            oxobject2attribute.oxvalue as `value`
                        FROM
                            oxobject2attribute
                            JOIN oxattribute ON oxobject2attribute.oxattrid = oxattribute.oxid
                        WHERE
                            oxobject2attribute.oxvalue != \'\'
                            AND oxobject2attribute.oxobjectid in (:productId, :variantId)
                        ';

    private Connection $database;

    private ?BaseModel $model = null;

    private ?string $activeSnippet = null;

    private string $modelClass;

    private UtilsObject $utilsObject;

    private ModuleSettingsProvider $moduleSettings;

    private TableTranslator $tableTranslator;

    /**
     * @param Connection             $database
     * @param string                 $modelClass
     * @param ModuleSettingsProvider $moduleSettings
     * @param UtilsObject            $utilsObject
     * @param TableTranslator        $tableTranslator
     */
    public function __construct(
        Connection $database,
        string $modelClass,
        ModuleSettingsProvider $moduleSettings,
        UtilsObject $utilsObject,
        TableTranslator $tableTranslator
    ) {
        $this->modelClass      = $modelClass;
        $this->database        = $database;
        $this->moduleSettings  = $moduleSettings;
        $this->utilsObject     = $utilsObject;
        $this->tableTranslator = $tableTranslator;
    }

    /**
     * Modify product and return modified product
     *
     * @param Product $product
     *
     * @return Type
     * @throws ConnectException
     * @throws DBALException
     * @throws Exception
     * @throws SystemComponentException
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function apply(Type $product)
    {
        if (!$product->id) {
            throw new ConnectException("Cannot fetch attributes without a product ID.");
        }

        $this->safeGuard();

        $product->attributes = [];

        /** @var Result $resultStatement */
        $resultStatement = $this->database->executeQuery(
            $this->tableTranslator->translate($this->selectVariantNameQuery),
            ['productId' => $product->id]
        );

        /** @var string $variantName */
        $variantName = $resultStatement->fetchOne();
        $single      = ($variantName === '');

        $hashArray = [];

        $variants = [['id' => '']];

        if (!$single) {
            $titleArray = array_map('trim', explode('|', $variantName));
            $hashArray  = array_map('md5', $titleArray);

            $query = str_replace('{{activeSnippet}}', (string) $this->activeSnippet, $this->selectVariantDataQuery);

            /** @var Result $resultStatement */
            $resultStatement = $this->database->executeQuery(
                $this->tableTranslator->translate($query),
                ['productId' => $product->id]
            );

            /** @var array<array<string, string>> $variants */
            $variants = $resultStatement->fetchAllAssociative();
        }

        /** @var array<string> $integerAttributes */
        $integerAttributes = $this->moduleSettings->get('makaira_attribute_as_int');

        /** @var array<string> $floatAttributes */
        $floatAttributes = $this->moduleSettings->get('makaira_attribute_as_float');

        foreach ($variants as $variant) {
            $variantAttributes = [];

            $id = $variant['id'];
            if ($id) {
                $valueArray = array_map('trim', explode('|', $variant['value']));

                foreach ($hashArray as $index => $hash) {
                    $variantAttributes[$hash] = (string) $valueArray[$index];

                    if (in_array($hash, $integerAttributes, true)) {
                        $variantAttributes[$hash] = (int) $valueArray[$index];
                    }

                    if (in_array($hash, $floatAttributes, true)) {
                        $variantAttributes[$hash] = (float) $valueArray[$index];
                    }
                }
            }
            $this->addVariantAttributes($product, $id, $variantAttributes, $integerAttributes, $floatAttributes);

            if (!empty($variantAttributes)) {
                $product->attributes[] = $variantAttributes;
            }
        }

        return $product;
    }

    protected function safeGuard(): void
    {
        if (!($this->model instanceof BaseModel)) {
            /** @var BaseModel $modelInstance */
            $modelInstance = $this->utilsObject->oxNew($this->modelClass);
            $this->model   = $modelInstance;
        }
        if (!$this->activeSnippet) {
            $this->activeSnippet = $this->model->getSqlActiveSnippet(true);
        }
    }

    /**
     * @param Product $product
     * @param string $id
     * @param array  $variantAttributes
     * @param array  $integerAttributes
     * @param array  $floatAttributes
     *
     * @return void
     * @throws DBALException
     * @throws Exception
     */
    private function addVariantAttributes(
        Product $product,
        string $id,
        array &$variantAttributes,
        array $integerAttributes,
        array $floatAttributes
    ): void {
        /** @var Result $resultStatement */
        $resultStatement = $this->database->executeQuery(
            $this->tableTranslator->translate($this->selectVariantAttributesQuery),
            [
                'productId' => $product->id,
                'variantId' => $id,
            ]
        );

        $attributes = $resultStatement->fetchAllAssociative();

        foreach ($attributes as $attribute) {
            /** @var string $hash */
            $hash = $attribute['id'];
            /** @var string|int|float $value */
            $value = $attribute['value'];

            $variantAttributes[$hash] = (string)$value;

            if (in_array($hash, $integerAttributes)) {
                $variantAttributes[$hash] = (int)$value;
            }

            if (in_array($hash, $floatAttributes)) {
                $variantAttributes[$hash] = (float)$value;
            }
        }
    }
}
