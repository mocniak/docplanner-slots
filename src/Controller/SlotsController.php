<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Slot;
use App\Service\SlotListQuery;
use App\Service\SlotsSorter;
use App\ValueObject\ListSlotsRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class SlotsController extends AbstractController
{

    private SlotListQuery $listQuery;

    public function __construct(SlotListQuery $listQuery)
    {
        $this->listQuery = $listQuery;
    }

    /**
     * @Route("/slots", name="slots")
     */
    public function list(Request $request): Response
    {
        //TODO: request validation
        $slots = $this->listQuery->find(new ListSlotsRequest(
            $request->query->get('sort_type'),
            $request->query->get('date_from') !== null ? new \DateTimeImmutable($request->query->get('date_from')) : null,
            $request->query->get('date_to') !== null ? new \DateTimeImmutable($request->query->get('date_to')) : null,
        ));

        // TODO: implement better way of serialization
        return new JsonResponse(array_map(function (Slot $slot) {
            return [
                'id' => $slot->id(),
                'doctorId' => $slot->doctorId(),
                'startTime' => $slot->startTime()->format(\DateTimeInterface::ISO8601),
                'endTime' => $slot->startTime()->format(\DateTimeInterface::ISO8601),
            ];
        }, $slots->getSlots()));
    }
}
