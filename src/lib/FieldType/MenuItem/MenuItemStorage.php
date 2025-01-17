<?php
/**
 * NovaeZMenuManagerBundle.
 *
 * @package   NovaeZMenuManagerBundle
 *
 * @author    Novactive <f.alexandre@novactive.com>
 * @copyright 2019 Novactive
 * @license   https://github.com/Novactive/NovaeZMenuManagerBundle/blob/master/LICENSE
 */

namespace Novactive\EzMenuManager\FieldType\MenuItem;

use eZ\Publish\SPI\FieldType\GatewayBasedStorage;
use eZ\Publish\SPI\Persistence\Content\Field;
use eZ\Publish\SPI\Persistence\Content\VersionInfo;
use Novactive\EzMenuManager\FieldType\MenuItem\MenuItemStorage\Gateway;

class MenuItemStorage extends GatewayBasedStorage
{
    /**
     * @var Gateway
     */
    protected $gateway;

    /**
     * @param VersionInfo $versionInfo
     * @param Field       $field
     * @param array       $context
     *
     * @return mixed
     */
    public function storeFieldData(VersionInfo $versionInfo, Field $field, array $context)
    {
        // VersionInfo return STATUS_DRAFT when publish Content
        // TODO: Store data field when content published
        //if (VersionInfo::STATUS_PUBLISHED === $versionInfo->status || 1 == $versionInfo->versionNo) {
        return $this->gateway->storeFieldData($versionInfo, $field);
        //}
    }

    /**
     * @param VersionInfo $versionInfo
     * @param Field       $field
     * @param array       $context
     *
     * @return mixed
     */
    public function getFieldData(VersionInfo $versionInfo, Field $field, array $context)
    {
        if (VersionInfo::STATUS_PUBLISHED === $versionInfo->status) {
            return $this->gateway->getFieldData($versionInfo, $field);
        }
    }

    /**
     * @param VersionInfo $versionInfo
     * @param array       $fieldIds
     * @param array       $context
     *
     * @return bool
     */
    public function deleteFieldData(VersionInfo $versionInfo, array $fieldIds, array $context)
    {
        if (VersionInfo::STATUS_PUBLISHED === $versionInfo->status) {
            return $this->gateway->deleteFieldData($versionInfo, $fieldIds);
        }
    }

    /**
     * @return bool
     */
    public function hasFieldData()
    {
        return true;
    }

    /**
     * @param VersionInfo $versionInfo
     * @param Field       $field
     * @param array       $context
     *
     * @return bool|\eZ\Publish\SPI\Search\Field[]
     */
    public function getIndexData(VersionInfo $versionInfo, Field $field, array $context)
    {
        return false;
    }

    /**
     * This method is used exclusively by Legacy Storage to copy external data of existing field in main language to
     * the untranslatable field not passed in create or update struct, but created implicitly in storage layer.
     *
     * By default the method falls back to the {@link \eZ\Publish\SPI\FieldType\FieldStorage::storeFieldData()}.
     * External storages implement this method as needed.
     *
     * @param \eZ\Publish\SPI\Persistence\Content\VersionInfo $versionInfo
     * @param \eZ\Publish\SPI\Persistence\Content\Field       $field
     * @param \eZ\Publish\SPI\Persistence\Content\Field       $originalField
     * @param array                                           $context
     *
     * @return bool|null same as {@link \eZ\Publish\SPI\FieldType\FieldStorage::storeFieldData()}
     */
    public function copyLegacyField(VersionInfo $versionInfo, Field $field, Field $originalField, array $context)
    {
        return $this->storeFieldData($versionInfo, $field, $context);
    }
}
