<?php
namespace App\Form\Type;

use App\Entity\Subreddit;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SubredditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Subreddit Name',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'e.g., python, gaming, science'
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 3,
                    'placeholder' => 'Describe what this subreddit is about...'
                ],
            ])
            ->add('rules', TextareaType::class, [
                'label' => 'Rules',
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 5,
                    'placeholder' => 'List the rules for this subreddit...'
                ],
            ])
            ->add('moderators', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'username',
                'label' => 'Moderators',
                'multiple' => true,
                'expanded' => false,
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Subreddit::class,
        ]);
    }
}
