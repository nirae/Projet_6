<?php

namespace NAO\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class ValidationsType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('validationMessage', TextareaType::class, array(
                'label' => 'Laissez un petit message !',
                'constraints' => array(
                    new NotBlank(),
                    new Type('string'),
                    new Length(array(
                        'min' => 10,
                        'max' => 500,
                        'minMessage' => '10 caractères minimum',
                        'maxMessage' => '500 caractères maximum',
                    )),
                ),
            ))
            ->add('status', ChoiceType::class, array(
                'label' => ' ',
                'choices' => array(
                    'Validée' => 'Validée',
                    'Refusée' => 'Refusée',
                ),
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'NAO\AppBundle\Entity\Observation'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'nao_appbundle_observation';
    }


}
