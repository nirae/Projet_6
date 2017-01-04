<?php

namespace NAO\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class ModifUserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, array(
                'label' => 'Pseudo',
                'constraints' => array(
                    new NotBlank(),
                    new Type('string'),
                    new Length(array(
                        'min' => 3,
                        'max' => 50,
                        'minMessage' => 'Le pseudo ne doit pas être inférieur à 3 caractères',
                        'maxMessage' => 'Le pseudo ne doit pas être supérieur à 50 caractères',
                    )),
                ),
            ))
            ->add('email', EmailType::class, array(
                'label' => 'Adresse Email',
                'constraints' => new NotBlank(),
            ))
            ->add('oldPassword', PasswordType::class, array(
                'label' => 'Mot de passe',
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'validation_groups' => array('registration', 'Default'),
            'data_class' => 'NAO\AppBundle\Entity\User'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'nao_appbundle_user';
    }


}
