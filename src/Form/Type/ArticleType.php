<?php

namespace MicroCMS\Form\Type;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;


class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Prénom'
                ],
                'constraints' => [
                    new Length([
                        'min' => 2,
                        'max' => 80,
                        'minMessage' => 'Saisir au moins 3 caractères',
                        'maxMessage' => 'Saisir moins de 80 caractères'
                    ]),
                    new NotBlank([
                        'message' => 'Veuillez compléter ce champ.'
                    ])
                ]
            ])
            ->add('content', TextareaType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Prénom'
                ],
                'constraints' => [
                    new Length([
                        'min' => 5,
                        'minMessage' => 'Saisir au moins 10 caractères',
                    ]),
                    new NotBlank([
                        'message' => 'Veuillez compléter ce champ.'
                    ])
                ]
            ]);


    }

    public function getName()
    {
        return 'article';
    }
}