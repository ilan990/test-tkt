<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Twig\Environment;

class DashboardController extends AbstractController
{
    #[Route('/', name: 'app_dashboard')]
    public function index(SerializerInterface $serializer, Environment $twig,HttpClientInterface $httpClient): Response
    {

        $response = $httpClient->request('GET','https://test.wertkt.com/api/biz/');

        $content = $response->getContent();
        $data = $serializer->decode($content, 'json');

        return $this->render('dashboard/index.html.twig', [
            'datas' => $data,
        ]);
    }

    #[Route('/{id}', name: 'app_company_show', methods: ['GET'])]
    public function show(SerializerInterface $serializer, Environment $twig,HttpClientInterface $httpClient,int $id): Response
    {
        $response = $httpClient->request('GET','https://test.wertkt.com/api/result/'.$id);
        $content = $response->getContent();
        $premiereAnnee = $serializer->decode($content, 'json');

        $response = $httpClient->request('GET','https://test.wertkt.com/api/result/'.$id+1);
        $content = $response->getContent();
        $deuxiemeAnnee = $serializer->decode($content, 'json');

        $labelsJson = '[{"caA1":'.$premiereAnnee['ca'].', "caA2":'.$deuxiemeAnnee['ca'].',"ebitdaA1":'.$premiereAnnee['ebitda'].', "ebitdaA2":'.$deuxiemeAnnee['ebitda'].',"lossA1":'.$premiereAnnee['loss'].', "lossA2":'.$deuxiemeAnnee['loss'].',"marginA1":'.$premiereAnnee['margin'].', "marginA2":'.$deuxiemeAnnee['margin'].'}]';


        $datas = json_decode($labelsJson, true);
        return $this->render('dashboard/show.html.twig', [
            'datas' => $datas,
        ]);
    }
}
