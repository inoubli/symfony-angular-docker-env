<?php

namespace App\Controller;

use App\Entity\Commande;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/api/commande")
 */
class ApiCommandeController extends AbstractController
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    public $amount = 0;

    /**
     * PaymentGateway Dependency
     * @var PaymentGateway
     */
    protected  $gateway;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager, SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
        $this->entityManager = $entityManager;
    }

//    public function __construct(SerializerInterface $serializer, PaymentGateway $gateway)
//    {
//        $this->serializer = $serializer;
//        $this->gateway = $gateway;
//    }



    /**
     * @Route("/list", name="commande_list")
     */
    public function index()
    {
        $commandes = $this->entityManager->getRepository(Commande::class)->findAll();

        $data = $this->serializer->serialize($commandes, 'json', SerializationContext::create()->setGroups(array('list')));

        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/post", name="commande_post")
     */
    public function create(Request $request)
    {

        $data = $request->getContent();

        $commande = $this->serializer->deserialize($data,'App\Entity\Commande', 'json');
        $this->entityManager->persist($commande);
        $this->entityManager->flush();

        return new Response('', Response::HTTP_CREATED);

    }


    /**
     * @Route("/put/{id}", name="commande_put", methods={"PUT"})
     */
    public function update(Request $request,  $id)
    {
        //dd($this->entityManager->getRepository(Commande::class));
        $cmd=$this->entityManager->getRepository(Commande::class)->find($id);

        if (!$cmd){
            return new Response('pas de commande passé avec ID = '.$id, Response::HTTP_BAD_REQUEST);
        }
        $data = $request->getContent();
        $commande = $this->serializer->deserialize($data, 'App\Entity\Commande', 'json');

        $cmd->setRef($commande->getRef());
        $cmd->setDate($commande->getDate());

        $this->entityManager->persist($cmd);
        $this->entityManager->flush();

        return new Response('', Response::HTTP_OK);

    }

    /**
     * @Route("/delete/{id}", name="commande_delete", methods={"DELETE"})
     */
    public function delete(Request $request,  $id)
    {
        $em = $this->getDoctrine()->getManager();
        $cmd=$em->getRepository('App\Entity\Commande')->find($id);
        if (!$cmd){
            return new Response('pas de commande passé avec ID = '.$id, Response::HTTP_BAD_REQUEST);
        }

        $em->remove($cmd);
        $em->flush();

        return new Response('', Response::HTTP_OK);

    }

//    /** This is an additional example just to Simulate a call for dependency function */
//    public function process(){
//        return $this->gateway->charge($this->amount);
//    }

}
