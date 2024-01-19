<?php

namespace App\Controller;

use App\Entity\Banner;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\BannerFormType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

#[Route("/admin",name:"admin_")]

class AdminController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function adminIndex(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/banniere', name: 'banner')]
    public function adminBanner(Request $request, ManagerRegistry $doctrine): Response
    {
        $bannerRepo = $doctrine->getRepository(Banner::class);
        $banner = $bannerRepo->findAll();
        $banner = $banner[0];
        $form = $this->createForm(BannerFormType::class, $banner);
        $form->handleRequest($request);
        $banner = $form->getData();

    // Gestion de la bannière pour l'affichage ordinateur et tablettes
    // unlink($this->getParameter('app.homepage.images.banners.dir').$banner->getMain());
        if ($form->isSubmitted()) {

            if( $form->isValid()) {
                $mainImage = $form->get('main')->getData();
                if (
                    $banner->getMain() != null && 
                    file_exists($this->getParameter('app.homepage.images.banners.dir').$banner->getMain())
                    ) {
                        dump("coucou");
                        unlink($this->getParameter('app.homepage.images.banners.dir').$banner->getMain());
                }
                do{
                    $newFileName = md5(random_bytes(30)).'.'.$mainImage->guessExtension();

                }while(
                    file_exists($this->getParameter('app.homepage.images.banners.dir').$newFileName)
                );
                $banner->setMain($newFileName);
                $mainImage->move($this->getParameter('app.homepage.images.banners.dir'),$newFileName);

        // Gestion de la bannière pour l'affichage mobile

                if(
                    $form->get('mobile')->getData()!= null
                ){
                    $mobileImage = $form->get('mobile')->getData();
                    if (
                        $banner->getMobile() != null && 
                        file_exists($this->getParameter('app.homepage.images.banners.dir').$banner->getMobile())
                        ) {
                            unlink($this->getParameter('app.homepage.images.banners.dir').$banner->getMobile());
                    }
                    do{
                        $newFileName = md5(random_bytes(30)).'.'.$mobileImage->guessExtension();

                    }while(
                        file_exists($this->getParameter('app.homepage.images.banners.dir').$newFileName)
                    );
                    $banner->setMobile($newFileName);
                    $mobileImage->move($this->getParameter('app.homepage.images.banners.dir'),$newFileName);
                }
                $em = $doctrine->getManager();
                $em->flush();
                $this->addFlash('success','Votre bannière a été modifiée avec succès');
                
            }else{
                $this->addFlash('error','Erreur lors du changement de bannière');
            }
            return $this->redirectToRoute('admin_banner');
        }

        return $this->render('admin/banner.html.twig', [
            'form'=> $form->createView(),
            "banner"=>$banner,
        ]);
    }
    #[Route('/banniere/suppression/{id}', name: 'mobile_banner_delete')]
    public function mobileBannerDelete(Request $request, Banner $banner, ManagerRegistry $doctrine): Response
    {
        $csrfToken = $request->query->get('csrf_token', '');
        if(!$this->isCsrfTokenValid('mobile_banner_delete'.$banner->getId(), $csrfToken)) {
            $this->addFlash('error','Token de sécurité invalide');
        }else{
            $banner->setMobile(null);
            $em = $doctrine->getManager();
            $em->flush();
            $this->addFlash('success','La bannière mobile a été supprimée');
        }
        return $this->redirectToRoute('admin_banner');
    }
}
