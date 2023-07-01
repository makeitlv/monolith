<?php

declare(strict_types=1);

namespace App\Common\Presentation\Form;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @psalm-suppress MixedPropertyFetch
 */
class NewPasswordType extends AbstractType
{
    public function __construct(private Security $security)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add("currentPassword", PasswordType::class, [
                "constraints" => [
                    new UserPassword([
                        "groups" => "CurrentPasswordGroup",
                    ]),
                ],
            ])
            ->add("newPassword", RepeatedType::class, [
                "type" => PasswordType::class,
                "first_options" => ["label" => "New password"],
                "second_options" => ["label" => "Repeat password"],
                "error_bubbling" => false,
                "invalid_message" => "The password fields do not match.",
                "constraints" => [
                    new NotBlank([
                        "groups" => "NewPasswordGroup",
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $defaults = [
            "compound" => true,
            "inherit_data" => true,
            "validation_groups" => function (FormInterface $form): array {
                $groups = ["Default"];

                /** @var mixed $data */
                $data = $form->getData();
                if (is_array($data)) {
                    $data = (object) $data;
                }

                if (!$data->currentPassword && $data->newPassword) {
                    $groups[] = "CurrentPasswordGroup";
                }

                if (!$data->newPassword && $data->currentPassword) {
                    $groups[] = "NewPasswordGroup";
                }

                return $groups;
            },
        ];

        $resolver->setDefaults($defaults);
    }
}
