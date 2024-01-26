<?php

namespace App\Controller;

use App\Entity\Banner;
use App\Entity\Biography;
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
        // affichage de la bannière
        // $bannerImage = "build/images/banner.png";
        $bannerRepo = $doctrine->getRepository(Banner::class);
        $banner = $bannerRepo->findAll();
        if ($banner != null) {
        $banner = $banner[0];
        }
        // affichage de la biographie

        
        $biographyRepo = $doctrine->getRepository(Biography::class);
        $biography = $biographyRepo->findAll();
        if ($biography != null) {
            $biography = $biography[0];
        }


        return $this->render('homepage/index.html.twig', [
            'banner' => $banner,
            'biography' => $biography,
        ]);
    }
}



