<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\ProductBundle\Form\Type;

use Sylius\Bundle\ProductBundle\Form\Extension\EntityToIdObjectTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Product selector type. It uses the product's ID via a data transformer.
 *
 * TODO/TOREVIEW: could we use EntityHiddenType (the problem why it is not used is that the repository passed to its
 * transformer has not the DisplayabilitySpecification set -> it looks like that is not the same repository instance that
 * is used elsewhere...)
 *
 * @author Lajos Fazakas <lajos@artkonekt.com>
 */
class ProductSelectorType extends AbstractType
{
    private $em;

    private $repository;

    public function __construct($em, $repository)
    {
        $this->em = $em;
        $this->repository = $repository;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformer = new EntityToIdObjectTransformer($this->em, $this->repository, 'Component\Core\Model\Product');
        $builder->addModelTransformer($transformer);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'hidden';
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setDefaults([
            'read_only' => true
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'sylius_product_selector';
    }
}
