<?php
/* For licensing terms, see /license.txt */

namespace Chamilo\ContactBundle\Form\Type;

use Gregwar\CaptchaBundle\Type\CaptchaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class ContactType.
 *
 * @package Chamilo\ContactBundle\Form\Type
 */
class ContactType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'category',
                EntityType::class,
                ['class' => 'Chamilo\ContactBundle\Entity\Category']
            )
            ->add('firstname')
            ->add('lastname')
            ->add('email')
            ->add('subject', 'text')
            ->add('message', 'textarea')
            // Ofaj
            ->add('gdpr_checkbox', CheckboxType::class, ['mapped' => false])
            ->add('captcha', CaptchaType::class,
                [
                    'width' => 200,
                    'height' => 50,
                    'length' => 6
                ])
            /*->add(
                'gdpr_textarea',
                'textarea',
                [
                    'disabled' => 'disabled',
                    'label' => false,
                    'attr' => ['rows' => 5],
                    //'label_attr' => ['style' => 'display:none'],
                ]
            )*/
            ->add(
                'send',
                SubmitType::class,
                [
                    'attr' => ['class' => 'btn btn-primary'],
                ]
            );
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $collectionConstraint = new Collection([
            'category' => [
                new NotBlank(['message' => 'Category should not be blank.']),
            ],
            'firstname' => [
                new NotBlank(['message' => 'Firstname should not be blank.']),
                new Length(['min' => 2]),
            ],
            'lastname' => [
                new NotBlank(['message' => 'Lastname should not be blank.']),
                new Length(['min' => 2]),
            ],
            'email' => [
                new NotBlank(['message' => 'Email should not be blank.']),
                new Email(['message' => 'Invalid email address.']),
            ],
            'subject' => [
                new NotBlank(['message' => 'Subject should not be blank.']),
                new Length(['min' => 3]),
            ],
            'message' => [
                new NotBlank(['message' => 'Message should not be blank.']),
                new Length(['min' => 5]),
            ],
        ]);

        $resolver->setDefaults([
            'constraints' => $collectionConstraint,
        ]);
    }
}
