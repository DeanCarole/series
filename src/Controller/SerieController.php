<?php

namespace App\Controller;

use App\Entity\Serie;
use App\Form\SerieType;
use App\Repository\SerieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

//attribut de la class qui permet de mutualiser des informations
#[Route('/serie', name: 'serie_')]
class SerieController extends AbstractController
{
    #[Route('/list/{page}', name: 'list', requirements: ['page' => '\d+'], methods: "GET")]
    public function list(SerieRepository $serieRepository, int $page = 1): Response
    {
        //TODO récupérer la liste des séries en BDD
        //on récupère toutes les séries en passant par le repository
       // $series= $serieRepository->findAll();

        //récupération de findby avec un tableau de clause WHERE, ORDER BY
        //$series = $serieRepository->findBy(["status"=> "ended"], ["popularity"=> 'DESC'), 10;

        //récupération des 50 séries les mieux notées
        //$series = $serieRepository->findBy([], ["vote"=> "DESC"], 50);

        //méthode magique qui est créée dynamiquement en fonction des attributs de l'entité associée
        //$series = $serieRepository->findByStatus ("ended");

        //nombre de séries dans ma table
        $nbSerieMax = $serieRepository->count([]);

        $maxPage = ceil($nbSerieMax / SerieRepository::SERIE_LIMIT);

    if ($page >= 1 && $page <= $maxPage){
        $series = $serieRepository->findBestSeries($page);
    }else {
        throw $this->createNotFoundException("Oops ! Page not found ! ");
    }


        return $this->render('serie/list.html.twig', [
            //on envoie les données à la vue
            'series' => $series,
            'currentPage'=> $page,
            'maxPage'=> $maxPage
        ]);
    }

    #[Route('/add', name: 'add')]
    public function add(SerieRepository $serieRepository, Request $request ): Response
    {
        $serie = new Serie();

       // $serie->setName("Sliders");

        //création d'une instance de form lié à une instance de série
        $serieForm = $this->createForm(SerieType::class, $serie);

        //méthode qui extrait les éléments du formulaire de la requête
        $serieForm->handleRequest($request);


        if($serieForm->isSubmitted() && $serieForm->isValid()){
            //sauvegarde en BDD
            $serieRepository->save($serie, true);

            $this->addFlash("success", "Serie added !");

            //redirige vers la page de détail de la série
            return $this->redirectToRoute('serie_show', ['id'=>$serie->getId()] );

        }

        //dump($serie);

        return $this->render('serie/add.html.twig', [
            'serieForm'=>$serieForm->createView()
        ]);
    }

    #[Route('/{id}', name: 'show', requirements: ['id' => '\d+'])]
    public function show(int $id, SerieRepository $serieRepository): Response
    {
        $serie = $serieRepository->find($id);

        if (!$serie){
            //lance une erreur 404 si la série n'existe pas
            throw $this->createNotFoundException("Oops ! Serie not found !");
        }
        //TODO récupération des infos de la série
        return $this->render('serie/show.html.twig', ['serie'=> $serie]);
    }
    #[Route('/remove/{id}', name: 'remove', requirements: ['id' => '\d+'])]
    public function remove(int $id, SerieRepository $serieRepository){
        $serie = $serieRepository->find($id);
        if($serie){
            //je le supprime
            $serieRepository->remove($serie, true);
            $this->addFlash("warning", "Serie deleted !");
        }else{
            throw $this->createNotFoundException("This serie can't be deleted ! ");
        }
        return $this->redirectToRoute('serie_list');

    }
}
