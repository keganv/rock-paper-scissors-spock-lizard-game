<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BattleType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'userWeapon',
                'choice',
                [
                    'choices' => ['Rock', 'Paper', 'Scissors', 'Spock', 'Lizard']
                ]
            )
            ->add(
                'computerWeapon',
                'hidden',
                [
                    'required' => true
                ]
            )
            ->add(
                'victor',
                'hidden',
                [
                    'required' => true
                ]
            )
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Battle',
        ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'battle_form';
    }
}
