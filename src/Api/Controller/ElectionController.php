<?php

declare(strict_types=1);

namespace App\Api\Controller;

use App\Api\DTO\ElectionVote;
use App\Api\Service\ElectionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/election')]
class ElectionController extends AbstractController
{
    #[Route(path: '/vote', methods: ['POST'])]
    public function vote(
        Request $request,
        ElectionService $electionService,
        SerializerInterface $serializer,
    ): Response {
        $json = $request->getContent();

        if (!$json) {
            throw new BadRequestHttpException();
        }

        /** @var string[]|string[][] */
        $content = json_decode($json, true);

        try {
            $electionVote = new ElectionVote($content);
        } catch (\InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        $results = $electionService->update($electionVote);

        return new Response($serializer->serialize(
            $results,
            'json'
        ));
    }
}
