<?php

namespace RedEye\SavedSettingsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;

// these import the "@Route" and "@Template" annotations
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


use RedEye\SavedSettingsBundle\Entity\SearchSettings;

class SearchSettingsController extends Controller
{
    protected $searchOptions = array('criteria1' => array('c1v1', 'c1v2', 'c1v3', 'c1v4'),'criteria2' => array('c2v1', 'c2v2', 'c2v3', 'c2v4'),'criteria3' => array('c3v1', 'c3v2', 'c3v3', 'c3v4'), 'criteria4' => array('c4v1', 'c4v2', 'c4v3', 'c4v4'));

    /**
     * @Route("/", name="_settings_index")
     * @Template()
     */
    public function indexAction()
    {
        return array('searchOptions' => $this->searchOptions);
    }

    /**
     * @Route("/create", name="_settings_create")
     * @Method({"POST"})
     */
    public function createAction(Request $request) {

        // decode incoming json and stash in $ajaxSearchData
        $ajaxSearchData = json_decode($request->getContent(), true);
        $searchCriteria = [];
        // create a new entity and set its properties
        $searchSettings = new SearchSettings();
        foreach($ajaxSearchData['searchCriteria'] as $value) {
            $searchCriteria[] = implode(':',$value);
        }
        $searchSettings->setSearchCriteria(implode(',', $searchCriteria));
        $searchSettings->setSearchDesc($ajaxSearchData['searchDesc']);
        $searchSettings->setSearchText($ajaxSearchData['searchText']);

        // save the received search settings
        $em = $this->getDoctrine()->getManager();
        $em->persist($searchSettings);
        $em->flush();

        return new Response(json_encode(array('response' => 'Saved search settings.')));
    }

    /**
     * @Route("/read", name="_settings_read")
     * @Method({"GET"})
     */

    public function readAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $savedSearches = $em->getRepository('SavedSettingsBundle:SearchSettings')->findAll();

        $serializer = new Serializer([new GetSetMethodNormalizer()], [new JsonEncoder()]);
        $jsonContent = $serializer->serialize(array('savedSearches' => $savedSearches), 'json');
        $response = new Response($jsonContent);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/clear", name="_settings_clear")
     * @Method({"GET"})
     */
    public function clearAllAction() {
        $em = $this->getDoctrine()->getManager();
        $savedSearches = $em->getRepository('SavedSettingsBundle:SearchSettings')->findAll();
        foreach($savedSearches as $search) {
            $em->remove($search);
        }
        $em->flush();
        $response = new Response(json_encode(array('response' => 'Deleted all search settings')));
        $response->headers->set('Content-Type', 'application/json');
        return $response;

    }

}
