<?php

namespace NAO\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class AdminUserType extends AbstractType
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
                'label' => 'Adresse email',
                'constraints' => new NotBlank(),
            ))
            ->add('role', ChoiceType::class, array(
                'choices' => array(
                    'Particulier' => 'ROLE_USER',
                    'Naturaliste' => 'ROLE_NATUR',
                    'Administrateur' => 'ROLE_ADMIN',
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
