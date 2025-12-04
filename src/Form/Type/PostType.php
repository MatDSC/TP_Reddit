<?php
namespace App\Form\Type;

use App\Entity\Post;
use App\Entity\Subreddit;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Title',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Entrez le titre de votre publication',
                ],

            ])
            ->add('content', TextareaType::class, [
                'label' => 'Content',
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 10,
                    'placeholder' => 'Ecrivez le contenu de votre publication'
                ],
            ])
            ->add('subreddit', EntityType::class, [
                'class' => Subreddit::class,
                'choice_label' => 'name',
                'label' => 'Subreddit',
                'attr' => ['class' => 'form-control'],
                'placeholder' => 'Choisissez un subreddit'

            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
