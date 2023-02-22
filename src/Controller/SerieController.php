<?php

namespace App\Controller;

use App\Entity\Serie;
use App\Repository\SerieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
//attribut de la class qui permet de mutualiser des informations
#[Route('/serie', name: 'serie_')]
class SerieController extends AbstractController
{
    #[Route('/list', name: 'list', methods: "GET")]
    public function list(SerieRepository $serieRepository): Response
    {
        //TODO récupérer la liste des séries en BDD
        //on récupère toutes les séries en passant par le repository
       // $series= $serieRepository->findAll();

        //récupération des 50 séries les mieux notées
    $series = $serieRepository->findBy(["status" => "ended"], ["popularity"=> 'DESC'], 50);


        return $this->render('serie/list.html.twig', [
            //on envoie les données à la vue
            'series' => $series
        ]);
    }

    #[Route('/add', name: 'add')]
    public function add(SerieRepository $serieRepository, EntityManagerInterface $entityManager): Response
    {
        $serie = new Serie();
        $serie
            //settage des infos de la série
            ->setName("Le magicien")
            ->setBackdrop("backdrop.png")
            ->setDateCreated(new \DateTime())
            ->setGenres("Comedy")
            ->setFirstAirDate(new \DateTime('2022-02-02'))
            ->setLastAirDate(new \DateTime("-6 month"))
            ->setPopularity(850.52)
            ->setPoster("poster.png")
            ->setTmdbId(123456)
            ->setVote(8.5)
            ->setStatus("Ended");



        //utilisation directement de l'entityManager
        $entityManager->persist($serie);
        $entityManager->flush();


        //affiche le contenu de la variable
        //dump($serie);
        //enregistrement en base de données
        //$serieRepository->save($serie, true);

        //dump($serie);

        //$serie->setName("The last of us");
       // $serieRepository->save($serie, true);

        //dump($serie);

        $serieRepository->remove($serie, true);

        //TODO créer un formulaire d'ajout de série

        return $this->render('serie/add.html.twig');
    }

    #[Route('/{id}', name: 'show', requirements: ['id' => '\d+'])]
    public function show(int $id, SerieRepository $serieRepository): Response
    {
        $serie = $serieRepository->find($id);
        dump($serie);
        //TODO récupération des infos de la série
        return $this->render('serie/show.html.twig', ['serie'=> $serie]);
    }
}
