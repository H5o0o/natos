<?php

namespace App\Controller;

use App\Entity\Banner;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/",name:"app_")]
class HomepageController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function homepage(ManagerRegistry $doctrine): Response
    {   
        // $bannerImage = "build/images/banner.png";
        $bannerRepo = $doctrine->getRepository(Banner::class);
        $banner = $bannerRepo->findAll();
        $banner = $banner[0];


        return $this->render('homepage/index.html.twig', [
            'banner' => $banner,
        ]);
    }
}



