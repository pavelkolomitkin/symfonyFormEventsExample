<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Country;
use AppBundle\Form\Type\CountryVehicleSearchType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
        ));
    }

    /**
     * @Route("/vehicles", name="contry_vehicles")
     * @param Request $request
     * @Template()
     * @return array
     */
    public function countryVehiclesAction(Request $request)
    {
        $form = $this->createForm(CountryVehicleSearchType::class);
        //$form->handleRequest($request);
        $form->submit($request->query->get('country_vehicle_search', []));

        $countryVehicles = $this->getDoctrine()->getRepository('AppBundle:CountryVehicle')->search($form->getData());

        return [
            'form' => $form->createView(),
            'countryVehicles' => $countryVehicles
        ];
    }

    /**
     * @param Country $country
     * @Route("/vehicles/country/{id}", name="country_vehicles")
     * @ParamConverter("country", class="AppBundle:Country")
     * @return JsonResponse
     */
    public function countryVehicleAjaxAction(Country $country)
    {
        $items = $this->getDoctrine()->getRepository('AppBundle:Vehicle')->search(['country' => $country])->getQuery()->getResult();

        $data = [];
        foreach ($items as $item)
        {
            $data[$item->getId()] = [
                'id' => $item->getId(),
                'title' => $item->getTitle()
            ];
        }

        $result = new JsonResponse($data);

        return $result;
    }
}
