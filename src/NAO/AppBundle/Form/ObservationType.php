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
use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

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
                'label' => 'Jour de l\'observation :',
            ))
            ->add('latitude', TextType::class, array(
                'constraints' => new Assert\Regex(array(
                    'pattern' => '/[0-9*]/',
                    'message' => 'Ne correspond pas a des coordonnées',
                ))
            ))
            ->add('longitude', TextType::class, array(
                'constraints' => new Assert\Regex(array(
                    'pattern' => '/[0-9*]/',
                    'message' => 'Ne correspond pas a des coordonnées',
                ))
            ))
            ->add('userMessage', TextareaType::class, array(
                'label' => 'Message :',
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
            ->add('species', EntityType::class, array(
                'label' => 'Espèce observée :',
                'class' => 'NAOAppBundle:Species',
                'choice_label' => 'nomValide',
            ))
            ->add('image', ImageType::class, array(
                'label' => ' ',
                'required' => false,
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
