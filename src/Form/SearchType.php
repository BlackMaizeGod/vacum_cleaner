<?php


namespace App\Form;

use App\Entity\Categories;
use App\Entity\ORM\Search;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
           ->add('minPrice',NumberType::class,[
               'required' => false,
           ])
           ->add('maxPrice',NumberType::class,[
               'required' => false,
           ])
           ->add('category', EntityType::class,[
               'class' => Categories::class,
               'choice_label'=>'name',
               'placeholder' => 'Choose an option',
               'required' => false, 
           ])
            ->add('string', TextType::class,[
                'required' => false,
            ])
           ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Search::class,
        ]);
    }
}
