<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Sylius\Bundle\AttributeBundle\DependencyInjection\Compiler;

use PhpSpec\ObjectBehavior;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author Mateusz Zalewski <mateusz.zalewski@lakion.com>
 */
class RegisterAttributeTypePassSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Sylius\Bundle\AttributeBundle\DependencyInjection\Compiler\RegisterAttributeTypePass');
    }

    function it_implements_compiler_pass_interface()
    {
        $this->shouldImplement(CompilerPassInterface::class);
    }

    function it_processes_with_given_container(ContainerBuilder $container, Definition $attributeTypeRegistryDefinition)
    {
        $container->hasDefinition('sylius.registry.attribute_type')->willReturn(true);
        $container->getDefinition('sylius.registry.attribute_type')->willReturn($attributeTypeRegistryDefinition);

        $attributeTypeServices = array(
            'sylius.form.type.attribute_type.test' => array(
                array('attribute-type' => 'test', 'label' => 'Test attribute type'),
            ),
        );
        $container->findTaggedServiceIds('sylius.attribute.type')->willReturn($attributeTypeServices);

        $attributeTypeRegistryDefinition->addMethodCall('register', array('test', new Reference('sylius.form.type.attribute_type.test')))->shouldBeCalled();
        $container->setParameter('sylius.attribute.attribute_types', array('test' => 'Test attribute type'))->shouldBeCalled();

        $this->process($container);
    }

    function it_does_not_process_if_container_has_not_proper_definition(ContainerBuilder $container)
    {
        $container->hasDefinition('sylius.registry.attribute_type')->willReturn(false);
        $container->getDefinition('sylius.registry.attribute_type')->shouldNotBeCalled();
    }

    function it_throws_exception_if_any_attribute_type_has_improper_attributes(ContainerBuilder $container, Definition $attributeTypeDefinition)
    {
        $container->hasDefinition('sylius.registry.attribute_type')->willReturn(true);
        $container->getDefinition('sylius.registry.attribute_type')->willReturn($attributeTypeDefinition);

        $attributeTypeServices = array(
            'sylius.form.type.attribute_type.test' => array(
                array('attribute_type' => 'test'),
            ),
        );
        $container->findTaggedServiceIds('sylius.attribute.type')->willReturn($attributeTypeServices);
        $this->shouldThrow(new \InvalidArgumentException('Tagged attribute type needs to have `attribute_type` and `label` attributes.'));
        $attributeTypeDefinition->addMethodCall('register', array('test', new Reference('sylius.form.type.attribute_type.test')))->shouldNotBeCalled();
    }
}
