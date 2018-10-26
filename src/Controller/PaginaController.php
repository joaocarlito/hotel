<?php

namespace App\Controller;

use App\Entity\Quarto;
use App\Entity\Reserva;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\DateTime;

class PaginaController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        return $this->render('pagina/index.html.twig', [
            'controller_name' => 'PaginaController',
        ]);
    }

    /**
     * @Route("/pesquisa", name="pesquisa")
     */
    public function pesquisa(Request $request)
    {
        $quantidade = $request->get("quantidade");
        $dias = explode(".", $request->get("data-selecionada"));

        $dataIni = \DateTime::createFromFormat("d/m/Y", $dias[0]);
        $dataFim = \DateTime::createFromFormat("d/m/Y", $dias[1]);



        $session = $request->getSession();
        $session->set("dataIni", $dataIni);
        $session->set("dataFim", $dataFim);
        $session->set("quantidade", $quantidade);

        //$em = $this->getDoctrine()->getRepository(Reserva::class);
        //$quartos = $em->quartosOcupados($dataIni, $dataFim, $quantidade);

        $em = $this->getDoctrine()->getRepository(Quarto::class);
        $quartos = $em->findAll();


        return $this->render('pagina/index.html.twig', array("quartos" => $quartos));
    }

    
    /**
     * @Route("/contato", name="contato")
     */
    public function contato(Request $request)
    {
        //var_dump($request);
        return $this->render('pagina/contato.html.twig');
    }
    
    /**
     * @Route("/reservar/{quarto}", name="reservar")
     */
    public function reservar($quarto, Request $request)
    {
        $dataIni = $request->getSession()->get("dataIni");
        $dataFim = $request->getSession()->get("dataFim");

        $totalDias = $dataIni->diff($dataFim);
        $quarto = $this->getDoctrine()->getRepository(Quarto::class)->find($quarto);
        $totalReserva = $totalDias->days * $quarto->getDiaria();

        return $this->render('pagina/reservar.html.twig', array(
            "quarto" => $quarto,
            "total_dias" => $totalDias->days,
            "dataIni" => $dataIni,
            "dataFim" => $dataFim,
            "totalReserva" => $totalReserva,));
    }
    
    /**
     * @Route("/login", name="login")
     */
    public function login()
    {
        return $this->render('pagina/login.html.twig');
    }
    
    /**
     * @Route("/admin", name="admin")
     */
    public function admin()
    {
        return $this->render('pagina/admin.html.twig');
    }
}
