<?php 
namespace App\Domain\Transaction\Actions;

use App\Repositories\Contracts\TransactionItemRepositoryInterface;

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