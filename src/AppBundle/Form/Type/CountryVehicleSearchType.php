<?php

namespace AppBundle\Form\Type;

use AppBundle\Entity\Country;
use AppBundle\Entity\Vehicle;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class CountryVehicleSearchType extends AbstractType
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('country', EntityType::class, [
                'required' => false,
                'class' => Country::class,
                'empty_value' => 'Select country'
            ])
            ->add('vehicle', EntityType::class, [
                'required' => false,
                'class' => Vehicle::class,
                'empty_value' => 'Select vehicle'
            ])
            ->add('Ok', SubmitType::class, ['attr' => ['value' => 1]]);


        $builder->get('country')->addEventListener(FormEvents::SUBMIT, function (FormEvent $event){
            $countryForm = $event->getForm();
            $form = $countryForm->getParent();


            /** @var Country $country */
            $country = $event->getData();

            if ($form->has('vehicle')) {
                $form->remove('vehicle');
            }

            $form->add('vehicle', EntityType::class, [
                'required' => false,
                'class' => Vehicle::class,
                'empty_value' => 'Select vehicle',
                'choices' => $this->getVehicleChoices($country)
            ]);
        });
    }

    public function getVehicleChoices(Country $country = null)
    {
        $result = [];

        if ($country)
        {
            $result = $this->entityManager->getRepository('AppBundle:Vehicle')->createQueryBuilder('vehicle')
                ->join('vehicle.countryLinks', 'countryLinks')
                ->where('countryLinks.country = :country')
                ->setParameter('country', $country)
                ->getQuery()
                ->getResult();
        }

        return $result;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'method' => 'GET',
            'data_class' => null,
            'allow_extra_fields' => true,
            'csrf_protection' => false,
            'data' => []
        ]);
    }
}