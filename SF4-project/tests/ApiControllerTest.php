<?php

namespace App\tests;

use App\Controller\ApiCommandeController;
use App\Entity\Commande;
use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use http\Env\Request;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use phpDocumentor\Reflection\Types\Void_;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request as Request1;
use Symfony\Component\Serializer\Encoder\JsonDecode;
use Symfony\Component\Serializer\Encoder\JsonEncode;

class ApiControllerTest extends KernelTestCase
{

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $em;
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $searlizer;
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $or;
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $req;
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $res;


    protected function setUp(): void
    {
        parent::setUp();

        self::bootKernel();

        $this->em = $this->getMockBuilder(EntityManagerInterface::class)->getMock();
        $this->searlizer = $this->getMockBuilder(SerializerInterface::class)->getMock();
        $this->or = $this->getMockBuilder(ObjectRepository::class)->getMock();
        $this->req = $this->getMockBuilder(\Symfony\Component\HttpFoundation\Request::class)->getMock();
        $this->res = $this->getMockBuilder(\Symfony\Component\HttpFoundation\Response::class)->getMock();

    }


    public function Index()
    {
        $data = new ArrayCollection();
        $this->em->expects($this->once())
            ->method('getRepository')
            ->with(Commande::class)
            ->willReturn($this->or)
        ;

        $this->or->expects($this->once())
            ->method('findAll')
            ->willReturn($data)
        ;

        $this->searlizer->expects($this->once())
            ->method('serialize')
            ->with($data, 'json', SerializationContext::create()->setGroups(array('list')))
            ->willReturn(\GuzzleHttp\json_encode([]));

        $controller = new ApiCommandeController($this->em, $this->searlizer);
        $response = $controller->index();

        $this->assertSame('application/json', $response->headers->get('Content-Type'));
        //dd($response);

    }

    public function testUpdate()
    {
        $id = 1000;
        $this->em->expects($this->once())
            ->method('getRepository')
            ->with(Commande::class)
            ->willReturn($this->or)
            ;
        $this->or->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn(null)
        ;

        $this->req->expects($this->never())
            ->method($this->anything());
        $this->searlizer->expects($this->never())
            ->method($this->anything());
        $this->em->expects($this->never())
        ->method($this->anything());

        $controller = new ApiCommandeController($this->em, $this->searlizer);
        $response = $controller->update($this->req,$id);

        $this->assertSame(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        //dd($response);




    }

    public function Create()
    {
        $json="{'lalal' : 'babab'}";

        $data = new ArrayCollection();

        $this->req->expects($this->once())
            ->method('getContent')
            ->willReturn($json)
        ;

        $this->searlizer->expects($this->once())
            ->method('deserialize')
            ->with($json,'App\Entity\Commande','json')

            ->willReturn($data)
        ;
        $this->em->expects($this->once())
            ->method('persist')
            ->with($data)
            ->willReturn(true)
        ;

        $this->em->expects($this->once())
            ->method('flush')
            ->willReturn(true)
        ;

        $controller = new ApiCommandeController($this->em, $this->searlizer);
        $response = $controller->create($this->req);


        $this->assertSame(201, $response->getStatusCode());
        //dd($response);

    }

//    public function testCREATE()
//    {
//        $client = new Client([
//            'base_uri' => "http://localhost:8000",
//            'defaults' => [
//                'exceptions' => false
//            ]
//        ]);
//        //raw params
//        $data = array(
//            'ref' => "abcd",
//            'date' =>"2014-08-25T22:37:37Z",
//        );
//        $response = $client ->post('/api/commande/post', [
//            'body' => json_encode($data)
//        ]);
//        $this->assertEquals(201, $response->getStatusCode());
//    }


//
//
//    /** @test  */
//    public function testGET()
//    {
//        $client = new Client([
//            'base_uri' => "http://localhost:8000",
//            'defaults' => [
//                'exceptions' => false
//            ]
//        ]);
//
//        $response = $client ->get('/api/commande/list/');
//        $this->assertEquals(200, $response->getStatusCode());
//        $this->assertJson($response->getBody());
//    }


//    /** @test  */
//    public function testPUT()
//    public function testPUT()
//    {
//        $client = new Client([
//            'base_uri' => "http://localhost:8000",
//            'defaults' => [
//                'exceptions' => false
//            ]
//        ]);
//        $data = array(
//            'ref' => "putt",
//            'date' =>"2018-08-25T22:37:37Z",
//        );
//        $response = $client ->put('/api/commande/put/2', [
//            'body' => json_encode($data)
//        ]);
//        $this->assertEquals(200, $response->getStatusCode());
//    }


//    /** @test  */
//    public function testDELETE()
//    {
//        $client = new Client([
//            'base_uri' => "http://localhost:8000",
//            'defaults' => [
//                'exceptions' => false
//            ]
//        ]);
//
//        $response = $client ->delete('/api/commande/delete/2');
//        $this->assertEquals(200, $response->getStatusCode());
//    }

//    public function testCommandeProcessd(){
//
//        $gateway = $this->getMockBuilder('App\Controller\PaymentGateway')
//                        ->setMethods(['charge'])
//                        ->getMock();
//
//        $gateway->method('charge')
//            ->willReturn(true);
//
//        $serializer = $this->getMockBuilder('JMS\Serializer\SerializerInterface')
//            ->getMock();
//
//
//
//        $commande = new ApiCommandeController($serializer,$gateway);
//        $commande->amount = 200 ;
//
//        $this->assertTrue($commande->process());
//
//    }









}

