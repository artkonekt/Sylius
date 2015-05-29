<?php
/**
 * Contains class EntityToIdObjectTransformer
 *
 * @package     Sylius\Bundle\ProductBundle\Form\Extension;
 * @copyright   Copyright (c) 2015 Artkonekt Rulez Srl
 * @author      Lajos Fazakas <lajos@artkonekt.com>
 * @license     Proprietary
 * @since       2015-05-29
 * @version     2015-05-29
 */

namespace Sylius\Bundle\ProductBundle\Form\Extension;


use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class EntityToIdObjectTransformer implements DataTransformerInterface
{
    /**
     * @var ObjectManager
     */
    private $om;

    private $repository;

    /**
     * @var String
     */
    private $entityName;

    /**
     * @param ObjectManager $om
     * @param               $repository
     * @param String        $entityName
     */
    public function __construct(ObjectManager $om, $repository, $entityName)
    {
        $this->entityName = $entityName;
        $this->om = $om;
        $this->repository = $repository;
    }

    /**
     * Do nothing.
     *
     * @param  Object|null $object
     * @return string
     */
    public function transform($object)
    {
        if (null === $object) {
            return "";
        }

        return current(array_values($this->om->getClassMetadata($this->entityName)->getIdentifierValues($object)));
    }

    /**
     * Transforms an identifier to an object.
     *
     * @param  mixed $id
     *
     * @return Object|null
     *
     * @throws TransformationFailedException if object is not found.
     */
    public function reverseTransform($id)
    {
        $identifier = current(array_values($this->om->getClassMetadata($this->entityName)->getIdentifier()));

        $object = $this->repository
            ->findOneBy(array($identifier => $id))
        ;

        if (null === $object) {
            throw new TransformationFailedException(sprintf(
                'An object with identifier key "%s" and value "%d" does not exist!',
                $identifier, $id
            ));
        }

        return $object;
    }
}