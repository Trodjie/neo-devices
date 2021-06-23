<?php declare(strict_types=1);

namespace App\Form\Type\User;


use App\DTO\Profile;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', null, [
                'label' => 'E-mail',
            ])
            ->add('name', null, [
                'label' => 'Имя',
            ])
            ->add('lastname', null, [
                'label' => 'Фамилия',
            ])
            ->add('address', null, [
                'label' => 'Адрес',
            ])
            ->add('phone', null, [
                'label' => 'Телефон',
                'attr' => [
                    'class' => 'js-phone',
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Сохранить',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Profile::class,
        ]);
    }
}
