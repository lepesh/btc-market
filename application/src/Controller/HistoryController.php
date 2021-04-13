<?php

declare(strict_types=1);

namespace App\Controller;

use App\Helper\DateTimeHelper;
use App\Service\HistoryService;
use App\Service\PairService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HistoryController
{
    /**
     * @Route("/history/{symbol}", methods={"GET"})
     */
    public function retrieve(
        Request $request,
        string $symbol,
        HistoryService $historyService,
        PairService $pairService
    ): JsonResponse {
        if (!$pair = $pairService->getBySymbol($symbol)) {
            return new JsonResponse(null ,JsonResponse::HTTP_NOT_FOUND);
        }
        $dateStart = DateTimeHelper::convert($request->get('dateStart'));
        $dateEnd = DateTimeHelper::convert($request->get('dateEnd'));

        $history = $historyService->filterHistory($pair, $dateStart, $dateEnd);

        return new JsonResponse($history);
    }
}
