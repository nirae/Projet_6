<?php

namespace NAO\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class ObservationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', DateType::class, array(
                'format' => 'dd-MM-yyyy',
                'label' => 'Jour de l\'observation',
            ))
            ->add('latitude', TextType::class)
            ->add('longitude', TextType::class)
            ->add('userMessage', TextareaType::class)
            ->add('species', EntityType::class, array(
                'class' => 'NAOAppBundle:Species',
                'choice_label' => 'nomValide',
            ))
            ->add('image', ImageType::class, array(
                'required' => false,
            ))
            ->add('save', SubmitType::class, array(
                'label' => 'Valider',
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
