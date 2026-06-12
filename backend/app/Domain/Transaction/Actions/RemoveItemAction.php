<?php 
namespace App\Domain\Transaction\Actions;

use App\Repository\Contracts\TransactionItemRepositoryInterface;

class RemoveItemAction
{
    public function __construct(
        protected TransactionItemRepositoryInterface $repository
    ) {}

    public function execute(string $id)
    {
        return $this->repository->delete($id);
    }
}