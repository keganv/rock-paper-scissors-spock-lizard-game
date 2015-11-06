<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use AppBundle\Form\Subscriber\BattleTypeSubscriber;

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
                    'label' => false,
                    'choices' => ['Rock', 'Paper', 'Scissors', 'Spock', 'Lizard'],
                    'expanded' => true
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

        $builder->addEventSubscriber(new BattleTypeSubscriber());
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
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
