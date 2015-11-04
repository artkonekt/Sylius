<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Component\Core\Model;

class ProductVariantImage extends Image implements ProductVariantImageInterface
{
    /**
     * The associated product variant.
     *
     * @var ProductVariantInterface
     */
    protected $variant;

    /**
     * The display order of image.
     *
     * @var int
     */
    protected $displayOrder;

    /**
     * {@inheritdoc}
     */
    public function getVariant()
    {
        return $this->variant;
    }

    /**
     * {@inheritdoc}
     */
    public function setVariant(ProductVariantInterface $variant = null)
    {
        $this->variant = $variant;

        return $this;
    }

    /**
     * @return int
     */
    public function getDisplayOrder()
    {
        return $this->displayOrder;
    }

    /**
     * @param int $displayOrder
     * @return ProductVariantImage
     */
    public function setDisplayOrder($displayOrder)
    {
        $this->displayOrder = (int)$displayOrder;

        return $this;
    }
}
