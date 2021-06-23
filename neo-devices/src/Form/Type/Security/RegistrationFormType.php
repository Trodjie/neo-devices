<?php declare(strict_types=1);

namespace App\Form\Type\Security;

use App\DTO\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationFormType extends AbstractType
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
            ->add('rawPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => ['label' => 'Пароль'],
                'second_options' => ['label' => 'Повторите пароль'],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Зарегестрироваться',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
